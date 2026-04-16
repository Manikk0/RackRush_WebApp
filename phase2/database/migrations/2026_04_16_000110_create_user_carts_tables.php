<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Create persistent cart tables for logged-in users.
    public function up(): void
    {
        Schema::create('user_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique('user_id');
        });

        Schema::create('user_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('user_carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
            $table->unique(['cart_id', 'product_id']);
        });
    }

    // Drop persistent cart tables.
    public function down(): void
    {
        Schema::dropIfExists('user_cart_items');
        Schema::dropIfExists('user_carts');
    }
};
