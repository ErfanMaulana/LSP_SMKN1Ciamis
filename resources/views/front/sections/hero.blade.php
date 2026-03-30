<section class="relative aspect-video min-h-0 md:min-h-[90vh] md:aspect-auto flex items-center overflow-hidden">

    {{-- Slides --}}
    <div id="carousel" class="absolute inset-0 z-0">

        @foreach($carousels as $index => $slide)
        <div class="carousel-item absolute inset-0 transition-opacity duration-1000 {{ $index==0?'opacity-100':'opacity-0' }}">

            <img src="{{ Str::startsWith($slide->image, 'carousels/') ? asset('storage/' . $slide->image) : asset($slide->image) }}"
                 class="w-full h-full object-cover">

        </div>
        @endforeach

    </div>

    {{-- Left overlay for text readability --}}
    <div class="absolute inset-0 z-10 pointer-events-none"
         style="background: linear-gradient(90deg, rgba(3, 10, 28, 0.76) 0%, rgba(3, 10, 28, 0.5) 34%, rgba(3, 10, 28, 0.2) 56%, rgba(3, 10, 28, 0) 78%);"></div>


    {{-- CONTENT --}}
    <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 py-4 sm:py-24 text-white">

        @foreach($carousels as $index => $slide)
        <div class="content-slide {{ $index==0?'block':'hidden' }}" data-reveal-stagger="120" data-reveal-start="40">

            <div class="max-w-2xl">

                <span data-scroll-reveal="left" data-reveal-item class="inline-block bg-blue-500/20 text-blue-100 px-2.5 sm:px-4 py-1 sm:py-2 rounded-full text-[10px] sm:text-sm leading-none">
                    {{ $slide->subtitle }}
                </span>

                <h1 data-scroll-reveal="left" data-reveal-item data-reveal-duration="760" class="mt-2 sm:mt-6 text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight max-w-[14ch] sm:max-w-none line-clamp-2 sm:line-clamp-none">
                    {{ $slide->title }}
                </h1>

                <p data-scroll-reveal="left" data-reveal-item class="mt-2 sm:mt-6 text-[10px] leading-tight sm:text-base lg:text-lg text-blue-100 max-w-[95%] sm:max-w-xl line-clamp-2 sm:line-clamp-none">
                    {{ $slide->description }}
                </p>

                <div data-scroll-reveal="left" data-reveal-item class="mt-2 sm:mt-8 flex gap-3 sm:gap-4 flex-wrap">
                    <a href="{{ $slide->button_link }}"
                       class="bg-blue-600 hover:bg-blue-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold shadow-lg transition text-xs sm:text-base w-auto text-center">
                        {{ $slide->button_text }}
                    </a>
                </div>

            </div>

        </div>
        @endforeach

        </div>
    </div>


    {{-- NAV --}}
    <button onclick="prevSlide()" data-scroll-reveal="left" data-reveal-delay="220" class="absolute left-2 sm:left-6 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 w-8 h-8 sm:w-12 sm:h-12 rounded-full flex items-center justify-center transition cursor-pointer group" title="Slide sebelumnya">
        <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <button onclick="nextSlide()" data-scroll-reveal="right" data-reveal-delay="260" class="absolute right-2 sm:right-6 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 w-8 h-8 sm:w-12 sm:h-12 rounded-full flex items-center justify-center transition cursor-pointer group" title="Slide berikutnya">
        <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- NAV sudah ditangani di atas --}}

</section>

{{-- Dot Indicators (di luar section, menempel di bawah carousel) --}}
<div class="relative z-30 flex items-center justify-center gap-2 px-4 -mt-5 sm:-mt-9 pb-2.5 sm:pb-4" data-scroll-reveal="zoom" data-reveal-delay="300">
    @foreach($carousels as $index => $slide)
    <button onclick="goToSlide({{ $index }})"
            class="dot"
            style="display:inline-block; width:12px; height:12px; border-radius:50%; cursor:pointer; transition: all 0.3s;
                   background: {{ $index==0 ? '#0073bd' : 'rgba(255,255,255,0.6)' }};
                   border: 2px solid {{ $index==0 ? '#0073bd' : 'rgba(255,255,255,0.9)' }};
                   transform: {{ $index==0 ? 'scale(1.25)' : 'scale(1)' }};"
            title="Slide {{ $index + 1 }}"></button>
    @endforeach
</div>

<script>
(function () {
    const initHeroCarousel = () => {
        const slides = Array.from(document.querySelectorAll('.carousel-item'));
        const contents = Array.from(document.querySelectorAll('.content-slide'));
        const dots = Array.from(document.querySelectorAll('.dot'));

        if (!slides.length || !contents.length || !dots.length) {
            return;
        }

        let current = 0;

        const showSlide = (index) => {
            slides.forEach((slide) => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            });

            contents.forEach((content) => content.classList.add('hidden'));

            dots.forEach((dot) => {
                dot.style.background = 'rgba(255,255,255,0.5)';
                dot.style.borderColor = 'rgba(255,255,255,0.8)';
                dot.style.transform = 'scale(1)';
            });

            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');

            contents[index].classList.remove('hidden');

            dots[index].style.background = '#0073bd';
            dots[index].style.borderColor = '#0073bd';
            dots[index].style.transform = 'scale(1.25)';
        };

        const goToSlide = (index) => {
            current = (index + slides.length) % slides.length;
            showSlide(current);
        };

        const nextSlide = () => {
            goToSlide(current + 1);
        };

        const prevSlide = () => {
            goToSlide(current - 1);
        };

        window.goToSlide = goToSlide;
        window.nextSlide = nextSlide;
        window.prevSlide = prevSlide;

        if (window.__heroCarouselInterval) {
            window.clearInterval(window.__heroCarouselInterval);
        }

        window.__heroCarouselInterval = window.setInterval(nextSlide, 5000);
        showSlide(current);
    };

    window.__initHeroCarousel = initHeroCarousel;

    if (!window.__heroCarouselTurboListenerRegistered) {
        document.addEventListener('turbo:load', () => {
            if (typeof window.__initHeroCarousel === 'function') {
                window.__initHeroCarousel();
            }
        });

        window.__heroCarouselTurboListenerRegistered = true;
    }

    initHeroCarousel();
})();
</script>
