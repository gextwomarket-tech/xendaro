<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Trade;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TradeController extends Controller
{
    // ── PAGE PRINCIPALE ───────────────────────────────────────────────────
    public function index(Request $request): \Illuminate\View\View
    {
        $user = $request->user();

        $wallet = $user->wallet ?? Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000]
        );

        // Positions ouvertes (réel + démo)
        $openPositions = Trade::with('instrument')
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->get()
            ->map(fn($t) => $this->formatPosition($t));

        // Historique des 50 derniers trades fermés
        $closedHistory = Trade::with('instrument')
            ->where('user_id', $user->id)
            ->where('status', 'closed')
            ->orderByDesc('closed_at')
            ->limit(50)
            ->get()
            ->map(fn($t) => $this->formatHistory($t));

        return view('trade.index', compact('user', 'wallet', 'openPositions', 'closedHistory'));
    }

    // ── OUVRIR UNE POSITION ───────────────────────────────────────────────
    public function openPosition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symbol'       => 'required|string',
            'direction'    => 'required|in:BUY,SELL',
            'volume'       => 'required|numeric|min:0.01',
            'entry_price'  => 'required|numeric|min:0',
            'stop_loss'    => 'nullable|numeric|min:0',
            'take_profit'  => 'nullable|numeric|min:0',
            'margin'       => 'required|numeric|min:0',
            'contract_size'=> 'required|numeric|min:0',
            'account_type' => 'required|in:demo,real',
            'is_bot'       => 'boolean',
        ]);

        $user   = $request->user();
        $wallet = $this->getOrCreateWallet($user->id);

        // Vérification du solde disponible
        $margin = (float) $validated['margin'];
        if ($validated['account_type'] === 'demo') {
            if ($wallet->demo_balance < $margin) {
                return response()->json(['error' => 'Marge insuffisante (compte démo)'], 422);
            }
        } else {
            if ($wallet->balance < $margin) {
                return response()->json(['error' => 'Marge insuffisante (compte réel)'], 422);
            }
        }

        // Résolution de l'instrument
        $instrument = Instrument::where('symbol', $validated['symbol'])->first();
        
        if (!$instrument) {
            return response()->json([
                'error' => "Instrument '{$validated['symbol']}' non trouvé. Assurez-vous que le symbol existe en base de données.",
                'symbol' => $validated['symbol']
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Déduction de la marge
            if ($validated['account_type'] === 'demo') {
                $wallet->decrement('demo_balance', $margin);
            } else {
                $wallet->decrement('balance', $margin);
                $wallet->increment('margin_used', $margin);
            }

            $trade = Trade::create([
                'user_id'       => $user->id,
                'instrument_id' => $instrument->id,
                'account_type'  => $validated['account_type'],
                'direction'     => $validated['direction'],
                'volume'        => $validated['volume'],
                'margin'        => $margin,
                'contract_size' => $validated['contract_size'],
                'entry_price'   => $validated['entry_price'],
                'stop_loss'     => $validated['stop_loss'] ?? null,
                'take_profit'   => $validated['take_profit'] ?? null,
                'profit_loss'   => 0,
                'status'        => 'open',
                'is_bot'        => $validated['is_bot'] ?? false,
                'opened_at'     => now(),
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'trade_id' => $trade->id,
                'balance'  => $this->balancePayload($wallet->fresh()),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('TradeController::openPosition Error', [
                'user_id'   => $user->id ?? null,
                'symbol'    => $validated['symbol'] ?? null,
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    // ── FERMER UNE POSITION ───────────────────────────────────────────────
    public function closePosition(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exit_price'   => 'required|numeric|min:0',
            'close_reason' => 'nullable|string|max:50',
        ]);

        $user  = $request->user();
        $trade = Trade::with('instrument')
                      ->where('user_id', $user->id)
                      ->where('status', 'open')
                      ->findOrFail($id);

        $wallet    = $this->getOrCreateWallet($user->id);
        $exitPrice = (float) $validated['exit_price'];

        // Calcul du P&L
        $pnl = $trade->direction === 'BUY'
            ? ($exitPrice - (float)$trade->entry_price) * (float)$trade->volume * (float)$trade->contract_size
            : ((float)$trade->entry_price - $exitPrice) * (float)$trade->volume * (float)$trade->contract_size;

        $duration = now()->diffInSeconds($trade->opened_at);

        DB::beginTransaction();
        try {
            $trade->update([
                'exit_price'      => $exitPrice,
                'profit_loss'     => $pnl,
                'status'          => 'closed',
                'close_reason'    => $validated['close_reason'] ?? 'Manuel',
                'closed_at'       => now(),
                'duration_seconds'=> $duration,
            ]);

            // Restitution marge + P&L au bon compte
            $returned = (float)$trade->margin + $pnl;
            if ($trade->account_type === 'demo') {
                $wallet->increment('demo_balance', $returned);
            } else {
                $wallet->increment('balance', $returned);
                $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
            }

            DB::commit();

            $freshWallet = $wallet->fresh();

            // Notification gain/perte avec mise & soldes
            try {
                $user->notify(new \App\Notifications\TradeClosedNotification(
                    pnl:         round($pnl, 2),
                    margin:      (float) $trade->margin,
                    symbol:      $trade->instrument?->symbol ?? 'N/A',
                    accountType: $trade->account_type,
                    demoBalance: (float) $freshWallet->demo_balance,
                    realBalance: (float) $freshWallet->balance,
                    isBot:       (bool) ($trade->is_bot ?? false),
                ));
            } catch (\Throwable $ne) {
                \Log::warning('TradeClosedNotification failed: ' . $ne->getMessage());
            }

            return response()->json([
                'success' => true,
                'pnl'     => round($pnl, 2),
                'balance' => $this->balancePayload($freshWallet),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('TradeController::closePosition Error', [
                'user_id'  => $user->id ?? null,
                'trade_id' => $id,
                'message'  => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    // ── POSITIONS OUVERTES (JSON) ─────────────────────────────────────────
    public function getPositions(Request $request): JsonResponse
    {
        $positions = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->get()
            ->map(fn($t) => $this->formatPosition($t));

        return response()->json($positions);
    }

    // ── HISTORIQUE (JSON) ─────────────────────────────────────────────────
    public function getHistoryData(Request $request): JsonResponse
    {
        $history = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'closed')
            ->when($request->filled('account_type'), fn($q) => $q->where('account_type', $request->account_type))
            ->orderByDesc('closed_at')
            ->limit(100)
            ->get()
            ->map(fn($t) => $this->formatHistory($t));

        return response()->json($history);
    }

    // ── SOLDES (JSON) ─────────────────────────────────────────────────────
    public function getBalance(Request $request): JsonResponse
    {
        $wallet = $this->getOrCreateWallet($request->user()->id);
        return response()->json($this->balancePayload($wallet));
    }

    // ── METTRE À JOUR LES SOLDES (SYNCHRONISATION) ────────────────────────
    public function updateBalance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'demo_balance' => 'nullable|numeric|min:0',
            'real_balance' => 'nullable|numeric|min:0',
            'margin_used'  => 'nullable|numeric|min:0',
        ]);

        $wallet = $this->getOrCreateWallet($request->user()->id);
        $updated = false;

        if (isset($validated['demo_balance'])) {
            $wallet->update(['demo_balance' => $validated['demo_balance']]);
            $updated = true;
        }

        if (isset($validated['real_balance'])) {
            $wallet->update(['balance' => $validated['real_balance']]);
            $updated = true;
        }

        if (isset($validated['margin_used'])) {
            $wallet->update(['margin_used' => $validated['margin_used']]);
            $updated = true;
        }

        if ($updated) {
            $wallet->refresh();
        }

        return response()->json([
            'success' => true,
            'balance' => $this->balancePayload($wallet)
        ]);
    }

    // ── TRADE BOT (FoxBot) ────────────────────────────────────────────────
    public function foxbotTrade(Request $request): JsonResponse
    {
        // Réutilise openPosition avec is_bot=true
        $request->merge(['is_bot' => true]);
        return $this->openPosition($request);
    }

    public function foxbotTick(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'is_win'       => 'required|boolean',
            'amount'       => 'required|numeric|min:0',
            'account_type' => 'required|in:demo,real',
        ]);

        $user = $request->user();
        $botState = cache()->get('user_' . $user->id . '_bot_state', []);

        if (empty($botState['bot_active'])) {
            return response()->json(['success' => false, 'reason' => 'bot_not_active']);
        }

        $wallet = $this->getOrCreateWallet($user->id);
        $field = $validated['account_type'] === 'demo' ? 'demo_balance' : 'balance';
        $amount = min((float) $validated['amount'], 5000);

        if ($validated['is_win']) {
            $wallet->increment($field, $amount);
        } else {
            $wallet->decrement($field, $amount);
        }

        return response()->json([
            'success' => true,
            'balance' => $this->balancePayload($wallet->fresh()),
            'trade_result' => [
                'is_win' => $validated['is_win'],
                'amount' => $amount
            ]
        ]);
    }

    // ── PAGE HISTORIQUE (VUE BLADE) ───────────────────────────────────────
    public function history(Request $request): \Illuminate\View\View
    {
        $user  = $request->user();
        $query = Trade::with('instrument')
            ->where('user_id', $user->id)
            ->where('status', 'closed');

        if ($request->filled('symbol')) {
            $query->whereHas('instrument', fn($q) => $q->where('symbol', $request->symbol));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('result')) {
            $request->result === 'profit'
                ? $query->where('profit_loss', '>=', 0)
                : $query->where('profit_loss', '<', 0);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('closed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('closed_at', '<=', $request->date_to);
        }

        $trades = $query->orderByDesc('closed_at')->paginate(20)->withQueryString();

        $allClosed   = Trade::where('user_id', $user->id)->where('status', 'closed');
        $totalTrades = (clone $allClosed)->count();
        $winCount    = (clone $allClosed)->where('profit_loss', '>=', 0)->count();
        $winRate     = $totalTrades > 0 ? round($winCount / $totalTrades * 100, 1) : 0;
        $totalProfit = (clone $allClosed)->where('profit_loss', '>=', 0)->sum('profit_loss');
        $totalLoss   = (clone $allClosed)->where('profit_loss', '<', 0)->sum('profit_loss');
        $bestTrade   = (clone $allClosed)->max('profit_loss');
        $worstTrade  = (clone $allClosed)->min('profit_loss');
        $avgDuration = (clone $allClosed)->avg('duration_seconds');

        $chartTrades = (clone $allClosed)->orderBy('closed_at')->get(['closed_at', 'profit_loss']);
        $cumulative  = 0;
        $chartData   = $chartTrades->map(function ($t) use (&$cumulative) {
            $cumulative += (float) $t->profit_loss;
            return ['date' => $t->closed_at->format('d/m'), 'pnl' => round($cumulative, 2)];
        });

        return view('dashboard.history', compact(
            'trades', 'totalTrades', 'winRate', 'totalProfit',
            'totalLoss', 'bestTrade', 'worstTrade', 'avgDuration', 'chartData'
        ));
    }

    // ── HELPERS PRIVÉS ────────────────────────────────────────────────────
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
            'real_balance' => (float) $wallet->balance,
            'demo_balance' => (float) $wallet->demo_balance,
            'margin_used'  => (float) $wallet->margin_used,
        ];
    }

    private function formatPosition(Trade $t): array
    {
        return [
            'id'            => $t->id,
            'symbol'        => $t->instrument?->symbol ?? '—',
            'side'          => strtolower($t->direction),
            'qty'           => (float) $t->volume,
            'entry'         => (float) $t->entry_price,
            'sl'            => $t->stop_loss ? (float) $t->stop_loss : null,
            'tp'            => $t->take_profit ? (float) $t->take_profit : null,
            'margin'        => (float) $t->margin,
            'contract_size' => (float) $t->contract_size,
            'account_type'  => $t->account_type,
            'is_bot'        => (bool) $t->is_bot,
            'time'          => $t->opened_at->timestamp * 1000,
        ];
    }

    private function formatHistory(Trade $t): array
    {
        return [
            'id'           => $t->id,
            'symbol'       => $t->instrument?->symbol ?? '—',
            'side'         => strtolower($t->direction),
            'qty'          => (float) $t->volume,
            'entry'        => (float) $t->entry_price,
            'exit'         => (float) ($t->exit_price ?? 0),
            'pnl'          => (float) $t->profit_loss,
            'reason'       => $t->close_reason ?? 'Manuel',
            'account_type' => $t->account_type,
            'is_bot'       => (bool) $t->is_bot,
            'time'         => $t->closed_at?->timestamp * 1000 ?? 0,
        ];
    }

    // ── STATS / GRAPHES (garder compatibilité routes existantes) ──────────
    public function stats(Request $request): \Illuminate\View\View
    {
        return view('trades.stats', ['user' => $request->user()]);
    }

    public function chartData(): JsonResponse
    {
        return response()->json([]);
    }

    public function openPositions(Request $request): \Illuminate\View\View
    {
        $positions = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->get();
        return view('trades.open', compact('positions'));
    }

    public function close(Request $request, $id): JsonResponse
    {
        // Redirection vers closePosition avec prix de fermeture au prix actuel
        $trade = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->findOrFail($id);

        $instrument = $trade->instrument;
        if (!$instrument) {
            return response()->json(['error' => 'Instrument non trouvé'], 404);
        }

        // Utiliser le bid ou ask selon la direction
        $exitPrice = $trade->direction === 'BUY'
            ? (float) $instrument->bid
            : (float) $instrument->ask;

        // Appeler la vraie méthode de fermeture
        return $this->closePosition(
            new Request([
                'exit_price' => $exitPrice,
                'close_reason' => 'Fermeture automatique',
            ]),
            $id
        );
    }

    // ── COINGECKO PROXY (CORS workaround) ──────────────────────────────────
    /**
     * Proxy pour CoinGecko /simple/price endpoint
     * Élimine les problèmes CORS en faisant la requête côté serveur
     */
    public function getCoinGeckoPrice(Request $request): JsonResponse
    {
        try {
            $ids = $request->query('ids', 'bitcoin,ethereum');
            $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids'                   => $ids,
                'vs_currencies'         => 'usd',
                'include_24hr_change'   => 'true',
                'include_24hr_vol'      => 'true',
                'include_market_cap'    => 'true',
                'include_high_low_24h'  => 'true',
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            \Log::warning('CoinGecko API error', ['status' => $response->status(), 'ids' => $ids]);
            return response()->json(['error' => 'CoinGecko API failed'], 503);
        } catch (\Throwable $e) {
            \Log::error('CoinGecko proxy error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Proxy error'], 500);
        }
    }

    /**
     * Proxy pour CoinGecko /coins/{id}/ohlc endpoint
     */
    public function getCoinGeckoOHLC(Request $request): JsonResponse
    {
        try {
            $coinId = $request->query('id', 'bitcoin');
            $days   = $request->query('days', '1');

            $response = Http::timeout(10)->get("https://api.coingecko.com/api/v3/coins/{$coinId}/ohlc", [
                'vs_currency' => 'usd',
                'days'        => $days,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            \Log::warning('CoinGecko OHLC error', ['status' => $response->status(), 'id' => $coinId, 'days' => $days]);
            return response()->json(['error' => 'CoinGecko OHLC failed'], 503);
        } catch (\Throwable $e) {
            \Log::error('CoinGecko OHLC proxy error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Proxy error'], 500);
        }
    }

    public function closeAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        Trade::where('user_id', $request->user()->id)
             ->where('status', 'open')
             ->update(['status' => 'closed', 'closed_at' => now()]);
        return back()->with('success', 'Toutes les positions fermées.');
    }

    // ── OBTENIR LE P&L EN TEMPS RÉEL ──────────────────────────────────────
    public function getLivePositionPnL(Request $request, int $id): JsonResponse
    {
        $trade = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->findOrFail($id);

        $instrument = $trade->instrument;
        if (!$instrument) {
            return response()->json(['error' => 'Instrument non trouvé'], 404);
        }

        // Utiliser le prix bid/ask selon la direction
        $currentPrice = $trade->direction === 'BUY' 
            ? (float) $instrument->bid 
            : (float) $instrument->ask;

        // Calcul du P&L
        $pnl = $trade->direction === 'BUY'
            ? ($currentPrice - (float) $trade->entry_price) * (float) $trade->volume * (float) $trade->contract_size
            : ((float) $trade->entry_price - $currentPrice) * (float) $trade->volume * (float) $trade->contract_size;

        $pnlPercent = $trade->margin > 0 ? ($pnl / (float) $trade->margin) * 100 : 0;

        return response()->json([
            'trade_id'     => $trade->id,
            'symbol'       => $instrument->symbol,
            'entry_price'  => (float) $trade->entry_price,
            'current_price'=> $currentPrice,
            'pnl'          => round($pnl, 2),
            'pnl_percent'  => round($pnlPercent, 2),
            'direction'    => $trade->direction,
            'volume'       => (float) $trade->volume,
            'margin'       => (float) $trade->margin,
            'status'       => $trade->status,
            'opened_at'    => $trade->opened_at,
            'bid'          => (float) $instrument->bid,
            'ask'          => (float) $instrument->ask,
        ]);
    }

    // ── OBTENIR TOUS LES P&L EN TEMPS RÉEL ─────────────────────────────────
    public function getAllLivePositionsPnL(Request $request): JsonResponse
    {
        $positions = Trade::with('instrument')
            ->where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->get();

        $data = $positions->map(function ($trade) {
            $instrument = $trade->instrument;
            if (!$instrument) return null;

            $currentPrice = $trade->direction === 'BUY' 
                ? (float) $instrument->bid 
                : (float) $instrument->ask;

            $pnl = $trade->direction === 'BUY'
                ? ($currentPrice - (float) $trade->entry_price) * (float) $trade->volume * (float) $trade->contract_size
                : ((float) $trade->entry_price - $currentPrice) * (float) $trade->volume * (float) $trade->contract_size;

            $pnlPercent = $trade->margin > 0 ? ($pnl / (float) $trade->margin) * 100 : 0;

            return [
                'trade_id'     => $trade->id,
                'symbol'       => $instrument->symbol,
                'direction'    => $trade->direction,
                'entry_price'  => (float) $trade->entry_price,
                'current_price'=> $currentPrice,
                'pnl'          => round($pnl, 2),
                'pnl_percent'  => round($pnlPercent, 2),
                'volume'       => (float) $trade->volume,
                'margin'       => (float) $trade->margin,
                'bid'          => (float) $instrument->bid,
                'ask'          => (float) $instrument->ask,
            ];
        })->filter();

        return response()->json($data);
    }

    // ── POPULER LES INSTRUMENTS (pour développement) ───────────────────────
    public function seedInstruments(): JsonResponse
    {
        $existingCount = Instrument::count();
        if ($existingCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Instruments déjà présents ($existingCount). Impossible de seed.",
            ], 409);
        }

        $instruments = [
            ['symbol' => 'EURUSD', 'name' => 'Euro / US Dollar', 'category' => 'forex', 'bid' => 1.08450, 'ask' => 1.08465, 'specs' => json_encode(['leverage' => 30, 'contract_size' => 100000])],
            ['symbol' => 'GBPUSD', 'name' => 'British Pound / US Dollar', 'category' => 'forex', 'bid' => 1.26480, 'ask' => 1.26495, 'specs' => json_encode(['leverage' => 30, 'contract_size' => 100000])],
            ['symbol' => 'USDJPY', 'name' => 'US Dollar / Japanese Yen', 'category' => 'forex', 'bid' => 150.200, 'ask' => 150.220, 'specs' => json_encode(['leverage' => 30, 'contract_size' => 100000])],
            ['symbol' => 'BTCUSD', 'name' => 'Bitcoin / US Dollar', 'category' => 'crypto', 'bid' => 64500.00, 'ask' => 64600.00, 'specs' => json_encode(['leverage' => 5, 'contract_size' => 1])],
            ['symbol' => 'ETHUSD', 'name' => 'Ethereum / US Dollar', 'category' => 'crypto', 'bid' => 3350.00, 'ask' => 3360.00, 'specs' => json_encode(['leverage' => 5, 'contract_size' => 1])],
            ['symbol' => 'US30', 'name' => 'US Wall Street 30', 'category' => 'indices', 'bid' => 38500.00, 'ask' => 38520.00, 'specs' => json_encode(['leverage' => 20, 'contract_size' => 1])],
            ['symbol' => 'US100', 'name' => 'US Tech 100', 'category' => 'indices', 'bid' => 16800.00, 'ask' => 16815.00, 'specs' => json_encode(['leverage' => 20, 'contract_size' => 1])],
            ['symbol' => 'XAUUSD', 'name' => 'Gold / US Dollar', 'category' => 'commodities', 'bid' => 2320.50, 'ask' => 2321.00, 'specs' => json_encode(['leverage' => 20, 'contract_size' => 100])],
            ['symbol' => 'XTIUSD', 'name' => 'US Crude Oil', 'category' => 'commodities', 'bid' => 78.50, 'ask' => 78.60, 'specs' => json_encode(['leverage' => 10, 'contract_size' => 1000])],
        ];

        $count = 0;
        foreach ($instruments as $inst) {
            $spread = $inst['ask'] - $inst['bid'];
            Instrument::create([
                'symbol' => $inst['symbol'],
                'name' => $inst['name'],
                'category' => $inst['category'],
                'bid' => $inst['bid'],
                'ask' => $inst['ask'],
                'spread' => $spread,
                'specs' => $inst['specs'],
                'is_active' => true,
            ]);
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "✅ $count instruments créés avec succès",
            'count' => $count,
        ]);
    }

    // ── RECONNECT GAIN (bot simule son activité pendant l'absence) ────────
    public function applyReconnectGain(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gain'          => 'required|numeric|min:0|max:100000',
            'elapsed_hours' => 'required|numeric|min:0|max:720',
            'account_type'  => 'required|in:demo,real',
        ]);

        $user     = $request->user();
        $botState = cache()->get('user_' . $user->id . '_bot_state', []);

        if (empty($botState['bot_active'])) {
            return response()->json(['applied' => false, 'reason' => 'bot_not_active']);
        }

        $gain   = round(min((float) $validated['gain'], 50000), 2);
        $wallet = $user->wallet ?? Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'demo_balance' => 10000]
        );

        $field = $validated['account_type'] === 'demo' ? 'demo_balance' : 'balance';
        $wallet->increment($field, $gain);

        return response()->json([
            'applied'       => true,
            'gain'          => $gain,
            'elapsed_hours' => $validated['elapsed_hours'],
            'balance'       => [
                'demo_balance' => (float) $wallet->fresh()->demo_balance,
                'real_balance' => (float) $wallet->fresh()->balance,
            ],
        ]);
    }
}
