<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isLogged = $request->user() ?? false;
        $isAdmin = $request->user()?->isAdmin() ?? false;

        if ($isAdmin) {
            $products = Product::withTrashed()->get();
        } else {
            $products = Product::all();
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'isLogged' => $isLogged,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create', [
            'success' => request()->session()->get('success') ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $vadation = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'type' => 'required|string|max:100',
            ]);      

            $vadation->validate();

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description ?? null,
                'image' => $request->image ?? null,
                'stock' => $request->stock,
                'type' => $request->type,
            ]);

            logger()->debug('producto creado > ', (array)$product);

            return redirect()->route('products.create')->with('success', true);

        } catch (Throwable $e) {
            
            logger()->error('errores en alta producto >>> ' . $e->getTraceAsString());

            return redirect()->route('products.create')->with('success', false);

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
            'success' => request()->session()->get('success') ?? null            
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

        return redirect()->route('products.edit', ['id' => $id])->with('success', true)->with('product', $product);
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
