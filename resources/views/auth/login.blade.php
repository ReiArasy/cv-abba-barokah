@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="max-w-5xl w-full bg-white rounded-xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        <div
            x-data="{
                current: 0,
                total: 7,
                start() {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.total;
                    }, 3000);
                }
            }"
            x-init="start()"
            class="hidden md:block md:w-1/2 relative overflow-hidden">

            <div
                class="flex transition-transform duration-700 ease-in-out"
                :style="'transform: translateX(-' + (current * 100) + '%)'">

                <img src="{{ asset('storage/products/proses1.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses2.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses3.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses4.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses5.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses6.jpg') }}" class="w-full flex-none h-[600px] object-cover">
                <img src="{{ asset('storage/products/proses7.jpg') }}" class="w-full flex-none h-[600px] object-cover">

            </div>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/40"></div>

        </div>

        <div class="w-full md:w-1/2 flex items-center">

            <div class="w-full px-10 py-12">

                <!-- Judul -->
                <div class="mb-10">
                    <h2 class="text-4xl font-bold text-gray-800">
                        Login Masuk
                    </h2>

                    <p class="mt-2 text-gray-500">
                        Silahkan Login!
                    </p>
                </div>

                <!-- Form -->
                <form
                    action="{{ route('login.post') }}"
                    method="POST"
                    class="space-y-6">

                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Silahkan Masukkan Email!"
                            class="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Silahkan Masukkan Password!"
                            class="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                            required>
                    </div>

                    <!-- Tombol -->
                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full h-12 bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold rounded-lg transition duration-300">
                            Masuk
                        </button>
                    </div>

                </form>

                <!-- Footer -->
                <div class="mt-8 text-center text-sm text-gray-600">

                    <span>Tidak Punya Akun?</span>

                    <a
                        href="{{ route('register') }}"
                        class="ml-1 font-semibold text-blue-600 hover:underline">
                        Registrasi
                    </a>

                    <span class="mx-3 text-gray-300">|</span>

                    <a
                        href="{{ route('password.request') }}"
                        class="font-semibold text-teal-600 hover:underline">
                        Lupa Password?
                    </a>

                </div>

            </div>

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