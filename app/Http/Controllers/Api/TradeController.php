<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Trade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function openPositions(Request $request): JsonResponse
    {
        $positions = $request->user()->trades()
            ->with('instrument')
            ->open()
            ->latest('opened_at')
            ->get();

        return ApiResponse::success($positions);
    }

    public function history(Request $request): JsonResponse
    {
        $query = $request->user()->trades()
            ->with('instrument')
            ->closed()
            ->latest('closed_at');

        if ($request->filled('symbol')) {
            $instrument = Instrument::where('symbol', $request->symbol)->first();
            if ($instrument) {
                $query->where('instrument_id', $instrument->id);
            }
        }
        if ($request->filled('direction')) {
            $query->where('direction', strtoupper($request->direction));
        }
        if ($request->filled('result')) {
            $query->when($request->result === 'profit', fn($q) => $q->where('profit_loss', '>', 0))
                  ->when($request->result === 'loss', fn($q) => $q->where('profit_loss', '<', 0));
        }
        if ($request->filled('from')) {
            $query->whereDate('closed_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('closed_at', '<=', $request->to);
        }

        $trades = $query->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($trades);
    }

    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $totalTrades = $user->trades()->closed()->count();
        $winTrades = $user->trades()->closed()->where('profit_loss', '>', 0)->count();
        $winRate = $totalTrades > 0 ? round(($winTrades / $totalTrades) * 100, 2) : 0;

        $totalProfit = $user->trades()->closed()->where('profit_loss', '>', 0)->sum('profit_loss');
        $totalLoss = abs($user->trades()->closed()->where('profit_loss', '<', 0)->sum('profit_loss'));
        $bestTrade = $user->trades()->closed()->max('profit_loss');
        $worstTrade = $user->trades()->closed()->min('profit_loss');
        $avgDuration = $user->trades()->closed()->avg('duration_seconds');

        return ApiResponse::success([
            'total_trades' => $totalTrades,
            'win_rate' => $winRate,
            'total_profit' => $totalProfit,
            'total_loss' => $totalLoss,
            'best_trade' => $bestTrade,
            'worst_trade' => $worstTrade,
            'avg_duration_seconds' => round($avgDuration ?? 0, 0),
            'profit_factor' => $totalLoss > 0 ? round($totalProfit / $totalLoss, 2) : null,
        ]);
    }

    public function chartData(Request $request): JsonResponse
    {
        $data = $request->user()->trades()
            ->closed()
            ->orderBy('closed_at')
            ->get(['closed_at', 'profit_loss'])
            ->map(function ($trade) {
                return [
                    'date' => $trade->closed_at->format('Y-m-d'),
                    'pnl' => (float) $trade->profit_loss,
                ];
            });

        $cumulative = [];
        $sum = 0;
        foreach ($data as $point) {
            $sum += $point['pnl'];
            $cumulative[] = ['date' => $point['date'], 'value' => round($sum, 2)];
        }

        return ApiResponse::success($cumulative);
    }

    public function close(Request $request, int $id): JsonResponse
    {
        $trade = $request->user()->trades()->open()->with('instrument')->find($id);

        if (! $trade) {
            return ApiResponse::error('Position introuvable', 404);
        }

        $exitPrice = $trade->instrument->bid ?? $trade->entry_price;
        $pnl = $this->calculatePnl($trade, $exitPrice);
        $duration = now()->diffInSeconds($trade->opened_at);

        $specs = $trade->instrument->specs ?? [];
        $leverage = $specs['leverage'] ?? 10;
        $contractSize = $specs['contract_size'] ?? 1;
        $margin = ($trade->volume * $trade->entry_price * $contractSize) / $leverage;

        $trade->update([
            'status' => 'closed',
            'exit_price' => $exitPrice,
            'profit_loss' => $pnl,
            'closed_at' => now(),
            'duration_seconds' => $duration,
        ]);

        $wallet = $request->user()->wallet;
        if ($wallet) {
            $wallet->increment('trading_balance', $margin + $pnl);
            $wallet->decrement('margin_used', $margin);
            $wallet->increment('balance', $pnl);
        }

        return ApiResponse::success($trade, 'Position clôturée');
    }

    public function closeAll(Request $request): JsonResponse
    {
        $trades = $request->user()->trades()->open()->with('instrument')->get();
        $closed = 0;
        $totalPnl = 0;

        foreach ($trades as $trade) {
            $exitPrice = $trade->instrument->bid ?? $trade->entry_price;
            $pnl = $this->calculatePnl($trade, $exitPrice);
            $totalPnl += $pnl;
            $duration = now()->diffInSeconds($trade->opened_at);

            $specs = $trade->instrument->specs ?? [];
            $leverage = $specs['leverage'] ?? 10;
            $contractSize = $specs['contract_size'] ?? 1;
            $margin = ($trade->volume * $trade->entry_price * $contractSize) / $leverage;

            $trade->update([
                'status' => 'closed',
                'exit_price' => $exitPrice,
                'profit_loss' => $pnl,
                'closed_at' => now(),
                'duration_seconds' => $duration,
            ]);
            $closed++;
        }

        $wallet = $request->user()->wallet;
        if ($wallet && $closed > 0) {
            $wallet->increment('balance', $totalPnl);
            $wallet->update(['margin_used' => 0]);
        }

        return ApiResponse::success(['closed_count' => $closed], 'Toutes les positions clôturées');
    }

    private function calculatePnl(Trade $trade, float $exitPrice): float
    {
        $diff = $exitPrice - $trade->entry_price;
        if ($trade->direction === 'SELL') {
            $diff = -$diff;
        }
        return round($diff * $trade->volume, 2);
    }
}
