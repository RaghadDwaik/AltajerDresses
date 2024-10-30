@extends('layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('css/product.css') }}">

<div class="product-grid-section mt-5">
    <h2 class="allcategory text-center">Loved Products</h2>
    <div class="product-grid">
        @forelse($lovedProducts as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                <a href="{{ route('products.show', ['id' => $product->id]) }}" class="text-decoration-none">
                    <div class="product-image-wrapper">
                        @if($product->images && json_decode($product->images))
                            <img src="{{ asset(json_decode($product->images)[0]) }}" class="product-image" alt="{{ $product->product_name }}">
                        @else
                            <img src="{{ asset('images/home/dress1.jpeg') }}" class="product-image" alt="Default Image">
                        @endif
                    </div>
                    <h5 class="product-title">{{ $product->product_name }}</h5>
                </a>
                <p class="product-description">{{ $product->description }}</p>
                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                
                <div class="product-footer">
                    <button class="love-icon" onclick="toggleLove(event)">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>
            </div>
        @empty
            <p class="text-center">You have no loved products yet.</p>
        @endforelse
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function toggleLove(event) {
    const button = event.currentTarget;
    const icon = button.querySelector('i');
    const productCard = button.closest('.product-card');
    const productId = productCard.getAttribute('data-product-id');

    // Determine action based on current icon class
    const action = icon.classList.contains('fa-heart') ? 'remove' : 'add';

    $.ajax({
        url: "{{ route('toggleFavorite') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            product_id: productId,
            action: action // Send the action to indicate add/remove
        },
        success: function(response) {
            console.log(response.message);

            // Reload the page to reflect the changes
            location.reload();
        },
        error: function(error) {
            console.error(error);
        }
    });
}
</script>
@endsection
