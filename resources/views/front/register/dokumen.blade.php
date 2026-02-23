<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pendukung - LSP SMKN1 Ciamis</title>

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

        .upload-card {
            transition: all 0.2s ease;
        }

        .upload-card:hover {
            border-color: #0073bd;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
        }

        .photo-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px dashed #cbd5e1;
            background: #f1f5f9;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-circle img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen py-6 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Blue Header -->
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
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

                <!-- Step Indicator -->
                <div class="px-8 pt-6">
                    <div class="flex items-center justify-center space-x-4 mb-6">
                        <!-- Step 1 - Completed -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="ml-2 text-xs font-medium text-blue-600">Formulir</span>
                        </div>
                        <div class="w-16 h-0.5 bg-blue-600"></div>
                        <!-- Step 2 - Active (Final) -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</div>
                            <span class="ml-2 text-xs font-semibold text-blue-600">Dokumen/Berkas</span>
                        </div>
                        <!-- <div class="w-16 h-0.5 bg-blue-600"></div> -->
                        <!-- Step 3 - Upload (Final) -->
                        <!-- <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">3</div>
                            <span class="ml-2 text-xs font-semibold text-blue-600">Upload Berkas</span>
                        </div> -->
                    </div>
                </div>

                <!-- Form Container -->
                <div class="px-8 pb-6">
                    <!-- Form Title -->
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Upload Bukti Pendukung</h2>
                        <p class="text-xs text-gray-500 leading-relaxed">Formulir ini berisi beberapa bukti yang perlu dilengkapi, sertifikat, ijazah, upload dokumen formal, dengan standar yang berlaku dan mengacu pada standar produk terkait.</p>
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
                                    <h3 class="text-xs font-semibold text-red-800 mb-1.5">Terdapat kesalahan:</h3>
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
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed"><strong>Ini adalah langkah terakhir!</strong> Pastikan Anda sudah memiliki softcopy berkas/dokumen yang akan diupload. Setelah submit, pendaftaran Anda akan langsung dikirim ke admin untuk diverifikasi. Anda tidak perlu mengisi form lagi, cukup tunggu email konfirmasi dari admin.</p>
                        </div>
                    </div>

                    <form action="{{ route('front.register.asesi.dokumen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Pas Foto Upload - Circular -->
                        <div class="flex flex-col items-center mb-8">
                            <div class="relative mb-3" style="width:120px;height:120px;">
                                <div id="photo-circle" class="photo-circle">
                                    <svg id="photo-placeholder-icon" class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <img id="photo-preview-img" src="" alt="Preview Foto" style="display:none;">
                                </div>
                                <!-- Camera badge -->
                                <div class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-1.5 shadow cursor-pointer" onclick="document.getElementById('pas_foto').click()">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <label class="text-xs font-medium text-gray-700 mb-2">Pas Foto <span class="text-red-500">*</span></label>
                            <label for="pas_foto"
                                class="inline-flex items-center px-4 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full cursor-pointer hover:bg-blue-100 transition">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Pilih File
                            </label>
                            <input type="file" name="pas_foto" id="pas_foto" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                            <span id="pas_foto_name" class="text-xs text-gray-400 mt-1"></span>
                        </div>

                        <!-- Transkrip Nilai Card -->
                        <div class="upload-card border border-gray-200 rounded-lg p-5 bg-white">
                            <div class="flex items-start space-x-4">
                                <div class="bg-violet-100 rounded-lg p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Transkrip Nilai <span class="text-red-500">*</span></h4>
                                    <p class="text-xs text-gray-400 mb-3">Scan/foto transkrip nilai akademik. Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                                    <div id="transkrip_nilai_list" class="space-y-2 mb-3"></div>
                                    <button type="button" onclick="addFileInput('transkrip_nilai')"
                                        class="inline-flex items-center px-4 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full cursor-pointer hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah File
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Identitas Pribadi Card -->
                        <div class="upload-card border border-gray-200 rounded-lg p-5 bg-white">
                            <div class="flex items-start space-x-4">
                                <div class="bg-amber-100 rounded-lg p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Identitas Pribadi (KTP / Kartu Pelajar / KK / Surat Keterangan) <span class="text-red-500">*</span></h4>
                                    <p class="text-xs text-gray-400 mb-3">Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                                    <div id="identitas_pribadi_list" class="space-y-2 mb-3"></div>
                                    <button type="button" onclick="addFileInput('identitas_pribadi')"
                                        class="inline-flex items-center px-4 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full cursor-pointer hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah File
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Kompetensi Card -->
                        <div class="upload-card border border-gray-200 rounded-lg p-5 bg-white">
                            <div class="flex items-start space-x-4">
                                <div class="bg-emerald-100 rounded-lg p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Bukti Kompetensi (Basic Skill Report...) <span class="text-red-500">*</span></h4>
                                    <p class="text-xs text-gray-400 mb-3">Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                                    <div id="bukti_kompetensi_list" class="space-y-2 mb-3"></div>
                                    <button type="button" onclick="addFileInput('bukti_kompetensi')"
                                        class="inline-flex items-center px-4 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full cursor-pointer hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah File
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center pt-4">
                            <button type="submit"
                                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm px-8 py-3 rounded-full shadow-sm transition duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Selesaikan Pendaftaran & Kirim ke Admin</span>
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

    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const icon = document.getElementById('photo-placeholder-icon');
                    const circle = document.getElementById('photo-circle');
                    const img = document.getElementById('photo-preview-img');
                    icon.style.display = 'none';
                    img.src = e.target.result;
                    img.style.display = 'block';
                    circle.style.border = '4px solid #e5e7eb';
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('pas_foto_name').textContent = input.files[0].name;
            }
        }

        // Auto-add first file input on page load
        document.addEventListener('DOMContentLoaded', function() {
            addFileInput('transkrip_nilai');
            addFileInput('identitas_pribadi');
            addFileInput('bukti_kompetensi');
        });

        function addFileInput(type) {
            const list = document.getElementById(type + '_list');
            const index = list.children.length;
            const id = type + '_' + index;

            const row = document.createElement('div');
            row.className = 'flex items-center space-x-2';
            row.id = 'row_' + id;

            row.innerHTML = `
                <label for="${id}"
                    class="inline-flex items-center px-4 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full cursor-pointer hover:bg-blue-100 transition whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Pilih File
                </label>
                <input type="file" name="${type}[]" id="${id}" accept="image/*,.pdf" class="hidden"
                    onchange="onFileSelected(this, '${id}')">
                <span id="name_${id}" class="text-xs text-gray-400 truncate max-w-xs">Belum ada file dipilih</span>
                ${index > 0 ? `<button type="button" onclick="removeFileInput('${id}')"
                    class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition flex-shrink-0" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>` : ''}
            `;

            list.appendChild(row);
        }

        function removeFileInput(id) {
            const row = document.getElementById('row_' + id);
            if (row) row.remove();
        }

        function onFileSelected(input, id) {
            const span = document.getElementById('name_' + id);
            if (input.files && input.files[0]) {
                span.textContent = input.files[0].name;
                span.classList.remove('text-gray-400');
                span.classList.add('text-gray-700');
            } else {
                span.textContent = 'Belum ada file dipilih';
                span.classList.remove('text-gray-700');
                span.classList.add('text-gray-400');
            }
        }
    </script>

</body>

</html>
