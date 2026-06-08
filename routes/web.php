<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\TradeController;
use App\Http\Controllers\Web\MarketController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\ReferralController;
use App\Http\Controllers\Web\AnalyticsController;
use App\Http\Controllers\Web\SupportController;
use App\Http\Controllers\Web\PaymentMethodController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\KycController;
use App\Http\Middleware\VerifyKyc;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════
//  PUBLIC ROUTES (Non-authentifiées)
// ═══════════════════════════════════════════════════════════════

Route::get('/', function () {
    return view('landing');
})->name('home');

// ── ALIAS ROUTES (compatibilité avec certains middlewares Laravel) ───
Route::redirect('/login', '/auth/login')->name('login');
Route::redirect('/register', '/auth/register')->name('register');

// ── PUBLIC STATIC PAGES ───────────────────────────────────────────
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/conditions', [PageController::class, 'conditions'])->name('conditions');
Route::get('/policies', [PageController::class, 'policies'])->name('policies');

// ── AUTHENTICATION (Auth pages & forms) ─────────────────────────
Route::middleware('guest')->group(function () {
    // Pages (GET)
    Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::get('/auth/forgot-password', [AuthController::class, 'showForgotPassword'])->name('auth.forgot-password');
    Route::get('/auth/reset-password', [AuthController::class, 'showResetPassword'])->name('auth.reset-password');
    Route::get('/auth/verify-email', [AuthController::class, 'showVerifyEmail'])->name('auth.verify-email');
    Route::get('/auth/2fa', [AuthController::class, 'show2FA'])->name('auth.2fa');

    // Forms (POST)
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register.post');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.post');
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password.post');
    Route::post('/auth/verify-code', [AuthController::class, 'verifyCode'])->name('auth.verify-code.post');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password.post');
    Route::post('/auth/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify-email.post');
    Route::post('/auth/resend-verification', [AuthController::class, 'resendVerificationEmail'])->name('auth.resend-verification.post');
    Route::post('/auth/verify-2fa', [AuthController::class, 'verify2fa'])->name('auth.verify-2fa.post');
});

// ── PUBLIC MARKETS & INFO ────────────────────────────────────────
Route::get('/instruments', [MarketController::class, 'instruments'])->name('instruments');
Route::get('/instruments/prices', [MarketController::class, 'prices'])->name('instruments.prices');
Route::get('/charts/{symbol}/{timeframe}', [MarketController::class, 'chartData'])->name('charts.data');
Route::post('/contact', [SupportController::class, 'contact'])->name('contact.post');
Route::post('/newsletter/subscribe', [SupportController::class, 'subscribeNewsletter'])->name('newsletter.subscribe.post');
Route::get('/faqs', [SupportController::class, 'faqs'])->name('faqs');

