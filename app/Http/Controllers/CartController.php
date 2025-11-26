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
            $cartItemsRaw = CartItem::where('user_id', auth()->id())
                ->with('product')
                ->get();

            $totalSembei = $cartItemsRaw
                ->filter(fn($ci) => str_contains($ci->product->name, 'Sembei'))
                ->sum('quantity');

            $cartItems = $cartItemsRaw->map(function ($item) use ($totalSembei) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'price' => $item->finalPriceFor($item, $item->user, $totalSembei),
                    'quantity' => $item->quantity,
                    'image' => $item->product->image
                ];
            });

        } else {
            // Carrito de sesión (sin usuario)
            $cart = $request->session()->get('cart', []);
            $cartItems = collect();

            $sessionItemsRaw = collect();
            foreach ($cart as $id => $details) {
                $product = Product::find($id);

                if ($product) {
                    $sessionItemsRaw->push(new CartItem([
                        'product_id' => $product->id,
                        'quantity' => $details['quantity'],
                    ]));
                }
            }

            $totalSembei = $sessionItemsRaw
                ->filter(fn($ci) => str_contains($ci->product->name, 'Sembei'))
                ->sum('quantity');

            foreach ($sessionItemsRaw as $ci) {
                $product = $ci->product;

                $cartItems->push([
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $ci->finalPriceFor($ci, null, $totalSembei),
                    'quantity' => $ci->quantity,
                    'image' => $product->image
                ]);
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
            $cartItem = CartItem::find($id);

            if ($cartItem) {
                $product = $cartItem->product;

                if ($validatedData['quantity'] > $product->stock) {
                    return back()->withErrors(['quantity' => 'No hay suficiente stock para esta cantidad']);
                }

                $cartItem->quantity = $validatedData['quantity'];
                $cartItem->save();

                return back()->with('success', 'Cantidad actualizada correctamente');
            }

            return back()->with('error', 'Producto no encontrado en el carrito');
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $product = Product::find($id);

                if ($validatedData['quantity'] > $product->stock) {
                    return back()->withErrors(['quantity' => 'No hay suficiente stock para esta cantidad']);
                }

                $cart[$id]['quantity'] = $validatedData['quantity'];
                session()->put('cart', $cart);

                return back()->with('success', 'Cantidad actualizada correctamente');
            }

            return back()->with('error', 'Producto no encontrado en el carrito');
        }
    }

    public function remove($id)
    {
        if (auth()->check()) {
            $cartItem = CartItem::find($id);

            if ($cartItem) {
                $cartItem->delete();

                return back()->with('success');
            }

            return back()->with('error', 'Producto no encontrado en el carrito');
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);

                return back()->with('success');
            }

            return back()->with('error', 'Producto no encontrado en el carrito');
        }
    }

    public function checkout(Request $request)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
            }

            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total, 
                'status' => 'pendiente'
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', auth()->id())->delete();
        } else {
            return redirect()->route('login')->with('success', 'Primero debes loguearte para comprar.');
        }

        return redirect()->route('cart.index')->with('success', 'Compra procesada correctamente.');
    }
}