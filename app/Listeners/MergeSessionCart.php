<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Product;

class MergeSessionCart
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Obtener el carrito de la sesión
        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $productId => $details) {
            // Verificar si ya existe en el carrito del usuario en la base de datos
            $cartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Si el producto ya está en el carrito, incrementa la cantidad
                $cartItem->quantity += $details['quantity'];
                $cartItem->save();
            } else {
                // Si no existe, crear un nuevo registro en el carrito de la base de datos
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                ]);
            }
        }

        // Limpiar el carrito de la sesión después de migrarlo
        Session::forget('cart');
    }
}
