<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Love;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
public function show($id)
{
    // Retrieve the product by ID
    $product = Products::with('productData')->findOrFail($id);
    $user = auth()->user(); // Get the authenticated user

    return view('products.show', compact('product', 'user'));
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
}