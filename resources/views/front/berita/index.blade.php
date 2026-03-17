@extends('front.layout.app')

@section('title', 'Berita & Pengumuman - LSP SMKN1 Ciamis')

@section('content')

<!-- Page Header -->
<section class="bg-blue-600 text-white py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3">Berita & Pengumuman</h1>
        <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Informasi terbaru seputar LSP SMKN 1 Ciamis</p>
    </div>
</section>

<!-- Content -->
<section class="py-8 sm:py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Search Bar -->
        <div class="mb-8">
            <form action="{{ route('front.berita.index') }}" method="GET" class="max-w-2xl">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}"
                           placeholder="Cari berita..." 
                           class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    @if($search)
                    <a href="{{ route('front.berita.index') }}" 
                       class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if($search)
        <div class="mb-6">
            <p class="text-gray-600">
                Menampilkan hasil pencarian untuk: <strong class="text-gray-900">"{{ $search }}"</strong>
                <span class="text-gray-500">({{ $beritaList->total() }} berita ditemukan)</span>
            </p>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                
                @if($beritaList->count() > 0)
                <div class="space-y-6">
                    @foreach($beritaList as $berita)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="md:flex">
                            @if($berita->gambar)
                            <div class="md:w-1/3">
                                <img src="{{ asset('storage/' . $berita->gambar) }}" 
                                     alt="{{ $berita->judul }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
                            </div>
                            @endif
                            <div class="p-4 sm:p-6 {{ $berita->gambar ? 'md:w-2/3' : 'w-full' }}">
                                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $berita->tanggal_publikasi->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-person"></i>
                                        {{ $berita->penulis }}
                                    </span>
                                </div>
                                
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                                    <a href="{{ route('front.berita.show', $berita->slug) }}">
                                        {{ $berita->judul }}
                                    </a>
                                </h2>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {!! Str::limit(strip_tags($berita->konten), 200) !!}
                                </p>
                                
                                <a href="{{ route('front.berita.show', $berita->slug) }}" 
                                   class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition">
                                    Baca Selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($beritaList->hasPages())
                <div class="mt-8">
                    {{ $beritaList->links() }}
                </div>
                @endif

                @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <i class="bi bi-newspaper text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada berita ditemukan</h3>
                    <p class="text-gray-500">
                        @if($search)
                        Coba gunakan kata kunci yang berbeda
                        @else
                        Belum ada berita yang dipublikasikan
                        @endif
                    </p>
                </div>
                @endif
                
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                
                <!-- Latest News Widget -->
                @if($latestBerita->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">
                        <i class="bi bi-clock-history text-blue-600"></i> Berita Terbaru
                    </h3>
                    <div class="space-y-4">
                        @foreach($latestBerita as $item)
                        <a href="{{ route('front.berita.show', $item->slug) }}" 
                           class="flex gap-3 group">
                            @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}"
                                 class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                                 onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
                            @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-image text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm sm:text-base font-semibold text-gray-900 group-hover:text-blue-600 transition line-clamp-2 mb-1">
                                    {{ $item->judul }}
                                </h4>
                                <p class="text-xs text-gray-500">
                                    {{ $item->tanggal_publikasi->format('d M Y') }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Info Widget -->
                <div class="bg-blue-600 text-white rounded-xl shadow-md p-5 sm:p-6">
                    <h3 class="text-lg font-bold mb-3">
                        <i class="bi bi-info-circle"></i> Informasi
                    </h3>
                    <p class="text-blue-100 text-sm mb-4">
                        Dapatkan informasi terbaru seputar kegiatan, pengumuman, dan berita terkini dari LSP SMKN 1 Ciamis.
                    </p>
                    <a href="{{ route('front.home') }}" 
                       class="inline-flex items-center gap-2 bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="bi bi-house-door"></i>
                        Kembali ke Beranda
                    </a>
                </div>
                
            </div>
            
        </div>
        
    </div>
</section>

@endsection
