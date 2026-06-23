@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    .ui-font {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .bg-teal-ui { background-color: #14b8a6; }
    .text-teal-ui { color: #14b8a6; }
    .text-dark-ui { color: #1e293b; }
</style>

<div class="ui-font bg-white min-h-screen w-full flex flex-col justify-between">
    
    <div class="w-full flex-grow">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            
            <div class="text-center mb-8 md:mb-12">
                <p class="uppercase tracking-[0.2em] text-[11px] font-bold text-teal-ui mb-2">CV ABBA BAROKAH</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-dark-ui tracking-tight">Product Overview</h1>
            </div>

            <form action="{{ route('products.index') }}" method="GET" class="max-w-3xl mx-auto flex flex-col md:flex-row gap-4 justify-center items-end mb-12 md:mb-16 bg-gray-50/50 p-4 md:p-0 rounded-lg md:bg-transparent">
                <div class="w-full md:w-1/3 flex flex-col">
                    <label class="text-xs font-bold text-dark-ui mb-1.5">Kategori</label>
                    <select name="category" class="w-full bg-white border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-teal-ui shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/2 flex flex-col">
                    <label class="text-xs font-bold text-dark-ui mb-1.5 md:hidden">Cari Produk</label>
                    <input type="text" name="search" placeholder="Cari produk anda!" value="{{ request('search') }}"
                           class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:border-teal-ui shadow-sm">
                </div>

                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-[#6366f1] text-white px-6 py-2.5 rounded font-bold text-sm shadow-md hover:bg-indigo-600 transition duration-200">
                        Cari produk
                    </button>
                </div>
            </form>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-dark-ui">Products</h2>
                    <div class="h-1 w-12 bg-teal-ui mt-1.5"></div>
                </div>
                <a href="{{ route('products.index') }}" class="px-5 py-2 border border-teal-ui text-teal-ui rounded font-bold text-xs hover:bg-teal-ui hover:text-white transition duration-200 shadow-sm">
                    View All
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 mb-16">
                @forelse($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="border border-gray-200 rounded p-4 md:p-5 hover:border-teal-ui/50 shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between relative bg-white group block text-left decoration-none">
                    
                    <div class="absolute top-3 right-3 w-7 h-7 bg-gray-100/90 group-hover:bg-teal-ui group-hover:text-white transition duration-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-sm shadow-sm z-10">
                        +
                    </div>

                   <div class="aspect-square bg-gray-50 border border-gray-100 mb-4 flex items-center justify-center rounded overflow-hidden">

                        @if(!empty($product->image) && is_array($product->image) && isset($product->image[0]))

                            <img 
                                src="{{ asset('storage/' . $product->image[0]) }}" 
                                alt="{{ $product->name }}" 
                                class="object-cover w-full h-full group-hover:scale-105 transition duration-500"
                            >

                        @else

                            <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>

                        @endif

                    </div>

                    <div class="bg-gray-50 p-4 border-t border-gray-100 rounded-b w-full">
                        <h3 class="font-bold text-dark-ui text-sm line-clamp-1 group-hover:text-teal-ui transition duration-200">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </p>
                        <p class="text-teal-ui font-bold text-base mt-3">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-16 text-gray-400 font-medium">
                    Belum ada produk terbaru yang tersedia.
                </div>
                @endforelse
            </div>

            <div class="relative flex py-5 items-center mb-6">
                <div class="flex-grow border-t border-gray-200"></div>
                <button class="flex-shrink mx-4 bg-white border border-teal-ui text-teal-ui px-6 py-2.5 rounded-full font-bold text-xs hover:bg-teal-ui hover:text-white transition duration-200 shadow-sm">
                    Load more products
                </button>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>
        </main>
    </div>

    <footer class="w-full bg-[#d1d5db] pt-16 pb-8 text-slate-800 border-t border-gray-300/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12">
            <div class="sm:col-span-2 lg:col-span-2">
                <div class="bg-white/60 p-3 w-16 h-14 rounded mb-6 flex items-center justify-center border border-gray-300/50">
                    <div class="w-6 h-5 bg-gray-400 rounded-sm"></div>
                </div>
                <h3 class="font-extrabold text-dark-ui leading-tight mb-4 uppercase text-sm md:text-base tracking-tight">
                    Membangun kualitas dan<br class="hidden sm:block">menyediakan kepercayaan
                </h3>
                <div class="text-xs text-dark-ui/80 space-y-2.5 leading-relaxed">
                    <p class="italic">Jl. MH Thamrin 3/10, Desa<br>Tlogobendung, Gresik, Jawa Timur</p>
                    <p class="font-bold text-sm text-dark-ui">+62 882-1712-6768</p>
                    <p class="font-medium">ABBABAROKAH@gmail.com</p>
                </div>
            </div>

            <div class="flex flex-col space-y-3 pt-2">
                <p class="font-extrabold text-dark-ui text-xs uppercase tracking-wider mb-2">Navigation</p>
                <a href="{{ route('home') }}" class="text-xs font-bold hover:text-teal-ui transition duration-200">Home</a>
                <a href="#" class="text-xs font-bold hover:text-teal-ui transition duration-200">About us</a>
                <a href="#" class="text-xs font-bold hover:text-teal-ui transition duration-200">Purchase</a>
                <a href="#" class="text-xs font-bold hover:text-teal-ui transition duration-200">Contact</a>
            </div>

            <div class="pt-2">
                <p class="font-extrabold text-dark-ui text-xs uppercase tracking-wider mb-4">Product</p>
                <ul class="text-xs text-gray-700 space-y-3 font-medium italic">
                    <li class="hover:text-dark-ui transition">Peralatan Kantor</li>
                    <li class="hover:text-dark-ui transition">Souvenir</li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 border-t border-gray-400/30 pt-6 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">&copy; 2026 CV ABBA BAROKAH. All Rights Reserved.</p>
        </div>
    </footer>

</div>
@endsection