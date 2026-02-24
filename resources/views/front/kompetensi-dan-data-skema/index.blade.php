@extends('front.layout.app')

@section('title', 'Kompetensi dan Data Skema')

@section('content')


<section class="bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($kompetensi as $item)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 flex flex-col">
                <!-- Badge Kode Jurusan -->
                <div class="px-6 pt-6 pb-0">
                    <span class="inline-flex items-center justify-center px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold tracking-wide">
                        {{ $item['kode'] }}
                    </span>
                </div>

                <!-- Content -->
                <div class="px-6 pb-6 flex-1 flex flex-col">
                    <!-- Title - Fixed height -->
                    <div class="h-16 flex items-start pt-4 pb-0">
                        <h3 class="text-lg font-bold text-gray-900 leading-snug">
                            {{ $item['nama'] }}
                        </h3>
                    </div>

                    <!-- Stats - Fixed height -->
                    <div class="h-12 flex items-center">
                        <div class="flex items-center gap-6 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-book text-blue-600"></i>
                                <span>{{ $item['unit_kompetensi'] }} Unit Kompetensi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-people text-blue-600"></i>
                                <span>{{ $item['jumlah_asesi'] }} Siswa</span>
                            </div>
                        </div>
                    </div>

                    <!-- Spacer -->
                    <div class="flex-1"></div>

                    <!-- Button -->
                    <a href="{{ route('front.kompetensi.detail', $item['slug']) }}" 
                       class="inline-block text-blue-600 font-semibold hover:text-blue-700 transition pt-2">
                        View Details <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-8 text-center">
                <p class="text-gray-600">Tidak ada data kompetensi tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
