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
    <!-- Dynamic Overlay -->
    <div class="stock-status-overlay" id="stockStatusOverlay"></div>
    
    <!-- Main Product Image -->
    <img id="mainProductImage" src="{{ asset(json_decode($product->images)[0] ?? 'images/home/dress1.jpeg') }}" class="product-image" alt="{{ $product->product_name }}">
</div>

        <!-- Product Details on the Right -->
        <div class="product-details">
            <h2 class="product-title">{{ $product->product_name }}</h2>
            <p class="product-description">{{ $product->description }}</p>
            <p class="product-price"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

            <!-- Available Sizes and Colors -->
            @if($product->productData->isNotEmpty())
    <!-- Available Sizes -->
    <div class="product-sizes">

     <!-- Available Colors -->
     <div class="product-colors">
        <p>Available Colors:</p>
        <div id="color-options" class="size-color-options">
            @foreach($product->productData->unique('color') as $data)
                <div class="size-color-item color-item" 
                     data-color="{{ $data->color }}" 
                     data-image="{{ asset($data->image) }}" 
                     onclick="filterSizesByColor('{{ $data->color }}')">
                    <span class="color" style="background-color: {{ $data->color }};"></span>
                </div>
            @endforeach
        </div>
    </div>
    
        <p>Available Sizes:</p>
        <div id="size-options" class="size-color-options">
            @foreach($product->productData as $data)
                <div class="size-color-item size-item" 
                     data-color="{{ $data->color }}" 
                     data-size="{{ $data->size }}" 
                     data-stock="{{ $data->stock_quantity }}">
                    <button 
                        class="size-btn {{ $data->stock_quantity == 0 ? 'sold-out' : '' }}" 
                        data-color="{{ $data->color }}" 
                        data-size="{{ $data->size }}" 
                        data-stock="{{ $data->stock_quantity }}"
                        {{ $data->stock_quantity == 0 ? 'disabled' : '' }}>
                        {{ $data->size }}
                        @if($data->stock_quantity == 0)
                            <span class="sold-out">Out of Stock</span>
                        @endif
                    </button>
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
function updateProductImage(element) {
    // Get the image URL from the clicked color item
    const imageUrl = element.getAttribute('data-image');

    // Find the product image element and update its source
    const productImage = document.getElementById('product-image');
    productImage.src = imageUrl;

    // Remove the active class from all color items
    document.querySelectorAll('.color-item').forEach(item => item.classList.remove('active'));

    // Add the active class to the clicked color item
    element.classList.add('active');
}


