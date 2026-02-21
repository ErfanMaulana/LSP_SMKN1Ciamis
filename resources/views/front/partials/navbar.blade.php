<header id="navbar"
class="bg-white/90 backdrop-blur-md fixed top-0 left-0 w-full z-50 transition-all duration-300 shadow-sm">

    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between h-15">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/lsp.png') }}" class="h-10 w-10 object-contain ml-4">

                <div class="leading-tight">
                    <h1 class="text-gray-800 font-semibold text-sm">
                        LSP SMKN 1 Ciamis
                    </h1>
                    <p class="text-xs text-gray-500">
                        SISTEM VERIFIKASI
                    </p>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="hidden md:flex items-center gap-2 text-sm font-medium">

                <a href="/"
                   class="px-5 py-2 rounded-lg bg-blue-900 text-white">
                    Beranda
                </a>

                <a href="#profil"
                   class="px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    Profil LSP
                </a>

                <a href="#skema"
                   class="px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    Kompetensi & Data Skema
                </a>

                <a href="#daftar"
                   class="px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    Daftar LSP
                </a>

                <a href="#kontak"
                   class="px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    Kontak
                </a>

                <a href="#login"
                   class="ml-3 bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 mr-5 rounded-lg shadow-sm transition">
                    Login
                </a>

            </nav>

            {{-- Mobile btn --}}
            <button id="menuBtn" class="md:hidden text-gray-800 text-2xl">
                ☰
            </button>

        </div>
    </div>
</header>
