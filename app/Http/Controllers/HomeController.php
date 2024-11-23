<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index() {
        // Retrieve the category by ID
       
        $categories = Categories::all();
        $products = Products::inRandomOrder()->take(9)->get();
        $user = Auth::user();

        // Assuming you want to fetch products related to this category:
    
        return view('mainfiles.Home', compact('categories','products','user'));
       
    }
    public function show($id) {
        // Retrieve the category by ID
        $category = Categories::findOrFail($id);
        $user = Auth::user();
        
        // Assuming you want to fetch products related to this category:
        $products = Products::where('category_id', $id)->distinct()->get();
        
        return view('Categories.show', compact('category', 'products','user'));
    }
    public function order()
    {
        // Fetch all orders with their order details
        $orders = Orders::where('user_id', Auth::id())->with('orderItems.product')->get();

        return view('mainfiles.order', compact('orders'));
    }
    public function showDetails($id)
{
    // Fetch the order and its associated items
    $orderDetail = Orders::with('orderItems.product')->findOrFail($id); // Ensure you load related products

    return view('mainfiles.orderdetails', compact('orderDetail')); // Pass the order details to the view
}

public function showLovedProducts()
{
    $user = Auth::user();
    $lovedProducts = $user->lovedProducts; // Fetch loved products
    
    return view('mainfiles.love', compact('lovedProducts'));
}
public function showProducts()
{
    // Fetch 6 random products from the 'products' table
    $products = Products::inRandomOrder()->take(6)->get();

    // Return the view with the products data
    return view('products', compact('products'));
}

}
