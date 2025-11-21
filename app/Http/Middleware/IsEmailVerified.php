<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || ($request->user() && is_null($request->user()->email_verified_at))) {
            return $request->expectsJson()
                    ? abort(403, 'Tu cuenta no ha sido verificada.')
                    : redirect()->guest(url()->route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
