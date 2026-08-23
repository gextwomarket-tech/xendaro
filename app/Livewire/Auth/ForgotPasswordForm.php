<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

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

        Password::sendResetLink(['email' => $this->email]);

        // Reponse volontairement identique que l'email existe ou non (anti enumeration).
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-form');
    }
}
