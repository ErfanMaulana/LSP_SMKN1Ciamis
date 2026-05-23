@php
    $latestBerita = $latestBerita ?? collect();
@endphp

<section id="berita" class="py-16 sm:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10 sm:mb-12">
            <h2 class="section-title" data-scroll-reveal>Berita Terbaru</h2>
            <p class="section-subtitle" data-scroll-reveal data-reveal-delay="70">Informasi terbaru seputar kegiatan, pengumuman, dan agenda LSP SMKN 1 Ciamis.</p>
        </div>

        @if($latestBerita->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6">
                @foreach($latestBerita as $berita)
                    <article class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition duration-300 flex flex-col" data-scroll-reveal="zoom" data-reveal-delay="{{ 100 + ($loop->index * 80) }}">
                        <div class="aspect-[16/10] bg-gray-100 overflow-hidden">
                            @if($berita->gambar)
                                <img src="{{ asset('storage/' . $berita->gambar) }}"
                                     alt="{{ $berita->judul }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="bi bi-newspaper text-6xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 sm:p-6 flex flex-col flex-1">
                            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3">
                                {{ $berita->tanggal_publikasi ? $berita->tanggal_publikasi->format('d M Y') : $berita->created_at->format('d M Y') }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 leading-snug mb-3">
                                {{ $berita->judul }}
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3 flex-1">
                                {!! Str::limit(strip_tags($berita->konten), 140) !!}
                            </p>

                            <a href="{{ route('front.berita.show', $berita->slug) }}"
                               class="inline-flex items-center gap-2 mt-5 text-blue-600 font-semibold hover:text-blue-700 transition">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-10 text-center text-gray-500" data-scroll-reveal="zoom">
                <i class="bi bi-newspaper text-4xl mb-3"></i>
                <p>Belum ada berita yang dipublikasikan.</p>
            </div>
        @endif
    </div>
</section>