<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FoxBotController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TradeController;
use App\Http\Controllers\Api\TradeOperationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════
//  PUBLIC ROUTES
// ═══════════════════════════════════════════════════════════════

Route::prefix('auth')->group(function () {
    Route::post('/register',           [AuthController::class, 'register']);
    Route::post('/login',              [AuthController::class, 'login']);
    Route::post('/verify-2fa',         [AuthController::class, 'verify2fa']);
    Route::post('/forgot-password',     [AuthController::class, 'forgotPassword']);
    Route::post('/verify-code',         [AuthController::class, 'verifyCode']);
    Route::post('/reset-password',      [AuthController::class, 'resetPassword']);
    Route::post('/verify-email',        [AuthController::class, 'verifyEmail']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerificationEmail']);
});

Route::post('/contact',             [ContactController::class, 'store']);
Route::post('/newsletter/subscribe', [SupportController::class, 'subscribeNewsletter']);
Route::get('/faqs',                  [SupportController::class, 'faqs']);

Route::get('/instruments',           [MarketController::class, 'instruments']);
Route::get('/instruments/prices',     [MarketController::class, 'prices']);
Route::get('/charts/{symbol}/{timeframe}', [MarketController::class, 'chartData']);

// ═══════════════════════════════════════════════════════════════
//  PROTECTED ROUTES (auth:sanctum)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->group(function () {

    // ── Auth ───────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::get('/me',            [AuthController::class, 'me']);
        Route::post('/logout',       [AuthController::class, 'logout']);
        Route::put('/password',      [AuthController::class, 'changePassword']);
        Route::post('/2fa/enable',   [AuthController::class, 'enable2fa']);
        Route::post('/2fa/confirm',  [AuthController::class, 'confirm2fa']);
        Route::delete('/2fa',         [AuthController::class, 'disable2fa']);
        Route::get('/sessions',       [AuthController::class, 'sessions']);
        Route::delete('/sessions/{id}', [AuthController::class, 'revokeSession']);
    });

    // ── Profile ────────────────────────────────────────────────
    Route::prefix('profile')->group(function () {
        Route::get('/',              [ProfileController::class, 'show']);
        Route::put('/',              [ProfileController::class, 'update']);
        Route::post('/avatar',       [ProfileController::class, 'uploadAvatar']);
        Route::get('/kyc',           [ProfileController::class, 'kycStatus']);
        Route::delete('/account',     [ProfileController::class, 'destroy']);
    });

    // ── Dashboard ──────────────────────────────────────────────
    Route::get('/dashboard/summary',  [DashboardController::class, 'summary']);

    // ── Wallet ─────────────────────────────────────────────────
    Route::prefix('wallet')->group(function () {
        Route::get('/balance',       [WalletController::class, 'balance']);
        Route::get('/summary',       [WalletController::class, 'summary']);
    });

    // ── Transactions ───────────────────────────────────────────
    Route::prefix('transactions')->group(function () {
        Route::get('/',              [TransactionController::class, 'index']);
        Route::get('/recent',        [TransactionController::class, 'recent']);
        Route::post('/deposit',      [TransactionController::class, 'deposit']);
        Route::post('/withdraw',     [TransactionController::class, 'withdraw']);
        Route::post('/transfer',     [TransactionController::class, 'transfer']);
        Route::get('/export',        [TransactionController::class, 'exportCsv']);
    });

    // ── Payment Methods ────────────────────────────────────────
    Route::apiResource('payment-methods', PaymentMethodController::class)
        ->only(['index', 'store', 'destroy'])
        ->names([
            'index' => 'api.payment-methods.index',
            'store' => 'api.payment-methods.store',
            'destroy' => 'api.payment-methods.destroy',
        ]);

    // ── Trading (Orders / Trades / Positions) ─────────────────
    Route::middleware('verified.trader')->group(function () {
        Route::apiResource('orders', OrderController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names([
                'index' => 'api.orders.index',
                'store' => 'api.orders.store',
                'update' => 'api.orders.update',
                'destroy' => 'api.orders.destroy',
            ]);

        Route::prefix('trades')->group(function () {
            Route::get('/history',       [TradeController::class, 'history']);
            Route::get('/stats',         [TradeController::class, 'stats']);
            Route::get('/chart-data',    [TradeController::class, 'chartData']);
            Route::get('/open',          [TradeController::class, 'openPositions']);
        });
        Route::prefix('positions')->group(function () {
            Route::post('/{id}/close',   [TradeController::class, 'close']);
            Route::post('/close-all',    [TradeController::class, 'closeAll']);
        });

        // ── Trade Operations (for trade.html real-time interface) ────
        Route::prefix('trade/operations')->group(function () {
            Route::post('/open',              [TradeOperationController::class, 'openPosition']);
            Route::post('/{id}/close',        [TradeOperationController::class, 'closePosition']);
            Route::post('/close-all',         [TradeOperationController::class, 'closeAllPositions']);
            Route::get('/pnl',                [TradeOperationController::class, 'getCurrentPnl']);
            Route::patch('/{id}/update-levels', [TradeOperationController::class, 'updateLevels']);
            Route::get('/orders',             [TradeOperationController::class, 'getPendingOrders']);
            Route::post('/orders',            [TradeOperationController::class, 'createOrder']);
            Route::delete('/orders/{id}',     [TradeOperationController::class, 'cancelOrder']);

            // ── FoxBot Operations ────────────────────────────────────────
            Route::post('/foxbot',                  [FoxBotController::class, 'openBotPosition']);
            Route::post('/foxbot/{id}/close',      [FoxBotController::class, 'closeBotPosition']);
            Route::post('/foxbot/close-all',       [FoxBotController::class, 'closeAllBotPositions']);
            Route::get('/foxbot/stats',            [FoxBotController::class, 'getBotStats']);
        });
    });

    // ── Markets & Favorites ──────────────────────────────────
    Route::post('/favorites/{symbol}', [MarketController::class, 'toggleFavorite']);

    // ── Analytics ────────────────────────────────────────────
    Route::get('/analytics/performance', [AnalyticsController::class, 'performance']);

    // ── Notifications ────────────────────────────────────────
    Route::prefix('notifications')->group(function () {
        Route::get('/',              [NotificationController::class, 'index']);
        Route::get('/unread-count',  [NotificationController::class, 'unreadCount']);
        Route::put('/read-all',      [NotificationController::class, 'markAllAsRead']);
        Route::put('/{id}/read',     [NotificationController::class, 'markAsRead']);
        Route::delete('/{id}',       [NotificationController::class, 'destroy']);
    });
    Route::get('/notification-preferences',   [NotificationController::class, 'preferences']);
    Route::put('/notification-preferences',   [NotificationController::class, 'updatePreferences']);

    // ── Support / Tickets ────────────────────────────────────
    Route::apiResource('tickets', TicketController::class)
        ->only(['index', 'store', 'show'])
        ->names([
            'index' => 'api.tickets.index',
            'store' => 'api.tickets.store',
            'show' => 'api.tickets.show',
        ]);
    Route::prefix('tickets')->group(function () {
        Route::post('/{id}/replies', [TicketController::class, 'reply']);
        Route::put('/{id}/close',    [TicketController::class, 'close']);
        Route::put('/{id}/reopen',   [TicketController::class, 'reopen']);
    });

    // ── Referral ─────────────────────────────────────────────
    Route::prefix('referral')->group(function () {
        Route::get('/info',          [ReferralController::class, 'info']);
        Route::get('/referees',      [ReferralController::class, 'referees']);
        Route::get('/commissions',   [ReferralController::class, 'commissions']);
        Route::post('/withdraw',     [ReferralController::class, 'withdraw']);
    });

    // ─────────────────────────────────────────────────────────────────
    // 🚀 TRADE PILOTIQ — 5 routes directes (pas de controller séparé)
    // Préfixe : /api/pilotiq/
    // ─────────────────────────────────────────────────────────────────
    Route::prefix('pilotiq')->group(function () {

        // ── ROUTE 1 : Auth check + info utilisateur ───────────────────
        Route::get('/auth-check', function (\Illuminate\Http\Request $request) {
            $user   = $request->user();
            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000]
            );
            $botState = cache()->get("user_{$user->id}_bot_state", [
                'bot_active' => false, 'bot_activated_at' => null, 'account_type' => 'demo'
            ]);

            return response()->json([
                'authenticated' => true,
                'token_valid'   => true,
                'user' => [
                    'id'     => $user->id,
                    'name'   => $user->first_name ?? $user->name,
                    'email'  => $user->email,
                    'avatar' => strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 2)),
                    'kyc'    => $user->kyc_status ?? 'unverified',
                ],
                'balance' => [
                    'real' => (float) $wallet->balance,
                    'demo' => (float) $wallet->demo_balance,
                    'margin_used' => (float) $wallet->margin_used,
                ],
                'bot_state' => $botState,
            ]);
        });

        // ── ROUTE 2 : Récupération de toutes les données ──────────────
        Route::get('/data', function (\Illuminate\Http\Request $request) {
            $user   = $request->user();
            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000]
            );

            $positions = \App\Models\Trade::with('instrument')
                ->where('user_id', $user->id)
                ->where('status', 'open')
                ->orderByDesc('opened_at')
                ->get()
                ->map(fn($t) => [
                    'id'           => $t->id,
                    'symbol'       => $t->instrument?->symbol ?? '—',
                    'side'         => strtolower($t->direction),
                    'qty'          => (float) $t->volume,
                    'entry'        => (float) $t->entry_price,
                    'sl'           => $t->stop_loss  ? (float) $t->stop_loss  : null,
                    'tp'           => $t->take_profit ? (float) $t->take_profit : null,
                    'margin'       => (float) $t->margin,
                    'account_type' => $t->account_type,
                    'is_bot'       => (bool) $t->is_bot,
                    'time'         => $t->opened_at?->timestamp * 1000 ?? 0,
                ]);

            $history = \App\Models\Trade::with('instrument')
                ->where('user_id', $user->id)
                ->where('status', 'closed')
                ->orderByDesc('closed_at')
                ->limit(50)
                ->get()
                ->map(fn($t) => [
                    'id'           => $t->id,
                    'symbol'       => $t->instrument?->symbol ?? '—',
                    'side'         => strtolower($t->direction),
                    'qty'          => (float) $t->volume,
                    'entry'        => (float) $t->entry_price,
                    'exit'         => (float) ($t->exit_price ?? 0),
                    'pnl'          => (float) $t->profit_loss,
                    'account_type' => $t->account_type,
                    'is_bot'       => (bool) $t->is_bot,
                    'time'         => $t->closed_at?->timestamp * 1000 ?? 0,
                ]);

            $botState = cache()->get("user_{$user->id}_bot_state", [
                'bot_active' => false, 'bot_activated_at' => null, 'account_type' => 'demo'
            ]);

            // Recalcul du gain bot offline (simulation)
            $botGainSinceDisconnect = 0;
            if (!empty($botState['bot_activated_at']) && $botState['bot_active']) {
                $activatedAt  = \Carbon\Carbon::parse($botState['bot_activated_at']);
                $secondsOnline= now()->diffInSeconds($activatedAt);
                $balance      = $botState['account_type'] === 'real' ? (float) $wallet->balance : (float) $wallet->demo_balance;
                $hourlyRate   = 0.035; // ~3.5% / heure
                $botGainSinceDisconnect = $balance * $hourlyRate * ($secondsOnline / 3600);
            }

            return response()->json([
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->first_name ?? $user->name,
                    'email' => $user->email,
                ],
                'balance' => [
                    'real'        => (float) $wallet->balance,
                    'demo'        => (float) $wallet->demo_balance,
                    'margin_used' => (float) $wallet->margin_used,
                ],
                'positions'  => $positions,
                'history'    => $history,
                'bot_state'  => $botState,
                'bot_offline_gain' => round($botGainSinceDisconnect, 2),
            ]);
        });

        // ── ROUTE 3 : Ouvrir une position ────────────────────────────
        Route::post('/position', function (\Illuminate\Http\Request $request) {
            $v = $request->validate([
                'symbol'        => 'required|string',
                'direction'     => 'required|in:BUY,SELL',
                'volume'        => 'required|numeric|min:0.01',
                'entry_price'   => 'required|numeric|min:0',
                'stop_loss'     => 'nullable|numeric|min:0',
                'take_profit'   => 'nullable|numeric|min:0',
                'margin'        => 'required|numeric|min:0',
                'contract_size' => 'required|numeric|min:0',
                'account_type'  => 'required|in:demo,real',
                'is_bot'        => 'boolean',
            ]);

            $user   = $request->user();
            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'trading_balance' => 0, 'demo_balance' => 10000]
            );
            $margin = (float) $v['margin'];

            if ($v['account_type'] === 'demo' && $wallet->demo_balance < $margin) {
                return response()->json(['error' => 'Marge insuffisante (compte démo)'], 422);
            }
            if ($v['account_type'] === 'real' && $wallet->balance < $margin) {
                return response()->json(['error' => 'Marge insuffisante (compte réel)'], 422);
            }

            $instrument = \App\Models\Instrument::where('symbol', $v['symbol'])->first();

            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                if ($v['account_type'] === 'demo') {
                    $wallet->decrement('demo_balance', $margin);
                } else {
                    $wallet->decrement('balance', $margin);
                    $wallet->increment('margin_used', $margin);
                }

                $trade = \App\Models\Trade::create([
                    'user_id'       => $user->id,
                    'instrument_id' => $instrument?->id,
                    'account_type'  => $v['account_type'],
                    'direction'     => $v['direction'],
                    'volume'        => $v['volume'],
                    'margin'        => $margin,
                    'contract_size' => $v['contract_size'],
                    'entry_price'   => $v['entry_price'],
                    'stop_loss'     => $v['stop_loss'] ?? null,
                    'take_profit'   => $v['take_profit'] ?? null,
                    'profit_loss'   => 0,
                    'status'        => 'open',
                    'is_bot'        => $v['is_bot'] ?? false,
                    'opened_at'     => now(),
                ]);

                \Illuminate\Support\Facades\DB::commit();

                $fresh = $wallet->fresh();
                return response()->json([
                    'success'  => true,
                    'trade_id' => $trade->id,
                    'balance'  => [
                        'real_balance' => (float) $fresh->balance,
                        'demo_balance' => (float) $fresh->demo_balance,
                        'margin_used'  => (float) $fresh->margin_used,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
            }
        });

        // ── ROUTE 4 : Fermer une position (met aussi à jour l'historique) ──
        Route::post('/position/{id}/close', function (\Illuminate\Http\Request $request, int $id) {
            $v = $request->validate([
                'exit_price'   => 'required|numeric|min:0',
                'close_reason' => 'nullable|string|max:50',
            ]);

            $user  = $request->user();
            $trade = \App\Models\Trade::where('user_id', $user->id)
                         ->where('status', 'open')
                         ->findOrFail($id);

            $wallet   = \App\Models\Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0, 'demo_balance' => 10000]);
            $exitPx   = (float) $v['exit_price'];
            $pnl      = $trade->direction === 'BUY'
                ? ($exitPx - (float)$trade->entry_price) * (float)$trade->volume * (float)$trade->contract_size
                : ((float)$trade->entry_price - $exitPx) * (float)$trade->volume * (float)$trade->contract_size;
            $duration = now()->diffInSeconds($trade->opened_at);

            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                $trade->update([
                    'exit_price'       => $exitPx,
                    'profit_loss'      => $pnl,
                    'status'           => 'closed',
                    'close_reason'     => $v['close_reason'] ?? 'Manuel',
                    'closed_at'        => now(),
                    'duration_seconds' => $duration,
                ]);

                $returned = (float)$trade->margin + $pnl;
                if ($trade->account_type === 'demo') {
                    $wallet->increment('demo_balance', $returned);
                } else {
                    $wallet->increment('balance', $returned);
                    $wallet->decrement('margin_used', min($trade->margin, $wallet->margin_used));
                }

                \Illuminate\Support\Facades\DB::commit();

                $fresh = $wallet->fresh();
                return response()->json([
                    'success' => true,
                    'pnl'     => round($pnl, 2),
                    'balance' => [
                        'real_balance' => (float) $fresh->balance,
                        'demo_balance' => (float) $fresh->demo_balance,
                        'margin_used'  => (float) $fresh->margin_used,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
            }
        });

        // ── ROUTE 5 : Update soldes + compte actif + état bot ──────────
        Route::patch('/settings', function (\Illuminate\Http\Request $request) {
            $v = $request->validate([
                'account_type'     => 'nullable|in:real,demo',
                'bot_active'       => 'nullable|boolean',
                'bot_activated_at' => 'nullable|string',
                'balance_delta'    => 'nullable|numeric',  // delta solde bot (simulation offline)
            ]);

            $user   = $request->user();
            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'demo_balance' => 10000]
            );

            // Sauvegarder l'état du bot en cache (30 jours)
            $cacheKey = "user_{$user->id}_bot_state";
            $current  = cache()->get($cacheKey, ['bot_active' => false, 'bot_activated_at' => null, 'account_type' => 'demo']);

            $newState = [
                'bot_active'       => $v['bot_active']       ?? $current['bot_active'],
                'bot_activated_at' => $v['bot_activated_at'] ?? $current['bot_activated_at'],
                'account_type'     => $v['account_type']     ?? $current['account_type'],
            ];

            // Si bot désactivé, effacer la date d'activation
            if (isset($v['bot_active']) && !$v['bot_active']) {
                $newState['bot_activated_at'] = null;
            }

            cache()->put($cacheKey, $newState, now()->addDays(30));

            // Appliquer un delta de solde (ex: gain bot simulé offline)
            if (!empty($v['balance_delta'])) {
                $delta = (float) $v['balance_delta'];
                if ($newState['account_type'] === 'demo') {
                    $wallet->increment('demo_balance', $delta);
                } else {
                    $wallet->increment('balance', $delta);
                }
            }

            $fresh = $wallet->fresh();
            return response()->json([
                'success'   => true,
                'bot_state' => $newState,
                'balance'   => [
                    'real' => (float) $fresh->balance,
                    'demo' => (float) $fresh->demo_balance,
                ],
            ]);
        });

    }); // end /api/pilotiq
});

// ═══════════════════════════════════════════════════════════════
//  API EXTERNE V1 — Sécurisée par header X-API-KEY
//  Aucune session requise. Idéale pour intégrations tierces,
//  bots externes, dashboards partenaires, scripts automatisés.
//
//  Base URL : https://xendaro-trade.it.com/api/v1
//  Auth     : Header  X-API-KEY: xendaro-api-2026-secret
// ═══════════════════════════════════════════════════════════════

use App\Http\Controllers\Api\ExternalApiController;

Route::prefix('v1')->group(function () {
    // GET  /api/v1/user          → Infos complètes utilisateur (email obligatoire)
    Route::get('/user',          [ExternalApiController::class, 'getUser']);

    // POST /api/v1/positions     → Insérer une position ouverte
    Route::post('/positions',    [ExternalApiController::class, 'insertPosition']);

    // POST /api/v1/history       → Insérer un historique de trade fermé
    Route::post('/history',      [ExternalApiController::class, 'insertHistory']);

    // PATCH /api/v1/users/update → Mettre à jour les données d'un utilisateur
    Route::patch('/users/update', [ExternalApiController::class, 'updateUser']);
});