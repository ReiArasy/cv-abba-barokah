@extends('layouts.app') 

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 font-sans">
    
    <div class="flex items-center justify-center relative mb-12">
        <a href="{{ route('purchase.history') }}" class="absolute left-0 text-slate-600 hover:text-slate-900 transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Detail Riwayat Pembelian</h1>
    </div>

    <div class="mb-8">
        <div class="flex mb-2 items-center">
            <span class="w-40 text-slate-500 font-medium text-sm">Code transaksi:</span>
            <span class="font-bold text-slate-800 text-lg">#{{ $order->code }}</span>
        </div>
        <div class="flex items-center">
            <span class="w-40 text-slate-500 font-medium text-sm">Transaction Created:</span>
            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <div>
            <h2 class="text-xl font-bold text-slate-800 mb-4">Detial Pembelian</h2>
            <div class="border border-gray-300 rounded p-6 bg-white">
                
                <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-200">
                    <span class="text-slate-600 font-medium text-sm">Status Transaksi</span>
                    @if($order->payment_status === 'paid' || $order->status === 'processing' || $order->status === 'shipped')
                        <span class="px-6 py-1.5 rounded-full text-sm font-bold bg-[#d1f2e9] text-[#1aa385]">Success</span>
                    @elseif($order->payment_status === 'unpaid' || $order->status === 'pending')
                        <span class="px-6 py-1.5 rounded-full text-sm font-bold bg-[#fcedd9] text-[#f5a623]">Pending</span>
                    @else
                        <span class="px-6 py-1.5 rounded-full text-sm font-bold bg-[#f8d7da] text-[#dc3545]">Failed</span>
                    @endif
                </div>

                <div class="space-y-6">
                    @foreach($order->items as $item)
                    <div class="flex gap-6">
                        
                        <div class="flex-1 text-sm grid grid-cols-[100px_1fr] gap-y-2">
                            <span class="text-slate-500">Nama Produk</span>
                            <span class="font-bold text-slate-800 text-right">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                            
                            <span class="text-slate-500">Kuantitas</span>
                            <span class="font-bold text-slate-800 text-right">{{ $item->quantity }}</span>
                            
                            <span class="text-slate-500 mt-2">Total Harga</span>
                            <span class="font-bold text-[#1aa385] text-right mt-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if(!$loop->last) <hr class="border-gray-200"> @endif
                    @endforeach
                </div>

                <div class="mt-8 pt-6 flex justify-between items-end">
                    <span class="text-slate-800 font-bold">Total ({{ $order->items->sum('quantity') }} Produk)</span>
                    <span class="text-2xl font-bold text-[#1aa385]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-800 mb-4">Informasi Pembeli</h2>
            
            <div class="border border-gray-300 rounded p-6 bg-white mb-6">
                <div class="mb-6">
                    <h3 class="text-slate-800 font-bold mb-1 text-lg">Nama</h3>
                    <p class="text-[#1aa385]">{{ auth()->user()->name }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-slate-800 font-bold mb-1 text-lg">Alamat</h3>
                    <p class="text-[#1aa385] max-w-[250px] leading-relaxed">{{ auth()->user()->alamat_lengkap ?? 'Jalan Batang no 100, Batang, Jawa Tengah' }}</p>
                </div>

                <div>
                    <h3 class="text-slate-800 font-bold mb-1 text-lg">Phone</h3>
                    <p class="text-[#1aa385]">{{ auth()->user()->phone ?? '+62 888 123 456' }}</p>
                </div>
            </div>

            <div class="w-64">
                @if($order->payment_status == 'unpaid' && isset($snapToken))
                    <button id="pay-button" class="w-full bg-[#3490dc] hover:bg-blue-600 text-white font-medium py-2 rounded mb-3 transition">
                        Bayar
                    </button>
                @endif
                
                <a href="https://wa.me/628123456789" target="_blank" class="w-full block text-center border border-[#3490dc] text-[#3490dc] hover:bg-blue-50 font-medium py-2 rounded transition bg-white">
                    CV ABBA Barokah What's-App
                </a>
            </div>
        </div>

    </div>
</div>
@if($order->payment_status === 'unpaid' && $snapToken)
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!"); 
                    window.location.reload(); 
                },
                onPending: function(result){
                    alert("Menunggu status pembayaran!");
                    window.location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                    window.location.reload();
                },
                onClose: function(){
                    alert("Anda menutup halaman pembayaran sebelum menyelesaikannya.");
                }
            });
        };
    </script>

@endif
@endsection