@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Konfirmasi Pesanan</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
        
        <div class="space-y-4 mb-6">
            @foreach($cart->items as $item)
            <div class="flex justify-between border-b pb-2">
                <div>
                    <p class="font-medium">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <p class="font-medium">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="flex justify-between text-lg font-bold border-t pt-4">
            <p>Total Pembayaran</p>
            <p>Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="mt-6">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-700 transition">
                Buat Pesanan & Pilih Metode Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection