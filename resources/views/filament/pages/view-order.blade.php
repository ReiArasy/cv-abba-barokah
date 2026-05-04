<x-filament::page>
    <div class="space-y-6">

        <!-- HEADER -->
        <div>
            <h2 class="text-xl font-bold">
                Order #{{ $record->code }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ $record->created_at->format('d F Y') }}
            </p>
        </div>

        <!-- STATUS -->
        <x-filament::card>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order Status</p>
                    <p class="font-semibold">{{ ucfirst($record->status) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <p class="font-semibold">{{ ucfirst($record->payment_status) }}</p>
                </div>
            </div>
        </x-filament::card>

        <!-- CUSTOMER -->
        <x-filament::card>
            <h3 class="font-bold mb-3">Customer Info</h3>

            <p>Name: {{ $record->user->name }}</p>
            <p>Email: {{ $record->user->email }}</p>
            <p>Phone: {{ $record->user->phone }}</p>
        </x-filament::card>

        <!-- ORDER ITEMS -->
        <x-filament::card>
            <h3 class="font-bold mb-4">Order Items</h3>

            <div class="space-y-3">
                @foreach($record->items as $item)
                    <div class="flex justify-between border-b pb-2">

                        <div>
                            <p class="font-medium">
                                {{ $item->product->name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $item->quantity }} x Rp {{ number_format($item->price) }}
                            </p>
                        </div>

                        <div class="font-semibold">
                            Rp {{ number_format($item->subtotal) }}
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- TOTAL -->
            <div class="flex justify-between mt-4 font-bold text-lg">
                <span>Total</span>
                <span>Rp {{ number_format($record->total_price) }}</span>
            </div>
        </x-filament::card>

        <!-- PAYMENT -->
        @if($record->payment)
        <x-filament::card>
            <h3 class="font-bold mb-3">Payment Info</h3>

            <p>Method: {{ $record->payment->payment_method }}</p>
            <p>Reference: {{ $record->payment->payment_reference }}</p>
            <p>Paid At: {{ $record->payment->paid_at }}</p>
        </x-filament::card>
        @endif

    </div>
</x-filament::page>