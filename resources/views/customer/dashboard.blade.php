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

    @include('components.navbar')

    <header class="break-container relative h-[500px] bg-cover bg-center" 
            style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1200');">
        
        <div class="flex flex-col items-center justify-center h-full -mt-20 px-4 text-center text-white relative z-10">
            <p class="uppercase tracking-[0.3em] text-[10px] font-extrabold mb-4 opacity-90">CV ABBA BAROKAH</p>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-8">
                Solusi terbaik untuk proyek Anda!<br>
                menyediakan berbagai produk berkualitas<br>
                dengan pelayanan terpercaya
            </h1>
            <div class="w-full max-w-lg relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" placeholder="Search product..." class="w-full pl-11 pr-4 py-3.5 rounded-sm text-gray-800 focus:outline-none shadow-xl text-sm">
            </div>
        </div>
    </header>

    <section class="container mx-auto px-6 py-20">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-extrabold text-dark-ui">Products</h2>
                <div class="h-1.5 w-12 bg-teal-ui mt-2"></div>
            </div>
            <a href="{{ route('products.index') }}" class="px-6 py-2 border border-teal-ui text-teal-ui rounded font-bold text-xs hover:bg-teal-ui hover:text-white transition inline-block">
                View All
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->id) }}" class="border border-gray-200 rounded-sm p-5 hover:border-teal-ui/50 shadow-sm hover:shadow-md transition flex flex-col justify-between relative bg-white group block text-left decoration-none">
                
                <div class="absolute top-4 right-4 w-6 h-6 bg-gray-100 group-hover:bg-teal-ui group-hover:text-white transition rounded-full flex items-center justify-center text-gray-500 font-bold text-sm shadow-sm z-10">
                    +
                </div>

                <div class="aspect-square bg-gray-50 border border-gray-100 mb-5 flex items-center justify-center rounded overflow-hidden">
                    @if(!empty($product->image) && is_array($product->image) && isset($product->image[0]))
                        <img 
                            src="{{ asset('storage/' . $product->image[0]) }}" 
                            alt="{{ $product->name }}" 
                            class="object-cover w-full h-full group-hover:scale-105 transition duration-300"
                        >
                    @else
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                    @endif
                </div>

                <div class="bg-gray-50 p-5 border-t border-gray-100 rounded-b w-full">
                    <h3 class="font-bold text-dark-ui text-sm line-clamp-1 group-hover:text-teal-ui transition">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-1 font-medium">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    <p class="text-teal-ui font-bold text-sm mt-3">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

            </a>
            @empty
            <div class="col-span-full text-center py-12 text-gray-400 font-medium">
                Belum ada produk terbaru yang tersedia.
            </div>
            @endforelse
        </div>
    </section>

    <section class="container mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-16">
        <div class="w-full md:w-1/2">
            <img src="https://images.unsplash.com/photo-1520691512911-205488137521?q=80&w=600" class="rounded-sm shadow-xl w-full h-[450px] object-cover">
        </div>
        <div class="w-full md:w-1/2 space-y-6">
            <p class="text-teal-ui font-extrabold text-xs tracking-wider">Low Price, Exclusive Product</p>
            <h2 class="text-4xl font-extrabold text-dark-ui leading-tight">Exclusive Souvenir<br>ABBA Barokah</h2>
            <p class="text-gray-500 text-sm leading-relaxed italic">
                Quando ambulabat agendis admonere te qualis actio. Si ad corpus, quae plerumque imaginare tecum in balineo quidam aquam fundes aliquod discrimen vituperiis usum alii furantur.
            </p>
            <div class="flex gap-10">
                <div class="flex items-start gap-3">
                    <div class="bg-teal-ui p-1 rounded text-white italic text-[10px] font-bold">HQ</div>
                    <div>
                        <p class="font-extrabold text-dark-ui text-sm">High Quality</p>
                        <p class="text-[10px] text-gray-400 mt-1">Quando ambulabat agendis<br>admonere te qualis actio.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="text-teal-ui text-xl">★</div>
                    <div>
                        <p class="font-extrabold text-dark-ui text-sm">Favorite Product</p>
                        <p class="text-[10px] text-gray-400 mt-1">Quando ambulabat agendis<br>admonere te qualis actio.</p>
                    </div>
                </div>
            </div>
            @guest
                <button type="button" onclick="peringatanLogin()" class="bg-teal-ui text-white px-10 py-3.5 rounded font-bold text-xs shadow-lg hover:brightness-110 transition cursor-pointer">
                Order Produk
            </button>
            @endguest

            @auth
                <a href="{{ route('products.index') }}" class="inline-block text-center bg-teal-ui text-white px-10 py-3.5 rounded font-bold text-xs shadow-lg hover:brightness-110 transition no-underline">
                Order Produk
            </a>
            @endauth
        </div>
    </section>

    <section class="break-container bg-gray-50 py-24 text-center">
        <div class="container mx-auto px-6">
            <p class="text-teal-ui font-bold text-xs mb-4">Why choose ABBA Barokah?</p>
            <h2 class="text-4xl font-extrabold text-dark-ui mb-6">Solusi terbaik untuk proyek Anda!<br>menyediakan berbagai produk berkualitas<br>dengan pelayanan terpercaya</h2>
            <p class="text-gray-500 text-xs max-w-3xl mx-auto mb-12 leading-relaxed italic">
                Sollicitant homines non sunt nisi quam formae rerum principiis opiniones.<br>Mors enim est terribilis ut Socrati aliud esse apparet. Sed timor mortis est
            </p>
        </div>
    </section>

    <footer id="main-footer" class="break-container bg-[#d1d5db] pt-20 pb-10">
        <div class="container mx-auto px-12 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="md:col-span-2">
                <div class="bg-white/60 p-4 w-20 h-16 rounded mb-8 flex items-center justify-center border border-gray-300">
                    <div class="w-8 h-6 bg-gray-300"></div>
                </div>
                <h3 class="font-extrabold text-dark-ui leading-tight mb-6 uppercase text-base">
                    Membangun kualitas dan<br>menyediakan kepercayaan
                </h3>
                <div class="text-xs text-dark-ui/80 space-y-3 leading-relaxed">
                    <p class="italic">Jl. MH Thamrin 3/10, Desa<br>Tlogobendung, Gresik, Jawa Timur</p>
                    <p class="font-bold text-sm">+62 882-1712-6768</p>
                    <p class="font-medium">ABBABAROKAH@gmail.com</p>
                </div>
            </div>
            <div class="flex flex-col space-y-4">
                <a href="#" class="font-extrabold text-dark-ui text-sm">Home</a>
                <a href="#" class="font-extrabold text-dark-ui text-sm">About us</a>
                <a href="#" class="font-extrabold text-dark-ui text-sm">Purchase</a>
                <a href="#" class="font-extrabold text-dark-ui text-sm">Contact</a>
            </div>
            <div>
                <p class="font-extrabold text-dark-ui text-sm mb-6 uppercase tracking-wider">Product</p>
                <ul class="text-xs text-gray-600 space-y-3 italic">
                    <li>Peralatan Kantor</li>
                    <li>Souvenir</li>
                </ul>
            </div>
        </div>
        <div class="container mx-auto px-12 mt-20 border-t border-gray-400/30 pt-8 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">&copy; 2026 CV ABBA BAROKAH. All Rights Reserved.</p>
        </div>
    </footer>

</div>
@endsection 