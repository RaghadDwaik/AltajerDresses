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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orders_id'); // Correct foreign key
            $table->unsignedBigInteger('products_id');
            $table->string('product_name');
            $table->string('color')->nullable();
            $table->string('size')->nullable(); // Assuming size can be null
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('total', 8, 2);
            $table->timestamps();

            $table->foreign('orders_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('products_id')->references('id')->on('Products')->onDelete('cascade');

            // Add additional foreign keys as necessary
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
