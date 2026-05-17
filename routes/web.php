<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/', [ProductController::class, 'dashboard'])->name('home'); 
Route::get('/products', [ProductController::class, 'index'])->name('products.index'); 
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show'); 

/*
|--------------------------------------------------------------------------
| Customer Routes (Auth & Role Customer)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', [ProductController::class, 'dashboard'])->name('customer.dashboard');

    // Orders
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    
    Route::get('/cart', 'App\Http\Controllers\CartController@index')->name('cart.index');
    Route::post('/cart/add/{product}', 'App\Http\Controllers\CartController@add')->name('cart.add');
    Route::patch('/cart/update/{cartItem}', 'App\Http\Controllers\CartController@update')->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', 'App\Http\Controllers\CartController@remove')->name('cart.remove');
    Route::post('/cart/checkout', 'App\Http\Controllers\CartController@checkout')->name('cart.checkout');
});

/*
|--------------------------------------------------------------------------
| Admin Redirect
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/admin');
    })->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Payment Callback (Midtrans Webhook)
|--------------------------------------------------------------------------
*/

Route::post('/payment/callback', [OrderController::class, 'callback'])->name('payment.callback');