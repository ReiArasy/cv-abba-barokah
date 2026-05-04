@extends('layouts.app')
 
@section('title', 'Beranda')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Selamat Datang di Katalog Produk</h1>
        <p class="mb-6">Ini adalah halaman utama proyek cv-abba-barokah.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="font-bold">{{ $product->name }}</h2>
                    <p>Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            @empty
                <!-- Jika database kosong, teks ini akan muncul -->
                <div class="col-span-3 text-center p-10 bg-yellow-100">
                    <p>Belum ada data produk di database. Silakan jalankan seeder atau isi data di Filament.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection