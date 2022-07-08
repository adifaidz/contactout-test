<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class HandleReferrals
{
    /**
     * Check for refer code in query params and create a referral cookie
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $referralCode = $request->query('refer');

        if ($referralCode && User::referralExists($referralCode)) {
            $referralCookie = cookie()->forever('referral', $referralCode);
            return $next($request)->withCookie($referralCookie);
        }

        return $next($request);
    }
}
