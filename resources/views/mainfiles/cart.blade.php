@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">

<div class="container">
    <h1>Your Cart</h1>
    <div class="cart-container">
        <div class="cart-items">
            @if ($cartCount == 0)
                <div class="empty-cart-message">
                    <p>Your cart is empty. Please add products to your cart!</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            @else
                @foreach($orders as $order)
                    @if ($order->status !== 'Completed')
                        @foreach($order->orderItems as $item)
                            <div class="cart-item" id="item-{{ $item->id }}">
                                <div class="item-image">
                                    @php
                                        $images = json_decode($item->product->images, true);
                                        $firstImage = $images[0] ?? 'images/default.jpg';
                                    @endphp
                                    <img src="{{ asset($firstImage) }}" alt="{{ $item->product->product_name }}">
                                </div>
                                <div class="item-details">
                                    <h3>{{ $item->product->product_name }}</h3>
                                    <div class="clickable" onclick="openEditModal(
                                        {{ $item->id }},
                                        '{{ $item->product->product_name }}',
                                        '{{ $item->product->description }}',
                                        {{ json_encode($item->product->productData) }},
                                        '{{ $item->price }}',
                                        {{ json_encode($item->product->images) }}
                                    )">
                                        <span class="color-circle" style="background-color: {{ $item->color }};"></span>
                                        {{ $item->color }} / {{ $item->size }}
                                        <span class="dropdown-arrow">&#9662;</span>
                                    </div>
                                    <p>Price: ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="item-quantity">
                                    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" title="Remove from cart">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <span class="quantity" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>
                                    <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>

        <div class="total-section">
            @php
                $deliveryFee = 5.00;
                $totalPrice = $orders->flatMap->orderItems->sum(fn($item) => $item->price * $item->quantity) + $deliveryFee;
            @endphp

            <h2>Choose Your Region:</h2>
            <select id="region-select" class="form-control w-auto" onchange="updateDeliveryFee()">
                <option value="0">Select Region</option>
                <option value="70">الداخل</option>
                <option value="20">الضفة</option>
                <option value="30">القدس</option>
            </select>

            <h2 id="delivery-fee-display">Delivery Fee: $0.00</h2>
            <h3 id="total-price-display">Total Price: ${{ number_format($totalPrice, 2) }}</h3>

            <form action="{{ url('orders/' . $order->id) }}" method="POST">
                @csrf
                <h3>Select Payment Method</h3>
                <div class="payment-method">
                    <label>
                        <input type="radio" name="payment_method" value="visa" required> Visa
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="cash" required> Cash
                    </label>
                </div>

                <h3>Note for Seller</h3>
                <textarea name="note" class="form-control" rows="4" placeholder="Leave a note for the seller (optional)"></textarea>

                <button type="submit" class="btn btn-success">Complete Order</button>
            </form>

            <div class="policy-notice">
                <h4>Exchange and Return Policy</h4>
                <p>Exchanges are allowed within 3 days of receiving your order. Unfortunately, returns are not accepted.</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit <span id="modalProductName"></span> Details</h3>

        <div class="product-image-wrapper">
            <img id="mainProductImage" src="{{ asset('images/dress1.jpeg') }}" alt="Product Image" class="product-image">
        </div>

        <div class="product-details">
            <p class="product-description" id="productDescription"></p>
            <p class="product-price"><strong>Price:</strong> <span id="productPrice"></span></p>

            <div class="product-sizes">
                <p>Available Sizes:</p>
                <div class="size-color-options" id="availableSizes"></div>
            </div>

            <div class="product-colors">
                <p>Available Colors:</p>
                <div class="size-color-options" id="availableColors"></div>
            </div>

            <form action="#" method="POST" id="editItemForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="products_id" id="productId" value="">
                <input type="hidden" name="size" id="selectedSize" value="">
                <input type="hidden" name="color" id="selectedColor" value="">

                <div class="product-actions mt-3">
                    <button type="submit" class="btn btn-primary btn-add-to-cart">Save Changes</button>
                    <div class="quantity-controls">
                        <button type="button" onclick="decreaseQuantity()">-</button>
                        <input type="text" name="quantity" id="quantity" value="1" min="1">
                        <button type="button" onclick="increaseQuantity()">+</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
   function openEditModal(itemId, productName, description, productData, price, imagesJson) {
       document.getElementById("editModal").style.display = "block";
       document.getElementById("modalProductName").textContent = productName;
       document.getElementById("productDescription").textContent = description;
       document.getElementById("productPrice").textContent = `$${parseFloat(price).toFixed(2)}`;

       const images = JSON.parse(imagesJson);
       const mainProductImage = document.getElementById("mainProductImage");

       if (mainProductImage && images.length > 0) {
           const baseUrl = window.location.origin;
           const imageSrc = `${baseUrl}/${images[0]}`;
           mainProductImage.src = imageSrc;
       }

       const availableSizes = document.getElementById("availableSizes");
       availableSizes.innerHTML = '';
       productData.forEach(data => {
           if (data.size) {
               const sizeItem = document.createElement("div");
               sizeItem.className = "size-color-item size-item";
               sizeItem.textContent = data.size;
               sizeItem.onclick = () => {
                   document.getElementById("selectedSize").value = data.size;
                   document.querySelectorAll('.size-item').forEach(el => el.classList.remove('selected'));
                   sizeItem.classList.add('selected');
               };
               availableSizes.appendChild(sizeItem);
           }
       });

       const availableColors = document.getElementById("availableColors");
       availableColors.innerHTML = '';
       productData.forEach(data => {
           if (data.color) {
               const colorItem = document.createElement("div");
               colorItem.className = "size-color-item color-item";
               colorItem.style.backgroundColor = data.color;
               colorItem.onclick = () => {
                   document.getElementById("selectedColor").value = data.color;
                   document.querySelectorAll('.color-item').forEach(el => el.classList.remove('selected'));
                   colorItem.classList.add('selected');
               };
               availableColors.appendChild(colorItem);
           }
       });
   }

   function closeEditModal() {
       document.getElementById("editModal").style.display = "none";
   }
</script>

<script>
    const baseTotalPrice = {{ $totalPrice }};

    function updateDeliveryFee() {
        const deliveryFee = parseFloat(document.getElementById("region-select").value);
        document.getElementById("delivery-fee-display").innerText = `Delivery Fee: $${deliveryFee.toFixed(2)}`;
        const totalPriceWithFee = baseTotalPrice + deliveryFee;
        document.getElementById("total-price-display").innerText = `Total Price: $${totalPriceWithFee.toFixed(2)}`;
    }
</script>
@endsection
