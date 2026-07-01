@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    .ui-font {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Teknik memaksa elemen menjadi Full Width meski di dalam container */
    .break-container {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }

    html {
        scroll-behavior: smooth;
    }

    .bg-teal-ui { background-color: #14b8a6; }
    .text-teal-ui { color: #14b8a6; }
    .text-dark-ui { color: #1e293b; }
</style>

<div class="ui-font bg-white overflow-x-hidden">

    <!-- Konten Utama (Body About Us Asli Tanpa Modifikasi CSS Aneh) -->
    <div class="bg-gray-50 min-h-screen pt-12 pb-20">
        <div class="container mx-auto px-6 md:px-12 max-w-5xl">
            
            <!-- Header Section -->
            <div class="text-center mb-16">
                <p class="text-teal-ui font-extrabold text-xs tracking-wider uppercase mb-3">Tentang Kami</p>
                <h1 class="text-4xl font-extrabold text-dark-ui mb-4">CV ABBA BAROKAH</h1>
                <div class="h-1.5 w-16 bg-teal-ui mx-auto rounded-full"></div>
            </div>

            <!-- Grid Content (Tentang Perusahaan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <img src="https://images.unsplash.com/photo-1606857521015-7f9fcf423740?q=80&w=800" 
                         alt="Office" 
                         class="rounded-lg shadow-md w-full h-[350px] object-cover border border-gray-200">
                </div>
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-dark-ui">Membangun Kualitas dan Menyediakan Kepercayaan</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        CV ABBA BAROKAH merupakan perusahaan profesional terpercaya yang bergerak di bidang pengadaan peralatan kantor, pembuatan souvenir eksklusif, serta berbagai produk berkualitas tinggi untuk kebutuhan proyek instansi maupun swasta.
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Kami selalu berkomitmen memberikan solusi terbaik dengan mengedepankan efisiensi kerja, ketepatan waktu pengiriman, serta pelayanan purnajual yang responsif demi menjaga kepuasan jangka panjang setiap mitra bisnis kami.
                    </p>
                </div>
            </div>

            <!-- Visi & Misi Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <div class="text-teal-ui text-2xl mb-3">👁️‍🗨️</div>
                    <h3 class="font-extrabold text-dark-ui text-lg mb-3">Visi Kami</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        Menjadi perusahaan pengadaan dan penyedia solusi kebutuhan kantor terkemuka yang dikenal karena keunggulan kualitas produk, integritas operasional yang tinggi, serta menjadi mitra strategis utama di seluruh wilayah Indonesia.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <div class="text-teal-ui text-2xl mb-3">🎯</div>
                    <h3 class="font-extrabold text-dark-ui text-lg mb-3">Misi Kami</h3>
                    <ul class="text-gray-500 text-xs space-y-2 list-disc list-inside leading-relaxed">
                        <li>Menyediakan variasi produk perlengkapan berkualitas tinggi yang up-to-date.</li>
                        <li>Membangun manajemen logistik yang andal demi kecepatan distribusi.</li>
                        <li>Menyelenggarakan kerja sama bisnis yang transparan, jujur, dan tepercaya.</li>
                    </ul>
                </div>
            </div>

            <!-- Bagian Alamat & Kontak CTA Section (Sudah Diperbaiki Jaraknya) -->
            <div class="w-full relative z-10">
                <div class="bg-teal-ui text-white rounded-lg p-8 shadow-md flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h4 class="font-extrabold text-lg mb-1">Butuh Pengadaan Proyek Skala Besar?</h4>
                        <p class="text-white/80 text-xs">Hubungi perwakilan penjualan kami untuk mendiskusikan penawaran harga terbaik.</p>
                    </div>
                    <a href="https://wa.me/6288217126768" target="_blank" rel="noopener noreferrer" class="bg-white text-teal-ui px-6 py-3 rounded text-xs font-bold shadow hover:bg-gray-100 transition no-underline whitespace-nowrap">
                        Hubungi Kontak Kami
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER (Gaya Dashboard) -->
    <!-- Menggunakan pt-16 karena jarak sekarang dikontrol dengan aman oleh pembungkus konten di dalamnya -->
    <footer id="main-footer" class="break-container bg-[#d1d5db] pt-16 pb-10">

        <!-- Konten Link dan Alamat Footer -->
        <div class="container mx-auto px-12 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="md:col-span-2 text-left">
                <div class="bg-white/60 p-4 w-20 h-16 rounded mb-8 flex items-center justify-center border border-gray-300">
                    <div class="w-8 h-6 bg-gray-300"></div>
                </div>
                <h3 class="font-extrabold text-dark-ui leading-tight mb-6 uppercase text-base">
                    Membangun kualitas dan<br>menyediakan kepercayaan
                </h3>
                <div class="text-xs text-dark-ui/80 space-y-3 leading-relaxed">
                    <p class="italic">Jl. MH Thamrin 3/10, Desa<br>Tlogobendung, Gresik, Jawa Timur</p>
                    <a href="https://wa.me/6288217126768" target="_blank" rel="noopener noreferrer" class="font-bold text-sm text-black hover:underline no-underline">
                        +62 882-1712-6768
                    </a>
                    <p class="font-medium">ABBABAROKAH@gmail.com</p>
                </div>
            </div>

            <div class="flex flex-col space-y-6 md:col-start-4 md:items-end md:text-right">
                <a href="{{ route('home') }}" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Dashboard</a>
                <a href="{{ route('about.index') }}" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Tentang Kami</a>
                
                @guest
                    <a href="javascript:void(0)" onclick="peringatanLogin()" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Produk</a>
                @endguest
                @auth
                    <a href="{{ route('products.index') }}" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Produk</a>
                @endauth

                @guest
                    <a href="javascript:void(0)" onclick="peringatanLogin()" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Pembelian</a>
                @endguest
                @auth
                    <a href="{{ route('purchase.history') }}" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Pembelian</a>
                @endauth
                
                <a href="{{ route('home') }}#main-footer" class="font-extrabold text-dark-ui text-sm hover:text-teal-500 transition">Kontak</a>
            </div>
        </div>
        
        <div class="container mx-auto px-12 mt-20 border-t border-gray-400/30 pt-8 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">&copy; 2026 CV ABBA BAROKAH. All Rights Reserved.</p>
        </div>
    </footer>

</div>
@endsection