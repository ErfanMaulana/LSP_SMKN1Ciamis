@extends('front.layout.app')

@section('title', $content['heading'] . ' - LSP SMKN1 Ciamis')

@section('content')
<section class="bg-blue-600 text-white py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <h1 class="text-2xl sm:text-4xl font-bold mb-3 leading-tight">{{ $content['heading'] }}</h1>
        <p class="text-blue-100 text-base sm:text-lg max-w-3xl leading-relaxed">{{ $content['intro'] }}</p>
    </div>
</section>

<section class="bg-gray-50 py-8 sm:py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-4 xl:col-span-3">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 lg:sticky lg:top-24">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Daftar Panduan</h2>
                    <nav class="space-y-2">
                        <a href="{{ route('front.panduan.overview') }}"
                           class="block rounded-lg px-3 py-2 text-sm transition {{ $activeSection === 'overview' ? 'bg-blue-600 text-white font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                            Alur keseluruhan sistem
                        </a>
                        <a href="{{ route('front.panduan.asesi') }}"
                           class="block rounded-lg px-3 py-2 text-sm transition {{ $activeSection === 'asesi' ? 'bg-blue-600 text-white font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                            Peran Asesi
                        </a>
                        <a href="{{ route('front.panduan.asesor') }}"
                           class="block rounded-lg px-3 py-2 text-sm transition {{ $activeSection === 'asesor' ? 'bg-blue-600 text-white font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                            Peran Asesor
                        </a>
                        <a href="{{ route('front.panduan.admin') }}"
                           class="block rounded-lg px-3 py-2 text-sm transition {{ $activeSection === 'admin' ? 'bg-blue-600 text-white font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                            Peran Admin
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="lg:col-span-8 xl:col-span-9 space-y-4">
                @forelse($content['steps'] as $step)
                    <article class="bg-white rounded-xl shadow-md border border-gray-100 p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $step['description'] }}</p>
                        @if(!empty($step['image']))
                            <figure class="mt-4 border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                                <img
                                    src="{{ $step['image'] }}"
                                    alt="{{ $step['image_alt'] ?? $step['title'] }}"
                                    class="w-full h-auto object-cover"
                                    loading="lazy"
                                >
                                @if(!empty($step['image_caption']))
                                    <figcaption class="px-4 py-3 text-xs text-gray-500 bg-white border-t border-gray-200">
                                        {{ $step['image_caption'] }}
                                    </figcaption>
                                @endif
                            </figure>
                        @endif
                    </article>
                @empty
                    <article class="bg-white rounded-xl shadow-md border border-gray-100 p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Konten Belum Tersedia</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Admin belum menambahkan poin panduan untuk bagian ini. Silakan cek kembali nanti.
                        </p>
                    </article>
                @endforelse

                @if($activeSection === 'overview')
                    <article class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-6">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Catatan Singkat</h3>
                        <p class="text-blue-800 leading-relaxed">
                            Untuk menjalankan alur secara optimal, data master (skema, unit, elemen, jadwal, dan penugasan) sebaiknya dituntaskan lebih dahulu oleh Admin sebelum pembukaan pendaftaran aktif.
                        </p>
                    </article>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
