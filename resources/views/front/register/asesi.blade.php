<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - LSP SMKN1 Ciamis</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
        }

        .border-b-3 {
            border-bottom-width: 3px;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen py-6 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Blue Header -->
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white rounded-full p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold">LSP SMKN 1 Ciamis</h1>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="bg-white border-b border-gray-200">
                    <div class="flex overflow-x-auto">
                        <a href="#"
                            class="px-6 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 whitespace-nowrap">Beranda</a>
                        <a href="#"
                            class="px-6 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 whitespace-nowrap">Profil
                            LSP</a>
                        <a href="#"
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 whitespace-nowrap">Kepesertaan &
                            Daftar Skema</a>
                        <a href="#"
                            class="px-6 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 whitespace-nowrap">Daftar
                            LSP</a>
                        <a href="#"
                            class="px-6 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 whitespace-nowrap">Kontak</a>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="px-8 py-6">
                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center space-x-4 mb-6">
                        <!-- Step 1 - Active -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</div>
                            <span class="ml-2 text-xs font-semibold text-blue-600">Formulir</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <!-- Step 2 - Upcoming -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xs font-bold">2</div>
                            <span class="ml-2 text-xs font-medium text-gray-400">Dokumen/Berkas</span>
                        </div>
                        <!-- <div class="w-16 h-0.5 bg-gray-300"></div> -->
                        <!-- Step 3 - Upcoming -->
                        <!-- <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xs font-bold">3</div>
                            <span class="ml-2 text-xs font-medium text-gray-400">Upload Berkas</span>
                        </div> -->
                    </div>

                    <!-- Form Title -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-1">FR.-APL-01. FORMULIR PERMOHONAN SERTIFIKASI
                        </h2>
                        <p class="text-xs text-gray-600">Bidang Sertifikasi yang akan diuji skema-industri-terkait</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-2.5">
                                    <h3 class="text-xs font-semibold text-red-800 mb-1.5">Terdapat kesalahan dalam pengisian
                                        form:</h3>
                                    <ul class="text-xs text-red-700 list-disc list-inside space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed">Melakukan aplikasi dengan mengisi formulir
                                aplikasi pada halaman berikutnya dan melengkapi form FR.AKL.03 (ASESMEN MANDIRI)</p>
                        </div>
                    </div>

                    <form action="{{ route('front.register.asesi.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Data Pribadi Section -->
                        <div class="bg-gray-50 rounded-md p-6 mb-6">
                            <div class="flex items-center mb-5">
                                <div class="bg-blue-600 rounded-full p-2 mr-2.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">Data Pribadi</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama" class="block text-xs font-semibold text-blue-600 mb-1">Nama
                                        Lengkap</label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="Masukkan nama lengkap">
                                </div>
                                <!-- NIK -->
                                <div>
                                    <label for="NIK" class="block text-xs font-medium text-gray-600 mb-1">NIK / Kode
                                        NIM</label>
                                    <input type="text" name="NIK" id="NIK" value="{{ old('NIK') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="Masukkan NIK">
                                </div>

                                <!-- Tempat Lahir -->
                                <div>
                                    <label for="tempat_lahir"
                                        class="block text-xs font-semibold text-blue-600 mb-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                        value="{{ old('tempat_lahir') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="Masukkan tempat lahir">
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label for="tanggal_lahir"
                                        class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label for="jenis_kelamin"
                                        class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white">
                                        <option value="">Laki-Laki / Perempuan</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <!-- Kewarganegaraan -->
                                <div>
                                    <label for="kewarganegaraan"
                                        class="block text-xs font-medium text-gray-600 mb-1">Kewarganegaraan</label>
                                    <input type="text" name="kewarganegaraan" id="kewarganegaraan"
                                        value="{{ old('kewarganegaraan', 'Indonesia') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="Indonesia">
                                </div>

                                <!-- Alamat (Full Width) -->
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-xs font-medium text-gray-600 mb-1">Alamat
                                        Lengkap</label>
                                    <textarea name="alamat" id="alamat" rows="2" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 resize-none"
                                        placeholder="Jl. Nama jalan RT/RW - Desa - Kecamatan">{{ old('alamat') }}</textarea>
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label for="kode_pos" class="block text-xs font-medium text-gray-600 mb-1">Kode
                                        POS</label>
                                    <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                                        required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="XXXXX">
                                </div>

                                <!-- No. Telepon/HP -->
                                <div>
                                    <label for="telepon_hp" class="block text-xs font-medium text-gray-600 mb-1">No
                                        Telepon/HP</label>
                                    <input type="text" name="telepon_hp" id="telepon_hp" value="{{ old('telepon_hp') }}"
                                        required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="0812XXXXXXXX">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-xs font-medium text-gray-600 mb-1">Email
                                        Asal</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="contoh@email.com">
                                </div>

                                <!-- Pekerjaan -->
                                <div>
                                    <label for="pekerjaan"
                                        class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan / Profesi</label>
                                    <select name="pekerjaan" id="pekerjaan" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white">
                                        <option value="">Pilih</option>
                                        <option value="Pelajar" {{ old('pekerjaan') == 'Pelajar' ? 'selected' : '' }}>
                                            Pelajar</option>
                                        <option value="Mahasiswa" {{ old('pekerjaan') == 'Mahasiswa' ? 'selected' : '' }}>
                                            Mahasiswa</option>
                                        <option value="Karyawan Swasta" {{ old('pekerjaan') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                                        <option value="PNS" {{ old('pekerjaan') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="Wiraswasta" {{ old('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                        <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>
                                            Lainnya</option>
                                    </select>
                                </div>

                                <!-- Pendidikan Terakhir -->
                                <div>
                                    <label for="pendidikan_terakhir"
                                        class="block text-xs font-medium text-gray-600 mb-1">Pendidikan Terakhir</label>
                                    <select name="pendidikan_terakhir" id="pendidikan_terakhir" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white">
                                        <option value="">Pilih</option>
                                        <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD
                                        </option>
                                        <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>
                                            SMP</option>
                                        <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                        <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3
                                        </option>
                                        <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1
                                        </option>
                                        <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2
                                        </option>
                                        <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3
                                        </option>
                                    </select>
                                </div>

                                <!-- Jurusan/Skema -->
                                <div>
                                    <label for="ID_jurusan" class="block text-xs font-medium text-gray-600 mb-1">Jurusan / Skema Sertifikasi</label>
                                    <select name="ID_jurusan" id="ID_jurusan" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach($jurusanList as $jurusan)
                                            <option value="{{ $jurusan->ID_jurusan }}" {{ old('ID_jurusan') == $jurusan->ID_jurusan ? 'selected' : '' }}>
                                                {{ $jurusan->nama_jurusan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pekerjaan/Sekolah Section -->
                        <div class="bg-gray-50 rounded-md p-6 mb-6">
                            <div class="flex items-center mb-5">
                                <div class="bg-emerald-500 rounded-full p-2 mr-2.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">Data Pekerjaan / Sekolah</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <!-- Nama Lembaga -->
                                <div>
                                    <label for="nama_lembaga" class="block text-xs font-medium text-gray-600 mb-1">Nama
                                        Lembaga / Perusahaan</label>
                                    <input type="text" name="nama_lembaga" id="nama_lembaga"
                                        value="{{ old('nama_lembaga', 'SMKN 1 Ciamis') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="SMKN 1 Ciamis">
                                </div>

                                <!-- Jabatan -->
                                <div>
                                    <label for="jabatan"
                                        class="block text-xs font-medium text-gray-600 mb-1">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="Siswa / Staff">
                                </div>

                                <!-- Alamat Lembaga (Full Width) -->
                                <div class="md:col-span-2">
                                    <label for="alamat_lembaga"
                                        class="block text-xs font-medium text-gray-600 mb-1">Alamat Lembaga</label>
                                    <textarea name="alamat_lembaga" id="alamat_lembaga" rows="2" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 resize-none"
                                        placeholder="Jl. Lembaga No. 123...">{{ old('alamat_lembaga') }}</textarea>
                                </div>

                                <!-- No. Telepon Lembaga -->
                                <div>
                                    <label for="no_telepon_lembaga" class="block text-xs font-medium text-gray-600 mb-1">No. Telepon Lembaga</label>
                                    <input type="text" name="no_fax_lembaga" id="no_telepon_lembaga"
                                        value="{{ old('no_fax_lembaga') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="[Oxxx] ...">
                                </div>

                                <!-- No. Fax Lembaga -->
                                <div>
                                    <label for="no_fax_lembaga_alt" class="block text-xs font-medium text-gray-600 mb-1">No. Fax Lembaga</label>
                                    <input type="text" name="telepon_rumah" id="no_fax_lembaga_alt"
                                        value="{{ old('telepon_rumah') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="[Oxxx] ...">
                                </div>

                                <!-- Email Lembaga -->
                                <div>
                                    <label for="email_lembaga"
                                        class="block text-xs font-medium text-gray-600 mb-1">Email Lembaga</label>
                                    <input type="email" name="email_lembaga" id="email_lembaga"
                                        value="{{ old('email_lembaga') }}" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="info@lembaga.com">
                                </div>

                                <!-- Kode POS Lembaga -->
                                <div>
                                    <label for="unit_lembaga" class="block text-xs font-medium text-gray-600 mb-1">Kode POS Lembaga</label>
                                    <input type="text" name="unit_lembaga" id="unit_lembaga"
                                        value="{{ old('unit_lembaga') }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        placeholder="XXXXX">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center pt-2">
                            <button type="submit"
                                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm px-8 py-3 rounded-full shadow-sm transition duration-200 flex items-center justify-center space-x-2">
                                <span>Selanjutnya (Langkah 2)</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Info -->
                <div class="bg-gray-50 px-8 py-4 text-xs text-gray-500 border-t">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center space-x-4">
                            <a href="#" class="flex items-center hover:text-blue-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                Beranda
                            </a>
                            <a href="#" class="hover:text-blue-600">© lsp-tkjsmkn1ciamis.sch.id</a>
                            <a href="#" class="hover:text-blue-600">Asesi LSP</a>
                        </div>
                        <div class="text-gray-400">© 2025 LSP SMKN1 Ciamis. All rights reserved.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>