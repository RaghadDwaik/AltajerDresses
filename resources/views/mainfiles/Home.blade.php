@extends('layout.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<!-- First Section -->
<div class="promo-section">
    <div class="promo-container">
        <div class="promo-image">
            <img src="{{ asset('/images/home/dress3.jpeg') }}" alt="New Arrivals">
        </div>
     
        <div class="promo-content">
            <h2 class="promo-title">Shop Our</h2>
            <h1 class="promo-subtitle">New Arrivals</h1>
            <a href="#category">
    <button  class="shop-btn">Shop Now</button>
</a>
        </div>
    </div>
</div>

<!-- End First Section -->



<!-- All Category Section -->
<div class="container mt-5 all" id="category">
    <h2 class="allcategory">All Categories</h2>
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-3 d-flex justify-content-center mb-4">
                <a href="{{ route('categories.show', ['id' => $category->id]) }}" class="category-card text-decoration-none">
                    <div class="card-title">{{ $category->name }}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>


<!-- End All Category Section -->


<!-- Best Seller Section -->
<section class="product-grid-section">
    <h2 class="allcategory">Products</h2>
    <div class="product-grid">
    @foreach($products as $product)
    <div class="product-card">
        <div class="product-image">
            @php
                $images = json_decode($product->images, true); // Decode the JSON to an array
                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'default.jpg'; // Get the first image or use a default
            @endphp
            <img src="{{ asset($firstImage) }}" alt="{{ $product->product_name }}">
        </div>
        <div class="product-info">
            <h3 class="product-name">{{ $product->product_name }}</h3>
            <p class="product-description">{{ $product->description }}</p>
            <p class="product-price">${{ number_format($product->price, 2) }}</p>
        </div>
    </div>
@endforeach


    </div>
</section>

<!-- End Best Seller Section -->



<!-- FeedBack Section -->
<section class="feedback-carousel-section" id ="feedback">
    <div class="feedback-carousel-header">
        <h2>Feedback From Our Customers</h2>
    </div>
    <div class="feedback-carousel">
        <!-- Feedback Card 1 -->
        <div class="feedback-card active">
            <div class="feedback-card-content">
                <img src="{{ asset('/images/home/feed1.jpeg') }}" alt="Author 1" class="feedback-author-photo">
           
                <div class="feedback-stars">
                    ★★★★★
                </div>
            </div>
        </div>

        <!-- Feedback Card 2 -->
        <div class="feedback-card">
            <div class="feedback-card-content">
                <img src="{{ asset('/images/home/feed2.jpeg') }}" alt="Author 2" class="feedback-author-photo">
                <div class="feedback-stars">
                    ★★★★★
                </div>
            </div>
        </div>

        <!-- Feedback Card 3 -->
        <div class="feedback-card">
            <div class="feedback-card-content">
                <img src="{{ asset('/images/home/feed3.jpeg') }}" alt="Author 3" class="feedback-author-photo">
                <div class="feedback-stars">
                    ★★★★★
                </div>
            </div>
        </div>

        <!-- Feedback Card 4 -->
        <div class="feedback-card">
            <div class="feedback-card-content">
                <img src="{{ asset('/images/home/feed4.jpeg') }}" alt="Author 4" class="feedback-author-photo">
                <div class="feedback-stars">
                    ★★★★★
                </div>
            </div>
        </div>

        <!-- Feedback Card 5 -->
        <div class="feedback-card">
            <div class="feedback-card-content">
                <img src="{{ asset('/images/home/feed5.jpeg') }}" alt="Author 5" class="feedback-author-photo">
                <div class="feedback-stars">
                    ★★★★★
                </div>
            </div>
        </div>
    </div>
    <div class="carousel-navigation">
        <span class="carousel-dot active" data-index="0"></span>
        <span class="carousel-dot" data-index="1"></span>
        <span class="carousel-dot" data-index="2"></span>
        <span class="carousel-dot" data-index="3"></span>
        <span class="carousel-dot" data-index="4"></span>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentIndex = 0;
    const cards = document.querySelectorAll(".feedback-card");
    const dots = document.querySelectorAll(".carousel-dot");

    function showCard(index) {
        cards.forEach((card, idx) => {
            card.classList.remove("active", "next", "prev");
            dots[idx].classList.remove("active");

            if (idx === index) {
                card.classList.add("active");
            } else if (idx === (index + 1) % cards.length) {
                card.classList.add("next");
            } else if (idx === (index - 1 + cards.length) % cards.length) {
                card.classList.add("prev");
            }

            if (idx === index) {
                dots[idx].classList.add("active");
            }
        });
    }

    function nextCard() {
        currentIndex = (currentIndex + 1) % cards.length;
        showCard(currentIndex);
    }

    // Set interval for automatic sliding
    setInterval(nextCard, 3000); // Change every 3 seconds

    // Navigation dots click event
    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            currentIndex = index;
            showCard(currentIndex);
        });
    });

    // Show the first card initially
    showCard(currentIndex);
});
</script>
<!-- End FeedBack Section -->


@endsection
