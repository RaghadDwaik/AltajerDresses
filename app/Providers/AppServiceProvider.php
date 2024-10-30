<?php

namespace App\Providers;

use App\Models\OrderDetails;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                // Fetch the count of products in the user's pending cart
                $cartCount = OrderDetails::whereHas('order', function ($query) {
                    $query->where('user_id', Auth::id())
                          ->where('status', 'pending'); // Ensure this matches your order status
                })->sum('quantity');
            }

            // Pass the cart count to all views
            $view->with('cartCount', $cartCount);
        });
    }
}