<section class="relative min-h-[90vh] flex items-center overflow-hidden">

    {{-- Slides --}}
    <div id="carousel" class="absolute inset-0 z-0">

        @foreach($carousels as $index => $slide)
        <div class="carousel-item absolute inset-0 transition-opacity duration-1000 {{ $index==0?'opacity-100':'opacity-0' }}">

            <img src="{{ asset($slide->image) }}"
                 class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-blue-900/80 to-blue-800/60"></div>

        </div>
        @endforeach

    </div>


    {{-- CONTENT --}}
    <div class="relative z-10 container mx-auto px-6 py-24 text-white ml-10">

        @foreach($carousels as $index => $slide)
        <div class="content-slide {{ $index==0?'block':'hidden' }}">

            <div class="max-w-2xl">

                <span class="bg-blue-500/20 text-blue-200 px-4 py-2 rounded-full text-sm">
                    {{ $slide->subtitle }}
                </span>

                <h1 class="mt-6 text-5xl font-bold leading-tight">
                    {{ $slide->title }}
                </h1>

                <p class="mt-6 text-lg text-blue-100">
                    {{ $slide->description }}
                </p>

                <div class="mt-8 flex gap-4">
                    <a href="{{ $slide->button_link }}"
                       class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                        {{ $slide->button_text }}
                    </a>
                </div>

            </div>

        </div>
        @endforeach

    </div>


    {{-- NAV --}}
    <button onclick="prevSlide()" class="absolute left-6 text-white text-3xl z-20">‹</button>
    <button onclick="nextSlide()" class="absolute right-6 text-white text-3xl z-20">›</button>

</section>

<script>
let current = 0;
const slides = document.querySelectorAll('.carousel-item');
const contents = document.querySelectorAll('.content-slide');

function showSlide(i){
    slides.forEach(s=>{
        s.classList.remove('opacity-100');
        s.classList.add('opacity-0');
    });

    contents.forEach(c=>c.classList.add('hidden'));

    slides[i].classList.remove('opacity-0');
    slides[i].classList.add('opacity-100');

    contents[i].classList.remove('hidden');
}

function nextSlide(){
    current = (current + 1) % slides.length;
    showSlide(current);
}

function prevSlide(){
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
}

setInterval(nextSlide,5000);
</script>
