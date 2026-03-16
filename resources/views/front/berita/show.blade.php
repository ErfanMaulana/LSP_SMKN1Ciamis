@extends('front.layout.app')

@section('title', $berita->judul . ' - LSP SMKN1 Ciamis')

@section('content')

<!-- Breadcrumb -->
<section class="bg-white py-4">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('front.home') }}" class="hover:text-blue-600">Beranda</a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('front.berita.index') }}" class="hover:text-blue-600">Berita</a>
            <i class="bi bi-chevron-right"></i>
            <span class="text-gray-900 font-semibold">{{ Str::limit($berita->judul, 50) }}</span>
        </div>
    </div>
</section>

<!-- Page Header -->

<!-- Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <article class="bg-white rounded-xl shadow-md overflow-hidden">
                    
                    @if($berita->gambar)
                    <div class="w-full">
                        <img src="{{ asset('storage/' . $berita->gambar) }}" 
                             alt="{{ $berita->judul }}"
                             class="w-full h-auto object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
                    </div>
                    @endif
                    
                    <div class="p-8">
                        <!-- Header -->
                        <div class="mb-6">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                {{ $berita->judul }}
                            </h1>
                            
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 pb-4 border-b">
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-person-circle text-blue-600 text-lg"></i>
                                    <span>{{ $berita->penulis }}</span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-calendar3 text-blue-600"></i>
                                    <span>{{ $berita->tanggal_publikasi->format('d F Y') }}</span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-clock text-blue-600"></i>
                                    <span>{{ $berita->created_at->diffForHumans() }}</span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="prose prose-lg max-w-none">
                            <div class="berita-content">
                                {!! $berita->konten !!}
                            </div>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="mt-8 pt-6 border-t">
                            <p class="text-sm text-gray-600 mb-3 font-semibold">Bagikan artikel ini:</p>
                            <div class="flex gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('front.berita.show', $berita->slug)) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    <i class="bi bi-facebook"></i>
                                    <span class="text-sm font-medium">Facebook</span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('front.berita.show', $berita->slug)) }}&text={{ urlencode($berita->judul) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition">
                                    <i class="bi bi-twitter"></i>
                                    <span class="text-sm font-medium">Twitter</span>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . route('front.berita.show', $berita->slug)) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="bi bi-whatsapp"></i>
                                    <span class="text-sm font-medium">WhatsApp</span>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </article>

                <!-- Related News -->
                @if($relatedBerita->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="bi bi-newspaper text-blue-600"></i> Berita Terkait
                    </h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($relatedBerita as $item)
                        <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}"
                                 class="w-full h-40 object-cover"
                                 onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
                            @else
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                <i class="bi bi-image text-gray-400 text-4xl"></i>
                            </div>
                            @endif
                            <div class="p-4">
                                <p class="text-xs text-gray-500 mb-2">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $item->tanggal_publikasi->format('d M Y') }}
                                </p>
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition">
                                    <a href="{{ route('front.berita.show', $item->slug) }}">
                                        {{ $item->judul }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                    {!! Str::limit(strip_tags($item->konten), 80) !!}
                                </p>
                                <a href="{{ route('front.berita.show', $item->slug) }}" 
                                   class="inline-flex items-center gap-1 text-sm text-blue-600 font-semibold hover:text-blue-700 transition">
                                    Baca <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                
                <!-- Back Button -->
                <a href="{{ route('front.berita.index') }}" 
                   class="flex items-center gap-2 bg-white rounded-xl shadow-md p-4 mb-6 hover:bg-gray-50 transition group">
                    <i class="bi bi-arrow-left text-blue-600 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="font-semibold text-gray-700">Kembali ke Daftar Berita</span>
                </a>

                <!-- Info Widget -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold mb-3">
                        <i class="bi bi-megaphone"></i> Ikuti Kami
                    </h3>
                    <p class="text-blue-100 text-sm mb-4">
                        Dapatkan update berita dan informasi terbaru dari LSP SMKN 1 Ciamis.
                    </p>
                    <div class="space-y-2">
                        @forelse($socialMedias as $social)
                        <a href="{{ $social->url }}" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex items-center gap-3 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">
                            @php
                                $iconMap = [
                                    'facebook' => 'bi-facebook',
                                    'instagram' => 'bi-instagram',
                                    'tiktok' => 'bi-tiktok',
                                    'youtube' => 'bi-youtube',
                                    'twitter' => 'bi-twitter',
                                    'linkedin' => 'bi-linkedin',
                                    'whatsapp' => 'bi-whatsapp',
                                ];
                                $icon = $iconMap[strtolower($social->platform)] ?? 'bi-link-45deg';
                            @endphp
                            <i class="bi {{ $icon }} text-xl"></i>
                            <span class="text-sm font-medium">{{ $social->name }}</span>
                        </a>
                        @empty
                        <p class="text-blue-100 text-sm italic">Tidak ada social media yang aktif</p>
                        @endforelse
                    </div>
                </div>

                
                
            </div>
            
        </div>
        
    </div>
</section>

@endsection

@push('head')
<style>
    .berita-content {
        line-height: 1.8;
    }
    .berita-content h1,
    .berita-content h2,
    .berita-content h3,
    .berita-content h4 {
        font-weight: 700;
        color: #1f2937;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .berita-content h1 { font-size: 2rem; }
    .berita-content h2 { font-size: 1.75rem; }
    .berita-content h3 { font-size: 1.5rem; }
    .berita-content h4 { font-size: 1.25rem; }
    .berita-content p {
        margin-bottom: 1rem;
        color: #4b5563;
    }
    .berita-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }
    .berita-content ul,
    .berita-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }
    .berita-content li {
        margin: 0.5rem 0;
        color: #4b5563;
    }
    .berita-content a {
        color: #2563eb;
        text-decoration: underline;
    }
    .berita-content a:hover {
        color: #1d4ed8;
    }
    .berita-content blockquote {
        border-left: 4px solid #2563eb;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
    }
    .berita-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }
    .berita-content table th,
    .berita-content table td {
        border: 1px solid #e5e7eb;
        padding: 0.75rem;
        text-align: left;
    }
    .berita-content table th {
        background-color: #f3f4f6;
        font-weight: 600;
    }
</style>
@endpush