document.querySelectorAll('.color-item').forEach(function (item) {
    item.addEventListener('click', function () {
        // Remove 'selected' class from other color items
        document.querySelectorAll('.color-item').forEach(function (i) {
            i.classList.remove('selected');
        });
        this.classList.add('selected');

        // Update the selected color in the hidden input
        const selectedColor = this.getAttribute('data-color');
        document.getElementById('selectedColor').value = selectedColor;

        // Change the main product image
        const imageUrl = this.getAttribute('data-image');
        document.getElementById('mainProductImage').src = imageUrl;
    });
});

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

    function updateProductImageAndSizes(element) {
    const selectedColor = element.getAttribute('data-color');
    const imageUrl = element.getAttribute('data-image');

    // Update the product image
    const productImage = document.getElementById('product-image');
    productImage.src = imageUrl;

    // Update the available sizes
    const sizeItems = document.querySelectorAll('.size-item');

    sizeItems.forEach(item => {
        const itemColor = item.getAttribute('data-color');
        const stock = parseInt(item.getAttribute('data-stock'));
        const sizeButton = item.querySelector('.size-btn');

        // Show or hide sizes based on the selected color
        if (itemColor === selectedColor) {
            item.style.display = 'inline-block'; // Show sizes of the selected color
            if (stock === 0) {
                sizeButton.classList.add('sold-out');
                sizeButton.disabled = true;
                sizeButton.innerHTML = `${item.getAttribute('data-size')} <span class="sold-out">X</span>`;
            } else {
                sizeButton.classList.remove('sold-out');
                sizeButton.disabled = false;
                sizeButton.innerHTML = item.getAttribute('data-size');
            }
        } else {
            item.style.display = 'none'; // Hide sizes of other colors
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const sizeItems = document.querySelectorAll('.size-item');
    const sizeContainer = document.getElementById('size-options');

    // Function to filter sizes based on selected color
    window.filterSizesByColor = function (selectedColor) {
        // Loop through all size items
        sizeItems.forEach(sizeItem => {
            const itemColor = sizeItem.getAttribute('data-color');
            const stock = parseInt(sizeItem.getAttribute('data-stock'));

            if (itemColor === selectedColor && stock > 0) {
                // Show sizes for the selected color if in stock
                sizeItem.style.display = 'inline-block';
                sizeItem.querySelector('.size-btn').disabled = false;
            } else if (itemColor === selectedColor && stock === 0) {
                // Show sizes for the selected color if out of stock
                sizeItem.style.display = 'inline-block';
                sizeItem.querySelector('.size-btn').disabled = true;
            } else {
                // Hide sizes of other colors
                sizeItem.style.display = 'none';
            }
        });

        // Set the selected color in the hidden input
        document.getElementById('selectedColor').value = selectedColor;
    };
});

document.addEventListener('DOMContentLoaded', function () {
    const quantityInput = document.getElementById('quantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const colorItems = document.querySelectorAll('.color-item');
    const sizeItems = document.querySelectorAll('.size-item');
    const stockStatusOverlay = document.getElementById('stockStatusOverlay');
    let currentStock = 0;

    /**
     * Updates the button states for increment and decrement buttons
     */
    function updateButtonStates() {
        const currentQuantity = parseInt(quantityInput.value);
        increaseBtn.disabled = currentQuantity >= currentStock;
        decreaseBtn.disabled = currentQuantity <= 1;
    }

    /**
     * Updates the stock status overlay text (Sold Out, Last One, etc.)
     */
    function updateStockStatusOverlay(stock) {
        if (stock === 0) {
            stockStatusOverlay.textContent = 'Sold Out';
            stockStatusOverlay.classList.add('sold-out');
        } else if (stock === 1) {
            stockStatusOverlay.textContent = 'Last One';
            stockStatusOverlay.classList.add('last-one');
        } else {
            stockStatusOverlay.textContent = '';
            stockStatusOverlay.classList.remove('sold-out', 'last-one');
        }
    }

    /**
     * Updates the stock and resets quantity when a variant is selected
     */
    function updateStockForVariant(stock) {
        currentStock = stock;
        quantityInput.value = 1; // Reset quantity to 1
        updateButtonStates();
        updateStockStatusOverlay(stock);
    }

    /**
     * Handles color selection
     */
    colorItems.forEach(colorItem => {
        colorItem.addEventListener('click', function () {
            // Remove the "selected" class from all colors and add it to the clicked one
            colorItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');

            // Update the selected color
            const selectedColor = this.getAttribute('data-color');
            const stockForColor = parseInt(this.getAttribute('data-stock')) || 0;
            const imageUrl = this.getAttribute('data-image');
            
            // Update product image
            const mainProductImage = document.getElementById('mainProductImage');
            if (mainProductImage && imageUrl) {
                mainProductImage.src = imageUrl;
            }

            // Update the stock for the selected color
            updateStockForVariant(stockForColor);

            // Show only the sizes available for the selected color
            sizeItems.forEach(sizeItem => {
                const itemColor = sizeItem.getAttribute('data-color');
                sizeItem.style.display = itemColor === selectedColor ? 'inline-block' : 'none';
            });
        });
    });

    /**
     * Handles size selection
     */
    sizeItems.forEach(sizeItem => {
        sizeItem.addEventListener('click', function () {
            // Remove the "selected" class from all sizes and add it to the clicked one
            sizeItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');

            // Update the stock for the selected size
            const stock = parseInt(this.getAttribute('data-stock')) || 0;
            updateStockForVariant(stock);
        });
    });

    /**
     * Handles incrementing the quantity
     */
    increaseBtn.addEventListener('click', function () {
        const currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < currentStock) {
            quantityInput.value = currentQuantity + 1;
            updateButtonStates();
        }
    });

    /**
     * Handles decrementing the quantity
     */
    decreaseBtn.addEventListener('click', function () {
        const currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
            updateButtonStates();
        }
    });

    /**
     * Prevents form submission if size and color are not selected or quantity exceeds stock
     */
    document.querySelector('form').addEventListener('submit', function (event) {
        const selectedSize = document.getElementById('selectedSize').value;
        const selectedColor = document.getElementById('selectedColor').value;
        const quantity = parseInt(quantityInput.value);

        if (!selectedSize || !selectedColor) {
            event.preventDefault();
            alert('Please select a size and color before adding to the cart.');
            return;
        }

        if (quantity > currentStock || quantity < 1) {
            event.preventDefault();
            alert(`Please select a quantity between 1 and ${currentStock}.`);
        }
    });

    /**
     * Automatically selects the first available size and color on page load
     */
    const firstColor = document.querySelector('.color-item');
    if (firstColor) firstColor.click();

    const firstSize = document.querySelector('.size-item:not(.sold-out)');
    if (firstSize) firstSize.click();

    // Initialize button states on page load
    updateButtonStates();
});

function updateStockForVariant(stock) {
    currentStock = stock; // Update the stock for the selected variant
    quantityInput.value = 1; // Reset quantity to 1
    updateButtonStates(); // Update button states
}


document.addEventListener('DOMContentLoaded', function () {
    const quantityInput = document.getElementById('quantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const colorItems = document.querySelectorAll('.color-item');
    const sizeItems = document.querySelectorAll('.size-item');
    const stockStatusOverlay = document.getElementById('stockStatusOverlay');

    let currentStock = 0; // Dynamically updated stock for the selected size/color

    // Function to update button states
    function updateButtonStates() {
        const currentQuantity = parseInt(quantityInput.value);

        // Disable "+" button if quantity reaches currentStock
        increaseBtn.disabled = currentQuantity >= currentStock;

        // Disable "-" button if quantity is 1
        decreaseBtn.disabled = currentQuantity <= 1;
    }

    // Function to dynamically update stock and UI for the selected color/size
    function updateStockForVariant(stock) {
        currentStock = stock; // Update the max stock dynamically
        quantityInput.value = 1; // Reset quantity to 1 when variant changes
        updateButtonStates(); // Update button states
    }

    // Increment quantity
    increaseBtn.addEventListener('click', function () {
        const currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < currentStock) {
            quantityInput.value = currentQuantity + 1;
            updateButtonStates();
        }
    });

    // Decrement quantity
    decreaseBtn.addEventListener('click', function () {
        const currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
            updateButtonStates();
        }
    });

    // Function to handle default color and size selection
    function selectDefaultVariant() {
        // Programmatically select the first color
        const firstColor = colorItems[0];
        if (firstColor) {
            firstColor.classList.add('selected');
            const colorStock = parseInt(firstColor.getAttribute('data-stock')) || 15; // Default stock
            updateStockForVariant(colorStock);
        }

        // Programmatically select the first size for the selected color
        sizeItems.forEach(sizeItem => {
            const itemColor = sizeItem.getAttribute('data-color');
            const stock = parseInt(sizeItem.getAttribute('data-stock'));

            if (itemColor === firstColor?.getAttribute('data-color') && stock > 0) {
                sizeItem.style.display = 'inline-block';
                if (!document.querySelector('.size-item.selected')) {
                    sizeItem.classList.add('selected');
                    updateStockForVariant(stock);
                }
            } else {
                sizeItem.style.display = 'none';
            }
        });
    }

    // Handle color selection
    colorItems.forEach(colorItem => {
        colorItem.addEventListener('click', function () {
            // Clear previously selected color
            colorItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');

            // Get stock for the selected color
            const selectedColor = this.getAttribute('data-color');
            const stockForColor = parseInt(this.getAttribute('data-stock')) || 15;

            // Update stock and filter sizes
            updateStockForVariant(stockForColor);

            // Show sizes only for the selected color
            sizeItems.forEach(sizeItem => {
                const itemColor = sizeItem.getAttribute('data-color');
                const stock = parseInt(sizeItem.getAttribute('data-stock'));

                if (itemColor === selectedColor && stock > 0) {
                    sizeItem.style.display = 'inline-block';
                } else {
                    sizeItem.style.display = 'none';
                }
            });
        });
    });

    // Handle size selection
    sizeItems.forEach(sizeItem => {
        sizeItem.addEventListener('click', function () {
            sizeItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');

            // Update stock for the selected size
            const stock = parseInt(this.getAttribute('data-stock'));
            updateStockForVariant(stock);
        });
    });

    // Prevent form submission if invalid quantity
    document.querySelector('form').addEventListener('submit', function (event) {
        const quantity = parseInt(quantityInput.value);
        if (quantity > currentStock) {
            event.preventDefault();
            alert(`You cannot add more than ${currentStock} items to the cart.`);
        }
    });

    // Handle stock status overlay
    function updateStockStatusOverlay(stock) {
        if (stock === 0) {
            stockStatusOverlay.textContent = 'Sold Out';
            stockStatusOverlay.classList.add('sold-out');
        } else if (stock === 1) {
            stockStatusOverlay.textContent = 'Last One';
            stockStatusOverlay.classList.add('last-one');
        } else {
            stockStatusOverlay.textContent = '';
            stockStatusOverlay.classList.remove('sold-out', 'last-one');
        }
    }

    // Initialize default selection
    selectDefaultVariant();
    updateButtonStates();
});





