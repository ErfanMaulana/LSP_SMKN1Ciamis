<section 
x-data="carousel()"
x-init="start()"
class="relative h-[520px] overflow-hidden text-white">

    {{-- Slides --}}
    <template x-for="(slide,index) in slides" :key="index">
        <div x-show="active==index" x-transition.opacity
             class="absolute inset-0">

            <img :src="slide.image"
                 class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-blue-700/40"></div>

            <div class="relative container mx-auto px-6 h-full flex items-center">
                <div class="max-w-xl">

                    <span class="bg-white/20 px-4 py-1 rounded-full text-sm">
                        Lembaga Sertifikasi Profesi (LSP)
                    </span>

                    <h1 class="text-5xl font-bold mt-4 leading-tight" x-text="slide.title"></h1>
                    <p class="mt-4 text-blue-100" x-text="slide.subtitle"></p>

                </div>
            </div>
        </div>
    </template>

    {{-- dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(s,i) in slides">
            <button @click="active=i"
                :class="active==i ? 'bg-white' : 'bg-white/40'"
                class="w-3 h-3 rounded-full"></button>
        </template>
    </div>

</section>

<script>
function carousel(){
    return{
        active:0,
        slides:@json($banners->map(fn($b)=>[
            'title'=>$b->title,
            'subtitle'=>$b->subtitle,
            'image'=>asset('storage/'.$b->image)
        ])),
        start(){
            setInterval(()=>{
                this.active = (this.active+1)%this.slides.length
            },5000)
        }
    }
}
</script>
