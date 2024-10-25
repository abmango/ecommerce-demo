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
        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $productId => $details) {
            $cartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $details['quantity'];
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                ]);
            }
        }

        Session::forget('cart');
    }
}
