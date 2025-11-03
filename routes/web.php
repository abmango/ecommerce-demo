<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\CheckAdmin;
use App\Models\Product;

Route::get('/', function () {
    $products = Product::take(6)->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products,
    ]);
})->name('welcome');

// Carrito
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Esto lo podemos eliminar, era solo para probar el middleware de administrador
Route::get('/admin-test', function () {
    return 'Accediste a una ruta de administrador';
})->middleware(CheckAdmin::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Agregar, editar o borrar productos
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware(CheckAdmin::class);
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware(CheckAdmin::class);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware(CheckAdmin::class);
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update')->middleware(CheckAdmin::class);
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware(CheckAdmin::class);

    // Órdenes de compra
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    Route::post('/orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/{id}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    // Facturas
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{order}/invoice', [OrderController::class, 'uploadInvoice'])->middleware(CheckAdmin::class);


    // Checkout (compra)
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

});

// No hace falta iniciar sesión para ver los productos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Formulario de contacto
Route::post('/contactar', [ContactController::class, 'send'])->name('contact.send');