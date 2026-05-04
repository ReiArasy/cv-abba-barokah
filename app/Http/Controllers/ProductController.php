<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil data produk dari database
        $products = Product::with('category')->latest()->get();

        // SINKRONISASI: Pastikan memanggil 'pages.home' sesuai file yang Anda edit
        return view('pages.home', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}