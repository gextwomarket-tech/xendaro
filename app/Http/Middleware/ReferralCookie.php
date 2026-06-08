<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferralCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $response = $next($request);
            $response->withCookie(cookie('referral_code', $request->get('ref'), 60 * 24 * 30)); // 30 days
            return $response;
        }

        return $next($request);
    }
}
