@extends('layouts.app') 

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    
    <div class="flex items-center justify-center relative mb-10">
        <a href="{{ route('products.index') }}" class="absolute left-0 text-slate-600 hover:text-slate-900 transition flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat Pembelian</h1>
    </div>

    <div class="flex space-x-6 border-b border-gray-200 mb-6">
        <button onclick="filterOrders('all')" id="tab-all" class="text-teal-500 font-semibold border-b-2 border-teal-500 pb-2 px-1 transition text-sm">Semua</button>
        <button onclick="filterOrders('success')" id="tab-success" class="text-gray-500 hover:text-teal-500 font-medium pb-2 px-1 transition text-sm">Sukses</button>
        <button onclick="filterOrders('pending')" id="tab-pending" class="text-gray-500 hover:text-teal-500 font-medium pb-2 px-1 transition text-sm">Menunggu</button>
    </div>

    <div id="order-container">
        @forelse($orders as $order)
            @php
                // Menentukan Status, Warna Badge, dan Kategori Filter
                if($order->payment_status === 'paid' || $order->status === 'processing' || $order->status === 'shipped') {
                    $statusText = 'Success';
                    $badgeClass = 'bg-[#d1f2e9] text-[#1aa385]'; // Warna kustom dari desain
                    $filterCategory = 'success';
                } elseif($order->payment_status === 'unpaid' || $order->status === 'pending') {
                    $statusText = 'Pending';
                    $badgeClass = 'bg-[#fcedd9] text-[#f5a623]'; // Warna kustom dari desain
                    $filterCategory = 'pending';
                } else {
                    $statusText = 'Failed'; 
                    $badgeClass = 'bg-[#f8d7da] text-[#dc3545]'; // Warna kustom merah
                    $filterCategory = 'failed';
                }
            @endphp

            <a href="{{ route('purchase.show', $order->code) }}" 
               class="order-card block border border-gray-300 rounded p-6 mb-4 hover:shadow-md transition bg-white"
               data-status="{{ $filterCategory }}">
               
                <div class="flex flex-col md:flex-row md:items-start gap-6 relative">

                    <div class="flex-1 flex flex-col justify-between h-32 w-full">
                        
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">#{{ $order->code }}</h2>
                            <p class="text-sm font-medium text-slate-500 mt-1">
                                {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <div class="flex justify-end items-center mt-auto">
                            <span class="text-slate-700 font-medium mr-3">Total Pesanan:</span>
                            <span class="text-teal-500 font-bold text-lg">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="absolute top-0 right-0">
                         <span class="inline-block px-5 py-2 font-bold text-sm {{ $badgeClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                </div>
            </a>
        @empty
            <div class="text-center py-12 border border-dashed border-gray-300 rounded">
                <p class="text-gray-500 text-lg">Belum ada riwayat pembelian saat ini.</p>
                <a href="{{ route('products.index') }}" class="text-teal-500 font-semibold hover:underline mt-2 inline-block">Mulai Belanja</a>
            </div>
        @endforelse
    </div>
</div>

<script>
    function filterOrders(status) {
        const tabs = ['all', 'success', 'pending'];
        tabs.forEach(t => {
            const tabEl = document.getElementById('tab-' + t);
            if (tabEl) {
                if (t === status) {
                    tabEl.className = "text-teal-500 font-semibold border-b-2 border-teal-500 pb-2 px-1 transition text-sm";
                } else {
                    tabEl.className = "text-gray-500 hover:text-teal-500 font-medium pb-2 px-1 transition text-sm";
                }
            }
        });

        const items = document.querySelectorAll('.order-card');
        items.forEach(item => {
            if (status === 'all') {
                item.style.display = 'block';
            } else {
                if (item.getAttribute('data-status') === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }
</script>
@endsection