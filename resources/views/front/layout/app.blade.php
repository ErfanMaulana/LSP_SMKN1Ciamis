<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/lsp.png') }}">


    <title>LSP SMKN1 Ciamis</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<script>
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
    if(window.scrollY > 40){
        navbar.classList.add('bg-white/90','backdrop-blur','shadow','text-gray-800');
        navbar.classList.remove('text-white');
    }else{
        navbar.classList.remove('bg-white/90','backdrop-blur','shadow','text-gray-800');
        navbar.classList.add('text-white');
    }
});
</script>

<body class="bg-gray-100 text-gray-800">

@include('front.partials.navbar')

<main>
    @yield('content')
</main>

@include('front.partials.footer')

</body>
</html>
