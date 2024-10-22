<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Test
{
    public function handle(Request $request, Closure $next)
    {

        //dd(Auth::user());

        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('/login'); // Redirigir al login si no está autenticado
        }
    
        // Verificar si el usuario es admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard'); // Redirigir a la página de inicio si no es admin
        }

        //dd('Middleware CheckAdmin pasó');
    
        // Si es admin, permitir que continúe
        return redirect('/products');
    }
}