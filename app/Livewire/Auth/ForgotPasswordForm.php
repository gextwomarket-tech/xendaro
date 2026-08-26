<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

/**
 * Page id 27 "forgot-password" - utilise le systeme natif Laravel Password::sendResetLink.
 */
#[Layout('components.layouts.auth')]
class ForgotPasswordForm extends Component
{
    public string $email = '';

    public bool $sent = false;

    protected function rules(): array
    {
        return ['email' => ['required', 'email']];
    }

    public function sendResetLink(): void
    {
        $this->validate();

        try {
            Password::sendResetLink(['email' => $this->email]);
        } catch (Throwable $e) {
            // Un SMTP en panne (ex. MAIL_HOST mal configure) ne doit jamais faire planter cette
            // page en 500 - voir App\Services\OtpMailerService pour le meme filet applique aux
            // envois d'OTP. $sent reste a true malgre l'echec : conserve le comportement
            // "anti enumeration" existant (ne pas reveler si l'email existe via un 500 ici alors
            // qu'un email inexistant ne provoque, lui, jamais d'envoi ni d'exception).
            Log::error('Echec envoi email de reinitialisation de mot de passe', [
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);
        }

        // Reponse volontairement identique que l'email existe ou non (anti enumeration).
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-form');
    }
}
