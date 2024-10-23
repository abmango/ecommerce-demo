@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalles de la Orden #{{ $order->id }}</h1>
        <p>Total: ${{ $order->total }}</p>
        <p>Estado: {{ $order->status }}</p>
        <h3>Productos</h3>
        <ul>
            @foreach($order->products as $product)
                <li>{{ $product->name }} - Cantidad: {{ $product->pivot->quantity }} - Precio: ${{ $product->pivot->price }}</li>
            @endforeach
        </ul>
    </div>
@endsection
