<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Asumsi Anda menggunakan Filament atau AuthController bawaan
 Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/', [ProductController::class, 'index'])->name('home'); 
Route::get('/products', [ProductController::class, 'index'])->name('products.index'); 
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show'); 

/*
|--------------------------------------------------------------------------
| Customer Routes (Harus Login & Role Customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    
    // -- ROUTES KERANJANG (CART) --
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    
    // -- ROUTES ORDER & MIDTRANS --
    // Memproses isi keranjang
    Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('checkout');
    
    // Memproses order langsung (Bypass Keranjang)
    Route::post('/orders/direct', [OrderController::class, 'directCheckout'])->name('orders.direct');
    
    // Menampilkan riwayat pesanan
    Route::get('/orders/history', [OrderController::class, 'index'])->name('orders.index');
    
    // Menampilkan detail 1 pesanan & Tombol Bayar
    Route::get('/orders/{order:code}', [OrderController::class, 'show'])->name('orders.show');
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
Route::post('/payment/callback', [OrderController::class, 'callback'])
    ->name('payment.callback')
    ->withoutMiddleware([VerifyCsrfToken::class]);