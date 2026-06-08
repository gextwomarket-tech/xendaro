<?php

namespace App\Http\Controllers\Web;

use App\Helpers\TotpHelper;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    //  VIEW METHODS (GET)
    // ═══════════════════════════════════════════════════════════════

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(Request $request)
    {
        $token = $request->query('token');
        return view('auth.reset-password', ['token' => $token]);
    }

    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    public function show2FA()
    {
        return view('auth.2fa');
    }

    public function setup2FA(Request $request)
    {
        $user = $request->user();
        $secret = TotpHelper::generateSecret();
        $recoveryCodes = TotpHelper::generateRecoveryCodes();
        $qrUri = TotpHelper::getUri($secret, $user->email, 'MoonTrade');

        return view('auth.2fa-setup', [
            'secret' => $secret,
            'qr_uri' => $qrUri,
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function sessions(Request $request)
    {
        $tokens = $request->user()->tokens()->get()->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'ip' => $token->last_used_ip ?? 'unknown',
            ];
        });

        return view('auth.sessions', ['sessions' => $tokens]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  FORM ACTIONS (POST/PUT/DELETE)
    // ═══════════════════════════════════════════════════════════════

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
        ]);

        $data['name']        = $data['first_name'] . ' ' . $data['last_name'];
        $data['referral_code'] = User::generateReferralCode();
        $data['password']    = Hash::make($data['password']);
        $data['kyc_status']  = 'unverified'; // Override le défaut 'pending' de la migration

        $user = User::create($data);
        $user->wallet()->create(['currency' => 'USD']);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('auth.verify-email')
            ->with('success', 'Inscription réussie ! Veuillez vérifier votre email.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->withInput($request->except('password'));
        }

        // Check if user is admin - redirect to admin panel
        if ($user->is_admin) {
            return redirect('/admin-dashboard/login')
                ->with('error', 'Les comptes administrateurs ne peuvent pas se connecter au panneau client. Veuillez utiliser le panneau d\'administration.');
        }

        // Check if 2FA is enabled
        if ($user->two_factor_enabled) {
            $request->session()->put('2fa:user:id', $user->id);
            return redirect()->route('auth.2fa')
                ->with('info', 'Veuillez entrer votre code 2FA.');
        }

        Auth::login($user, $request->filled('remember'));

        return redirect()->route('dashboard')
            ->with('success', 'Connexion réussie !');
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = $request->session()->get('2fa:user:id');
        if (!$userId) {
            return back()->withErrors(['code' => 'Session 2FA invalide.']);
        }

        $user = User::find($userId);
        if (!$user || !TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return back()->withErrors(['code' => 'Code 2FA invalide.']);
        }

        // Double-check: Verify user is not admin (security layer)
        if ($user->is_admin) {
            $request->session()->forget('2fa:user:id');
            return redirect('/admin-dashboard/login')
                ->with('error', 'Les comptes administrateurs ne peuvent pas se connecter au panneau client.');
        }

        $request->session()->forget('2fa:user:id');
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Authentification réussie !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Déconnexion réussie.');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'reset_code' => Hash::make($code),
            'reset_code_expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new ResetPasswordCodeMail($code));

        return back()->with('success', 'Code de réinitialisation envoyé par email.');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user->reset_code ||
            !Hash::check($request->code, $user->reset_code) ||
            now()->isAfter($user->reset_code_expires_at)
        ) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        return redirect()
            ->route('auth.reset-password', ['token' => $request->code, 'email' => $request->email])
            ->with('success', 'Code validé. Vous pouvez maintenant réinitialiser votre mot de passe.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user->reset_code ||
            !Hash::check($request->code, $user->reset_code) ||
            now()->isAfter($user->reset_code_expires_at)
        ) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_code' => null,
            'reset_code_expires_at' => null,
        ]);

        return redirect()->route('auth.login')
            ->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $user = User::where('email_verification_token', $request->token)
            ->where('email_verification_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Token invalide ou expiré.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email vérifié avec succès !');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->withErrors(['email' => 'Email déjà vérifié.']);
        }

        $token = strtoupper(bin2hex(random_bytes(32)));
        $user->update([
            'email_verification_token' => $token,
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        // TODO: Send verification email
        // Mail::to($user->email)->send(new VerifyEmailMail($token));

        return back()->with('success', 'Email de vérification renvoyé.');
    }

    // ── 2FA MANAGEMENT ──────────────────────────────────────────

    public function enable2fa(Request $request)
    {
        $user = $request->user();
        $secret = TotpHelper::generateSecret();
        $recoveryCodes = TotpHelper::generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => Hash::make(json_encode($recoveryCodes)),
            'two_factor_enabled' => false, // pending confirmation
        ]);

        $qrUri = TotpHelper::getUri($secret, $user->email, 'MoonTrade');

        return view('auth.2fa-setup', [
            'secret' => $secret,
            'qr_uri' => $qrUri,
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function confirm2fa(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = $request->user();

        if (!$user->two_factor_secret || !TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        $user->update(['two_factor_enabled' => true]);

        return redirect()->route('profile.security')
            ->with('success', '2FA activé avec succès.');
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        if (!TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return back()->withErrors(['code' => 'Code 2FA invalide.']);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null,
        ]);

        return redirect()->route('profile.security')
            ->with('success', '2FA désactivé.');
    }

    public function revokeSession(Request $request, int $id)
    {
        $token = $request->user()->tokens()->find($id);

        if (!$token) {
            return back()->withErrors(['error' => 'Session introuvable.']);
        }

        $token->delete();

        return back()->with('success', 'Session révoquée.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe changé avec succès.');
    }
}
