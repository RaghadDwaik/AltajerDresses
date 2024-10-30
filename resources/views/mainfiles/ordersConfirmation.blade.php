@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order-confirmation.css') }}">

<div class="container">
    <h1>Order Confirmation</h1>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="alert alert-success">
        <strong>Payment Status:</strong> Your payment was successful! Thank you for your order.
    </div>

    <h3>Order Details</h3>
    <p><strong>Order Number:</strong> {{ $orderDetail->id }}</p>
    <p><strong>Payment Method:</strong> {{ $orderDetail->payment_method }}</p>
    <p><strong>Note:</strong> {{ $orderDetail->note }}</p>
    <p><strong>Status:</strong> {{ $orderDetail->status }}</p>

    <h4>Products in Your Order</h4>
    <div class="order-products">
        @if($orderDetail->orderItems->isEmpty())
            <p>No products in this order.</p>
        @else
            @foreach($orderDetail->orderItems as $item)
                <div class="order-product">
                    <img src="{{ asset($item->product->images[0]) }}" alt="{{ $item->product_name }}" style="width: 100px;">
                    <div class="product-details">
                        <h5>{{ $item->product_name }}</h5>
                        <p>Size: {{ $item->size }}</p>
                        <p>Color: {{ $item->color }}</p>
                        <p>Quantity: {{ $item->quantity }}</p>
                        <p>Price: ${{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @php
        // Calculate the total price of the order
        $totalPrice = $orderDetail->orderItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
    @endphp

    <h4>Total Price: ${{ number_format($totalPrice, 2) }}</h4>
</div>

<style>
    .order-products {
        margin-top: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .order-product {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .product-details {
        margin-left: 10px;
    }
</style>
@endsection
