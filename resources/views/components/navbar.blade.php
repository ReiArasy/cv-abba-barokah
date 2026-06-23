<nav class="bg-white text-slate-800 shadow-sm border-b border-gray-100">
    <div class="container mx-auto px-6 md:px-12 py-5 flex justify-between items-center">
        
        <a href="{{ route('home') }}" class="bg-gray-50 p-2 rounded hover:bg-gray-100 transition flex items-center justify-center border border-gray-200">
            <div class="w-10 h-6 bg-gray-200 flex items-center justify-center rounded-sm">
                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                </svg>
            </div>
            <span class="ml-3 font-extrabold text-xs tracking-wider uppercase hidden sm:inline text-slate-800">CV ABBA BAROKAH</span>
        </a>

        <div class="flex space-x-8 text-sm font-bold items-center">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-teal-500 border-b-2 border-teal-500 pb-1' : 'text-slate-600 hover:text-teal-500 transition' }}">
                Home
            </a>
            
            <a href="{{ route('about.index') }}" class="{{ request()->routeIs('about.index') ? 'text-teal-500 border-b-2 border-teal-500 pb-1' : 'text-slate-600 hover:text-teal-500 transition' }}">
            About us
            </a>
            
            @guest
                <a href="javascript:void(0)" onclick="peringatanLogin()" class="text-slate-600 hover:text-teal-500 transition">Product</a>
                <a href="javascript:void(0)" onclick="peringatanLogin()" class="text-slate-600 hover:text-teal-500 transition">Purchase</a>
            @endguest

            @auth
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'text-teal-500 border-b-2 border-teal-500 pb-1' : 'text-slate-600 hover:text-teal-500 transition' }}">
                    Product
                </a>
                <a href="{{ route('purchase.history') }}" class="text-slate-600 hover:text-teal-500 transition">Purchase</a>
            @endauth
            
            <a href="{{ route('home') }}#main-footer" class="text-slate-600 hover:text-teal-500 transition">Contact</a>

            <div class="h-5 w-[1px] bg-gray-200 hidden sm:block"></div>
            
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-teal-500 transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-[#14b8a6] text-white px-4 py-2 rounded-sm text-xs font-bold hover:bg-[#0d9488] transition shadow-sm">
                        Sign Up
                    </a>
                @endguest

                @auth
                    <div class="flex items-center space-x-3">
                        
                        @php
                            $cart = \App\Models\Cart::with('items')->where('user_id', Auth::id())->first();
                            $cartItemCount = $cart ? $cart->items->sum('quantity') : 0;
                        @endphp

                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center p-2 text-slate-600 hover:text-teal-500 transition-colors mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            
                            @if($cartItemCount > 0)
                                <div class="absolute inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-[#14b8a6] border-2 border-white rounded-full -top-1 -right-1">
                                    {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                                </div>
                            @endif
                        </a>
                        <span class="text-xs font-bold text-slate-700 bg-slate-50 px-2.5 py-1.5 rounded border border-gray-200">
                            👋 {{ Auth::user()->name }}
                        </span>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                            @csrf
                            <button type="button" onclick="konfirmasiLogout()" class="text-xs font-bold text-red-500 hover:text-red-700 transition cursor-pointer bg-transparent border-none p-0">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>

<script>
    // 1. Fungsi Pop-up Peringatan Login sebelum masuk ke fitur
    function peringatanLogin() {
        Swal.fire({
            title: 'Akses Terbatas!',
            text: 'Silahkan Login / Registrasi Terlebih Dahulu!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#14b8a6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        });
    }

    // 2. Fungsi Pop-up Konfirmasi Keluar/Logout
    function konfirmasiLogout() {
        Swal.fire({
            title: 'Keluar Akun?',
            text: 'Apakah anda yakin Logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>