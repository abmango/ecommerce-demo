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

        if (auth()->check()) {
            // Guardar en la base de datos
            $cartItem = CartItem::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $product->id],
                ['quantity' => DB::raw('quantity + 1')]
            );
        } else {
            // Guardar en sesión para usuarios no autenticados
            $cart = $request->session()->get('cart', []);
            if (!isset($cart[$id])) {
                $cart[$id] = ['quantity' => 1];
            } else {
                $cart[$id]['quantity']++;
            }
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito');
    }
    public function update(Request $request, $id)
    {

        if (auth()->check()) {
            // Usuario autenticado, actualizar el carrito en la base de datos
            $cartItem = CartItem::find($id);

            if ($cartItem) {
                $validatedData = $request->validate([
                    'quantity' => 'required|integer|min:1'
                ]);

                $cartItem->quantity = $validatedData['quantity'];
                $cartItem->save();

                return response()->json(['success' => true]); // Asegúrate de que esto se envíe
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        } else {
            // Usuario no autenticado, manejar carrito en la sesión
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $validatedData = $request->validate([
                    'quantity' => 'required|integer|min:1'
                ]);

                $cart[$id]['quantity'] = $validatedData['quantity'];
                session()->put('cart', $cart);

                return Inertia::render('Cart/Index', [
                    'cart' => $cart
                ]);
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
                'status' => 'completed'
            ]);

            foreach ($cartItems as $item) {
                // Crear OrderItem y reducir el stock del producto
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Aquí también deberías reducir el stock del producto
                $item->product->decrement('stock', $item->quantity);
            }

            // Limpiar el carrito
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            dd('Llegué al controlador');
            // Si no está logueado, le pide que se loguee
            /*$cart = $request->session()->get('cart', []);
            $request->session()->forget('cart');*/
            return redirect()->route('login')->with('error', 'Primero debes loguearte para comprar.');
        }

        return redirect()->route('cart.index')->with('success', 'Compra procesada correctamente.');
    }
}