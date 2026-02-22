<section class="relative min-h-[90vh] flex items-center overflow-hidden">

    {{-- Slides --}}
    <div id="carousel" class="absolute inset-0 z-0">

        @foreach($carousels as $index => $slide)
        <div class="carousel-item absolute inset-0 transition-opacity duration-1000 {{ $index==0?'opacity-100':'opacity-0' }}">

            <img src="{{ Str::startsWith($slide->image, 'carousels/') ? asset('storage/' . $slide->image) : asset($slide->image) }}"
                 class="w-full h-full object-cover">

        </div>
        @endforeach

    </div>


    {{-- CONTENT --}}
    <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-6 py-24 text-white">

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
    </div>


    {{-- NAV --}}
    <button onclick="prevSlide()" class="absolute left-6 text-white text-3xl z-20 bg-black/30 hover:bg-black/50 w-12 h-12 rounded-full flex items-center justify-center transition">‹</button>
    <button onclick="nextSlide()" class="absolute right-6 text-white text-3xl z-20 bg-black/30 hover:bg-black/50 w-12 h-12 rounded-full flex items-center justify-center transition">›</button>

    {{-- NAV sudah ditangani di atas --}}

</section>

{{-- Dot Indicators (di luar section, menempel di bawah carousel) --}}
<div style="margin-top: -44px; position: relative; z-index: 30; display: flex; align-items: center; justify-content: center; gap: 8px; padding-bottom: 16px;">
    @foreach($carousels as $index => $slide)
    <button onclick="goToSlide({{ $index }})"
            class="dot"
            style="display:inline-block; width:12px; height:12px; border-radius:50%; cursor:pointer; transition: all 0.3s;
                   background: {{ $index==0 ? '#3b82f6' : 'rgba(255,255,255,0.6)' }};
                   border: 2px solid {{ $index==0 ? '#3b82f6' : 'rgba(255,255,255,0.9)' }};
                   transform: {{ $index==0 ? 'scale(1.25)' : 'scale(1)' }};"
            title="Slide {{ $index + 1 }}"></button>
    @endforeach
</div>

<script>
let current = 0;
const slides = document.querySelectorAll('.carousel-item');
const contents = document.querySelectorAll('.content-slide');
const dots = document.querySelectorAll('.dot');

function showSlide(i){
    slides.forEach(s=>{
        s.classList.remove('opacity-100');
        s.classList.add('opacity-0');
    });

    contents.forEach(c=>c.classList.add('hidden'));

    dots.forEach(d=>{
        d.style.background = 'rgba(255,255,255,0.5)';
        d.style.borderColor = 'rgba(255,255,255,0.8)';
        d.style.transform = 'scale(1)';
    });

    slides[i].classList.remove('opacity-0');
    slides[i].classList.add('opacity-100');

    contents[i].classList.remove('hidden');

    dots[i].style.background = '#3b82f6';
    dots[i].style.borderColor = '#3b82f6';
    dots[i].style.transform = 'scale(1.25)';
}

function goToSlide(i){
    current = i;
    showSlide(current);
}

function nextSlide(){
    current = (current + 1) % slides.length;
    showSlide(current);
}

function prevSlide(){
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
}

setInterval(nextSlide, 5000);
</script>
