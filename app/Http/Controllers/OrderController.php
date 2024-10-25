<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $request->total,
                'status' => 'pendiente',
            ]);

            $cartItems = CartItem::where('user_id', auth()->id())->get();

            foreach ($cartItems as $item) {
                $order->products()->attach($item->product_id, ['quantity' => $item->quantity, 'price' => $item->product->price]);
            }

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
        $orders = Order::with('user')->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }
    public function show($id) {
        $order = Order::with('items.product')->findOrFail($id);
        
        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);

        $order->status = 'confirmada';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Orden confirmada con éxito.');
    }

    public function reject($id)
    {
        $order = Order::findOrFail($id);

        $order->status = 'rechazada';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Orden rechazada con éxito.');
    }
}