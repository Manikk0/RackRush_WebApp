<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [ProductController::class, 'index'])->name('index');

// AUTH
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CATEGORIES
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{kategoria}', [CategoryController::class, 'show'])->name('category');

// PRODUCTS
Route::get('/product/{produkt}', [ProductController::class, 'show'])->name('product-detail');

// Search products by name, description, recipe, code
Route::get('/search', [SearchController::class, 'index'])->name('search');

// CART & ORDERS
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty');
Route::get('/cart/api', [CartController::class, 'getCart'])->name('cart.api');

Route::get('/order-details', function () {
    return view('order_details');
})->name('order_details');

Route::get('/order-success', function () {
    return view('order_success');
})->name('order_success');

// ADMIN
Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin');
