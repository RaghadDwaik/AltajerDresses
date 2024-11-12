<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Slide-in from top for nav links */
@keyframes slideInTop {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Fade-in for the top bar */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Apply the animations */
.header .top-bar {
    animation: fadeIn 1s ease-in-out;
}

.navbar .nav-links li {
    opacity: 0;
    animation: slideInTop 0.6s forwards;
}

/* Add delay to each link to create a staggered effect */
.navbar .nav-links li:nth-child(1) { animation-delay: 0.2s; }
.navbar .nav-links li:nth-child(2) { animation-delay: 0.4s; }
.navbar .nav-links li:nth-child(3) { animation-delay: 0.6s; }
.navbar .nav-links li:nth-child(4) { animation-delay: 0.8s; }
.navbar .nav-links li:nth-child(5) { animation-delay: 1s; }

/* Fade-in for nav icons */
.nav-icons a {
    opacity: 0;
    animation: fadeIn 1s forwards;
}

/* Staggered delay for icons */
.nav-icons a:nth-child(1) { animation-delay: 1.2s; }
.nav-icons a:nth-child(2) { animation-delay: 1.4s; }
.nav-icons a:nth-child(3) { animation-delay: 1.6s; }
.nav-icons a:nth-child(4) { animation-delay: 1.8s; }

/* Slide-in from the left for logo */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

.logo a {
    opacity: 0;
    animation: slideInLeft 1s forwards;
    animation-delay: 0.5s; /* Slight delay */
}

</style>
<header class="header">
    <div class="top-bar">
        <p>Welcome to Our Store!</p>
    </div>
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo in the Center -->
            <div class="logo">
                <a href="#">Altajer Dresses</a>
            </div>
            <!-- Navigation Links -->
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
              
                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                <li><a href="#footer">Contact</a></li> 
                 <li><a href="{{ route('orders') }}">My Orders</a></li>
                <li><a href="#feedback">FeedBack</a></li>

            </ul>
           
            <!-- Right Icons -->
            <div class="nav-icons">
            <a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i></a>
                <a href="#"><i class="fas fa-search"></i></a>
                <a href="{{ route('loved.products') }}"><i class="fas fa-heart"></i></a>
                <a href="{{ route('cart.view') }}" class="cart-container">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count">{{ $cartCount }}</span>
</a>





            </div>
        </div>
    </nav>
</header>

