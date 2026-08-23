<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\EnsureEmailIsVerifiedOtp;
use App\Http\Middleware\EnsureTwoFactorVerified;
use Illuminate\Support\Facades\Route;

/*
 * Routes de l'Espace Client (Pages id 31 a 43, hors Trade dans xendaro-fox-plan.json).
 * Module reserve aux sous-agents "espace-client" - ne pas toucher aux autres fichiers routes/*.php.
 * Layout de reference: resources/views/components/layouts/dashboard.blade.php (sidebar + navbar)
 */

Route::post('/deconnexion', LogoutController::class)->middleware(['auth'])->name('logout');

Route::middleware(['auth', EnsureTwoFactorVerified::class, EnsureEmailIsVerifiedOtp::class])
    ->prefix('espace-client')
    ->name('client.')
    ->group(function () {
        Route::get('/', App\Livewire\Client\Dashboard::class)->name('dashboard');
        Route::get('/profil', App\Livewire\Client\Dashboard::class)->name('edit-profile');
        Route::get('/securite', App\Livewire\Client\SecuritySettings::class)->name('security-settings');
        Route::get('/historique-trade', App\Livewire\Client\TradeHistoryPage::class)->name('trade-history');
        Route::get('/marches', App\Livewire\Client\MarketsReadOnly::class)->name('markets');
        Route::get('/wallet', App\Livewire\Client\WalletPage::class)->name('wallet');
        Route::get('/kyc', App\Livewire\Client\KycUploadForm::class)->name('kyc');
        Route::get('/notifications', App\Livewire\Client\Notifications::class)->name('notifications');
        Route::get('/support', App\Livewire\Client\SupportTickets::class)->name('support');
        Route::get('/support/{ticket}', App\Livewire\Client\TicketDetail::class)->name('ticket-detail');
        Route::get('/parrainage', App\Livewire\Client\AffiliateDashboard::class)->name('affiliate-dashboard');
    });
