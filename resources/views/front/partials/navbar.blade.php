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
                    ['href' => url('/#beranda'), 'target' => 'beranda', 'label' => 'Beranda'],
                    ['href' => url('/#ruang-lingkup'), 'target' => 'ruang-lingkup', 'label' => 'Ruang Lingkup Sertifikasi'],
                    ['href' => url('/#berita'), 'target' => 'berita', 'label' => 'Berita'],
                    ['href' => url('/#kontak'), 'target' => 'kontak', 'label' => 'Kontak'],
                ];
            @endphp
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   data-nav-target="{{ $link['target'] }}"
                   class="nav-scroll-link px-4 py-2 transition-colors duration-200 text-gray-700 hover:text-[#0073bd]"
                   style="text-decoration: none; border-bottom: 2px solid transparent;">
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
        @php
            $navLinks = [
                ['href' => url('/#beranda'), 'target' => 'beranda', 'label' => 'Beranda'],
                ['href' => url('/#ruang-lingkup'), 'target' => 'ruang-lingkup', 'label' => 'Ruang Lingkup Sertifikasi'],
                ['href' => url('/#berita'), 'target' => 'berita', 'label' => 'Berita'],
                ['href' => url('/#kontak'), 'target' => 'kontak', 'label' => 'Kontak'],
            ];
        @endphp

        <nav class="flex flex-col gap-2 text-sm font-medium">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   data-nav-target="{{ $link['target'] }}"
                   @click="open = false"
                   class="nav-scroll-link px-4 py-2.5 transition-colors duration-200 text-gray-700 hover:text-[#0073bd]"
                   style="text-decoration: none; border-bottom: 2px solid transparent;">
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
    const initNavScrollSpy = () => {
        const links = Array.from(document.querySelectorAll('[data-nav-target]'));
        const targets = links
            .map((link) => document.getElementById(link.dataset.navTarget))
            .filter(Boolean);

        if (!links.length || !targets.length) {
            return;
        }

        const setActive = (targetId) => {
            links.forEach((link) => {
                const active = link.dataset.navTarget === targetId;
                link.style.color = active ? '#0073bd' : '';
                link.style.borderBottomColor = active ? '#0073bd' : 'transparent';
                link.setAttribute('aria-current', active ? 'page' : 'false');
            });
        };

        const getActiveTarget = () => {
            const offset = 160;
            let activeId = targets[0].id;

            targets.forEach((target) => {
                const rect = target.getBoundingClientRect();
                if (rect.top <= offset) {
                    activeId = target.id;
                }
            });

            return activeId;
        };

        const update = () => setActive(getActiveTarget());

        let ticking = false;
        const onScroll = () => {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(() => {
                update();
                ticking = false;
            });
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('hashchange', update);
        document.addEventListener('turbo:load', update);
        window.addEventListener('load', update);

        links.forEach((link) => {
            link.addEventListener('click', () => {
                setActive(link.dataset.navTarget);
            });
        });

        update();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavScrollSpy);
    } else {
        initNavScrollSpy();
    }
})();
</script>

