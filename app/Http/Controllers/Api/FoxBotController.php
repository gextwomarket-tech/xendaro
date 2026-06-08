<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Trade;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * FoxBot Controller - Handles automated bot trading
 * POST /api/trade/operations/foxbot
 */
class FoxBotController extends Controller
{
    /**
     * Configuration du FoxBot
     */
    private const BOT_CONFIG = [
        'winRate'           => 0.75,      // 70-80% de taux de réussite
        'minWinRate'        => 0.70,      // Minimum 70%
        'maxWinRate'        => 0.80,      // Maximum 80%
        'riskPerTrade'      => 0.02,      // 2% du solde par trade
        'riskRangePct'      => 0.015,     // 1.5% de la distance SL/TP
        'tpMultiplier'      => 2.0,       // TP = 2x la distance du SL
        'maxConcurrentBots' => 3,         // Max 3 positions bot concurrentes
        'minHoldTimeMs'     => 5000,      // Min 5 secondes avant fermeture
        'maxHoldTimeMs'     => 25000,     // Max 25 secondes avant fermeture
    ];

    /**
     * Ouvrir une position pour FoxBot
     * POST /api/trade/operations/foxbot
     * 
     * Body JSON:
     * {
     *   "symbol": "BTC/USD",
     *   "direction": "BUY",
     *   "volume": 0.5,
     *   "entry_price": 65000.00,
     *   "stop_loss": 64000,
     *   "take_profit": 67000,
     *   "margin": 325,
     *   "contract_size": 1,
     *   "account_type": "demo"
     * }
     */
    public function openBotPosition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symbol'       => 'required|string|exists:instruments,symbol',
            'direction'    => 'required|in:BUY,SELL',
            'volume'       => 'required|numeric|min:0.01',
            'entry_price'  => 'required|numeric|min:0',
            'stop_loss'    => 'nullable|numeric|min:0',
            'take_profit'  => 'nullable|numeric|min:0',
            'margin'       => 'required|numeric|min:0',
            'contract_size'=> 'required|numeric|min:0',
            'account_type' => 'required|in:demo,real',
        ]);

        $user   = $request->user();
        $wallet = $this->getOrCreateWallet($user->id);

        // Vérifier le solde disponible
        $margin = (float) $validated['margin'];
        if ($validated['account_type'] === 'demo') {
            if ($wallet->demo_balance < $margin) {
                return ApiResponse::error('Insufficient demo balance for bot trade', 422);
            }
            // Limiter le nombre de positions bot concurrentes
            $activeBotTrades = Trade::where('user_id', $user->id)
                ->where('account_type', 'demo')
                ->where('status', 'open')
                ->where('is_bot', true)
                ->count();
            if ($activeBotTrades >= self::BOT_CONFIG['maxConcurrentBots']) {
                return ApiResponse::error('Max concurrent bot positions reached', 422);
            }
        } else {
            if ($wallet->balance < $margin) {
                return ApiResponse::error('Insufficient real balance for bot trade', 422);
            }
            $activeBotTrades = Trade::where('user_id', $user->id)
                ->where('account_type', 'real')
                ->where('status', 'open')
                ->where('is_bot', true)
                ->count();
            if ($activeBotTrades >= self::BOT_CONFIG['maxConcurrentBots']) {
                return ApiResponse::error('Max concurrent bot positions reached', 422);
            }
        }

        // Récupérer l'instrument
        $instrument = Instrument::where('symbol', $validated['symbol'])->firstOrFail();

        DB::beginTransaction();
        try {
            // Déduction de la marge
            if ($validated['account_type'] === 'demo') {
                $wallet->decrement('demo_balance', $margin);
            } else {
                $wallet->decrement('balance', $margin);
                $wallet->increment('margin_used', $margin);
            }

            // Créer la position du bot
            $trade = Trade::create([
                'user_id'       => $user->id,
                'instrument_id' => $instrument->id,
                'account_type'  => $validated['account_type'],
                'direction'     => strtoupper($validated['direction']),
                'volume'        => (float) $validated['volume'],
                'margin'        => $margin,
                'contract_size' => (float) $validated['contract_size'],
                'entry_price'   => (float) $validated['entry_price'],
                'stop_loss'     => $validated['stop_loss'] ? (float) $validated['stop_loss'] : null,
                'take_profit'   => $validated['take_profit'] ? (float) $validated['take_profit'] : null,
                'profit_loss'   => 0,
                'status'        => 'open',
                'is_bot'        => true,  // ✅ Marquer comme position bot
                'opened_at'     => now(),
            ]);

            DB::commit();

            return ApiResponse::success([
                'trade_id' => $trade->id,
                'balance'  => $this->balancePayload($wallet->fresh()),
            ], 'Bot position opened successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FoxBotController::openBotPosition Error', [
                'user_id'   => $user->id ?? null,
                'symbol'    => $validated['symbol'] ?? null,
                'message'   => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Fermer une position du bot
     * POST /api/trade/operations/foxbot/{id}/close
     * 
     * Body JSON:
     * {
     *   "exit_price": 66000.00,
     *   "is_winning_trade": true
     * }
     */
    public function closeBotPosition(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exit_price'         => 'required|numeric|min:0',
            'is_winning_trade'   => 'required|boolean',
        ]);

        $user  = $request->user();
        $trade = Trade::where('user_id', $user->id)
                      ->where('status', 'open')
                      ->where('is_bot', true)
                      ->find($id);

        if (!$trade) {
            return ApiResponse::error('Bot position not found', 404);
        }

        $wallet    = $this->getOrCreateWallet($user->id);
        $exitPrice = (float) $validated['exit_price'];
        $isWin     = (bool) $validated['is_winning_trade'];

        // Calcul du P&L
        $pnl = $trade->direction === 'BUY'
            ? ($exitPrice - (float)$trade->entry_price) * (float)$trade->volume * (float)$trade->contract_size
            : ((float)$trade->entry_price - $exitPrice) * (float)$trade->volume * (float)$trade->contract_size;

        // Adapter le P&L pour respecter le taux de réussite
        if (!$isWin && $pnl > 0) {
            // Si c'est une perte, s'assurer que le P&L est négatif
            $pnl = -abs($pnl);
        } elseif ($isWin && $pnl <= 0) {
            // Si c'est une victoire, s'assurer que le P&L est positif
            $pnl = abs($pnl);
        }

        $duration = now()->diffInSeconds($trade->opened_at);

        DB::beginTransaction();
        try {
            $trade->update([
                'exit_price'       => $exitPrice,
                'profit_loss'      => round($pnl, 2),
                'status'           => 'closed',
                'close_reason'     => $isWin ? 'Take Profit (FoxBot)' : 'Stop Loss (FoxBot)',
                'closed_at'        => now(),
                'duration_seconds' => $duration,
            ]);

            // Retourner la marge + P&L au compte
            $returned = (float)$trade->margin + $pnl;
            if ($trade->account_type === 'demo') {
                $wallet->increment('demo_balance', $returned);
            } else {
                $wallet->increment('balance', $returned);
                $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
            }

            DB::commit();

            return ApiResponse::success([
                'trade_id' => $trade->id,
                'pnl'      => round($pnl, 2),
                'is_win'   => $isWin,
                'balance'  => $this->balancePayload($wallet->fresh()),
            ], 'Bot position closed successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FoxBotController::closeBotPosition Error', [
                'user_id'  => $user->id ?? null,
                'trade_id' => $id,
                'message'  => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Obtenir les statistiques du bot
     * GET /api/trade/operations/foxbot/stats
     */
    public function getBotStats(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $allBotTrades = Trade::where('user_id', $user->id)
            ->where('is_bot', true)
            ->get();

        $totalTrades = $allBotTrades->count();
        $closedTrades = $allBotTrades->where('status', 'closed')->count();
        $winTrades = $allBotTrades->where('status', 'closed')->where('profit_loss', '>', 0)->count();
        
        $winRate = $totalTrades > 0 ? ($winTrades / $closedTrades) * 100 : 0;
        $totalPnl = $allBotTrades->where('status', 'closed')->sum('profit_loss');

        $openBotTrades = $allBotTrades->where('status', 'open');
        $demoOpen = $openBotTrades->where('account_type', 'demo')->count();
        $realOpen = $openBotTrades->where('account_type', 'real')->count();

        return ApiResponse::success([
            'total_trades'     => $totalTrades,
            'closed_trades'    => $closedTrades,
            'open_trades'      => $totalTrades - $closedTrades,
            'win_trades'       => $winTrades,
            'win_rate'         => round($winRate, 2),
            'total_pnl'        => round($totalPnl, 2),
            'open_demo_count'  => $demoOpen,
            'open_real_count'  => $realOpen,
        ]);
    }

    /**
     * Fermer toutes les positions du bot
     * POST /api/trade/operations/foxbot/close-all
     */
    public function closeAllBotPositions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'account_type' => 'required|in:demo,real',
        ]);

        $user   = $request->user();
        $wallet = $this->getOrCreateWallet($user->id);
        
        $trades = Trade::where('user_id', $user->id)
                       ->where('account_type', $validated['account_type'])
                       ->where('status', 'open')
                       ->where('is_bot', true)
                       ->get();

        if ($trades->isEmpty()) {
            return ApiResponse::success(['closed_count' => 0], 'No open bot positions');
        }

        DB::beginTransaction();
        try {
            $closed = 0;
            $totalPnl = 0;

            foreach ($trades as $trade) {
                $exitPrice = $trade->entry_price;  // Exit at entry price (break-even)
                $pnl = 0;

                $trade->update([
                    'exit_price'       => $exitPrice,
                    'profit_loss'      => $pnl,
                    'status'           => 'closed',
                    'close_reason'     => 'Manual Close (FoxBot)',
                    'closed_at'        => now(),
                    'duration_seconds' => now()->diffInSeconds($trade->opened_at),
                ]);

                // Retourner la marge
                $wallet->increment($trade->account_type === 'demo' ? 'demo_balance' : 'balance', $trade->margin);
                if ($trade->account_type === 'real') {
                    $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
                }

                $closed++;
                $totalPnl += $pnl;
            }

            DB::commit();

            return ApiResponse::success([
                'closed_count' => $closed,
                'total_pnl'    => round($totalPnl, 2),
                'balance'      => $this->balancePayload($wallet->fresh()),
            ], 'All bot positions closed');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FoxBotController::closeAllBotPositions Error', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
            ]);
            return ApiResponse::error('Server error: ' . $e->getMessage(), 500);
        }
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    private function getOrCreateWallet(int $userId): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000]
        );
    }

    private function balancePayload(Wallet $wallet): array
    {
        return [
            'demo_balance' => (float) $wallet->demo_balance,
            'real_balance' => (float) $wallet->balance,
            'margin_used'  => (float) $wallet->margin_used,
        ];
    }
}
