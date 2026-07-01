@php
    use App\Filament\Resources\ProductResource;
@endphp

<x-filament::card>
    <h2 class="text-lg font-bold">Produk Yang Baru Ditambahkan</h2>

    <div class="space-y-4">
        @forelse($products as $product)

            <!-- Row Wrapper -->
            <div class="flex items-center justify-between">

                <!-- LEFT SIDE -->
                <div class="flex items-center gap-4">

                    <!-- Product Image -->
                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0">
                    @if($product->image)
                        @php
                                $imageSrc = is_array($product->image) ? head($product->image) : $product->image;
                        @endphp
                            <img 
                                src="{{ asset('storage/' . $product->imageSrc) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-md w-full h-full flex items-center justify-center">
                                <span class="text-xs text-gray-500">Tidak Ada Gambar</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div>
                        <div class="font-medium text-sm">
                            {{ $product->name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $product->category->name ?? '-' }}
                        </div>
                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center gap-10">

                    <!-- Price -->
                    <div class="text-sm font-semibold w-28 text-right">
                        Rp {{ number_format($product->price) }}
                    </div>

                    <!-- Stock -->
                    <div class="text-sm font-semibold w-16 text-right">
                        {{ $product->stock }} stok
                    </div>

                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">
                Produk belum tersedia.
            </div>
        @endforelse

         <a 
            href="{{ ProductResource::getUrl() }}"
            class="text-sm font-medium text-primary-600 mt-4 block"
        >
            Lihat Produk Lainnya
        </a>
    </div>
</x-filament::card>