<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\OtpMailerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 26 "login". Auth::attempt + RateLimiter (protection brute-force),
 * redirection vers /2fa si two_factor_enabled, sinon /espace-client.
 */
#[Layout('components.layouts.auth')]
class LoginForm extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    protected function throttleKey(): string
    {
        return 'login:'.mb_strtolower($this->email).'|'.request()->ip();
    }

    public function login(): void
    {
        $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            $this->addError('email', __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]));

            return;
        }

        if (! Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->throttleKey(), 60);
            $this->addError('email', __('auth.failed'));

            return;
        }

        RateLimiter::clear($this->throttleKey());

        /** @var User $user */
        $user = User::where('email', $this->email)->firstOrFail();

        Auth::login($user, $this->remember);
        session()->regenerate();

        if ($user->two_factor_enabled) {
            $otp = (string) random_int(100000, 999999);
            $user->forceFill([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ])->save();
            OtpMailerService::send($user, $otp, 'two_factor');

            session()->put('needs_2fa', true);
            $this->redirectRoute('two-factor-auth', navigate: false);

            return;
        }

        if (! $user->email_verified_at) {
            $this->redirectRoute('verify-email', navigate: false);

            return;
        }

        $this->redirectRoute('client.dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
