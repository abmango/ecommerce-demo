@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tus Órdenes</h1>
        <ul>
            @foreach($orders as $order)
                <li>
                    <a href="{{ route('orders.show', $order->id) }}">Orden #{{ $order->id }} - Total: ${{ $order->total }} - Estado: {{ $order->status }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection