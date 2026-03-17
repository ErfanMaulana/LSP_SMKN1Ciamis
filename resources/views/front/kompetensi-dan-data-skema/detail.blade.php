@extends('front.layout.app')

@section('title', $jurusan['nama'])

@section('content')

<!-- Breadcrumb -->
<section class="bg-white py-3 sm:py-4">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 overflow-x-auto whitespace-nowrap pb-1">
            <a href="{{ route('front.kompetensi.index') }}" class="hover:text-blue-600">Kompetensi</a>
            <i class="bi bi-chevron-right"></i>
            <span class="text-gray-900 font-semibold">{{ $jurusan['nama'] }}</span>
        </div>
    </div>
</section>

<!-- Header Section -->
<section class="bg-blue-600 text-white py-8 sm:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                <span class="text-xl sm:text-2xl text-blue-600 font-bold">{{ $jurusan['kode'] }}</span>
            </div>
        </div>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4">{{ $jurusan['nama'] }}</h1>
        <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Program Kompetensi SMKN 1 Ciamis</p>
    </div>
</section>

<!-- Content Section -->
<section class="bg-gray-100 py-8 sm:py-12 lg:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Visi & Misi -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-5 sm:mb-6">Visi & Misi</h2>
                    
                    <div class="mb-8">
                        <h3 class="text-base sm:text-lg font-semibold text-blue-600 mb-3">Visi</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $jurusan['visi'] }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-blue-600 mb-3">Misi</h3>
                        <ul class="space-y-3">
                            @foreach($jurusan['misi'] as $m)
                            <li class="flex gap-3 text-gray-700">
                                <span class="text-blue-600 font-bold mt-1">•</span>
                                <span>{{ $m }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Tentang Program</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{ $jurusan['deskripsi'] }}
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 lg:sticky lg:top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Program</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-1">KODE JURUSAN</p>
                            <p class="text-lg font-bold text-gray-900">{{ $jurusan['kode'] }}</p>
                        </div>

                        <hr class="border-gray-200">

                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-1">UNIT KOMPETENSI</p>
                            <p class="text-lg font-bold text-gray-900">{{ $jurusan['unit_kompetensi'] }} Unit</p>
                        </div>

                        <hr class="border-gray-200">

                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-1">DATA ASESI</p>
                            <p class="text-lg font-bold text-gray-900">{{ $jurusan['jumlah_asesi'] }} Asesi</p>
                        </div>

                        <hr class="border-gray-200">

                        <div>
                            <p class="text-sm text-gray-600 font-semibold mb-1">STANDAR KOMPETENSI</p>
                            <p class="text-gray-700 text-sm">{{ $jurusan['standar_kompetensi'] }}</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-8">
                        <a href="{{ route('front.kompetensi.index') }}" 
                           class="block w-full text-center px-4 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition">
                            <i class="bi bi-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
