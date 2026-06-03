@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Riwayat Pesanan</h1>

    @if($orders->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-gray-500">Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ route('products.index') }}" class="text-blue-600 mt-2 inline-block">Mulai Belanja</a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kode Pesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Total Harga</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status Pembayaran</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium">{{ $order->code }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($order->payment_status == 'paid')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Berhasil</span>
                            @elseif($order->payment_status == 'failed')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Gagal</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('orders.show', $order->code) }}" class="bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 transition">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection