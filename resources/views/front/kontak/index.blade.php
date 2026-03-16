@extends('front.layout.app')

@section('title', 'Hubungi Kami - LSP SMKN1 Ciamis')

@section('content')

<!-- Page Header -->
<section class="bg-blue-600 text-white py-16">
    <div class="max-w-6xl mx-auto px-6">
        <h1 class="text-4xl font-bold mb-3">Hubungi Kami</h1>
        <p class="text-blue-100 text-lg">Kami siap membantu Anda Kapanpun Anda butuh bantuan.</p>
    </div>
</section>

<!-- Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="bi bi-envelope text-blue-600"></i> Kirim Pesan
                </h2>
                <p class="text-gray-600 text-sm mb-6">Isi formulir di bawah ini dan kami akan segera merespons</p>

                <form class="space-y-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Masukkan nama lengkap" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" placeholder="nama@email.com" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" placeholder="08xx-xxxx-xxxx" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                                <option value="">Pilih subjek</option>
                                <option value="info">Informasi Umum</option>
                                <option value="pendaftaran">Pendaftaran</option>
                                <option value="pertanyaan">Pertanyaan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan <span class="text-red-500">*</span></label>
                        <textarea placeholder="Tulis pesan Anda di sini..." rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i class="bi bi-send"></i> Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div>
                <!-- Info Widget -->
                <div class="bg-blue-600 text-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">
                        <i class="bi bi-info-circle"></i> Informasi Kontak
                    </h3>
                    <p class="text-blue-100 text-sm mb-6">
                        Hubungi kami kapan saja untuk pertanyaan atau informasi lebih lanjut
                    </p>

                    <div class="space-y-4">
                        <!-- Alamat -->
                        @if($kontak->alamat)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-geo-alt text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-200 uppercase">Alamat</p>
                                <p class="text-sm text-blue-50">{{ $kontak->alamat }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Telepon -->
                        @if($kontak->telepon)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-telephone text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-200 uppercase">Telepon</p>
                                <p class="text-sm text-blue-50">{{ $kontak->telepon }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- WhatsApp -->
                        @if($kontak->telepon_whatsapp)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-whatsapp text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-200 uppercase">WhatsApp</p>
                                <a href="https://wa.me/{{ str_replace(['+', '-', ' ', '(', ')'], '', $kontak->telepon_whatsapp) }}" 
                                   target="_blank"
                                   class="text-sm text-blue-50 hover:text-white transition">
                                    {{ $kontak->telepon_whatsapp }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Email 1 -->
                        @if($kontak->email_1)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-envelope text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-200 uppercase">Email</p>
                                <a href="mailto:{{ $kontak->email_1 }}" class="text-sm text-blue-50 hover:text-white transition">
                                    {{ $kontak->email_1 }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Email 2 -->
                        @if($kontak->email_2)
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-envelope-at text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-200 uppercase">Email Alternatif</p>
                                <a href="mailto:{{ $kontak->email_2 }}" class="text-sm text-blue-50 hover:text-white transition">
                                    {{ $kontak->email_2 }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Jam Pelayanan -->
                @if($kontak->jam_pelayanan)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="bi bi-clock text-blue-600"></i> Jam Pelayanan
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Senin - Kamis</span>
                            <span class="text-gray-900 font-semibold">
                                {{ $kontak->jam_pelayanan['senin_kamis']['awal'] ?? '07:00' }} - {{ $kontak->jam_pelayanan['senin_kamis']['akhir'] ?? '15:00' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Jumat</span>
                            <span class="text-gray-900 font-semibold">
                                {{ $kontak->jam_pelayanan['jumat']['awal'] ?? '07:00' }} - {{ $kontak->jam_pelayanan['jumat']['akhir'] ?? '11:30' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-gray-700 font-medium">Sabtu & Minggu</span>
                            <span class="text-red-600 font-semibold">Tutup</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
