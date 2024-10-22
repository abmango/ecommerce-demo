<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        //dd(Auth::user());
        // Verificar si el usuario es admin
        if (Auth::check()) {
            if (Auth::user()->role !== 'admin') {
                return redirect('/dashboard'); // Redirigir a la página de inicio si no es admin
            }
        }

        // Si es admin, permitir que continúe
        return $next($request);
    }
}