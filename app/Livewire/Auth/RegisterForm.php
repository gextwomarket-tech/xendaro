<?php

namespace App\Livewire\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

/**
 * Page id 25 "register" - inscription + creation automatique du Wallet (event User::booted())
 * + envoi d'un email de bienvenue. L'unicite de l'email (regle 'unique:users,email' ci-dessous)
 * suffit desormais : plus de verification OTP de la boite mail (flow verify-email desactive).
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'parrain_id' => $parrainId,
        ]);

        // email_verified_at n'est pas dans $fillable (mass assignment volontairement restreint) -
        // forceFill comme le reste du codebase le fait deja pour otp_code/otp_expires_at.
        $user->forceFill(['email_verified_at' => now()])->save();

        try {
            Mail::to($user)->send(new WelcomeMail($user));
        } catch (Throwable $e) {
            // Un email de bienvenue en echec ne doit jamais bloquer l'inscription
            // (compte deja cree en base a ce stade) - meme filet que ForgotPasswordForm/OtpMailerService.
            Log::error('Echec envoi email de bienvenue', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        Auth::login($user);

        $this->redirectRoute('client.dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
