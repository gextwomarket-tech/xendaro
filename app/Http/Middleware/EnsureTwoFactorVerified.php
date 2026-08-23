<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'acces a l'Espace Client tant que l'etape 2FA (page id 30 "two-factor-auth")
 * n'est pas validee pour les comptes ayant active le two_factor_enabled.
 * Le flag 'needs_2fa' est pose en session par LoginForm et leve par TwoFactorForm.
 */
class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('needs_2fa')) {
            return redirect()->route('two-factor-auth');
        }

        return $next($request);
    }
}