// ═══════════════════════════════════════════════════════════════
//  PROTECTED ROUTES (auth middleware)
// ═══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {

    // ── LOGOUT ──────────────────────────────────────────────────
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // ── KYC & PROFILE (NO KYC REQUIRED - User must complete KYC first) ────
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.post');
    Route::get('/profile/kyc', [ProfileController::class, 'kycStatus'])->name('profile.kyc');
    Route::delete('/profile/account', [ProfileController::class, 'destroy'])->name('profile.account.delete');
    
    // ── KYC VERIFICATION (User must complete this before accessing dashboard) ────
    Route::get('/kyc/verify', [KycController::class, 'show'])->name('kyc.show');
    Route::post('/kyc/verify', [KycController::class, 'store'])->name('kyc.store');
    
    // ── 2FA MANAGEMENT (No KYC required - Security settings) ──────────────────
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.put');
    Route::get('/auth/2fa/setup', [AuthController::class, 'setup2FA'])->name('auth.2fa.setup');
    Route::post('/auth/2fa/enable', [AuthController::class, 'enable2fa'])->name('auth.2fa.enable.post');
    Route::post('/auth/2fa/confirm', [AuthController::class, 'confirm2fa'])->name('auth.2fa.confirm.post');
    Route::delete('/auth/2fa', [AuthController::class, 'disable2fa'])->name('auth.2fa.disable');
    Route::get('/auth/sessions', [AuthController::class, 'sessions'])->name('auth.sessions');
    Route::delete('/auth/sessions/{id}', [AuthController::class, 'revokeSession'])->name('auth.sessions.revoke');

    // ═══════════════════════════════════════════════════════════════════════════
    // PROTECTED ROUTES WITH KYC VERIFICATION
    // ═══════════════════════════════════════════════════════════════════════════
    Route::middleware(VerifyKyc::class)->group(function () {

        // ── DASHBOARD ───────────────────────────────────────────────────
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

        // ── WALLET ──────────────────────────────────────────────────
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::post('/wallet/create', [WalletController::class, 'createWallet'])->name('wallet.create');
        Route::post('/wallet/deposit', [WalletController::class, 'storeDeposit'])->name('wallet.deposit.store');
        Route::post('/wallet/withdraw', [WalletController::class, 'storeWithdraw'])->name('wallet.withdraw.store');
        Route::post('/wallet/transfer', [WalletController::class, 'storeTransfer'])->name('wallet.transfer.store');
        Route::get('/wallet/balance', [WalletController::class, 'balance'])->name('wallet.balance');
        Route::get('/wallet/summary', [WalletController::class, 'summary'])->name('wallet.summary');

        // ── TRANSACTIONS ────────────────────────────────────────────
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/recent', [TransactionController::class, 'recent'])->name('transactions.recent');
        Route::get('/transactions/deposit', [TransactionController::class, 'showDeposit'])->name('transactions.deposit.show');
        Route::post('/transactions/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit.post');
        Route::get('/transactions/withdraw', [TransactionController::class, 'showWithdraw'])->name('transactions.withdraw.show');
        Route::post('/transactions/withdraw', [TransactionController::class, 'withdraw'])->name('transactions.withdraw.post');
        Route::get('/transactions/export', [TransactionController::class, 'exportCsv'])->name('transactions.export');

        // ── PAYMENT METHODS ─────────────────────────────────────────
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

        // ── MARKETS & FAVORITES ────────────────────────────────────
        Route::get('/markets', [MarketController::class, 'showMarkets'])->name('markets.show');
        Route::post('/favorites/{symbol}', [MarketController::class, 'toggleFavorite'])->name('favorites.toggle');

        // ── NOTIFICATIONS ───────────────────────────────────────────
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/notification-preferences', [NotificationController::class, 'preferences'])->name('notification-preferences.show');
        Route::put('/notification-preferences', [NotificationController::class, 'updatePreferences'])->name('notification-preferences.update');

        // ── SUPPORT / TICKETS ───────────────────────────────────────
        Route::get('/support', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/support/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/support', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/support/{id}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/support/{id}/replies', [TicketController::class, 'reply'])->name('tickets.reply');
        Route::put('/support/{id}/close', [TicketController::class, 'close'])->name('tickets.close');
        Route::put('/support/{id}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');

        // ── ANALYTICS ───────────────────────────────────────────────
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/performance', [AnalyticsController::class, 'performance'])->name('analytics.performance');

        // ── REFERRAL ────────────────────────────────────────────────
        Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
        Route::get('/referral/info', [ReferralController::class, 'info'])->name('referral.info');
        Route::get('/referral/referees', [ReferralController::class, 'referees'])->name('referral.referees');
        Route::get('/referral/commissions', [ReferralController::class, 'commissions'])->name('referral.commissions');
        Route::post('/referral/withdraw', [ReferralController::class, 'withdraw'])->name('referral.withdraw');

        // ── TRADING WORKSPACE ───────────────────────────────────
        Route::get('/trade', [TradeController::class, 'index'])->name('trade.index');

        // ── ORDERS ──────────────────────────────────────────────
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // ── TRADES ──────────────────────────────────────────────
        Route::get('/trades/history', [TradeController::class, 'history'])->name('trades.history');
        Route::get('/trades/stats', [TradeController::class, 'stats'])->name('trades.stats');
        Route::get('/trades/chart-data', [TradeController::class, 'chartData'])->name('trades.chart-data');
        Route::get('/trades/open', [TradeController::class, 'openPositions'])->name('trades.open');

        // ── POSITIONS ───────────────────────────────────────────
        Route::post('/positions/{id}/close', [TradeController::class, 'close'])->name('positions.close');
        Route::post('/positions/close-all', [TradeController::class, 'closeAll'])->name('positions.close-all');

        // ── TRADING AJAX API ─────────────────────────────────────
        Route::post('/trade/position/open', [TradeController::class, 'openPosition'])->name('trade.position.open');
        Route::post('/trade/position/{id}/close', [TradeController::class, 'closePosition'])->name('trade.position.close');
        Route::get('/trade/positions', [TradeController::class, 'getPositions'])->name('trade.positions.json');
        Route::get('/trade/history-data', [TradeController::class, 'getHistoryData'])->name('trade.history.json');
        Route::get('/trade/balance', [TradeController::class, 'getBalance'])->name('trade.balance.json');
        Route::post('/trade/foxbot', [TradeController::class, 'foxbotTrade'])->name('trade.foxbot');
        Route::get('/trade/positions/{id}/pnl', [TradeController::class, 'getLivePositionPnL'])->name('trade.position.pnl');
        Route::get('/trade/positions/pnl/all', [TradeController::class, 'getAllLivePositionsPnL'])->name('trade.positions.pnl.all');
        
        // ── COINGECKO PROXY (CORS bypass) ────────────────────────────
        Route::get('/trade/api/coingecko/price', [TradeController::class, 'getCoinGeckoPrice'])->name('trade.api.coingecko.price');
        Route::get('/trade/api/coingecko/ohlc', [TradeController::class, 'getCoinGeckoOHLC'])->name('trade.api.coingecko.ohlc');
        
        // ── SEED INSTRUMENTS (DEV) ──────────────────────────────────
        Route::post('/trade/seed-instruments', [TradeController::class, 'seedInstruments'])->name('trade.seed-instruments');

    }); // End verify.kyc middleware group

});
