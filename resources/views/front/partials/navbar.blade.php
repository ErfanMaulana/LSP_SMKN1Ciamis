<header id="navbar" x-data="{ open: false }" class="bg-white shadow fixed top-0 left-0 right-0 z-50 w-full" style="background-color: #ffffff;">
    <div class="max-w-6xl mx-auto flex items-center justify-between gap-3 px-4 py-3 sm:px-6">

        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
            <div class="bg-white p-1 rounded">
                <img src="{{ asset('images/lsp.png') }}" class="w-9 h-9 sm:w-10 sm:h-10 object-contain" alt="Logo LSP">
            </div>
            <span class="font-semibold text-sm sm:text-base truncate">LSP SMKN 1 CIAMIS</span>
        </div>

        @php
            $isLanding = request()->routeIs('front.home');
            $navLinks = $isLanding
                ? [
                    ['href' => route('front.home'), 'target' => 'beranda', 'label' => 'Beranda'],
                    ['href' => route('front.home'), 'target' => 'ruang-lingkup', 'label' => 'Ruang Lingkup Sertifikasi'],
                    ['href' => route('front.home'), 'target' => 'berita', 'label' => 'Berita'],
                ]
                : [
                    ['href' => route('front.home'), 'route' => 'front.home', 'label' => 'Beranda'],
                    ['href' => route('front.kompetensi.index'), 'route' => 'front.kompetensi.*', 'label' => 'Ruang Lingkup Sertifikasi'],
                    ['href' => route('front.berita.index'), 'route' => 'front.berita.*', 'label' => 'Berita'],
                ];
        @endphp

        <nav class="hidden md:flex gap-1 text-sm font-medium">
            @foreach($navLinks as $link)
                @php $isActive = $isLanding ? ($loop->first) : request()->routeIs($link['route']); @endphp
                <a href="{{ $link['href'] }}"
                   @if($isLanding) data-scroll-target="{{ $link['target'] }}" @endif
                   class="px-4 py-2 transition-colors duration-200 text-gray-700 hover:text-[#0073bd]"
                   style="text-decoration: none; border-bottom: 2px solid {{ $isActive ? '#0073bd' : 'transparent' }}; color: {{ $isActive ? '#0073bd' : 'inherit' }};">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden md:block">
        <a href="{{ route('login') }}"
           data-turbo="false"
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
        <nav class="flex flex-col gap-2 text-sm font-medium">
            @foreach($navLinks as $link)
                @php $isActive = $isLanding ? ($loop->first) : request()->routeIs($link['route']); @endphp
                <a href="{{ $link['href'] }}"
                   @if($isLanding) data-scroll-target="{{ $link['target'] }}" @endif
                   @click="open = false"
                   class="px-4 py-2.5 transition-colors duration-200 text-gray-700 hover:text-[#0073bd]"
                   style="text-decoration: none; border-bottom: 2px solid {{ $isActive ? '#0073bd' : 'transparent' }}; color: {{ $isActive ? '#0073bd' : 'inherit' }};">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <a href="{{ route('login') }}"
               data-turbo="false"
               class="mt-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-center font-semibold hover:bg-blue-700 transition-colors duration-150">
                Login
            </a>
        </nav>
    </div>
</header>

<script>
(function () {
    const initLandingNav = () => {
        const links = Array.from(document.querySelectorAll('[data-scroll-target]'));
        if (!links.length) {
            return;
        }

        const sections = links
            .map((link) => document.getElementById(link.dataset.scrollTarget))
            .filter(Boolean);

        if (!sections.length) {
            return;
        }

        const setActive = (targetId) => {
            links.forEach((link) => {
                const active = link.dataset.scrollTarget === targetId;
                link.style.color = active ? '#0073bd' : '';
                link.style.borderBottomColor = active ? '#0073bd' : 'transparent';
                link.setAttribute('aria-current', active ? 'page' : 'false');
            });
        };

        const scrollToSection = (targetId) => {
            const section = document.getElementById(targetId);
            if (!section) return;

            const navbar = document.getElementById('navbar');
            const offset = (navbar ? navbar.offsetHeight : 0) + 8;
            const top = section.getBoundingClientRect().top + window.pageYOffset - offset;

            window.scrollTo({ top, behavior: 'smooth' });
            setActive(targetId);
        };

        const getActiveSection = () => {
            const offset = 170;
            let activeId = sections[0].id;

            sections.forEach((section) => {
                const rect = section.getBoundingClientRect();
                if (rect.top <= offset) {
                    activeId = section.id;
                }
            });

            return activeId;
        };

        let ticking = false;
        const update = () => setActive(getActiveSection());
        const onScroll = () => {
            if (ticking) return;

            ticking = true;
            window.requestAnimationFrame(() => {
                update();
                ticking = false;
            });
        };

        links.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                scrollToSection(link.dataset.scrollTarget);
            });
        });

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('load', update);
        document.addEventListener('turbo:load', update);

        update();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLandingNav);
    } else {
        initLandingNav();
    }
})();
</script>

