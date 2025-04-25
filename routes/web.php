<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ProductDetailController;

Route::middleware(['auth'])->group(function () {
Route::get('/', [ProductController::class, 'index'])->name('products');
Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
Route::get('/add-to-cart/{product}', [ProductController::class, 'addToCart'])->name('add-cart');
Route::get('/remove/{id}', [ProductController::class, 'removeFromCart'])->name('remove');
Route::post('/update-cart/{id}', [ProductController::class, 'updateCart'])->name('update-cart');

Route::get('/checkout', [RazorpayController::class, 'payment'])->name('razorpay.payment');

Route::post("/razorpay/payment",[RazorpayController::class, 'payment'])->name('razorpay.payment');
Route::post('/razorpay/callback', [RazorpayController::class, 'callback'])->name('razorpay.callback');
Route::get('/products', [ProductController::class, 'index'])->name('products');
});

Route::get('/products/{product}', [ProductDetailController::class, 'show'])->name('product.detail');
    Route::post('/products/{product}/reviews', [ProductDetailController::class, 'storeReview'])->name('products.reviews.store');
require __DIR__.'/auth.php';
