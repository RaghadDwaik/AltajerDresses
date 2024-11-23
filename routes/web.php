<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/



Route::get('/aboutUs', function () {
    return view('mainfiles.aboutus');
})->name('aboutus');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\ProductController;
use App\Http\Controllers\SocialAuthController;

Route::get('/category/{id}', [HomeController::class, 'show'])->name('categories.show');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/orders/{id}', [CartController::class, 'completeOrder'])->name('orders.complete');
Route::get('/orders', [HomeController::class, 'order'])->name('orders');
Route::get('/orders/{id}/details', [HomeController::class, 'showDetails'])->name('orders.details');
Route::delete('/cart/items/{id}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::post('/toggle-favorite', [ProductController::class, 'toggleFavorite'])->name('toggleFavorite');


Route::get('/loved-products', [HomeController::class, 'showLovedProducts'])->name('loved.products')->middleware('auth');

// routes/web.php

Route::put('/product-data/{id}/update', [ProductController::class, 'update'])->name('productData.update');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/update-order-quantity/{itemId}', [ProductController::class, 'updateOrderQuantity']);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/test-session', function () {
    session(['test' => 'This is a test']);
    return session('test'); // Should return 'This is a test'
});



Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
require __DIR__.'/auth.php';
