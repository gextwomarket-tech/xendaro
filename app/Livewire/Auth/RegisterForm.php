<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Page id 25 "register" - inscription + creation automatique du Wallet (event User::booted())
 * + envoi d'un OTP email pour la verification (page id 29).
 */
#[Layout('components.layouts.auth')]
class RegisterForm extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $password = '';

    public string $password_confirmation = '';

    public bool $accept_terms = false;

    public ?string $ref = null;

    public function mount(): void
    {
        $this->ref = request()->query('ref');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_terms' => ['accepted'],
        ];
    }

    public function register(): void
    {
        $validated = $this->validate();

        $parrainId = null;
        if ($this->ref) {
            $parrainId = User::where('referral_code', $this->ref)->value('id');
        }

        $otp = (string) random_int(100000, 999999);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'parrain_id' => $parrainId,
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new OtpCodeNotification($otp, 'verification'));

        Auth::login($user);

        $this->redirectRoute('verify-email', navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
