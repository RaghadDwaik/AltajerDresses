<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

