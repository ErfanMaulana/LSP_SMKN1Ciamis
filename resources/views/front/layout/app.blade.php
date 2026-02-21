<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP SMKN1 Ciamis</title>

    @vite(['public/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

@include('front.partials.navbar')

<main>
    @yield('content')
</main>

@include('front.partials.footer')

</body>
</html>
