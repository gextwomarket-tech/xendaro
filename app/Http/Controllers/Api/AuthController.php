<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Helpers\TotpHelper;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCodeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ─── REGISTER ────────────────────────────────────────────
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'nullable|string|max:50',
            'country'    => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
        ]);

        $data['name']        = $data['first_name'] . ' ' . $data['last_name'];
        $data['referral_code'] = User::generateReferralCode();
        $data['kyc_status']  = 'unverified';

        $user = User::create($data);
        $user->wallet()->create(['currency' => 'USD']);

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success([
            'user'  => $user,
            'token' => $token,
        ], 'Inscription réussie', 201);
    }

    // ─── LOGIN ────────────────────────────────────────────────
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        if ($user->two_factor_enabled) {
            $request->session()->put('2fa:user:id', $user->id);
            return ApiResponse::success(['two_factor_required' => true], '2FA requis');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success([
            'user'  => $user,
            'token' => $token,
        ], 'Connexion réussie');
    }

    // ─── VERIFY 2FA LOGIN ────────────────────────────────────
    public function verify2fa(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = $request->session()->get('2fa:user:id');
        if (! $userId) {
            return ApiResponse::error('Session 2FA invalide', 422);
        }

        $user = User::find($userId);
        if (! $user || ! TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return ApiResponse::error('Code 2FA invalide', 422);
        }

        $request->session()->forget('2fa:user:id');
        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success([
            'user'  => $user,
            'token' => $token,
        ], 'Connexion réussie');
    }

    // ─── LOGOUT ───────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(null, 'Déconnexion réussie');
    }

    // ─── PROFIL ───────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success($request->user());
    }

    // ─── VERIFY EMAIL ────────────────────────────────────────
    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $user = User::where('email_verification_token', $request->token)
            ->where('email_verification_expires_at', '>', now())
            ->first();

        if (! $user) {
            return ApiResponse::error('Token invalide ou expiré', 422);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);

        return ApiResponse::success(null, 'Email vérifié avec succès');
    }

    // ─── RESEND VERIFY EMAIL ─────────────────────────────────
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return ApiResponse::error('Email déjà vérifié', 400);
        }

        $token = strtoupper(bin2hex(random_bytes(32)));
        $user->update([
            'email_verification_token' => $token,
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        // Mail::to($user->email)->send(new VerifyEmailMail($token)); // TODO: implement mail

        return ApiResponse::success(null, 'Email de vérification renvoyé');
    }

    // ─── CHANGE PASSWORD ───────────────────────────────────────
    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return ApiResponse::error('Mot de passe actuel incorrect', 422);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        $user->tokens()->delete();

        return ApiResponse::success(null, 'Mot de passe changé. Veuillez vous reconnecter.');
    }

    // ─── FORGOT PASSWORD ─────────────────────────────────────
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        User::where('email', $request->email)->update([
            'reset_code'            => Hash::make($code),
            'reset_code_expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($request->email)->send(new ResetPasswordCodeMail($code));

        return ApiResponse::success(null, 'Code envoyé par email');
    }

    // ─── VERIFY CODE ─────────────────────────────────────────
    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            ! $user->reset_code ||
            ! Hash::check($request->code, $user->reset_code) ||
            now()->isAfter($user->reset_code_expires_at)
        ) {
            return ApiResponse::error('Code invalide ou expiré', 422);
        }

        return ApiResponse::success(['email' => $request->email], 'Code valide');
    }

    // ─── RESET PASSWORD ───────────────────────────────────────
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'code'     => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            ! $user->reset_code ||
            ! Hash::check($request->code, $user->reset_code) ||
            now()->isAfter($user->reset_code_expires_at)
        ) {
            return ApiResponse::error('Code invalide ou expiré', 422);
        }

        $user->update([
            'password'              => Hash::make($request->password),
            'reset_code'            => null,
            'reset_code_expires_at' => null,
        ]);

        $user->tokens()->delete();

        return ApiResponse::success(null, 'Mot de passe réinitialisé avec succès');
    }

    // ─── 2FA ENABLE ──────────────────────────────────────────
    public function enable2fa(Request $request): JsonResponse
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

        return ApiResponse::success([
            'secret' => $secret,
            'qr_uri' => $qrUri,
            'recovery_codes' => $recoveryCodes,
        ], 'Scannez le QR code et confirmez');
    }

    // ─── 2FA CONFIRM ─────────────────────────────────────────
    public function confirm2fa(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = $request->user();

        if (! $user->two_factor_secret || ! TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return ApiResponse::error('Code invalide', 422);
        }

        $user->update(['two_factor_enabled' => true]);

        return ApiResponse::success(null, '2FA activé avec succès');
    }

    // ─── 2FA DISABLE ─────────────────────────────────────────
    public function disable2fa(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Mot de passe incorrect', 422);
        }

        if (! TotpHelper::verifyCode($user->two_factor_secret, $request->code)) {
            return ApiResponse::error('Code 2FA invalide', 422);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null,
        ]);

        return ApiResponse::success(null, '2FA désactivé');
    }

    // ─── SESSIONS LIST ───────────────────────────────────────
    public function sessions(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get()->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'ip' => $token->last_used_ip ?? 'unknown',
                'device' => 'unknown',
            ];
        });

        return ApiResponse::success($tokens);
    }

    // ─── REVOKE SESSION ──────────────────────────────────────
    public function revokeSession(Request $request, int $id): JsonResponse
    {
        $token = $request->user()->tokens()->find($id);

        if (! $token) {
            return ApiResponse::error('Session introuvable', 404);
        }

        $token->delete();

        return ApiResponse::success(null, 'Session révoquée');
    }
}