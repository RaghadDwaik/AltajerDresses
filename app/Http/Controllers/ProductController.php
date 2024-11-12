<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Love;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\ProductData;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{

    public function index()
    {
        // Fetch all products from the database
        $products = Products::all();
        $user = Auth::user();
        // Pass the products to the 'products.index' view
        return view('products.allproduct', compact('products','user'));
    }

public function show($id)
{
    // Retrieve the product by ID
    $product = Products::with('productData')->findOrFail($id);
    $user = auth()->user(); // Get the authenticated user
    $relatedProducts = Products::where('id', '!=', $id)->inRandomOrder()->take(4)->get();

    return view('products.show', compact('product', 'user','relatedProducts'));
}

public function toggleFavorite(Request $request)
{
    $user = Auth::user();
    $productId = $request->input('product_id');
    $action = $request->input('action');

    if ($action === 'add') {
        Love::create([
            'user_id' => $user->id,
            'products_id' => $productId,
        ]);
        return response()->json(['message' => 'Product added to favorites.']);
    } else {
        Love::where('user_id', $user->id)
            ->where('products_id', $productId)
            ->delete();
        return response()->json(['message' => 'Product removed from favorites.']);
    }
}

public function update(Request $request, $id)
{
    Log::info("Update request received with data", ['data' => $request->all()]);
    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'size' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'quantity' => 'nullable|integer|min:1', // Changed to nullable
        ]);

        // Find the product data by ID
        $productData = OrderDetails::find($id);
        if (!$productData) {
            Log::error("ProductData not found for ID: " . $id);
            return response()->json(['error' => 'Product not found'], 404);
        }

        Log::info("ProductData found. Proceeding with update", ['productData' => $productData->toArray()]);

        // Prepare an array to hold the fields to be updated
        $updateData = [];

        // Only add fields to $updateData if they are provided in the request
        if (isset($validatedData['size'])) {
            $updateData['size'] = $validatedData['size'];
        }
        if (isset($validatedData['color'])) {
            $updateData['color'] = $validatedData['color'];
        }

        // If quantity is provided, use it; otherwise, use the existing quantity from the database
        $updateData['quantity'] = $validatedData['quantity'] ?? $productData->quantity;

        // Update the product data in the database
        $productData->update($updateData);

        Log::info("ProductData updated successfully", ['updated_data' => $productData->toArray()]);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => [
                'size' => $productData->size,
                'color' => $productData->color,
                'quantity' => $productData->quantity,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error("Error during update", ['exception' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred while updating the product.'], 500);
    }
}



public function updateOrderQuantity(Request $request, $itemId)
{
    try {
        // Validate the change
        $change = $request->input('change');

        // Find the order detail item
        $orderDetail = OrderDetails::find($itemId);
        if (!$orderDetail) {
            return response()->json(['error' => 'Item not found in the cart.'], 404);
        }

        // Calculate new quantity, making sure it doesn't go below 1
        $newQuantity = max($orderDetail->quantity + $change, 1);

        // Find related product data to check stock availability
        $productData = ProductData::where('products_id', $orderDetail->products_id)
            ->where('size', $orderDetail->size)
            ->where('color', $orderDetail->color)
            ->first();

        if (!$productData) {
            return response()->json(['error' => 'The selected size and color combination is not available.'], 404);
        }

      // Ensure new quantity does not exceed available stock
if ($newQuantity > $productData->stock_quantity) {
    return response()->json(['error' => 'Not enough stock for the selected quantity.'], 400);
}

// Set or clear session based on whether max stock has been reached
if ($newQuantity == $productData->stock_quantity) {
    // Set a session variable to indicate that max stock has been reached
    session()->put('max_stock_reached_' . $itemId, true);
} else {
    // Remove the session variable if the quantity is below max stock
    session()->forget('max_stock_reached_' . $itemId);
}

        
      
        

        // Update the order detail quantity and total
        $orderDetail->quantity = $newQuantity;
        $orderDetail->total = $orderDetail->price * $newQuantity;
        $orderDetail->save();

        // Update the order's total amount
        $order = Orders::find($orderDetail->orders_id);
        $order->total_amount = $order->orderItems()->sum('total');
        $order->save();

        return response()->json([
            'new_quantity' => $newQuantity,
            'max_stock' => $productData->stock_quantity // Send max stock for UI handling
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred. Please try again.'], 500);
    }
}



}


