@extends('admin.layout')

@section('title', 'Akun Asesi')
@section('page-title', 'Kelola Akun Asesi')

@section('content')
<div class="akun-asesi-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Kelola Akun Asesi</h2>
            <p class="subtitle">Buat dan kelola akun asesi berdasarkan NIK. Import massal via XLSX/CSV.</p>
        </div>
        <div style="display:flex;gap:10px;">
            <button class="btn btn-primary" onclick="openImportModal()">
                <i class="bi bi-file-earmark-arrow-up"></i> Import Excel/CSV
            </button>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-circle"></i> Tambah Akun
            </button>
        </div>
    </div>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <div id="toast-success" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;display:flex;align-items:center;gap:12px;min-width:320px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="width:40px;height:40px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:20px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:2px;">Berhasil!</div>
                <div style="color:#64748b;font-size:13px;">{{ session('success') }}</div>
            </div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div id="toast-error" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;display:flex;align-items:center;gap:12px;min-width:320px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="width:40px;height:40px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-exclamation-circle-fill" style="color:#ef4444;font-size:20px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:2px;">Error!</div>
                <div style="color:#64748b;font-size:13px;">{{ session('error') }}</div>
            </div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                <i class="bi bi-x"></i>
            </button>
        </div>
    @endif
    @if(session('import_errors') && count(session('import_errors')))
        <div id="toast-warning" class="toast-notification" style="position:fixed;top:24px;right:24px;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;min-width:360px;max-width:450px;z-index:9999;animation:slideInRight 0.3s ease-out;">
            <div style="display:flex;align-items:start;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:20px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;color:#1e293b;font-size:14px;margin-bottom:6px;">Catatan Import</div>
                    <ul style="margin:0;padding-left:16px;line-height:1.7;color:#64748b;font-size:12px;">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL AKUN</div>
                <div class="stat-value">{{ number_format($totalAkun) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">SUDAH APL-01</div>
                <div class="stat-value">{{ number_format($verified) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">BELUM APL-01</div>
                <div class="stat-value">{{ number_format($unverified) }}</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan NIK atau nama..." autocomplete="off">
                </div>
                <div class="filter-controls">
                    <select class="filter-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="verified">Sudah APL-01</option>
                        <option value="unverified">Belum APL-01</option>
                    </select>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" style="display:none;text-align:center;padding:20px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" style="display:none;text-align:center;padding:20px;color:#dc2626;">
                <i class="bi bi-exclamation-triangle" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                <span>Terjadi kesalahan saat memuat data</span>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($accounts->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-person-vcard"></i>
                        <p>Belum ada akun asesi</p>
                        <span>Buat satu per satu atau import massal dari file XLSX/CSV.</span>
                        <div style="display:flex;gap:10px;justify-content:center;margin-top:16px;">
                            <button onclick="openImportModal()" class="btn btn-primary">
                                <i class="bi bi-file-earmark-arrow-up"></i> Import Excel/CSV
                            </button>
                            <button onclick="openCreateModal()" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Akun
                            </button>
                        </div>
                    </div>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:50px;">NO</th>
                                <th>NIK</th>
                                <th>NAMA</th>
                                <th>STATUS APL-01</th>
                                <th>DIBUAT</th>
                                <th style="width:140px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="accountsTableBody">
                            @include('admin.akun-asesi.partials.table-rows')
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($accounts->hasPages())
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan {{ $accounts->firstItem() }} sampai {{ $accounts->lastItem() }} dari {{ $accounts->total() }} entri
                            </div>
                            <div class="pagination">
                                @if($accounts->currentPage() > 1)
                                    <a href="{{ $accounts->previousPageUrl() }}" class="page-link">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach(range(1, $accounts->lastPage()) as $page)
                                    @if($page == $accounts->currentPage())
                                        <span class="page-link active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $accounts->url($page) }}" class="page-link">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($accounts->currentPage() < $accounts->lastPage())
                                    <a href="{{ $accounts->nextPageUrl() }}" class="page-link">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- IMPORT MODAL -->
<div id="import-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:520px;
                margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-file-earmark-arrow-up" style="color:#16a34a;"></i> Import Akun Asesi
            </h3>
            <button onclick="closeImportModal()" style="background:none;border:none;font-size:20px;
                    color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="import-form" action="{{ route('admin.akun-asesi.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="import-drop" onclick="document.getElementById('import-file').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;
                        cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px;">
                <i class="bi bi-cloud-upload" style="font-size:36px;color:#9ca3af;display:block;margin-bottom:8px;"></i>
                <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Klik atau seret file ke sini</p>
                <p style="font-size:11px;color:#9ca3af;">.xlsx atau .csv &bull; Maks 5 MB</p>
                <p id="import-file-label" style="margin-top:8px;font-size:12px;font-weight:600;color:#16a34a;display:none;"></p>
            </div>
            <input type="file" id="import-file" name="file" accept=".xlsx,.csv"
                   style="display:none;" onchange="onImportFileChange(this)" required>

            <div style="background:#f0fdf4;border-left:3px solid #14532d;padding:10px 14px;
                        border-radius:6px;margin-bottom:12px;font-size:12px;color:#14532d;line-height:1.7;">
                <strong>Format kolom:</strong> Kolom A = NIK (16 digit) &bull; Kolom B = Nama<br>
                Password default akun = NIK.
                <a href="{{ route('admin.akun-asesi.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">
                    <i class="bi bi-download"></i> Download template XLSX
                </a>
            </div>

            <div style="background:#eff6ff;border-left:3px solid #0061A5;padding:10px 14px;
                        border-radius:6px;margin-bottom:16px;font-size:12px;color:#1e40af;line-height:1.7;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-lightbulb" style="font-size:16px;color:#0061A5;flex-shrink:0;"></i>
                <span>NIK berubah jadi angka aneh (1.23E+15)?
                    <a href="javascript:void(0)" onclick="closeImportModal();openTutorialModal()" style="color:#0061A5;font-weight:700;text-decoration:underline;">
                        Lihat tutorial format Text di Excel <i class="bi bi-arrow-right"></i>
                    </a>
                </span>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeImportModal()"
                        style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;
                               background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">
                    Batal
                </button>
                <button id="import-submit-btn" type="submit"
                        style="flex:2;padding:10px;border-radius:8px;border:none;
                               background:#14532d;color:white;font-size:14px;font-weight:600;cursor:pointer;
                               display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-upload"></i> Upload &amp; Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TUTORIAL MODAL -->
<div id="tutorial-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
     z-index:10000;align-items:center;justify-content:center;overflow-y:auto;padding:20px 0;">
    <div style="background:white;border-radius:16px;padding:0;width:100%;max-width:640px;
                margin:20px auto;box-shadow:0 25px 60px rgba(0,0,0,.25);max-height:90vh;display:flex;flex-direction:column;">

        {{-- Header --}}
        <div style="padding:24px 28px 16px;border-bottom:1px solid #e2e8f0;flex-shrink:0;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:18px;font-weight:700;color:#1e293b;margin:0;display:flex;align-items:center;gap:10px;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#eff6ff;border-radius:10px;">
                        <i class="bi bi-mortarboard" style="font-size:18px;color:#0061A5;"></i>
                    </span>
                    Tutorial: Format NIK sebagai Text
                </h3>
                <button onclick="closeTutorialModal()" style="background:none;border:none;font-size:22px;
                        color:#94a3b8;cursor:pointer;line-height:1;padding:4px;" title="Tutup">&times;</button>
            </div>
            <p style="font-size:13px;color:#64748b;margin:10px 0 0;line-height:1.5;">
                Agar NIK 16 digit tidak berubah menjadi <code style="background:#fee2e2;color:#dc2626;padding:1px 6px;border-radius:4px;font-size:12px;">1.23E+15</code>,
                kolom NIK harus diformat sebagai <strong>Text</strong> di Excel sebelum disimpan.
            </p>
        </div>

        {{-- Body (scrollable) --}}
        <div style="padding:20px 28px;overflow-y:auto;flex:1;">

            {{-- Tip box --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:18px;flex-shrink:0;margin-top:1px;"></i>
                <div style="font-size:12px;color:#14532d;line-height:1.6;">
                    <strong>Cara paling mudah:</strong> Gunakan template XLSX dari tombol
                    <a href="{{ route('admin.akun-asesi.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">Download Template XLSX</a>.
                    Kolom NIK sudah otomatis diformat sebagai Text.
                </div>
            </div>

            <p style="font-size:13px;font-weight:700;color:#475569;margin:0 0 16px;text-transform:uppercase;letter-spacing:0.5px;">Jika ingin membuat file sendiri:</p>

            {{-- Step 1 --}}
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="flex-shrink:0;width:32px;height:32px;background:#0061A5;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">1</div>
                <div style="flex:1;">
                    <h4 style="font-size:14px;font-weight:600;color:#1e293b;margin:0 0 6px;">Seleksi kolom NIK</h4>
                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.6;">Klik header kolom <strong>A</strong> (atau kolom yang berisi NIK) untuk menyeleksi seluruh kolom.</p>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-top:8px;text-align:center;">
                        <div style="display:inline-flex;align-items:center;gap:4px;background:#dbeafe;border:1px solid #93c5fd;border-radius:4px;padding:4px 14px;">
                            <span style="font-weight:700;font-size:13px;color:#1e40af;">A</span>
                        </div>
                        <p style="font-size:11px;color:#94a3b8;margin:6px 0 0;">Klik huruf "A" di atas kolom</p>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="flex-shrink:0;width:32px;height:32px;background:#0061A5;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">2</div>
                <div style="flex:1;">
                    <h4 style="font-size:14px;font-weight:600;color:#1e293b;margin:0 0 6px;">Klik kanan → Format Cells</h4>
                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.6;">Klik kanan pada kolom yang sudah diseleksi, lalu pilih <strong>"Format Cells..."</strong></p>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-top:8px;">
                        <div style="display:inline-flex;flex-direction:column;gap:2px;background:#fff;border:1px solid #d1d5db;border-radius:6px;padding:6px 0;min-width:180px;text-align:left;">
                            <span style="padding:4px 14px;font-size:12px;color:#6b7280;">Cut</span>
                            <span style="padding:4px 14px;font-size:12px;color:#6b7280;">Copy</span>
                            <span style="padding:4px 14px;font-size:12px;color:#6b7280;">Paste Special...</span>
                            <span style="border-top:1px solid #e5e7eb;margin:2px 0;"></span>
                            <span style="padding:4px 14px;font-size:12px;color:#1e293b;font-weight:600;background:#eff6ff;"><i class="bi bi-grid-3x3" style="font-size:11px;"></i> Format Cells...</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="flex-shrink:0;width:32px;height:32px;background:#0061A5;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">3</div>
                <div style="flex:1;">
                    <h4 style="font-size:14px;font-weight:600;color:#1e293b;margin:0 0 6px;">Pilih kategori "Text"</h4>
                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.6;">Pada tab <strong>Number</strong>, di daftar <strong>Category</strong>, pilih <strong>"Text"</strong> lalu klik <strong>OK</strong>.</p>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-top:8px;">
                        <p style="font-size:11px;font-weight:600;color:#475569;margin:0 0 8px;">Category:</p>
                        <div style="display:flex;flex-direction:column;gap:1px;background:#fff;border:1px solid #d1d5db;border-radius:6px;padding:4px 0;max-width:160px;">
                            <span style="padding:3px 12px;font-size:12px;color:#6b7280;">General</span>
                            <span style="padding:3px 12px;font-size:12px;color:#6b7280;">Number</span>
                            <span style="padding:3px 12px;font-size:12px;color:#6b7280;">Currency</span>
                            <span style="padding:3px 12px;font-size:12px;color:#6b7280;">Date</span>
                            <span style="padding:3px 12px;font-size:12px;color:#fff;background:#0061A5;border-radius:4px;font-weight:600;">Text</span>
                            <span style="padding:3px 12px;font-size:12px;color:#6b7280;">Special</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="flex-shrink:0;width:32px;height:32px;background:#0061A5;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">4</div>
                <div style="flex:1;">
                    <h4 style="font-size:14px;font-weight:600;color:#1e293b;margin:0 0 6px;">Ketik ulang NIK</h4>
                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.6;">Setelah format diubah ke Text, <strong>ketik ulang</strong> NIK di setiap sel. NIK yang sudah terlanjur jadi angka harus diketik ulang agar disimpan sebagai teks.</p>
                    <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px 14px;margin-top:8px;font-size:12px;color:#92400e;line-height:1.6;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i>
                        <strong>Penting:</strong> Jika NIK sudah menjadi <code style="background:#fee2e2;padding:1px 4px;border-radius:3px;">1.23E+15</code>, mengubah format saja tidak cukup. Harus diketik ulang angka aslinya.
                    </div>
                </div>
            </div>

            {{-- Step 5 --}}
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="flex-shrink:0;width:32px;height:32px;background:#16a34a;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">5</div>
                <div style="flex:1;">
                    <h4 style="font-size:14px;font-weight:600;color:#1e293b;margin:0 0 6px;">Simpan & Upload</h4>
                    <p style="font-size:13px;color:#64748b;margin:0;line-height:1.6;">Simpan file sebagai <strong>.xlsx</strong> (disarankan) atau <strong>.csv</strong>, lalu upload ke halaman import.</p>
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin-top:8px;font-size:12px;color:#14532d;line-height:1.6;">
                        <i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>
                        Ciri NIK sudah benar sebagai Text: angka rata kiri di sel dan ada tanda <span style="color:#16a34a;font-weight:700;">segitiga hijau kecil</span> di pojok kiri atas sel.
                    </div>
                </div>
            </div>

            {{-- Alternative: apostrophe trick --}}
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px;margin-bottom:8px;">
                <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 8px;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i> Trik Cepat: Tambahkan tanda petik satu
                </h4>
                <p style="font-size:12px;color:#64748b;margin:0;line-height:1.7;">
                    Ketik <code style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:4px;font-weight:700;font-size:13px;">'1234567898765430</code>
                    (awali dengan tanda <strong>'</strong> petik satu/apostrophe). Excel akan otomatis menyimpan sebagai teks.
                    Tanda petik tidak akan masuk ke data.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:16px 28px;border-top:1px solid #e2e8f0;display:flex;gap:10px;justify-content:space-between;align-items:center;flex-shrink:0;">
            <a href="{{ route('admin.akun-asesi.template') }}" style="font-size:13px;color:#0061A5;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-download"></i> Download Template XLSX
            </a>
            <button onclick="closeTutorialModal()" style="padding:10px 24px;border-radius:8px;border:none;
                    background:#0061A5;color:white;font-size:14px;font-weight:600;cursor:pointer;
                    display:flex;align-items:center;gap:6px;transition:background .15s;"
                    onmouseover="this.style.background='#004d84'" onmouseout="this.style.background='#0061A5'">
                <i class="bi bi-check-lg"></i> Mengerti
            </button>
        </div>
    </div>
</div>

<!-- CREATE MODAL -->
<div id="create-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:480px;
                margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-person-plus" style="color:#2563eb;"></i> Tambah Akun Asesi
            </h3>
            <button onclick="closeCreateModal()" style="background:none;border:none;font-size:20px;
                    color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form action="{{ route('admin.akun-asesi.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">NIK</label>
                <input type="text" name="NIK" required maxlength="16" minlength="16" pattern="\d{16}"
                       placeholder="Masukkan 16 digit NIK"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;
                              font-family:'Courier New',monospace;letter-spacing:1px;outline:none;">
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Nama Lengkap</label>
                <input type="text" name="nama" required maxlength="255"
                       placeholder="Masukkan nama lengkap"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;">
            </div>

            <div style="background:#eff6ff;border-left:3px solid #2563eb;padding:10px 14px;
                        border-radius:6px;margin-bottom:16px;font-size:12px;color:#1e40af;line-height:1.6;">
                <i class="bi bi-info-circle"></i>
                Password default = NIK. Asesi bisa login menggunakan NIK sebagai username dan password.
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeCreateModal()"
                        style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;
                               background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">
                    Batal
                </button>
                <button type="submit"
                        style="flex:2;padding:10px;border-radius:8px;border:none;
                               background:#2563eb;color:white;font-size:14px;font-weight:600;cursor:pointer;
                               display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-check-lg"></i> Simpan Akun
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('import-modal').style.display = 'flex';
}
function closeImportModal() {
    document.getElementById('import-modal').style.display = 'none';
    document.getElementById('import-file').value = '';
    document.getElementById('import-file-label').style.display = 'none';
}
function openCreateModal() {
    document.getElementById('create-modal').style.display = 'flex';
}
function closeCreateModal() {
    document.getElementById('create-modal').style.display = 'none';
}

// Dropdown actions
function toggleActionsDropdown(event, button) {
    event.stopPropagation();
    const dropdown = button.nextElementSibling;
    
    // Close all other dropdowns
    document.querySelectorAll('.actions-dropdown').forEach(d => {
        if (d !== dropdown) d.style.display = 'none';
    });
    
    // Toggle current dropdown
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function() {
    document.querySelectorAll('.actions-dropdown').forEach(d => {
        d.style.display = 'none';
    });
});
function onImportFileChange(input) {
    var label = document.getElementById('import-file-label');
    if (input.files.length > 0) {
        label.textContent = input.files[0].name;
        label.style.display = 'block';
    } else {
        label.style.display = 'none';
    }
}

// Tutorial modal
function openTutorialModal() {
    document.getElementById('tutorial-modal').style.display = 'flex';
}
function closeTutorialModal() {
    document.getElementById('tutorial-modal').style.display = 'none';
}

// Close modals on backdrop click
['import-modal', 'create-modal', 'tutorial-modal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Import form loading state
document.getElementById('import-form').addEventListener('submit', function() {
    var btn = document.getElementById('import-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
});

// Auto-hide toast notifications after 5 seconds
setTimeout(function() {
    var toasts = document.querySelectorAll('.toast-notification');
    toasts.forEach(function(toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 300);
    });
}, 5000);

// AJAX Search & Filter
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const tableBody = document.getElementById('accountsTableBody');
const loadingSpinner = document.getElementById('loadingSpinner');
const errorMessage = document.getElementById('errorMessage');
let debounceTimer;

function performSearch() {
    const search = searchInput.value.trim();
    const status = statusFilter.value;

    // Show loading state
    loadingSpinner.style.display = 'flex';
    errorMessage.style.display = 'none';
    tableBody.style.opacity = '0.5';

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status) params.append('status', status);

    // Make AJAX request
    fetch(`{{ route('admin.akun-asesi.index') }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        // Update table body
        tableBody.innerHTML = html;
        tableBody.style.opacity = '1';
        loadingSpinner.style.display = 'none';
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.style.display = 'block';
        loadingSpinner.style.display = 'none';
        tableBody.style.opacity = '1';
    });
}

// Debounced search on input (500ms delay)
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 500);
    });
}

// Immediate search on filter change
if (statusFilter) {
    statusFilter.addEventListener('change', performSearch);
}
</script>

<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h2 {
        font-size: 22px;
        color: #0F172A;
        font-weight: 700;
        margin: 0 0 4px 0;
    }

    .subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #003961;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
    }

    /* Card & Table */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .card-body {
        padding: 20px;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }

    .search-box input:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .filter-controls {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 160px;
    }

    .filter-select:hover {
        border-color: #cbd5e1;
    }

    .filter-select:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table thead tr {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .data-table td {
        padding: 14px 16px;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state p {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #475569;
    }

    .empty-state span {
        font-size: 14px;
        color: #94a3b8;
        display: block;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Page specific styles */
    .code-badge {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.3px;
        color: #334155;
        background: #f8fafc;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-info {
        background: #e0e7ff;
        color: #3730a3;
    }

    .badge-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    .actions-wrapper {
        position: relative;
        display: inline-block;
    }

    .action-btn {
        background: none;
        border: none;
        font-size: 18px;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all .2s;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #475569;
    }

    .actions-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
        min-width: 180px;
        z-index: 100;
        overflow: hidden;
        margin-top: 4px;
    }

    .dropdown-item {
        width: 100%;
        padding: 12px 16px;
        text-align: left;
        background: none;
        border: none;
        color: #475569;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        color: #0061A5;
    }

    .dropdown-item.danger {
        color: #dc2626;
    }

    .dropdown-item.danger:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: 13px;
        color: #64748b;
    }

    .pagination {
        display: flex;
        gap: 6px;
    }

    .page-link {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
    }

    .page-link:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .page-link.active {
        background: #0073bd;
        color: white;
        border-color: #0073bd;
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            min-width: 100%;
        }

        .filter-controls {
            width: 100%;
        }

        .filter-select {
            flex: 1;
            min-width: 0;
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>
@endsection
