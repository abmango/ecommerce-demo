<!-- products/index.blade.php -->
@foreach ($products as $product)
    <div class="product-card">
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->description }}</p>
        <p>Precio: {{ $product->price }}</p>

        <!-- Mostrando la imagen usando la URL almacenada en la base de datos -->
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
        @else
            <p>No hay imagen disponible</p>
        @endif
    </div>

    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
        onsubmit="return confirm('¿Estás seguro de dar de baja este producto?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white">Dar de baja</button>
    </form>

@endforeach


<!--
<products :products="{{ json_encode($products) }}"></products>


@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text">Precio: ${{ number_format($product->price, 2) }}</p>
                        <p class="card-text">Stock: {{ $product->stock }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
-->