<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::where('is_active', true)->get();
        // Gunakan query builder agar bisa menyaring data secara dinamis
        $query = Product::with('category');

        // Fitur Pencarian berdasarkan nama produk
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter berdasarkan slug/nama kategori (opsional, sesuaikan dengan struktur DB Anda)
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Mengambil data produk terbaru
        $products = $query->latest()->get();

        return view('pages.home', compact('products', 'categories'));
    }

    public function dashboard()
    {
        // Batasi produk yang muncul di landing page/dashboard awal (misal: hanya 4 produk)
        $products = Product::with('category')->latest()->take(4)->get();

        return view('customer.dashboard', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        
        return view('products.show', compact('product'));
    }
}