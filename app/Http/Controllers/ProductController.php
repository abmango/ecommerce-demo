<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::withTrashed()->get();        

        return Inertia::render('Products/Index', [
            'products' => $products,
            'isLogged' => $request->user(),
            'isAdmin' => $request->user()?->isAdmin() ?? false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'type' => 'required|string|max:100',
            ]);

            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $request->image,
                'stock' => $request->stock,
                'type' => $request->type,
            ]);

            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Products/Show', [
            'product' => $product,
            //'auth' => auth()->user(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'id' => $product->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'type' => 'required|string|max:100',
        ]);

        $product->update($request->all());

        return Inertia::render('Products/Show', ['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto dado de baja correctamente');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->restore();
        return redirect()->route('products.index')->with('success', 'Producto restaurado correctamente');
    }
}
