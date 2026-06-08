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

        // 3. KYC non vérifié (PRIORITAIRE - les utilisateurs doivent pouvoir accéder à la page KYC)
        if ($user->kyc_status !== 'verified') {
            if ($user->kyc_status === 'rejected') {
                return redirect()->route('profile.kyc')
                    ->with('error', 'Votre vérification KYC a été rejetée. Veuillez la relancer.');
            }
            
            // pending, null, ou toute autre valeur
            return redirect()->route('profile.kyc')
                ->with('warning', 'Vous devez compléter et faire approuver votre KYC pour accéder au trading.');
        }

        // 4. Compte inactif ou suspendu
        if ($user->status !== 'active') {
            return redirect()->route('dashboard')
                ->with('error', 'Votre compte est suspendu ou inactif.');
        }

        return $next($request);
    }
}