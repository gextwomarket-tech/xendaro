<?php

use Illuminate\Support\Facades\Route;

/*
 * Routes d'authentification (Pages id 25 a 30 dans xendaro-fox-plan.json).
 * Module reserve aux sous-agents "auth" - ne pas toucher aux autres fichiers routes/*.php.
 * Layout de reference: resources/views/components/layouts/auth.blade.php (2 colonnes, voir images_design_ui/login_design.jpg)
 * Route de deconnexion (page id 43) definie dans routes/client.php.
 */

Route::middleware(['guest'])->group(function () {
    Route::get('/inscription', App\Livewire\Auth\RegisterForm::class)->name('register');
    Route::get('/connexion', App\Livewire\Auth\LoginForm::class)->name('login');
    Route::get('/mot-de-passe-oublie', App\Livewire\Auth\ForgotPasswordForm::class)->name('forgot-password');
    Route::get('/reinitialiser-mot-de-passe/{token}', App\Livewire\Auth\ResetPasswordForm::class)->name('reset-password');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/verification-email', App\Livewire\Auth\VerifyEmailForm::class)->name('verify-email');
    Route::get('/2fa', App\Livewire\Auth\TwoFactorForm::class)->name('two-factor-auth');
});
