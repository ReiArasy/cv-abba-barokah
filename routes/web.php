<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses siapa saja tanpa login)
|--------------------------------------------------------------------------
*/

// RUTE UTAMA (LANDING PAGE): Bisa dilihat umum, tapi hanya sebagai brosur awal
Route::get('/', function () {
    $products = \App\Models\Product::all(); 
    return view('customer.dashboard', compact('products'));
})->name('home');

// Rutes Authentication (Halaman Login & Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Routes (WAJIB LOGIN & Role Customer untuk akses semua fitur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    
    // -- RUTES KATALOG & DETAIL PRODUK (Sekarang aman diproteksi auth) --
    Route::get('/products', [ProductController::class, 'index'])->name('products.index'); 
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show'); 

    // -- ROUTES KERANJANG (CART) --
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product_id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product_id}', [CartController::class, 'remove'])->name('cart.remove');
    
    // -- ROUTES ORDER & MIDTRANS --
    Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders/direct', [OrderController::class, 'directCheckout'])->name('orders.direct');
    Route::get('/orders/history', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:code}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Admin Redirect Routes
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