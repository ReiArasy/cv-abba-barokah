@extends('layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-100 mt-10">
    
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pembayaran</h2>
        <p class="text-gray-500">Kode Transaksi: <span class="font-semibold">{{ $order->code }}</span></p>
    </div>
    
    <div class="mb-8 p-6 bg-gray-50 rounded border border-gray-200 text-center">
        <p class="text-lg text-gray-600 mb-2">Total Tagihan:</p>
        <p class="text-4xl font-extrabold text-teal-500 mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        
        <div class="inline-block px-3 py-1 mt-2 rounded-full text-sm font-semibold {{ $order->payment_status == 'unpaid' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
            Status: {{ strtoupper($order->payment_status) }}
        </div>
    </div>

    @if($order->payment_status == 'unpaid')
        <button id="pay-button" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded hover:bg-indigo-700 transition duration-300">
            Pilih Metode Pembayaran
        </button>
    @else
        <a href="{{ route('orders.history') }}" class="block text-center w-full bg-gray-800 text-white font-bold py-3 px-4 rounded hover:bg-gray-900 transition duration-300">
            Kembali ke Riwayat
        </a>
    @endif

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // Panggil pop-up Snap Midtrans menggunakan token dari controller
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // Arahkan ke halaman history jika berhasil
                window.location.href = "{{ route('orders.history') }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('orders.history') }}";
            },
            onError: function(result){
                alert("Pembayaran gagal, silakan coba lagi.");
            },
            onClose: function(){
                // Aksi jika user menutup popup tanpa membayar
                alert('Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.');
            }
        });
    };
</script>
@endsection