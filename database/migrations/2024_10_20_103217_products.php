<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('product_name'); // Product name
            $table->text('description')->nullable(); // Product description
            $table->decimal('price'); // Product price
            $table->unsignedInteger('stock_quantity'); // Quantity in stock
            $table->json('images')->nullable(); // Path to product image
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
