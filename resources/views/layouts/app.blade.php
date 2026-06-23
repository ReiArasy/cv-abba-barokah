<!-- master layout file -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <!-- Pastikan terminal 'npm run dev' berjalan agar baris ini tidak error -->
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

@if(!request()->is('login') && !request()->is('register') && !request()->is('forgot-password') && !request()->is('reset-password/*'))
    @include('components.navbar')
@endif

    <!-- Hapus class container dan mx-auto di sini agar konten bisa penuh -->
    <main>
        @yield('content')
    </main>

    @include('components.footer')

</body>
</html>