<!-- resources/views/products/create.blade.php -->

@extends('layouts.app')

<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <!-- Campo Nombre del Producto -->
    <div class="mb-4">
        <label for="name">Nombre del Producto</label>
        <input type="text" name="name" id="name" class="form-input" required>
    </div>

    <!-- Campo Descripción -->
    <div class="mb-4">
        <label for="description">Descripción</label>
        <textarea name="description" id="description" class="form-input"></textarea>
    </div>

    <!-- Campo Precio -->
    <div class="mb-4">
        <label for="price">Precio</label>
        <input type="number" name="price" id="price" class="form-input" required>
    </div>

    <!-- Campo URL de Imagen -->
    <div class="mb-4">
        <label for="image">URL de la Imagen</label>
        <input type="text" name="image" id="image" class="form-input">
    </div>

    <!-- Campo Stock -->
    <div class="mb-4">
        <label for="stock">Stock</label>
        <input type="number" name="stock" id="stock" class="form-input" required>
    </div>

    <!-- Campo Tipo de Producto -->
    <div class="mb-4">
        <label for="type">Tipo de Producto</label>
        <input type="text" name="type" id="type" class="form-input" required>
    </div>

    <!-- Botón para enviar -->
    <button type="submit" class="bg-blue-500 text-white">Crear Producto</button>
</form>
