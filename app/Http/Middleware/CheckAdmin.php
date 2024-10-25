<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión.'], 405);
        }

        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Se requieren permisos de administrador'], 405);
        }

        return $next($request);
    }
}