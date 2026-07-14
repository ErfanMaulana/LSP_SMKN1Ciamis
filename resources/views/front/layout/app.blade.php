<!DOCTYPE html>
<html lang="id" class="bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/lsp.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>@yield('title', 'LSP SMKN1 Ciamis')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('head')
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-800">

@include('front.partials.navbar')

<main class="pt-16">
    @yield('content')
</main>

@include('front.partials.footer')

@stack('scripts')

<script>
    // Jika ada hash #ruang-lingkup: scroll ke section, lalu hapus hash dari URL
    (function () {
        if (window.location.hash === '#ruang-lingkup') {
            history.replaceState(null, '', window.location.pathname);
            var el = document.getElementById('ruang-lingkup');
            if (el) {
                setTimeout(function () {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }
    })();
</script>

</body>
</html>
