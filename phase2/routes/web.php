<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/categories', function () {
    return view('categories');
})->name('categories');

Route::get('/category', function () {
    return view('category');
})->name('category');

Route::get('/product-detail', function () {
    return view('product-detail');
})->name('product-detail');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/order-details', function () {
    return view('order_details');
})->name('order_details');

Route::get('/order-success', function () {
    return view('order_success');
})->name('order_success');

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin');
