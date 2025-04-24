<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'comentario' => 'required|string',
        ]);

        // Aquí podrías usar un Mailable, pero para arrancar probamos así:
        Mail::raw("Mensaje de: {$validated['nombre']} ({$validated['email']})\n\n{$validated['comentario']}", function ($message) {
            $message->to('abmango@hotmail.com') // Cambiá esto por tu dirección
                    ->subject('Nuevo mensaje de contacto');
        });

        return back()->with('success', 'Mensaje enviado correctamente.');
    }
}
