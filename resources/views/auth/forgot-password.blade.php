@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white shadow-lg rounded-xl overflow-hidden p-8 border border-gray-100">
        
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Reset Password</h2>
            <p class="text-gray-500 text-sm mt-2">Masukkan email terdaftar dan password baru Anda.</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" placeholder="Masukkan email akun Anda yang Terdaftar" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" placeholder="Password Minimal 8 karakter" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm" placeholder="Ulangi password baru" required>
            </div>

            <button type="submit" class="w-full bg-[#14b8a6] hover:bg-[#0d9488] text-white font-bold py-2 px-4 rounded-lg transition duration-200 text-sm shadow-md">
                Perbarui Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline font-semibold">
                Kembali ke Login
            </a>
        </div>
    </div>
</div>

<script>
    // =========================================================================
    // MENANGKAPI POP-UP ERROR SELEKTIF SESUAI TABEL TEST CASE (DENGAN SWEETALERT2)
    // =========================================================================
    @if ($errors->any())
        let titleAlert = 'Password Gagal Diperbarui'; // Default title bawaan
        let textAlert = '{{ $errors->first() }}';

        // 1. Skenario TC-008-B: Jika error validasi email tidak terdaftar di database
        @if ($errors->has('email') && str_contains($errors->first('email'), 'tidak terdaftar'))
            titleAlert = 'Email tidak terdaftar';
        @endif

        // 2. Skenario TC-008-C: Jika error konfirmasi password tidak sesuai
        @if ($errors->has('password') && str_contains($errors->first('password'), 'tidak cocok'))
            titleAlert = 'Konfirmasi password baru tidak cocok';
        @endif

        // 3. Skenario TC-008-D: Amankan judul pop-up agar pas dengan ekspektasi asersi TC-008-D Cypress
        @if ($errors->has('password') && (str_contains($errors->first('password'), 'sama dengan password lama') || str_contains($errors->first('password'), 'minimal')))
            titleAlert = 'Gagal Mengubah Password';
        @endif

        Swal.fire({
            icon: 'error',
            title: titleAlert,
            text: textAlert,
            confirmButtonColor: '#14b8a6',
        });
    @endif
</script>
@endsection