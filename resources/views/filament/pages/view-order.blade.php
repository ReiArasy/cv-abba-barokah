<x-filament::page>
    <div class="space-y-6">

        <div>
            <h2 class="text-xl font-bold">
                Transaction #{{ $record->code }}
            </h2>
            <p>{{ $record->created_at->format('d F Y') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- grid kiri -->
            <x-filament::card>
                <h3 class="font-bold mb-4">Order Details</h3>

                <p>Status: {{ $record->status }}</p>
                <p>Product: {{ $record->product->name }}</p>
                <p>Quantity: {{ $record->quantity }}</p>
                <p>Total: Rp {{ number_format($record->total_price) }}</p>
            </x-filament::card>

            <!-- grid kanan -->
            <x-filament::card>
                <h3 class="font-bold mb-4">Customer Information</h3>

                <p>Name: {{ $record->user->name }}</p>
                <p>Address: {{ $record->user->address }}</p>
                <p>Phone: {{ $record->user->phone }}</p>
            </x-filament::card>
        </div>

        <div class="flex gap-4">
            <!-- approve jika status pending -->
            @if($record->status === 'pending')
            <x-filament::button
                color="success"
                wire:click="approve">
                Approve
            </x-filament::button>
            @endif

            <!-- ship jika status processing -->
            @if($record->status === 'processing')
            <x-filament::button
                color="info"
                wire:click="ship">
                Tandai Dikirim
            </x-filament::button>
            @endif

            <!-- Reject hanya kalau belum final -->
            @if(in_array($record->status, ['pending', 'processing']))
            <x-filament::button
                color="danger"
                wire:click="reject">
                Reject
            </x-filament::button>
            @endif
        </div>

    </div>
</x-filament::page>