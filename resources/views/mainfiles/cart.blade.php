@extends('layout.app')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('css/cart.css') }}">

</head>
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
                <!-- Image Link -->
                <div class="item-image">
                    @php
                    $images = json_decode($item->product->images, true);
                    $firstImage = $images[0] ?? 'images/default.jpg';
                    @endphp
                    <a href="{{ route('products.show', $item->product->id) }}">
                        <img src="{{ asset($firstImage) }}" alt="{{ $item->product->product_name }}">
                    </a>
                </div>

                <div class="item-details">
                    <!-- Title Link -->
                    <a href="{{ route('products.show', $item->product->id) }}">
                        <h3>{{ $item->product->product_name }}</h3>
                    </a>

                    <!-- Description Link -->
                    <a href="{{ route('products.show', $item->product->id) }}">
                        <p class="product-description" id="productDescription">
                            {{ $item->product->description }}
                        </p>
                    </a>

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
                <div id="cart-item-{{ $item->id }}" class="item-quantity">
    <!-- Delete Button -->
    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-btn" title="Remove from cart">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
    
    <!-- Decrease Quantity Button -->
    <button id="minus-btn-{{ $item->id }}" class="quantity-btn" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
    
    <!-- Display Current Quantity -->
    <span class="quantity" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>
    
    <!-- Increase Quantity Button -->
    <button 
        id="plus-btn-{{ $item->id }}" 
        class="quantity-btn {{ session('max_stock_reached_' . $item->id) ? 'disabled' : '' }}" 
        onclick="updateQuantity({{ $item->id }}, 1)"
        {{ session('max_stock_reached_' . $item->id) ? 'disabled' : '' }}
    >+</button>
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
        <h3 class="product-title">Edit <span id="modalProductName">Product</span> Details</h3>

        <div class="modal-body">
            <div class="product-image-wrapper">
                <img id="mainProductImage" src="" alt="Product Image" class="product-image">
            </div>

            <div class="product-details">
                <p class="product-description" id="productDescription">Product description goes here.</p>
                <p class="product-price"><strong>Price:</strong> <span id="productPrice">$0.00</span></p>

                <div class="product-sizes">
   <p>Available Sizes:</p>
   <div class="size-color-options" id="availableSizes">Size: One Size</div>
</div>

<div class="product-colors">
   <p>Available Colors:</p>
   <div class="size-color-options" id="availableColors">Color: Beige</div>
</div>


                <form action="javascript:void(0);" method="POST" id="editItemForm" onsubmit="submitEditForm(event)">
                @csrf
    <input type="hidden" name="products_id" id="productId" value="">
    <input type="hidden" name="size" id="selectedSize" value="">
    <input type="hidden" name="color" id="selectedColor" value="">
    
    <div class="product-actions">
    <button type="submit" class="btn-add-to-cart" onclick="submitEditForm(event)">Save Changes</button>
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
</div>




<script>
 function submitEditForm(event) {
    event.preventDefault(); // Prevent default form submission

    console.log("Submit Edit Form Triggered");

    const itemId = document.getElementById("productId").value;
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const token = csrfMetaTag ? csrfMetaTag.getAttribute('content') : null;

    if (!token) {
        console.error("CSRF Token not found. Ensure <meta name='csrf-token' content='...'> is present in your HTML.");
        alert("CSRF Token not found.");
        return; // Stop execution if CSRF token is missing
    }

    const url = `/product-data/${itemId}/update`;

    const formData = {
        _token: token, // Use the token retrieved from the meta tag
        size: document.getElementById("selectedSize").value,
        color: document.getElementById("selectedColor").value,
        quantity: parseInt(document.getElementById("quantity").value, 10)
    };

    console.log("Item ID:", itemId);
    console.log("Selected Size:", formData.size);
    console.log("Selected Color:", formData.color);
    console.log("Quantity:", formData.quantity);
    console.log("CSRF Token:", token); // Debug to confirm token retrieval
    
    fetch(url, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify(formData)
})
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
})
.then(data => {
    console.log("Received data:", data);

    if (data.message === 'Product updated successfully') {
        alert(data.message); // Display success message
        closeEditModal(); // Close modal on success

        // Update the UI elements with new data dynamically
        const itemElement = document.getElementById(`item-${itemId}`);
        
        // Update the color circle's background color
        itemElement.querySelector(".color-circle").style.backgroundColor = data.product.color;

        // Update the text that displays color and size
        const colorSizeText = itemElement.querySelector(".color-circle").nextSibling;
        if (colorSizeText) {
            colorSizeText.nodeValue = ` ${data.product.color} / ${data.product.size}`;
        }

        // Update the quantity displayed
        itemElement.querySelector(".quantity").innerText = data.product.quantity;
    }
})
.catch(error => {
    console.error("Fetch error:", error);
    alert('An error occurred while updating the product.');
});



 }

