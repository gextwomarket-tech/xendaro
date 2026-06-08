<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToFilament
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_admin) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/admin-dashboard/login')
                ->with('error', 'Comptes admins non autorisés sur le panneau client.');
        }

        return $next($request);
    }
}
