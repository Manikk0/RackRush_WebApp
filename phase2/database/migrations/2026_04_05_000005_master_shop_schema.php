<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Create all custom eshop domain tables.
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('product_code')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('quantity', 10, 3);
            $table->string('unit');
            $table->text('description')->nullable();
            $table->text('recipe')->nullable();
            $table->integer('discount')->default(0);
            $table->decimal('sold_count', 10, 2)->default(0);
            $table->string('country_of_origin')->nullable();
            $table->boolean('is_bio')->nullable();
            $table->boolean('is_plastic_free')->nullable();
            $table->string('allergens')->nullable();
            $table->timestamps();
        });

        // 3. Product images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('url');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 4. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('order_date')->useCurrent();
            $table->string('status', 50);
            $table->string('payment_method', 50)->nullable();
            $table->text('courier_note')->nullable();
            $table->string('discount_code', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->decimal('price_per_item', 10, 2);
            $table->decimal('quantity', 10, 3);
            $table->string('unit', 50);
            $table->timestamps();
        });

        // 5. Invoices and payments
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('invoice_number', 100)->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('vat_amount', 10, 2);
            $table->timestamp('issued_at')->useCurrent();
            $table->text('billing_details');
            $table->string('currency', 10)->default('EUR');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('method', 50);
            $table->string('status', 50);
            $table->timestamp('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    // Drop all custom eshop domain tables.
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
