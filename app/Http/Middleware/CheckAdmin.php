<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            return redirect()->route('welcome');
        }

        return $next($request);
    }
}