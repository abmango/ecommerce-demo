<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Carrito
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/admin-test', function () {
    return 'Accediste a una ruta de administrador';
})->middleware('admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Agregar, editar o borrar productos
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware(\App\Http\Middleware\CheckAdmin::class);
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware(\App\Http\Middleware\CheckAdmin::class);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware(\App\Http\Middleware\CheckAdmin::class);
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update')->middleware(\App\Http\Middleware\CheckAdmin::class);
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware(\App\Http\Middleware\CheckAdmin::class);

    // Órdenes de compra
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware(\App\Http\Middleware\CheckAdmin::class);;
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show')->middleware(\App\Http\Middleware\CheckAdmin::class);;
    
    Route::post('/orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/{id}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    // Facturas
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{order}/invoice', [OrderController::class, 'uploadInvoice'])->middleware(\App\Http\Middleware\CheckAdmin::class);


    // Checkout (compra)
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

});

// No hace falta iniciar sesión para ver los productos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Formulario de contacto
Route::post('/contactar', [ContactController::class, 'send'])->name('contact.send');