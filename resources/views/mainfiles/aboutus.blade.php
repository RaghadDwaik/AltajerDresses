@extends('layout.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/aboutus.css') }}">

<!-- About Us Section -->
<section class="about-section">
    <div class="about-container">
        <div class="about-image">
        <img src="{{ asset('images/aboutus/aboutus.jpeg') }}" alt="About Us Image">
        </div>
        <div class="about-content">
            <h1 class="about-title">About Us</h1>
            <p class="about-text">
                We are a passionate team dedicated to providing high-quality products that blend style and function. Our story began with a simple idea: to create beautiful, sustainable, and affordable accessories that empower individuals to express themselves with confidence.
            </p>
            <p class="about-text">
                Our collections are thoughtfully curated with attention to detail and craftsmanship. We believe in creating pieces that not only look good but also feel good, allowing you to carry your essentials with ease and elegance.
            </p>
        </div>
    </div>
</section>

@endsection