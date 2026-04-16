<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Homepage.
Route::get('/', [ProductController::class, 'index'])->name('index');

// Customer authentication.
Route::get('/login', fn () => redirect()->route('index'));
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Category browsing.
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{kategoria}', [CategoryController::class, 'show'])->name('category');

// Product details.
Route::get('/product/{produkt}', [ProductController::class, 'show'])->name('product-detail');

// Product search by name/code.
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Cart endpoints.
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty');
Route::get('/cart/api', [CartController::class, 'getCart'])->name('cart.api');

// Checkout pages.
Route::get('/order-details', [OrderController::class, 'details'])->name('order_details');
Route::post('/checkout', [OrderController::class, 'place'])->name('checkout.place');
Route::get('/order-success/{order}', [OrderController::class, 'success'])->name('order_success');

// Admin (login na mieste; dashboard len po session prihlásení).
Route::get('/admin', function () {
    return view('admin');
})->name('admin');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
