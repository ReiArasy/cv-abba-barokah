@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-5xl w-full bg-white shadow-lg rounded-xl overflow-hidden flex flex-col md:flex-row">
        
        <!-- Sisi Kiri: Placeholder Gambar -->
        <div class="hidden md:flex md:w-1/3 bg-gray-100 items-center justify-center p-8">
            <div class="w-full aspect-square border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg">
                <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Sisi Kanan: Form Registrasi -->
        <div class="w-full md:w-2/3 p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Sign Up</h2>
                <p class="text-gray-500 text-sm">Hi! Please enter valid data.</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username</label>
                        <input type="text" name="username" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter your username">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter your Full Name">
                    </div>

                    <!-- Baris 2 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter your Password">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat_lengkap" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter a valid Address">
                    </div>

                    <!-- Baris 3 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter your Email">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Provinsi</label>
                        <select name="provinsi" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm bg-white">
                            <option>Jawa Timur</option>
                            <option>Jawa Tengah</option>
                            <option>Jawa Barat</option>
                        </select>
                    </div>

                    <!-- Baris 4 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm" placeholder="Enter your Phone">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kota</label>
                        <select name="kota" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-teal-500 outline-none text-sm bg-white">
                            <option>Surabaya</option>
                            <option>Sidoarjo</option>
                            <option>Malang</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center">
                    <button type="submit" class="w-1/2 bg-teal-500 text-white font-bold py-2 rounded-lg hover:bg-teal-600 transition shadow-md">
                        Sign Up
                    </button>
                    <p class="mt-4 text-sm text-gray-600">
                        Already have account? <a href="{{ route('login') }}" class="text-blue-500 font-bold">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#14b8a6',
        });
    @endif
</script>

@endsection
