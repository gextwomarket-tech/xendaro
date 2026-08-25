<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Envoi de l'OTP email (inscription, verification, 2FA) sans jamais faire planter
 * le flux auth appelant : un SMTP mal configure (ex. MAIL_HOST de dev laisse en prod)
 * ne doit pas transformer l'inscription/connexion en erreur 500 - le compte existe deja
 * en base a ce stade (User::create ou Auth::login precedent), l'utilisateur doit pouvoir
 * atteindre la page OTP et cliquer "Renvoyer" une fois le SMTP corrige.
 */
class OtpMailerService
{
    public static function send(User $user, string $code, string $context): bool
    {
        try {
            $user->notify(new OtpCodeNotification($code, $context));

            return true;
        } catch (Throwable $e) {
            Log::error('Echec envoi OTP email', [
                'user_id' => $user->id,
                'context' => $context,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
