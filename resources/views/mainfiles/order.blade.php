@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order-confirmation.css') }}">

<div class="container">
    <h1>Your Orders</h1>

    @if($orders->isEmpty())
        <div class="alert alert-warning">You have no orders.</div>
    @else
        @foreach($orders as $order)
            <div class="order-summary">
                <p><strong>Order #:</strong> {{ $order->id }}</p>
                <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Ordered At:</strong> 
                    {{ $order->ordered_at ? $order->ordered_at->format('d M Y, H:i') : 'N/A' }}
                </p>
                <a href="{{ route('orders.details', $order->id) }}" text-align: right; class="btn btn-info">View Details</a>
            </div>
        @endforeach
    @endif
</div>

<style>
    .order-summary {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .order-summary p {
        margin: 5px 0; /* Add space between paragraphs */
    }

    .button-container {
        text-align: right; /* Aligns the button to the right */
    }

    .btn {
        display: inline-block; /* Ensure it behaves like a button */
        padding: 10px 15px; /* Add some padding for size */
        margin-top: 10px; /* Space above the button */
        background-color: #8c5b36; /* Brown color */
        color: white; /* Text color */
        text-decoration: none; /* Remove underline */
        border: none; /* Remove border */
        border-radius: 5px; /* Rounded corners */
        transition: background-color 0.3s; /* Smooth transition for hover effect */
        font-size: 16px; /* Font size */
        
    }

    .btn:hover {
        background-color: #7a3e10; /* Darker shade on hover */
        cursor: pointer; /* Change cursor to pointer */
    }

    .btn:active {
        background-color: #6c3410; /* Even darker shade on click */
    }
</style>
@endsection
