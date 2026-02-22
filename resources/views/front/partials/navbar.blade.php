<header id="navbar" class="bg-white shadow fixed top-0 left-0 right-0 z-50 w-full" style="background-color: #ffffff;">
    <div class="max-w-6xl mx-auto flex items-center justify-between px-6 py-3">

        <div class="flex items-center gap-3">
            <div class="bg-white p-1 rounded">
                <img src="{{ asset('images/lsp.png') }}" class="w-9 h-9 object-contain">
            </div>
            <span class="font-semibold">LSP SMKN1 Ciamis</span>
        </div>

        <nav class="hidden md:flex gap-1 text-sm font-medium">
            @php
                $navLinks = [
                    ['route' => 'front.home',       'label' => 'Beranda'],
                    ['route' => 'front.profil',      'label' => 'Profil LSP'],
                    ['route' => 'front.kompetensi',  'label' => 'Kompetensi & Data Skema'],
                    ['route' => 'front.daftar',      'label' => 'Daftar LSP'],
                    ['route' => 'front.kontak',      'label' => 'Kontak'],
                ];
            @endphp
            @foreach($navLinks as $link)
                @php $active = request()->routeIs($link['route']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="px-4 py-2 rounded-lg transition-colors duration-150
                          {{ $active
                              ? 'bg-blue-600 text-white'
                              : 'text-gray-700 hover:bg-blue-600 hover:text-white' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <a href="{{ route('admin.login') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm">
           Login
        </a>

    </div>
</header>

