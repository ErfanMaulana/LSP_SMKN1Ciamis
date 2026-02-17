<header class="bg-white shadow">
    <div class="container mx-auto flex items-center justify-between px-6 py-3">

        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" class="w-9">
            <span class="font-semibold">LSP SMKN1 Ciamis</span>
        </div>

        <nav class="hidden md:flex gap-7 text-sm font-medium">
            <a class="text-blue-600">Beranda</a>
            <a>Profil LSP</a>
            <a>Kompetensi & Skema</a>
            <a>Daftar LSP</a>
            <a>Kontak</a>
        </nav>

        <a href="{{ route('login') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">
           Login
        </a>

    </div>
</header>

