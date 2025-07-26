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
        $user = auth()->user();

        $orders = $user->role === 'admin'
            ? Order::with('user')->get()
            : Order::with('user')->where('user_id', $user->id)->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'auth' => [
                'user' => $user
            ]
        ]);
    }
    public function show($id)
    {
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

    public function downloadInvoice(Order $order)
    {
        if (!$order->approved) {
            abort(403);
        }

        $path = storage_path('app/' . $order->factura_path);

        if (!file_exists($path)) {
            abort(404, 'Factura no encontrada.');
        }

        return response()->download($path);
    }

    // public function uploadInvoice(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'invoice' => 'required|file|mimes:pdf|max:2048',
    //     ]);

    //     $invoice = $request->file('invoice');

    //     // Generamos el nombre correcto según la convención
    //     $numFactura = str_pad($order->id, 8, '0', STR_PAD_LEFT);
    //     $filename = "20150978387_011_00005_{$numFactura}.pdf";

    //     // Guardamos en 'public/invoices'
    //     $path = $invoice->storeAs('invoices', $filename, 'public');

    //     // Guardamos el path en la base de datos
    //     $order->invoice_path = $path;
    //     $order->save();

    //     return back()->with('success', 'Factura cargada exitosamente.');
    // }

    public function uploadInvoice(Request $request, Order $order)
    {
        if (!$order->exists) {
            abort(404, 'Orden no encontrada.');
        }

        $request->validate([
            'invoice' => 'required|file|mimes:pdf|max:2048',
        ]);

        $file = $request->file('invoice');

        $name = "20150978387_011_00005_" . str_pad($order->id, 8, '0', STR_PAD_LEFT) . ".pdf";

        $path = $file->storeAs('invoices', $name, 'public');

        $order->invoice_path = $path;
        $order->save();

        return back()->with('success', 'Factura subida correctamente.');
    }

    public function confirmation()
    {
        return Inertia::render('Orders/Confirmation', [
            'order_id' => session('order_id'),
        ]);
    }

}