document.addEventListener('DOMContentLoaded', function () {
    const stockStatusOverlay = document.getElementById('stockStatusOverlay');
    const sizeItems = document.querySelectorAll('.size-item');

    function updateStockStatus(selectedColor) {
        let totalStock = 0;

        // Calculate total stock for the selected color
        sizeItems.forEach(item => {
            const itemColor = item.getAttribute('data-color');
            const stock = parseInt(item.getAttribute('data-stock'));

            if (itemColor === selectedColor) {
                totalStock += stock;
            }
        });

        // Update overlay text based on total stock
        if (totalStock === 0) {
            stockStatusOverlay.textContent = "Sold Out";
            stockStatusOverlay.className = "stock-status-overlay sold-out";
        } else if (totalStock === 1) {
            stockStatusOverlay.textContent = "Last One";
            stockStatusOverlay.className = "stock-status-overlay last-one";
        } else {
            stockStatusOverlay.textContent = ""; // Hide overlay
            stockStatusOverlay.className = "stock-status-overlay";
        }
    }

    // Add event listener for color selection
    document.querySelectorAll('.color-item').forEach(function (item) {
        item.addEventListener('click', function () {
            // Get the selected color
            const selectedColor = this.getAttribute('data-color');

            // Update the stock status overlay
            updateStockStatus(selectedColor);
        });
    });

    // Initial load: Update stock status for the default color
    const defaultColor = document.querySelector('.color-item.selected')?.getAttribute('data-color');
    if (defaultColor) {
        updateStockStatus(defaultColor);
    }
});

