@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    .ui-font {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .break-container {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }

    .bg-teal-ui { background-color: #14b8a6; }
    .text-teal-ui { color: #14b8a6; }
    .text-dark-ui { color: #1e293b; }
</style>

<div class="ui-font bg-white min-h-screen">

    <main class="container mx-auto px-6 md:px-12 py-8">
        
        <div class="flex items-center justify-between mb-10">
            <a href="{{ route('products.index') }}" class="text-slate-700 hover:text-teal-ui transition flex items-center gap-2 font-bold text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
            </a>
            <h1 class="text-xl font-extrabold text-[#344054] tracking-tight">Details</h1>
            <div class="w-5"></div> </div>

        <div class="flex justify-between items-start mb-6">
            <h2 class="text-2xl md:text-3xl font-extrabold text-dark-ui tracking-tight">
                {{ $product->name ?? 'Kursi Kantor Premium' }}
            </h2>
            <span class="bg-[#f2f4f7] text-[#344054] text-xs font-semibold px-3 py-1.5 rounded border border-gray-100">
                {{ $product->category->name ?? 'Peralatan Kantor' }}
            </span>
        </div>

        <div class="relative w-full bg-[#eaecf0] rounded-sm aspect-[16/8] mb-10 flex items-center justify-between px-4 group">
            <button class="w-10 h-10 flex items-center justify-center text-slate-700 hover:text-dark-ui transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                </svg>
            </button>

            <div class="w-full h-full max-h-[400px] flex items-center justify-center p-8">

                @if(!empty($product->image) && is_array($product->image) && isset($product->image[0]))

                    <img 
                        src="{{ asset('storage/' . $product->image[0]) }}" 
                        alt="{{ $product->name }}" 
                        class="object-contain max-w-full max-h-full rounded shadow-sm"
                    >

                @else

                    <svg class="w-32 h-32 text-[#98a2b3]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>

                @endif

            </div>

            <button class="w-10 h-10 flex items-center justify-center text-slate-700 hover:text-dark-ui transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-8 items-start mb-20">
            
            <div class="w-full md:w-7/12 bg-[#eaecf0]/50 border border-gray-100 rounded-sm p-8 space-y-4">
                <h3 class="text-teal-ui font-extrabold text-base tracking-wide">About</h3>
                <div class="text-sm text-[#475467] leading-relaxed space-y-4 font-medium">
                    @if($product->description)
                        <p>{!! nl2br(e($product->description)) !!}</p>
                    @else
                        <p>Oportet uti solum de actibus prosequtionem et fugam, haec leniter et blandus et reservato.</p>
                        <p>Quae tibi placent quicunq prosunt aut diligebat multum, quod memor sis ad communia sunt ab initio minima. Quod si, exempli gratia, cupidum rerum in propria sunt ceramic calicem, admonere te solum Ceramic, quod sit.</p>
                    @endif
                </div>
            </div>

            <div class="w-full md:w-5/12 bg-white border border-gray-200 rounded-sm p-6 shadow-sm">
                <p class="text-xs font-semibold text-[#475467] mb-1">Total Price</p>
                <p class="text-3xl font-extrabold text-teal-ui mb-6">
                    Rp {{ number_format($product->price ?? 999999, 0, ',', '.') }}
                </p>

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="w-full bg-[#6366f1] text-white py-3 rounded-md font-bold text-sm shadow hover:bg-indigo-600 transition tracking-wide text-center">
                        Order Produk
                    </button>
                </form>
            </div>

        </div>
    </main>

    <footer class="break-container bg-[#d1d5db] pt-20 pb-10">
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
                <a href="{{ route('home') }}" class="font-extrabold text-dark-ui text-sm">Home</a>
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