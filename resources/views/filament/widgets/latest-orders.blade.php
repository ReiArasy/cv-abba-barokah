@php
    use App\Filament\Resources\OrderResource;
@endphp

<x-filament::card>
    <h2 class="text-lg font-bold">Riwayat Transaksi</h2>

    <div class="space-y-3">
        @forelse($orders as $order)
            <div>
                <div class="font-medium">
                    {{ $order->code }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $order->created_at->format('d F Y') }}
                </div>
            </div>
            @empty
            <div class="text-sm text-gray-500">
                Order belum tersedia.
            </div>
        @endforelse

    
        <a 
            href="{{ OrderResource::getUrl() }}"
            class="text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline transition duration-150 ease-in-out mt-4 block"
        >
            Lihat Transaksi Lainnya
        </a>
    </div>
</x-filament::card>
