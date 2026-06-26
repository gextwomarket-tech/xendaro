<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedTrader
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Non authentifié
        if (!$user) {
            return redirect()->route('auth.login')
                ->with('error', 'Authentification requise.');
        }

        // 2. Email non vérifié
        if (!$user->email_verified_at) {
            return redirect()->route('auth.verify-email')
                ->with('error', 'Veuillez vérifier votre email avant de trader.');
        }

        // 3. KYC — accès libre, notification gérée côté vue (toast)
        // Aucune redirection KYC ici.

        // 4. Compte inactif ou suspendu
        if ($user->status !== 'active') {
            return redirect()->route('dashboard')
                ->with('error', 'Votre compte est suspendu ou inactif.');
        }

        return $next($request);
    }
}