@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Pesanan: {{ $order->code }}</h1>
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Riwayat</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between border-b pb-4 mb-4">
            <div>
                <p class="text-sm text-gray-500">Status Pesanan</p>
                <p class="font-semibold capitalize">{{ $order->status }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Status Pembayaran</p>
                @if($order->payment_status == 'paid')
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">Berhasil</span>
                @elseif($order->payment_status == 'failed')
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">Gagal/Expired</span>
                @else
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">Menunggu Pembayaran</span>
                @endif
            </div>
        </div>

        <h2 class="text-lg font-bold mb-4">Daftar Produk</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex justify-between">
                <div>
                    <p class="font-medium">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="flex justify-between text-xl font-bold border-t mt-4 pt-4">
            <p>Total</p>
            <p>Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        @if($order->payment_status == 'unpaid' && isset($snapToken))
            <div class="mt-8 text-right">
                <button id="pay-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow transition">
                    Selesaikan Pembayaran Sekarang
                </button>
            </div>
        @endif
    </div>
</div>

@if($order->payment_status == 'unpaid' && isset($snapToken))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            // Buka Popup Snap Midtrans
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!"); 
                    window.location.reload(); // Reload halaman untuk update status
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