document.querySelector('form').addEventListener('submit', function (event) {
    const quantity = parseInt(quantityInput.value);
    if (quantity > currentStock) {
        event.preventDefault();
        alert(`You cannot add more than ${currentStock} items to the cart.`);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const colorItems = document.querySelectorAll('.color-item');
    const mainProductImage = document.getElementById('mainProductImage');

    /**
     * Updates the main product image based on the selected color
     * @param {HTMLElement} element The clicked color element
     */
    function updateProductImage(element) {
        // Get the image URL associated with the selected color
        const imageUrl = element.getAttribute('data-image');
        
        if (imageUrl && mainProductImage) {
            mainProductImage.src = imageUrl;
        }
    }

    /**
     * Handles color selection and updates the UI accordingly
     */
    colorItems.forEach(colorItem => {
        colorItem.addEventListener('click', function () {
            // Remove the "selected" class from all color items
            colorItems.forEach(item => item.classList.remove('selected'));
            this.classList.add('selected'); // Add "selected" class to the clicked color item

            // Update the selected color value
            const selectedColor = this.getAttribute('data-color');
            document.getElementById('selectedColor').value = selectedColor;

            // Change the product image dynamically
            updateProductImage(this);

            // Filter available sizes for the selected color (optional, if needed)
            const sizeItems = document.querySelectorAll('.size-item');
            sizeItems.forEach(sizeItem => {
                const itemColor = sizeItem.getAttribute('data-color');
                sizeItem.style.display = itemColor === selectedColor ? 'inline-block' : 'none';
            });

            // Optionally reset size selection if a new color is chosen
            const firstSize = document.querySelector(`.size-item[data-color="${selectedColor}"]:not(.sold-out)`);
            if (firstSize) {
                sizeItems.forEach(size => size.classList.remove('selected'));
                firstSize.classList.add('selected');
                document.getElementById('selectedSize').value = firstSize.getAttribute('data-size');
            }
        });
    });

    // Automatically select the first color and update the image on page load
    const firstColor = document.querySelector('.color-item');
    if (firstColor) {
        firstColor.click();
    }
});

</script>


@endsection
