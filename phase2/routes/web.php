<?php

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

// Admin (login view for guests; dashboard only for users with is_admin).
Route::get('/admin', function () {
    return view('admin');
})->name('admin');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');

// Admin product API: must be logged in and is_admin (see EnsureUserIsAdmin middleware).
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/api/admin/products/{id}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::post('/api/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::post('/api/admin/products/{id}/update', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/api/admin/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
});

// Ak chýba symlink public/storage, tieto URL by inak vrátili 404. Symlink rieši "php artisan storage:link".
Route::get('/storage/{path}', function (string $path) {
    $path = str_replace('\\', '/', $path);
    if (str_contains($path, '..')) {
        abort(404);
    }
    if (! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->where('path', '.+');
