<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Rafraîchir les données utilisateur depuis la base (pour les changements de statut KYC, etc)
        $user = Auth::user();
        $freshUser = $user->fresh();
        if ($freshUser) {
            Auth::setUser($freshUser);
        }

        // Check if email is verified (optional, based on your requirements)
        if (!$request->user()->email_verified_at && !$request->routeIs('auth.verify-email')) {
            return redirect()->route('auth.verify-email')
                ->with('info', 'Veuillez vérifier votre email pour continuer.');
        }

        // Store user in request for later use
        $request->user = Auth::user();

        return $next($request);
    }
}
