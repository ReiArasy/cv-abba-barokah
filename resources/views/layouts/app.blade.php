<!-- master layout file -->
<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

    @include('components.navbar')

    <div class="container mx-auto py-8 px-6">
        @yield('content')
    </div>

    @include('components.footer')

</body>

</html>