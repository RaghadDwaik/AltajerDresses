<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\ProductData;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
  /* public function addToCart(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'products_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size'       => 'required|string',
        ]);

        $productId = $validatedData['products_id'];
        $quantity = $validatedData['quantity'];
        $size = $validatedData['size'];

        // Check if the user is logged in
        $user = Auth::user();

        // Fetch or create a new order for the user (status 'pending' for cart)
        $order = Orders::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_amount' => 0] // Initial total amount
        );

        // Check if the product is already in the cart (order details)
        $orderDetail = OrderDetails::where('orders_id', $order->id)
            ->where('products_id', $productId)
            ->where('size', $size)
            ->first();

        if ($orderDetail) {
            // Update the quantity if the product already exists in the cart
            $orderDetail->quantity += $quantity;
            $orderDetail->total = $orderDetail->quantity * $orderDetail->price; // Update total for this item
            $orderDetail->save();
        } else {
            // Add new product to the cart (order details)
            $product = Products::find($productId);  // Fetch product to get its price
            OrderDetails::create([
                'orders_id'   => $order->id,
                'products_id' => $productId,
                'product_name'=> $product->product_name,
                'quantity'   => $quantity,
                'size'       => $size,
                'price'      => $product->price,
                'total'      => $product->price * $quantity,
            ]);
        }
       
        // Update the order's total amount
        $order->total_amount = $order->orderItems->sum('total');  // Sum of all order details
        $order->save();


        // Optional: Flash message for success

        // Return success response
        return redirect()->back()->with('success', 'product added successfully.');
    }*/

    public function addToCart(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'products_id' => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'size'        => 'required|string',
            'color'       => 'required|string', // Assuming color is required too
        ]);

        $cart = session()->get('cart', []);
    
        $productId = $validatedData['products_id'];
        $quantity = $validatedData['quantity'];
        $size = $validatedData['size'];
        $color = $validatedData['color'];
    
        // Check if the user is logged in
        $user = Auth::user();
    
        // Fetch the specific product and its variant data (size, color)
        $product = Products::find($productId);
    
        // Retrieve the specific ProductData entry for size and color
        $productData = ProductData::where('products_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();
    
        if (!$productData) {
            return redirect()->back()->with('error', 'The selected size and color combination is not available.');
        }
    
        // Check if there is enough stock for the selected product variant
        if ($productData->stock_quantity < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock for the selected variant.');
        }
    
        // Fetch or create a new order for the user (status 'pending' for cart)
        $order = Orders::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_amount' => 0] // Initial total amount
        );
    
        // Check if the product variant is already in the cart (order details)
        $orderDetail = OrderDetails::where('orders_id', $order->id)
            ->where('products_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();
    
        if ($orderDetail) {
            // Update the quantity if the product variant already exists in the cart
            $orderDetail->quantity += $quantity;
            $orderDetail->total = $orderDetail->quantity * $orderDetail->price; // Update total for this item
            $orderDetail->save();
        } else {
            // Add new product variant to the cart (order details)
            OrderDetails::create([
                'orders_id'   => $order->id,
                'products_id' => $productId,
                'product_name'=> $product->product_name,
                'quantity'    => $quantity,
                'size'       => $size,
                'color'      => $color,
                'price'      => $product->price, // Assuming the price is for the base product
                'total'      => $product->price * $quantity,
            ]);
        }
    
        // Reduce the stock quantity of the product variant
       // $productData->stock_quantity -= $quantity;
        $productData->save();
    
        // Update the order's total amount
        $order->total_amount = $order->orderItems()->sum('total');  // Ensure orderDetails() method exists for relation
        $order->save();
    
        session()->put('cart', $cart);
        // Return success response
        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }
    
    

    public function viewCart(Request $request)
{
    $orders = Orders::with('orderItems.product')->where('user_id', auth()->id())->get();
    $cartCount = $orders->sum(fn($order) => $order->orderItems->count());

    // Check if `editProduct` parameter exists in the request
    $editProduct = null;
    if ($request->has('editProduct')) {
        $editProductId = $request->get('editProduct');
        $editProduct = Products::with('productData')->find($editProductId);
    }

    return view('mainfiles.cart', compact('orders', 'cartCount', 'editProduct'));
}

    public function completeOrder(Request $request, $id)
    {
        // Validate and process the order completion logic
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);
    
        // Fetch order details with related items
        $orderDetail = Orders::with('orderItems.product')->find($id);
    
        // Check if order exists
        if ($orderDetail) {
            // Update order details based on payment method and note
         /*   $orderDetail->payment_method = $validatedData['payment_method'];
            $orderDetail->note = $validatedData['note'];*/
            $orderDetail->status = 'Completed'; // Update status as necessary
            $orderDetail->save();
    
            session()->forget('cart');
    

            return view('mainfiles.ordersConfirmation', compact('orderDetail'))->with('message', 'Order completed successfully!');
        } else {
            return redirect()->back()->withErrors('Order not found.');
        }
    }
    
    
    public function destroy($id)
{
    // Find the order detail by ID
    $orderDetail = OrderDetails::find($id);

    // Check if the order detail exists
    if ($orderDetail) {
        $orderDetail->delete(); // Delete the order detail
        return redirect()->back()->with('message', 'Item removed from cart successfully!');
    } else {
        return redirect()->back()->withErrors('Item not found in cart.');
    }
}
    
}
