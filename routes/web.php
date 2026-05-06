<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak perlu login)
|--------------------------------------------------------------------------
| Digunakan untuk halaman publik seperti katalog produk
|
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/', [ProductController::class, 'dashboard'])
    ->name('home'); 
// Menampilkan semua produk (landing page)

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index'); 
// Menampilkan list produk

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show'); 
// Menampilkan detail 1 produk


/*
|--------------------------------------------------------------------------
| Customer Routes (Harus Login & Role Customer)
|--------------------------------------------------------------------------
| Digunakan untuk proses order dan melihat history
|
*/

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', [ProductController::class, 'dashboard'])->name('customer.dashboard');

    Route::post('/orders', [OrderController::class, 'store'])
        ->name('orders.store');
    // Membuat order (1 produk per order)

    Route::get('/orders/history', [OrderController::class, 'history'])
        ->name('orders.history');
    // Melihat riwayat pesanan user

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
    // Melihat detail 1 order milik user
});


/*
|--------------------------------------------------------------------------
| Admin Redirect (Optional)
|--------------------------------------------------------------------------
| Jika ingin admin diarahkan ke filament panel
|
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect('/admin');
    })->name('admin.dashboard');
    // Redirect admin ke filament panel
});


/*
|--------------------------------------------------------------------------
| Payment Callback (Midtrans Webhook)
|--------------------------------------------------------------------------
| Route ini tidak pakai auth karena dipanggil oleh server Midtrans
| WAJIB DIAMANKAN DENGAN SIGNATURE VERIFICATION
|
*/

Route::post('/payment/callback', [OrderController::class, 'callback'])
    ->name('payment.callback');
