@extends('layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<div class="product-grid-section mt-5">
    <h2 class="allcategory text-center">{{ $category->name }}</h2>
    <div class="product-grid">
        @foreach($products as $product)
            <div class="product-card">
                <!-- Clickable Image Container -->
                <div class="product-image-wrapper">
                    <a href="{{ route('products.show', ['id' => $product->id]) }}">
                        @if($product->images && json_decode($product->images))
                            <img src="{{ asset(json_decode($product->images)[0]) }}" class="product-image" alt="{{ $product->product_name }}">
                        @else
                            <img src="{{ asset('images/home/dress1.jpeg') }}" class="product-image" alt="Default Image">
                        @endif
                    </a>
                </div>

                <!-- Clickable Product Details -->
                <div class="product-info">
                    <a href="{{ route('products.show', ['id' => $product->id]) }}" class="text-decoration-none">
                        <h5 class="product-title">{{ $product->product_name }}</h5>
                        <p class="product-description">{{ $product->description }}</p>
                    </a>
                    <p class="product-price">${{ number_format($product->price, 2) }}</p>
                    <div class="product-footer">
                        <button class="love-icon" onclick="toggleLove(event, {{ $product->id }})">
                            <i class="fa {{ $user && $user->lovedProducts->contains($product->id) ? 'fa-heart' : 'fa-heart-o' }}"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>



<!-- Toast Message -->
<div id="toast" class="toast"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    function toggleLove(event, productId) {
        const icon = event.currentTarget.querySelector('i');
        const isAddingToFavorite = icon.classList.contains('fa-heart-o');
        
        icon.classList.toggle('fa-heart-o');
        icon.classList.toggle('fa-heart');

        const message = isAddingToFavorite ? "Added to favorites" : "Removed from favorites";
        showToast(message);

        $.ajax({
            url: "{{ route('toggleFavorite') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId,
                action: isAddingToFavorite ? 'add' : 'remove'
            },
            success: function(response) {
                console.log(response.message);
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    function showToast(message) {
        const toast = document.getElementById("toast");
        toast.textContent = message;
        toast.classList.add("show");
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
</script>

@endsection
