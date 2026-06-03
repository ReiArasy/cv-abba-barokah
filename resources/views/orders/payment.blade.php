@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-indigo-600 text-white px-6 py-4">
            <h1 class="text-2xl font-bold">Selesaikan Pembayaran</h1>
            <p class="text-sm opacity-80">Order ID: {{ $order->code }}</p>
        </div>
        
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    @if($order->payment_status == 'unpaid')
                        <span class="px-3 py-1 mt-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Pembayaran
                        </span>
                    @elseif($order->payment_status == 'paid')
                        <span class="px-3 py-1 mt-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Pembayaran Berhasil
                        </span>
                    @else
                        <span class="px-3 py-1 mt-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Dibatalkan / Gagal
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">Total Tagihan</p>
                    <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 mt-2">
                @if($order->payment_status == 'unpaid' && isset($snapToken))
                    <button id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-4 rounded-lg shadow-md transition duration-300 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Pilih Metode Pembayaran
                    </button>
                @endif
                
                <div class="mt-4 text-center">
                    <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-indigo-600 text-sm">
                        @if($order->payment_status == 'unpaid')
                            Bayar Nanti (Kembali ke Riwayat)
                        @else
                            Kembali ke Riwayat
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Midtrans Sandbox --}}
@if($order->payment_status == 'unpaid' && isset($snapToken))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Redirect ke history dengan pesan sukses
                    window.location.href = "{{ route('orders.index') }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda.");
                    window.location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                    window.location.reload();
                },
                onClose: function(){
                    alert('Anda menutup popup sebelum menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endif
@endsection