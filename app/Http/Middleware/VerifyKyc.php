<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyKyc
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Si KYC non vérifié : on laisse passer mais on flag une fois par session
        if ($user->kyc_status !== 'verified') {
            if (!$request->session()->has('_kyc_popup_shown')) {
                $request->session()->flash('kyc_popup', true);
            }
        }

        return $next($request);
    }
}
