<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            // Recuperar los productos del carrito de la base de datos para el usuario autenticado
            $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ];
            });


        } else {
            // Recuperar el carrito de la sesión para usuarios no autenticados
            $cart = $request->session()->get('cart', []);
            $cartItems = collect();

            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) {
                    $cartItems->push([
                        'id' => $id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $details['quantity'],
                    ]);
                }
            }
        }

        return Inertia::render('Cart/Index', [
            'cart' => $cartItems,
        ]);
    }

    // Agregar producto al carrito
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Verificar si hay suficiente stock antes de agregar al carrito
        if ($product->stock < 1) {
            return redirect()->route('cart.index')->with('error', 'No hay suficiente stock para este producto.');
        }

        if (auth()->check()) {
            // Verificar stock antes de actualizar o crear el CartItem
            $cartItem = CartItem::where('user_id', auth()->id())->where('product_id', $product->id)->first();

            $newQuantity = $cartItem ? $cartItem->quantity + 1 : 1;

            if ($newQuantity > $product->stock) {
                return redirect()->route('cart.index')->with('error', 'No hay suficiente stock para esta cantidad.');
            }

            // Guardar o actualizar en la base de datos
            CartItem::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $product->id],
                ['quantity' => $newQuantity]
            );
        } else {
            // Guardar en sesión para usuarios no autenticados
            $cart = $request->session()->get('cart', []);
            $newQuantity = isset($cart[$id]) ? $cart[$id]['quantity'] + 1 : 1;

            if ($newQuantity > $product->stock) {
                return redirect()->route('cart.index')->with('error', 'No hay suficiente stock para esta cantidad.');
            }

            $cart[$id] = ['quantity' => $newQuantity];
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (auth()->check()) {
            // Usuario autenticado, actualizar el carrito en la base de datos
            $cartItem = CartItem::find($id);

            if ($cartItem) {
                $product = $cartItem->product;

                // Verificar si hay suficiente stock para la cantidad solicitada
                if ($validatedData['quantity'] > $product->stock) {
                    return response()->json(['success' => false, 'message' => 'No hay suficiente stock para esta cantidad'], 400);
                }

                $cartItem->quantity = $validatedData['quantity'];
                $cartItem->save();

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        } else {
            // Usuario no autenticado, manejar carrito en la sesión
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $product = Product::find($id);

                // Verificar si hay suficiente stock para la cantidad solicitada
                if ($validatedData['quantity'] > $product->stock) {
                    return response()->json(['success' => false, 'message' => 'No hay suficiente stock para esta cantidad'], 400);
                }

                $cart[$id]['quantity'] = $validatedData['quantity'];
                session()->put('cart', $cart);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        }
    }


    public function remove($id)
    {
        if (auth()->check()) {
            // Usuario autenticado, eliminar producto del carrito en la base de datos
            $cartItem = CartItem::find($id);


            if ($cartItem) {
                $cartItem->delete();

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        } else {
            // Usuario no autenticado, eliminar producto del carrito en la sesión
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        }
    }

    public function checkout(Request $request)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
            }

            // Calcular el total de la orden
            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total, // Asegúrate de que el campo total esté presente
                'status' => 'pendiente'
            ]);

            foreach ($cartItems as $item) {
                // Crear OrderItem y reducir el stock del producto
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Limpiar el carrito
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            return redirect()->route('login')->with('success', 'Primero debes loguearte para comprar.');
        }

        return redirect()->route('cart.index')->with('success', 'Compra procesada correctamente.');
    }
}