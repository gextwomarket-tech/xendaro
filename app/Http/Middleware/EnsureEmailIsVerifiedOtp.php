<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'acces a l'Espace Client tant que l'email n'est pas verifie via OTP
 * (page id 29 "verify-email"). Independant du contrat MustVerifyEmail natif de
 * Laravel puisque le mecanisme est un OTP maison (User n'implemente pas ce contrat).
 */
class EnsureEmailIsVerifiedOtp
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->email_verified_at) {
            return redirect()->route('verify-email');
        }

        return $next($request);
    }
}