</script>
<script>
function openEditModal(itemId, productName, description, productData, price, imagesJson) {
    document.getElementById("editModal").style.display = "flex";
    document.getElementById("modalProductName").textContent = productName;
    document.getElementById("productDescription").textContent = description;
    document.getElementById("productPrice").textContent = `$${parseFloat(price).toFixed(2)}`;
    document.getElementById("productId").value = itemId; // Set product ID

    // Set the form action dynamically
    document.getElementById("editItemForm").action = `/product-data/${itemId}/update`;

    // Set main image dynamically
    const images = JSON.parse(imagesJson);
    const mainProductImage = document.getElementById("mainProductImage");
    mainProductImage.src = images.length > 0 ? `${window.location.origin}/${images[0]}` : '';

    // Populate available sizes
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

    // Populate available colors
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

function decreaseQuantity() {
    const quantityInput = document.getElementById("quantity");
    if (quantityInput.value > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
}

function updateQuantity(itemId, change) {
    fetch(`/update-order-quantity/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ change: change })
    })
    .then(response => response.json())
    .then(data => {
        const quantityElement = document.getElementById(`quantity-${itemId}`);
        const plusButton = document.getElementById(`plus-btn-${itemId}`);
        const minusButton = document.getElementById(`minus-btn-${itemId}`);

        if (data.error) {
            alert(data.error); // Show error message if any
        } else {
            const newQuantity = data.new_quantity;

            // Update the quantity in the UI
            quantityElement.innerText = newQuantity;

            // Check if max stock is reached
            if (newQuantity >= data.max_stock) {
                plusButton.disabled = true;
                plusButton.classList.add('disabled');
                // Store max stock state in localStorage
                localStorage.setItem(`maxStockReached_${itemId}`, true);
                location.reload(); // Refresh the page to apply changes
            } else {
                plusButton.disabled = false;
                plusButton.classList.remove('disabled');
                // Remove max stock state from localStorage
                localStorage.removeItem(`maxStockReached_${itemId}`);
            }

            // If quantity is 1 and user clicks -, remove the item from cart
            if (newQuantity === 1 && change === -1) {
                fetch(`/cart/items/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        document.getElementById(`cart-item-${itemId}`).remove();
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error("Error updating quantity:", error);
    });
}




    function openEditModal(itemId, productName, description, productData, price, imagesJson) {
        // Open the modal
        document.getElementById("editModal").style.display = "flex";

        // Update modal with product data
        document.getElementById("modalProductName").textContent = productName;
        document.getElementById("productDescription").textContent = description;
        document.getElementById("productPrice").textContent = `$${parseFloat(price).toFixed(2)}`;
        document.getElementById("productId").value = itemId; // Set product ID

        // Set the main image
        const images = JSON.parse(imagesJson);
        const mainProductImage = document.getElementById("mainProductImage");
        mainProductImage.src = images.length > 0 ? `${window.location.origin}/${images[0]}` : '';

        // Populate sizes
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

        // Populate colors
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

    
    function closeEditModal() {
        document.getElementById("editModal").style.display = "none";
    }

    function updateDeliveryFee() {
        const deliveryFee = parseFloat(document.getElementById("region-select").value);
        const totalPriceWithFee = {{ $totalPrice }} + deliveryFee;
        document.getElementById("delivery-fee-display").innerText = `Delivery Fee: $${deliveryFee.toFixed(2)}`;
        document.getElementById("total-price-display").innerText = `Total Price: $${totalPriceWithFee.toFixed(2)}`;
    }
</script>
@endsection
