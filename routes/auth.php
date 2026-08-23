<?php

use Illuminate\Support\Facades\Route;

/*
 * Routes d'authentification (Pages id 25 a 30 dans xendaro-fox-plan.json).
 * Module reserve aux sous-agents "auth" - ne pas toucher aux autres fichiers routes/*.php.
 * Layout de reference: resources/views/components/layouts/auth.blade.php (2 colonnes, voir images_design_ui/login_design.jpg)
 */

// TODO (sous-agent auth): implementer avec des composants Livewire (RegisterForm, LoginForm, ...)
// Route::get('/inscription', App\Livewire\Auth\Register::class)->name('register')->middleware('guest');
// Route::get('/connexion', App\Livewire\Auth\Login::class)->name('login')->middleware('guest');
// Route::get('/mot-de-passe-oublie', App\Livewire\Auth\ForgotPassword::class)->name('forgot-password')->middleware('guest');
// Route::get('/reinitialiser-mot-de-passe/{token}', App\Livewire\Auth\ResetPassword::class)->name('reset-password')->middleware('guest');
// Route::get('/verification-email', App\Livewire\Auth\VerifyEmail::class)->name('verify-email')->middleware('auth');
// Route::get('/2fa', App\Livewire\Auth\TwoFactor::class)->name('two-factor-auth')->middleware('auth');
