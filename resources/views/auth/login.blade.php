@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row">
        
        <div class="hidden md:flex md:w-1/2 bg-gray-100 items-center justify-center p-12">
            <div class="w-48 h-48 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg">
                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Login Masuk</h2>
                <p class="text-gray-500">Silahkan Login!</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Silahkan Masukkan Email!" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Silahkan Masukkan Password!" required>
                </div>

                <button type="submit" class="w-full bg-[#14b8a6] hover:bg-[#0d9488] text-white font-bold py-2 px-4 rounded transition duration-200">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600 space-x-2">
                <span>Tidak Punya Akun?</span>
                <a href="{{ route('register') }}" class="text-blue-500 font-bold hover:underline">Registrasi</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('password.request') }}" class="text-teal-600 font-bold hover:underline">Lupa Password?</a>
            </p>
        </div>
    </div>
</div>

<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#14b8a6',
        });
    @endif

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#14b8a6',
        });
    @endif
</script>
@endsection