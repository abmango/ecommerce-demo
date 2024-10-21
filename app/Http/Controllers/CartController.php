<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    // Mostrar el carrito
    public function index(Request $request)
    {
        $cart = array_values($request->session()->get('cart', []));
        return Inertia::render('Cart/Index', ['cart' => $cart]);
    }

    // Agregar producto al carrito
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        } else {
            $cart[$id]['quantity']++;
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito');
    }

    // Eliminar producto del carrito
    public function remove(Request $request, $id)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito');
    }

    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        foreach ($cart as $item) {
            $product = Product::find($item['id']);

            if ($product) {
                // Verificar si hay suficiente stock
                if ($product->stock >= $item['quantity']) {
                    // Decrementar el stock
                    $product->stock -= $item['quantity'];
                    $product->save();
                } else {
                    return redirect()->route('cart.index')->with('error', "Stock insuficiente para el producto: {$product->name}");
                }
            }
        }

        // Vaciar el carrito después de la compra
        $request->session()->forget('cart');

        return redirect()->route('products.index')->with('success', 'Compra procesada correctamente.');
    }

}