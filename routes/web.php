<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('products');
Route::get('/cart', [ProductController::class, 'cart'])->name('cart');
Route::get('/add-to-cart/{product}', [ProductController::class, 'addToCart'])->name('add-cart');
Route::get('/remove/{id}', [ProductController::class, 'removeFromCart'])->name('remove');
Route::post('/update-cart/{id}', [ProductController::class, 'updateCart'])->name('update-cart');


