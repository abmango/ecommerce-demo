<!-- resources/views/products/edit.blade.php -->

<form method="POST" action="{{ route('products.update', $product->id) }}">
    @csrf
    @method('PUT')

    <!-- Campos del formulario (similares a los de creación, pero con los valores actuales) -->
    <div class="mb-4">
        <label for="name">Nombre del Producto</label>
        <input type="text" name="name" id="name" value="{{ $product->name }}" class="form-input" required>
    </div>

    <!-- Agrega los demás campos aquí (description, price, image, stock, type) -->

    <button type="submit" class="bg-blue-500 text-white">Actualizar Producto</button>
</form>
