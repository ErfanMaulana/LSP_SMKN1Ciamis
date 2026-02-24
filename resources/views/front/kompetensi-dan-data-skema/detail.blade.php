@extends('front.layout.app')

@section('title', $jurusan['nama'])

@section('content')

<!-- Breadcrumb -->
<section class="bg-white py-4 border-b">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('front.kompetensi.index') }}" class="hover:text-blue-600">Kompetensi</a>
            <i class="bi bi-chevron-right"></i>
            <span class="text-gray-900 font-semibold">{{ $jurusan['nama'] }}</span>
        </div>
    </div>
</section>

<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center gap-6 mb-6">
            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                <span class="text-2xl text-blue-600 font-bold">{{ $jurusan['kode'] }}</span>
            </div>
        </div>
        <h1 class="text-4xl font-bold mb-4">{{ $jurusan['nama'] }}</h1>
        <p class="text-blue-100 text-lg">Program Kompetensi SMKN 1 Ciamis</p>
    </div>
</section>

<!-- Content Section -->
<section class="bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Visi & Misi -->
                <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Visi & Misi</h2>
                    
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-blue-600 mb-3">Visi</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $jurusan['visi'] }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-blue-600 mb-3">Misi</h3>
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
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tentang Program</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{ $jurusan['deskripsi'] }}
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
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
                            <p class="text-sm text-gray-600 font-semibold mb-1">PESERTA ASESI</p>
                            <p class="text-lg font-bold text-gray-900">{{ $jurusan['jumlah_asesi'] }} Peserta</p>
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
