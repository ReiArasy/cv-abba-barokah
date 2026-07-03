<x-filament::page>
    <!-- Container Utama dengan gap responsif -->
    <div class="space-y-4 sm:space-y-6">
        
        <!-- TOP NAVIGATION & STATUS BANNER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-4 sm:p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div>
                <span class="text-[10px] sm:text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-full uppercase tracking-wider">
                    Rincian Transaksi
                </span>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-2 lg:mt-3">
                    Order #{{ $record->code }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Dibuat pada {{ $record->created_at->translatedFormat('d F Y H:i') }}
                </p>
            </div>
            
            <div class="flex items-center self-start sm:self-auto">
                <!-- Status Pembayaran Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg border text-xs sm:text-sm font-medium
                    @if($record->payment_status === 'paid')
                        bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20
                    @elif($record->payment_status === 'failed')
                        bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20
                    @else
                        bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20
                    @endif">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full shadow-sm
                        @if($record->payment_status === 'paid') bg-emerald-500
                        @elif($record->payment_status === 'failed') bg-red-500
                        @else bg-amber-500 @endif"></span>
                    <span>{{ $record->payment_status === 'paid' ? 'Lunas' : ($record->payment_status === 'failed' ? 'Gagal' : 'Belum Bayar') }}</span>
                </div>
            </div>
        </div>

        <!-- MAIN LAYOUT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- LEFT COLUMN: ITEMS & PAYMENT INFO (lg:col-span-2) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                
                <!-- ORDER ITEMS CARD -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-50 dark:border-gray-800 flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Item Pesanan
                        </h3>
                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium bg-gray-50 dark:bg-gray-800 px-2 py-1 rounded-md">
                            {{ count($record->items) }} Produk
                        </span>
                    </div>
                    
                    <div class="divide-y divide-gray-50 dark:divide-gray-800/60">
                        @foreach($record->items as $item)
                            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                                <!-- Product Details Section -->
                                <div class="flex items-start gap-3 sm:gap-4 flex-1 min-w-0">
                                    <!-- Image -->
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 overflow-hidden shrink-0 flex items-center justify-center shadow-sm">
                                        @if($item->product && $item->product->image)
                                            @php
                                                $imageSrc = is_array($item->product->image) ? head($item->product->image) : $item->product->image;
                                            @endphp
                                            <img src="{{ asset('storage/' . $imageSrc) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375 0 11-.75 0 .375 0 01.75 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Title & Unit Price -->
                                    <div class="flex-1 min-w-0 pt-1 sm:pt-0">
                                        <h4 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white hover:text-emerald-600 transition-colors line-clamp-2 leading-tight">
                                            {{ $item->product ? $item->product->name : 'Produk Tidak Ditemukan' }}
                                        </h4>
                                        <div class="mt-1.5 sm:mt-2 flex flex-wrap items-center gap-1.5 sm:gap-2">
                                            <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-1.5 py-0.5 rounded font-medium text-[11px] sm:text-xs">
                                                {{ $item->quantity }}x
                                            </span>
                                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400"></span>
                                            <span class="font-medium text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subtotal Section -->
                                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center shrink-0 pl-19 sm:pl-0">
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 sm:mb-1 block sm:hidden">Subtotal</span>
                                    <div class="font-bold text-sm sm:text-base text-emerald-600 dark:text-emerald-400 text-right">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- TOTAL SUMMARY AREA -->
                    <div class="bg-emerald-50/50 dark:bg-emerald-500/5 p-4 sm:p-6 border-t border-emerald-100 dark:border-emerald-500/10">
                        <div class="flex justify-between items-center text-gray-900 dark:text-white">
                            <span class="text-sm sm:text-base font-bold">Total Pembayaran</span>
                            <span class="text-lg sm:text-xl font-extrabold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($record->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PAYMENT INFORMATION CARD -->
                @if($record->payment)
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-gray-50 dark:border-gray-800">
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm13.5 3.75a1.125 1.125 0 11-2.25 0 1.125 1.125 0 012.25 0zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm3.375-3h.008v.008h-.008V12zm0 3h.008v.008h-.008V15zm3.375-3h.008v.008h-.008V12zm0 3h.008v.008h-.008V15zm3.375-3h.008v.008h-.008V12zm0 3h.008v.008h-.008V15z" />
                                </svg>
                                Informasi Pembayaran
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6 grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                            <div class="col-span-2 md:col-span-1">
                                <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Metode</p>
                                <p class="font-semibold text-sm text-gray-800 dark:text-gray-200 mt-1 uppercase">{{ $record->payment->payment_method ?? '-' }}</p>
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">ID Referensi</p>
                                <p class="font-semibold text-sm text-gray-800 dark:text-gray-200 mt-1 break-all">{{ $record->payment->payment_reference ?? '-' }}</p>
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waktu Bayar</p>
                                <p class="font-semibold text-sm text-gray-800 dark:text-gray-200 mt-1">
                                    {{ $record->payment->paid_at ? \Carbon\Carbon::parse($record->payment->paid_at)->translatedFormat('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50/80 dark:bg-amber-500/10 rounded-xl border border-amber-200/60 dark:border-amber-500/20 p-4 sm:p-6 flex items-start gap-3 sm:gap-4">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div>
                            <h4 class="font-bold text-sm sm:text-base text-amber-800 dark:text-amber-400">Menunggu Pembayaran</h4>
                            <p class="text-xs sm:text-sm text-amber-700/80 dark:text-amber-500/70 mt-1">
                                Transaksi ini belum memiliki data pembayaran yang tercatat dalam sistem.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: CUSTOMER DETAILS & SHIPPING (lg:col-span-1) -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                
                <!-- CUSTOMER CONTACT CARD -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-50 dark:border-gray-800 flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Informasi Pelanggan
                        </h3>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama Pelanggan</span>
                            <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $record->user->name ?? '-' }}</span>
                        </div>
                        <div class="border-t border-gray-50 dark:border-gray-800/60 pt-4 flex flex-col gap-1">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Username</span>
                            <span class="font-medium text-sm text-gray-800 dark:text-gray-300">{{ $record->user->username ?? '-' }}</span>
                        </div>
                        <div class="border-t border-gray-50 dark:border-gray-800/60 pt-4 flex flex-col gap-1">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alamat Email</span>
                            <a href="mailto:{{ $record->user->email }}" class="font-semibold text-sm text-emerald-600 dark:text-emerald-400 hover:underline truncate">
                                {{ $record->user->email ?? '-' }}
                            </a>
                        </div>
                        <div class="border-t border-gray-50 dark:border-gray-800/60 pt-4 flex flex-col gap-1">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nomor Telepon</span>
                            <a href="tel:{{ $record->user->phone }}" class="font-semibold text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $record->user->phone ?? '-' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SHIPPING ADDRESS CARD -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-50 dark:border-gray-800">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Alamat Pengiriman
                        </h3>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alamat Lengkap</span>
                            <p class="font-medium text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                                {{ $record->user->alamat_lengkap ?? '-' }}
                            </p>
                        </div>
                        <div class="border-t border-gray-50 dark:border-gray-800/60 pt-4 grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kota</span>
                                <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $record->user->kota ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Provinsi</span>
                                <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $record->user->provinsi ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-filament::page>