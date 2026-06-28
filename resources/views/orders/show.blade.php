@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Detail Pesanan: {{ $order->code }}
        </h1>

        <a href="{{ route('orders.index') }}"
           class="text-blue-600 hover:underline">
            &larr; Kembali ke Riwayat
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">

        <div class="flex justify-between border-b pb-4 mb-4">

            <div>
                <p class="text-sm text-gray-500">
                    Status Pesanan
                </p>

                <p class="font-semibold capitalize">
                    {{ $order->status }}
                </p>
            </div>

            <div class="text-right">

                <p class="text-sm text-gray-500">
                    Status Pembayaran
                </p>

                @if($order->payment_status == 'paid')

                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                        Berhasil
                    </span>

                @elseif($order->payment_status == 'failed')

                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                        Gagal / Expired
                    </span>

                @else

                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                        Menunggu Pembayaran
                    </span>

                @endif

            </div>

        </div>

        <h2 class="text-lg font-bold mb-4">
            Daftar Produk
        </h2>

        <div class="space-y-4">

            @foreach($order->items as $item)

            <div class="flex justify-between">

                <div>

                    <p class="font-medium">
                        {{ $item->product->name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $item->quantity }}
                        x
                        Rp {{ number_format($item->price,0,',','.') }}
                    </p>

                </div>

                <p class="font-medium">
                    Rp {{ number_format($item->subtotal,0,',','.') }}
                </p>

            </div>

            @endforeach

        </div>

        <div class="flex justify-between text-xl font-bold border-t mt-6 pt-4">

            <span>Total</span>

            <span>
                Rp {{ number_format($order->total_price,0,',','.') }}
            </span>

        </div>

        @if($order->payment_status == 'unpaid' && isset($snapToken))

        <div class="mt-8 text-right">

            <button
                id="pay-button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg shadow">

                Selesaikan Pembayaran

            </button>

        </div>

        @endif

    </div>

</div>

@if($order->payment_status == 'unpaid' && isset($snapToken))

<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>

document.getElementById('pay-button').addEventListener('click', function () {

    window.snap.pay('{{ $snapToken }}', {

        onSuccess: function(result){

            fetch("{{ route('orders.check-payment',$order) }}",{

                method:'POST',

                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Accept':'application/json',
                    'Content-Type':'application/json'
                }

            })
            .then(response => response.json())
            .then(data => {

                if(data.success){

                    alert("Pembayaran berhasil");

                    window.location.reload();

                }else{

                    alert(data.message);

                }

            })
            .catch(error=>{

                console.log(error);

                alert("Gagal mengecek status pembayaran");

            });

        },

        onPending:function(result){

            alert("Pembayaran masih Pending.");

        },

        onError:function(result){

            alert("Pembayaran gagal.");

        },

        onClose:function(){

            alert("Anda menutup popup pembayaran.");

        }

    });

});

</script>

@endif

@endsection