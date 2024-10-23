<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
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

                return Inertia::render('Cart/Index', [
                    'cart' => $cart
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        }
    }



    /*public function checkout(Request $request)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())->get();

            foreach ($cartItems as $item) {
                // Por ejemplo, podrías restar el stock del producto y generar una orden

            }
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            $cart = $request->session()->get('cart', []);

            $request->session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Compra procesada correctamente.');
    }*/

    public function checkout(Request $request)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())->get();

            $total = 0;
            $orderItems = [];

            foreach ($cartItems as $item) {
                // Restar stock del producto
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock -= $item->quantity;
                    $product->save();

                    // Agregar el item a la orden
                    $orderItems[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                    ];

                    // Sumar el total
                    $total += $product->price * $item->quantity;
                }
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'completed', // o el estado que desees
            ]);

            // Crear los items de la orden
            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
            }

            // Limpiar el carrito
            CartItem::where('user_id', auth()->id())->delete();

        } else {
            $cart = $request->session()->get('cart', []);
            // Aquí podrías manejar el proceso de checkout para usuarios no autenticados si es necesario
            $request->session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Compra procesada correctamente.');
    }
}