@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-details.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .size-color-item {
        cursor: pointer;
        transition: transform 0.2s;
        border: 1px solid transparent;
        padding: 5px;
    }
    .size-color-item.size-item.selected,
    .size-color-item.color-item.selected {
        border: 1px solid #007bff;
        background-color: #e0e7ff;
    }
    .product-price {
        font-size: 20px;
        font-weight: bold;
        color: #8B4513; /* Brown color for price */
    }
    .product-actions {
        display: flex;
        align-items: center;
        gap: 5px; /* Slight gap between Add to Cart and quantity */
    }
    .btn-add-to-cart {
        background-color: #ff9800;
        color: white;
        border: none;
        padding: 8px 15px;
        font-size: 16px;
        border-radius: 5px 0 0 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-add-to-cart:hover {
        background-color: #e68a00;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-left: none; /* Ensures Add to Cart and quantity are connected */
        border-radius: 0 5px 5px 0;
    }
    .quantity-controls button {
        background-color: #f0f0f0;
        border: none;
        padding: 5px 10px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .quantity-controls button:hover {
        background-color: #ddd;
    }
    .quantity-controls input {
        width: 30px;
        height: 10px;
        text-align: center;
        font-size: 16px;
        border: none;
        padding: 5px;
        outline: none; /* Removes default input outline */
        margin-top: 30px;
    }
</style>

<div class="container mt-5">
    <div class="product-details-container d-flex align-items-start">
        <!-- Product Thumbnails on the Left -->
        <div class="product-thumbnails">
            @if($product->images && json_decode($product->images))
                @foreach(json_decode($product->images) as $thumbnail)
                    <div class="thumbnail-item">
                        <img src="{{ asset($thumbnail) }}" onclick="showImage('{{ asset($thumbnail) }}')" alt="{{ $product->product_name }}">
                    </div>
                @endforeach
            @else
                <div class="thumbnail-item">
                    <img src="{{ asset('images/home/dress1.jpeg') }}" onclick="showImage('{{ asset('images/home/dress1.jpeg') }}')" alt="Default Image">
                </div>
            @endif
        </div>

        <!-- Main Product Image -->
        <div class="product-image-wrapper">
            <img id="mainProductImage" src="{{ asset(json_decode($product->images)[0] ?? 'images/home/dress1.jpeg') }}" class="product-image" alt="{{ $product->product_name }}">
        </div>

        <!-- Product Details on the Right -->
        <div class="product-details">
            <h2 class="product-title">{{ $product->product_name }}</h2>
            <p class="product-description">{{ $product->description }}</p>
            <p class="product-price"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

            <!-- Available Sizes and Colors -->
            @if($product->productData->isNotEmpty())
                <div class="product-sizes">
                    <p>Available Sizes:</p>
                    <div class="size-color-options">
                        @foreach($product->productData as $data)
                            <div class="size-color-item size-item" data-size="{{ $data->size }}">
                                <span class="size">{{ $data->size }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="product-colors">
                    <p>Available Colors:</p>
                    <div class="size-color-options">
                        @foreach($product->productData as $data)
                            <div class="size-color-item color-item" data-color="{{ $data->color }}">
                                <span class="color" style="background-color: {{ $data->color }};"></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="products_id" value="{{ $product->id }}">
                <input type="hidden" name="size" id="selectedSize" value="">
                <input type="hidden" name="color" id="selectedColor" value="">

                <!-- Add to Cart Button and Quantity Controls -->
                <div class="product-actions mt-3">
                    <button type="submit" class="btn btn-primary btn-add-to-cart">Add to Cart</button>
                    <div class="quantity-controls">
                        <button type="button" id="decreaseQuantity">-</button>
                        <input type="text" name="quantity" id="quantity" value="1">
                        <button type="button" id="increaseQuantity">+</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Products You May Like Section -->
<div class="related-products-container mt-5">
    <h3 class="related-products-title">Products You May Like</h3>
    <div class="related-products-grid">
        @foreach($relatedProducts as $relatedProduct)
            <div class="related-product-card">
                <!-- Clickable Image Container -->
                <a href="{{ route('products.show', ['id' => $relatedProduct->id]) }}">
                    <img src="{{ asset(json_decode($relatedProduct->images)[0] ?? 'images/home/dress1.jpeg') }}" class="related-product-image" alt="{{ $relatedProduct->product_name }}">
                </a>

                <!-- Product Info -->
                <div class="related-product-info">
                    <a href="{{ route('products.show', ['id' => $relatedProduct->id]) }}" class="text-decoration-none">
                        <h5 class="related-product-title">{{ $relatedProduct->product_name }}</h5>
                    </a>
                    <p class="related-product-price">${{ number_format($relatedProduct->price, 2) }}</p>
                    <button class="love-icon" onclick="toggleLove(event, {{ $relatedProduct->id }})">
                        <i class="fa fa-heart-o"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

</div>

<!-- JavaScript to handle image switching and form validation -->
<script>
    function showImage(src) {
        document.getElementById('mainProductImage').src = src;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Quantity Controls
        document.getElementById('increaseQuantity').addEventListener('click', function() {
            let quantity = parseInt(document.getElementById('quantity').value);
            document.getElementById('quantity').value = quantity + 1;
        });

        document.getElementById('decreaseQuantity').addEventListener('click', function() {
            let quantity = parseInt(document.getElementById('quantity').value);
            if (quantity > 1) {
                document.getElementById('quantity').value = quantity - 1;
            }
        });

        // Handle Size Selection
        document.querySelectorAll('.size-item').forEach(function(item) {
            item.addEventListener('click', function() {
                document.querySelectorAll('.size-item').forEach(function(i) {
                    i.classList.remove('selected');
                });
                this.classList.add('selected');
                const selectedSize = this.getAttribute('data-size');
                document.getElementById('selectedSize').value = selectedSize;
            });
        });

        // Handle Color Selection
        document.querySelectorAll('.color-item').forEach(function(item) {
            item.addEventListener('click', function() {
                document.querySelectorAll('.color-item').forEach(function(i) {
                    i.classList.remove('selected');
                });
                this.classList.add('selected');
                const selectedColor = this.getAttribute('data-color');
                document.getElementById('selectedColor').value = selectedColor;
            });
        });

        // Prevent form submission if size and color are not selected
        document.querySelector('form').addEventListener('submit', function(event) {
            if (!document.getElementById('selectedSize').value || !document.getElementById('selectedColor').value) {
                event.preventDefault();
                alert('Please select a size and color before adding to the cart.');
            }
        });
    });

    
</script>


@endsection
