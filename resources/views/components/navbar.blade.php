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
            
            <a href="#" class="text-slate-600 hover:text-teal-500 transition">About us</a>
            
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'text-teal-500 border-b-2 border-teal-500 pb-1' : 'text-slate-600 hover:text-teal-500 transition' }}">
                Product
            </a>
            
            <a href="#" class="text-slate-600 hover:text-teal-500 transition">Purchase</a>
            <a href="#" class="text-slate-600 hover:text-teal-500 transition">Contact</a>
        </div>

    </div>
</nav>