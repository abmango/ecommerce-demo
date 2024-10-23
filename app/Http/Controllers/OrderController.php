<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $request->total,
                'status' => 'pendiente',
            ]);

            // Obtener los productos del carrito
            $cartItems = CartItem::where('user_id', auth()->id())->get();

            // Asociar los productos con la orden
            foreach ($cartItems as $item) {
                $order->products()->attach($item->product_id, ['quantity' => $item->quantity, 'price' => $item->product->price]);
            }

            // Vaciar el carrito
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Compra procesada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cart.index')->with('error', 'Error al procesar la compra.');
        }
    }

    public function index()
    {
        //$orders = Order::where('user_id', auth()->id())->with('products')->get();

        $orders = Order::with('user')->get();

        // Asegúrate de pasar los datos a la vista
        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    /*public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }*/

    public function show($id) {
        $order = Order::with('items.product')->findOrFail($id);
        
        //dd($order);
        //return response()->json($order);
        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }
}