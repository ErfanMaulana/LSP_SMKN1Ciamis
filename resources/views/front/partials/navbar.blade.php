<header id="navbar" x-data="{ open: false }" class="bg-white shadow fixed top-0 left-0 right-0 z-50 w-full" style="background-color: #ffffff;">
    <div class="max-w-6xl mx-auto flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
            <div class="bg-white p-1 rounded">
                <img src="{{ asset('images/lsp.png') }}" class="w-9 h-9 sm:w-10 sm:h-10 object-contain" alt="Logo LSP">
            </div>
            <span class="font-semibold text-sm sm:text-base truncate">LSP SMKN 1 CIAMIS</span>
        </div>

        <nav class="hidden md:flex gap-1 text-sm font-medium">
            @php
                $navLinks = [
                    ['route' => 'front.home', 'active_pattern' => 'front.home', 'label' => 'Beranda'],
                    ['route' => 'front.kompetensi.index', 'active_pattern' => 'front.kompetensi.*', 'label' => 'Kompetensi & Data Skema'],
                    ['route' => 'front.berita.index', 'active_pattern' => 'front.berita.*', 'label' => 'Berita'],
                    ['route' => 'front.kontak', 'active_pattern' => 'front.kontak', 'label' => 'Kontak'],
                ];
            @endphp
            @foreach($navLinks as $link)
                @php $active = request()->routeIs($link['active_pattern']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="px-4 py-2 rounded-lg transition-colors duration-150
                          {{ $active
                              ? 'bg-blue-600 text-white'
                              : 'text-gray-700 hover:bg-blue-700 hover:text-white' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden md:block">
        <a href="{{ route('login') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-150 whitespace-nowrap">
           Login
        </a>
        </div>

        <button @click="open = !open"
                class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 text-gray-700"
                :aria-expanded="open.toString()"
                aria-label="Toggle menu">
            <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>

    </div>

    <div x-show="open"
         x-transition
         @click.away="open = false"
         class="md:hidden border-t border-gray-100 bg-white px-4 pb-4 pt-2">
        @php
            $navLinks = [
                ['route' => 'front.home', 'label' => 'Beranda'],
                ['route' => 'front.kompetensi.index', 'label' => 'Kompetensi & Data Skema'],
                ['route' => 'front.berita.index', 'label' => 'Berita'],
                ['route' => 'front.kontak', 'label' => 'Kontak'],
            ];
        @endphp

        <nav class="flex flex-col gap-2 text-sm font-medium">
            @foreach($navLinks as $link)
                @php $active = request()->routeIs($link['route']); @endphp
                <a href="{{ route($link['route']) }}"
                   @click="open = false"
                   class="px-4 py-2.5 rounded-lg transition-colors duration-150 {{ $active ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <a href="{{ route('login') }}"
               class="mt-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-center font-semibold hover:bg-blue-700 transition-colors duration-150">
                Login
            </a>
        </nav>
    </div>
</header>

