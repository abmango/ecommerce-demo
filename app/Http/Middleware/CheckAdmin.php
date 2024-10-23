<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /*public function handle(Request $request, Closure $next)
    {
        //dd(Auth::user());
        // Verificar si el usuario es admin
        if (Auth::check()) {
            if (Auth::user()->role !== 'admin') {
                return redirect('/'); // Redirigir a la página de inicio si no es admin
            }
        }

        // Si es admin, permitir que continúe
        return $next($request);
    }*/

    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión.'], 405);
        }

        if (auth()->user()->role !== 'admin') {
            // Puedes redirigir a una vista accesible para todos los usuarios
            return response()->json(['success' => false, 'message' => 'Se requieren permisos de administrador'], 405);
        }

        return $next($request);
    }
}