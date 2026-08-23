<?php

namespace App\Livewire\Auth;

use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 30 "two-factor-auth" - meme mecanisme OTP email que verify-email (MVP).
 * L'utilisateur est deja authentifie (Auth::login effectue au LoginForm) mais bloque
 * par le middleware EnsureTwoFactorVerified tant que ce formulaire n'est pas valide.
 */
#[Layout('components.layouts.auth')]
class TwoFactorForm extends Component
{
    public string $code = '';

    public ?int $resendAvailableAt = null;

    public function mount(): void
    {
        if (! session('needs_2fa')) {
            $this->redirectRoute('client.dashboard', navigate: false);

            return;
        }

        $this->resendAvailableAt = now()->addSeconds(60)->timestamp;
    }

    public function verify(): void
    {
        $this->validate(['code' => ['required', 'digits:6']]);

        $user = Auth::user();

        if (
            $user->otp_code
            && hash_equals($user->otp_code, $this->code)
            && $user->otp_expires_at
            && now()->lessThan($user->otp_expires_at)
        ) {
            $user->forceFill(['otp_code' => null, 'otp_expires_at' => null])->save();
            session()->forget('needs_2fa');

            if (! $user->email_verified_at) {
                $this->redirectRoute('verify-email', navigate: false);

                return;
            }

            $this->redirectRoute('client.dashboard', navigate: false);

            return;
        }

        $this->addError('code', __('app.common.otp_invalid'));
    }

    public function resend(): void
    {
        if ($this->resendAvailableAt && now()->timestamp < $this->resendAvailableAt) {
            return;
        }

        $user = Auth::user();
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        $user->notify(new OtpCodeNotification($otp, 'two_factor'));

        $this->resendAvailableAt = now()->addSeconds(60)->timestamp;
        $this->dispatch('toast', type: 'success', message: __('app.auth.otp_resent'));
    }

    public function render()
    {
        return view('livewire.auth.two-factor-form');
    }
}
