@extends('layout.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    /* Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    opacity: 0;
    animation: fadeIn 1s forwards;
}

.fade-in-delay-1 { animation-delay: 0.2s; }
.fade-in-delay-2 { animation-delay: 0.4s; }
.fade-in-delay-3 { animation-delay: 0.6s; }
.fade-in-delay-4 { animation-delay: 0.8s; }
.fade-in-delay-5 { animation-delay: 1s; }

</style>
<!-- First Section -->
<div class="promo-section fade-in fade-in-delay-1">
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
<div class="container mt-5 all fade-in fade-in-delay-2" id="category">
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


<!-- All Products Section -->
<section class="product-grid-section fade-in fade-in-delay-3">
    <h2 class="allcategory">Products</h2>
    <div class="product-grid">
        @foreach($products as $product)
        <div class="product-card">
            <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-link">
                <div class="product-image">
                    @php
                        $images = json_decode($product->images, true);
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'default.jpg';
                    @endphp
                    <img src="{{ asset($firstImage) }}" alt="{{ $product->product_name }}">
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->product_name }}</h3>
                    <p class="product-description">{{ $product->description }}</p>
                    <p class="product-price">${{ number_format($product->price, 2) }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Show All Button -->
    <div class="show-all-container">
        <a href="{{ route('products.index') }}" class="show-all-button">Show All</a>
    </div>
</section>


<!-- End Best Seller Section -->



<!-- FeedBack Section -->
<section class="feedback-carousel-section fade-in fade-in-delay-4" id="feedback">
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

<div id="toast" class="toast"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>

</script>


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

document.addEventListener("DOMContentLoaded", function () {
    const fadeInElements = document.querySelectorAll(".fade-in");

    function handleScrollAnimation() {
        fadeInElements.forEach((el) => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight) {
                el.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", handleScrollAnimation);
    handleScrollAnimation(); // Trigger animation on load
});

document.addEventListener("DOMContentLoaded", function () {
    const productCards = document.querySelectorAll(".product-card");

    function animateProductCards() {
        productCards.forEach((card) => {
            const rect = card.getBoundingClientRect();
            if (rect.top < window.innerHeight) {
                card.classList.add("slide-in-up");
            }
        });
    }

    window.addEventListener("scroll", animateProductCards);
    animateProductCards(); // Trigger animation on load
});

</script>
<!-- End FeedBack Section -->




@endsection
