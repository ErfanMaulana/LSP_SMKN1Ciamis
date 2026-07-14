@extends('admin.layout')

@section('title', 'Manajemen Asesor')
@section('page-title', 'Manajemen Asesor')

@section('content')
<div class="asesor-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Manajemen Asesor</h2>
            <p class="subtitle">Kelola dan awasi semua asesor yang terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('admin.asesor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Asesor Baru
        </a>
    </div>

    @if(session('import_errors') && count(session('import_errors')))
        <div style="background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;margin-bottom:20px;border-left:4px solid #f59e0b;">
            <div style="display:flex;align-items:start;gap:12px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:18px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;color:#1e293b;font-size:13px;margin-bottom:6px;">Catatan Import Data Asesor</div>
                    <ul style="margin:0;padding-left:16px;line-height:1.7;color:#64748b;font-size:12px;max-height:220px;overflow:auto;">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    <!-- Statistics Cards -->
    <div class="stats-grid">
        <a href="{{ route('admin.asesor.index') }}" class="stat-card {{ $cardFilter == '' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL ASESOR</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesor.index', ['card_filter' => 'with_skema']) }}" class="stat-card {{ $cardFilter == 'with_skema' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DENGAN SKEMA</div>
                <div class="stat-value">{{ $stats['with_skema'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesor.index', ['card_filter' => 'without_skema']) }}" class="stat-card {{ $cardFilter == 'without_skema' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TANPA SKEMA</div>
                <div class="stat-value">{{ $stats['without_skema'] }}</div>
            </div>
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.asesor.index') }}" id="filterForm">
            @if($cardFilter)
                <input type="hidden" name="card_filter" value="{{ $cardFilter }}">
            @endif
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Cari berdasarkan nama atau ID..."
                           value="{{ request('search') }}" autocomplete="off">
                </div>
                <div class="filter-group" style="display:flex; gap:12px; align-items:center;">
                    <select class="filter-select" name="keahlian" onchange="performAjaxSearch()">
                        <option value="">Semua Skema</option>
                        @foreach($skemaList as $skema)
                            <option value="{{ $skema->id }}" {{ request('keahlian') == $skema->id ? 'selected' : '' }}>
                                {{ $skema->nama_skema }}
                            </option>
                        @endforeach
                    </select>
                    @php
                        $globalMax = \App\Models\Setting::get('max_asesi_per_asesor');
                    @endphp
                    <button type="button" class="btn btn-primary" onclick="openMaxAsesiGlobalModal('{{ $globalMax }}')" style="padding: 10px 16px; font-size: 14px; white-space: nowrap; height: 100%;">
                        <i class="bi bi-gear-fill"></i> Atur Batas Global
                    </button>
                </div>
            </div>
            </form>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;">
                <button type="button" class="btn btn-primary" id="asesor-import-btn" onclick="openAsesorImportModal()">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import Excel/CSV
                </button>
                <button type="button" class="btn btn-outline" id="asesor-export-btn" onclick="openAsesorExportModal()">
                    <i class="bi bi-download"></i> Export Data Asesor
                </button>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NAMA ASESOR</th>
                            <th>SKEMA</th>
                            <th>NO. MET / AKUN</th>
                            <th>BATAS ASESI</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asesor as $item)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        @if($item->foto_profil)
                                            <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="{{ $item->nama }}">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=3b82f6&color=fff" alt="{{ $item->nama }}">
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $item->nama }}</div>
                                        
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->skemas->count() > 0)
                                    @foreach($item->skemas as $skema)
                                        <span class="expertise-text">{{ $skema->nama_skema }}</span>@if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    <span style="color:#94a3b8;font-size:13px;">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:600;color:#1e293b;">{{ $item->no_met ?? '—' }}</div>
                            </td>
                            <td>
                                @php
                                    $globalMax = \App\Models\Setting::get('max_asesi_per_asesor');
                                @endphp
                                <div style="font-size:13px;font-weight:600;color:#334155;">
                                    @if($globalMax)
                                        {{ $globalMax }} asesi
                                    @else
                                        <span style="color:#94a3b8;font-weight:normal;">Tidak dibatasi</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-active">AKTIF</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(event, this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.asesor.show', $item->ID_asesor) }}">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('admin.asesor.edit', $item->ID_asesor) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.asesor.reset-password', $item->ID_asesor) }}" method="POST" style="margin:0;" onsubmit="return confirm('Reset password asesor {{ $item->nama }} ke No. Met?')">
                                            @csrf
                                            <button type="submit">
                                                <i class="bi bi-key"></i> Reset Password
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.asesor.destroy', $item->ID_asesor) }}" method="POST" style="margin:0;" onsubmit="return openAsesorDeleteModal(event, this, @js('Hapus asesor ' . $item->nama . '?'))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px 20px;">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
                                <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data asesor ditemukan</h4>
                                <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $asesor->firstItem() ?? 0 }} sampai {{ $asesor->lastItem() ?? 0 }} dari {{ $asesor->total() }} data
                </div>
                <div class="pagination">
                    @if($asesor->currentPage() > 1)
                        <a href="{{ $asesor->previousPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif
                    
                    @for($i = 1; $i <= min($asesor->lastPage(), 5); $i++)
                        <a href="{{ $asesor->url($i) }}" class="page-link {{ $i == $asesor->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($asesor->lastPage() > 5)
                        <span class="page-dots">...</span>
                        <a href="{{ $asesor->url($asesor->lastPage()) }}" class="page-link">{{ $asesor->lastPage() }}</a>
                    @endif
                    
                    @if($asesor->hasMorePages())
                        <a href="{{ $asesor->nextPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="asesor-delete-confirm-overlay" class="asesor-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="asesorDeleteConfirmTitle" aria-hidden="true">
    <div class="asesor-delete-confirm-modal">
        <h3 id="asesorDeleteConfirmTitle" class="asesor-delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="asesorDeleteConfirmText" class="asesor-delete-confirm-text">Apakah Anda yakin?</p>
        <div class="asesor-delete-confirm-actions">
            <button type="button" id="asesorDeleteConfirmCancel" class="asesor-delete-btn-cancel">Batal</button>
            <button type="button" id="asesorDeleteConfirmSubmit" class="asesor-delete-btn-submit">Hapus</button>
        </div>
    </div>
</div><!-- Modal Atur Batas Asesi Global -->
<div id="asesor-limit-overlay" class="asesor-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="asesorLimitTitle" aria-hidden="true">
    <div class="asesor-delete-confirm-modal" style="max-width: 480px;">
        <h3 id="asesorLimitTitle" class="asesor-delete-confirm-title" style="margin-bottom: 12px;">Atur Batas Asesi Global</h3>
        <p style="font-size: 13px; color: #64748b; margin: 0 0 16px;">
            Atur jumlah maksimal asesi yang dapat ditangani oleh semua asesor secara general.
        </p>
        <form id="asesorLimitForm" action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group" style="margin-bottom: 16px; display: flex; flex-direction: column;">
                <label for="modal_max_asesi" style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                    Jumlah Maksimal Asesi per Asesor <span style="font-weight:400;font-size:12px;color:#94a3b8;">(Kosongkan jika tidak dibatasi)</span>
                </label>
                <input type="number" id="modal_max_asesi" name="max_asesi_per_asesor" class="form-control" placeholder="Tidak dibatasi" min="1" max="9999" style="padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #f8fafc; outline: none; transition: border-color 0.2s;">
                <small style="font-size: 12px; color: #64748b; margin-top: 6px;">
                    Batas ini berlaku umum untuk semua asesor yang terdaftar dalam sistem.
                </small>
            </div>
            <div class="asesor-delete-confirm-actions" style="margin-top: 20px;">
                <button type="button" onclick="closeMaxAsesiGlobalModal()" style="border: 1px solid #cbd5e1; background: #fff; color: #475569; border-radius: 8px; padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Batal</button>
                <button type="submit" style="background: #0073bd; border: 1px solid #0073bd; color: #fff; border-radius: 8px; padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Simpan Batas</button>
            </div>
        </form>
    </div>
</div>

<!-- ASESOR EXPORT MODAL -->
<div id="asesor-export-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:520px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-download" style="color:#0073bd;"></i> Export Data Asesor
            </h3>
            <button onclick="closeAsesorExportModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="asesor-export-form" action="{{ route('admin.asesor.export') }}" method="GET">
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Filter Skema (bisa pilih lebih dari satu)</label>
                <div id="asesor-export-skema-dropdown" style="position:relative;">
                    <button type="button" id="asesor-export-skema-toggle"
                            style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;text-align:left;display:flex;align-items:center;justify-content:space-between;">
                        <span id="asesor-export-skema-toggle-text">Semua skema</span>
                        <i class="bi bi-chevron-down" style="color:#64748b;"></i>
                    </button>
                    <div id="asesor-export-skema-menu"
                         style="display:none;position:absolute;left:0;right:0;top:calc(100% + 6px);z-index:20;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:6px;box-shadow:0 8px 24px rgba(15,23,42,.15);min-width:100%;">
                        <div style="padding:4px 4px 6px;">
                            <input type="text" id="asesor-export-skema-search" placeholder="Cari skema..." autocomplete="off"
                                   style="width:100%;padding:8px 10px;border:1px solid #dbe3ee;border-radius:7px;font-size:13px;outline:none;">
                        </div>
                        <label style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;background:#f0f9ff;">
                            <input type="checkbox" id="asesor-export-skema-all-cb" style="width:16px;height:16px;accent-color:#0073bd;" checked>
                            <span style="font-size:13px;font-weight:600;color:#0073bd;">Semua Skema</span>
                        </label>
                        <div style="height:1px;background:#e2e8f0;margin:4px 0;"></div>
                        <div id="asesor-export-skema-options" style="max-height:160px;overflow-y:auto;">
                            @foreach($skemaList as $skema)
                                <label class="asesor-export-skema-item"
                                       data-label="{{ strtolower($skema->nama_skema) }}"
                                       style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;width:100%;box-sizing:border-box;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <input type="checkbox" name="skema[]" class="asesor-export-skema-option"
                                           value="{{ $skema->id }}" data-label="{{ $skema->nama_skema }}" style="flex-shrink:0;">
                                    <span style="font-size:13px;color:#1e293b;overflow:hidden;text-overflow:ellipsis;">{{ $skema->nama_skema }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">Klik untuk pilih lebih dari satu skema.</small>
                <div id="asesor-export-skema-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
            </div>

            <div style="background:#eff6ff;border-left:3px solid #0073bd;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:12px;color:#0f3a5f;line-height:1.6;">
                Export memakai kolom: <strong>No, Nama Asesor, No. Met, Skema</strong>.
                Filter skema dapat dipakai untuk membatasi data yang diekspor.
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesorExportModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button id="asesor-export-submit-btn" type="submit"
                        style="flex:2;padding:10px;border-radius:8px;border:none;background:#0073bd;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-file-earmark-excel"></i> Export XLSX
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ASESOR IMPORT MODAL -->
<div id="asesor-import-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:520px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-file-earmark-arrow-up" style="color:#16a34a;"></i> Import Data Asesor
            </h3>
            <button onclick="closeAsesorImportModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <form id="asesor-import-form" action="{{ route('admin.asesor.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="asesor-import-drop" onclick="document.getElementById('asesor-import-file').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px;">
                <i class="bi bi-cloud-upload" style="font-size:36px;color:#9ca3af;display:block;margin-bottom:8px;"></i>
                <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Klik atau seret file ke sini</p>
                <p style="font-size:11px;color:#9ca3af;">.xlsx atau .csv &bull; Maks 5 MB</p>
                <p id="asesor-import-file-label" style="margin-top:8px;font-size:12px;font-weight:600;color:#16a34a;display:none;"></p>
            </div>
            <input type="file" id="asesor-import-file" name="file" accept=".xlsx,.csv" style="display:none;" onchange="onAsesorImportFileChange(this)" required>
            <div style="background:#f0fdf4;border-left:3px solid #14532d;padding:10px 14px;border-radius:6px;margin-bottom:12px;font-size:12px;color:#14532d;line-height:1.7;">
                <strong>Format kolom:</strong> Kolom A = Nama Asesor &bull; Kolom B = No. Met &bull; Kolom C = Skema (pisahkan dengan koma jika lebih dari satu)<br>
                Password default akun = No. Met.<br>
                <a href="{{ route('admin.asesor.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">
                     <i class="bi bi-download"></i> Download template XLSX
                </a>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesorImportModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button id="asesor-import-submit-btn" type="submit" style="flex:2;padding:10px;border-radius:8px;border:none;background:#14532d;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-upload"></i> Upload &amp; Import
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .asesor-management {
        padding: 0;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
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
        background: #005f9a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .btn-outline {
        background: #0073bd;
        color: white;
    }

    .btn-outline:hover {
        background: #005f9a;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
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
        text-decoration: none;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.2);
        transform: translateY(-2px);
        border-color: #bfdbfe;
    }

    .stat-card-active {
        border-color: #0073bd !important;
        box-shadow: 0 4px 16px rgba(0, 115, 189, 0.25) !important;
        background: #f0f9ff;
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
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.yellow { background: linear-gradient(135deg, #eab308, #ca8a04); }

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
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .stat-change {
        font-size: 12px;
        font-weight: 500;
    }

    .stat-change.positive {
        color: #10b981;
    }

    .stat-subtitle {
        font-size: 12px;
        font-weight: 400;
        color: #64748b;
    }

    /* Card */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 24px;
    }

    /* Filter Section */
    .filter-section {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .filter-group {
        display: flex;
        gap: 12px;
    }

    .filter-select {
        padding: 10px 36px 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s;
    }

    .filter-select:hover {
        border-color: #cbd5e1;
    }

    .btn-filter-search {
        padding: 9px 14px;
        background: #0073bd;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background 0.2s;
    }
    .btn-filter-search:hover { background: #005f99; }

    .btn-filter-reset {
        padding: 9px 12px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-filter-reset:hover { background: #fecaca; }

    /* Table */
    .table-container {
        overflow-x: auto;
        overflow-y: visible;
        padding-bottom: 200px;
        margin-bottom: -200px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .data-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        overflow: visible;
    }

    .data-table tbody tr {
        transition: background 0.2s;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #0F172A;
    }

    .user-id {
        font-size: 12px;
        color: #64748b;
    }

    .expertise-text {
        font-size: 14px;
        color: #475569;
    }

    /* Badge */
    .badge {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Action Menu */
    .action-menu {
        position: relative;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #f1f5f9;
    }

    .action-dropdown {
        display: none;
        position: fixed;
        margin-top: 4px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        min-width: 160px;
        z-index: 9990;
        overflow: visible;
    }

    .action-dropdown.show {
        display: block;
    }

    .action-dropdown a,
    .action-dropdown button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        border: none;
        background: none;
        text-align: left;
        font-size: 14px;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .action-dropdown form:not(:last-of-type) button:hover {
        background: #dcfce7;
        color: #16a34a;
    }

    .action-dropdown form:last-of-type button:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }

    .pagination {
        display: flex;
        gap: 4px;
    }

    .page-link {
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
        padding: 0 8px;
    }

    .page-link:hover {
        background: #f1f5f9;
        color: #0F172A;
    }

    .page-link.active {
        background: #0F172A;
        color: white;
    }

    .page-dots {
        display: flex;
        align-items: center;
        padding: 0 8px;
        color: #94a3b8;
    }

    .text-center {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .asesor-delete-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 10000;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .asesor-delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .asesor-delete-confirm-modal {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.3);
        transform: translateY(10px) scale(0.96);
        opacity: 0.92;
        transition: transform 0.22s ease, opacity 0.22s ease;
    }

    .asesor-delete-confirm-overlay.show .asesor-delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .asesor-delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .asesor-delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .asesor-delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .asesor-delete-btn-cancel,
    .asesor-delete-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
    }

    .asesor-delete-btn-cancel {
        background: #0073bd;
    }
    .asesor-delete-btn-cancel:hover {
        background: #005f99;
    }

    .asesor-delete-btn-submit {
        background: #0073bd;
    }
    .asesor-delete-btn-submit:hover {
        background: #005f99;
    }

    @media (prefers-reduced-motion: reduce) {
        .asesor-delete-confirm-overlay,
        .asesor-delete-confirm-modal {
            transition: none;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
            gap: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
            border-radius: 10px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    let pendingAsesorDeleteForm = null;

    function openAsesorDeleteModal(event, form, message) {
        if (event) {
            event.preventDefault();
        }

        pendingAsesorDeleteForm = form;

        const overlay = document.getElementById('asesor-delete-confirm-overlay');
        const text = document.getElementById('asesorDeleteConfirmText');
        if (!overlay || !text) return false;

        text.textContent = message || 'Apakah Anda yakin?';
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');

        return false;
    }

    function closeAsesorDeleteModal() {
        const overlay = document.getElementById('asesor-delete-confirm-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        pendingAsesorDeleteForm = null;
    }

    const asesorDeleteOverlay = document.getElementById('asesor-delete-confirm-overlay');
    const asesorDeleteCancelBtn = document.getElementById('asesorDeleteConfirmCancel');
    const asesorDeleteSubmitBtn = document.getElementById('asesorDeleteConfirmSubmit');

    asesorDeleteCancelBtn?.addEventListener('click', closeAsesorDeleteModal);

    asesorDeleteOverlay?.addEventListener('click', function(event) {
        if (event.target === asesorDeleteOverlay) {
            closeAsesorDeleteModal();
        }
    });

    asesorDeleteSubmitBtn?.addEventListener('click', function() {
        if (!pendingAsesorDeleteForm) return;
        const formToSubmit = pendingAsesorDeleteForm;
        closeAsesorDeleteModal();
        formToSubmit.submit();
    });

    function openMaxAsesiGlobalModal(currentLimit) {
        const overlay = document.getElementById('asesor-limit-overlay');
        const input = document.getElementById('modal_max_asesi');
        if (!overlay || !input) return;

        input.value = currentLimit ? currentLimit : '';

        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
    }

    function closeMaxAsesiGlobalModal() {
        const overlay = document.getElementById('asesor-limit-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAsesorDeleteModal();
            closeMaxAsesiGlobalModal();
        }
    });

    const asesorLimitOverlay = document.getElementById('asesor-limit-overlay');
    asesorLimitOverlay?.addEventListener('click', function(event) {
        if (event.target === asesorLimitOverlay) {
            closeMaxAsesiGlobalModal();
        }
    });

    function toggleMenu(event, button) {
        event.stopPropagation();
        const dropdown = button.nextElementSibling;
        const isOpen = dropdown.classList.contains('show');

        // Close all open dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.style.top = '';
            d.style.left = '';
        });

        if (!isOpen) {
            const rect = button.getBoundingClientRect();
            dropdown.classList.add('show');
            // Position below the button, aligned to its right edge
            const dropW = 160;
            let left = rect.right - dropW;
            if (left < 8) left = 8;
            dropdown.style.top  = (rect.bottom + 4) + 'px';
            dropdown.style.left = left + 'px';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(d => {
                d.classList.remove('show');
                d.style.top = '';
                d.style.left = '';
            });
        }
    });

    // Submit filter form on Enter key
    const searchInputAsesor = document.querySelector('input[name="search"]');
    if (searchInputAsesor) {
        searchInputAsesor.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performAjaxSearch();
            }
        });
        // Add real-time search on input
        searchInputAsesor.addEventListener('input', function(e) {
            performAjaxSearch();
        });
    }

    // Perform AJAX search and filter
    function performAjaxSearch() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        
        fetch('{{ route("admin.asesor.index") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Replace table body with new rows
            const tableBody = document.querySelector('.data-table tbody');
            if (tableBody) {
                tableBody.innerHTML = html;
            }
            // Re-attach event listeners to action menus
            attachActionMenuListeners();
        })
        .catch(error => console.error('Search error:', error));
    }

    // Attach click handlers to action menus
    function attachActionMenuListeners() {
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.removeEventListener('click', toggleMenu);
            btn.addEventListener('click', function() { toggleMenu(this); });
        });
    }

    // Initial attachment
    attachActionMenuListeners();

    // Reset filters
    function resetFilters() {
        document.querySelector('input[name="search"]').value = '';
        document.querySelector('select[name="keahlian"]').value = '';
        performAjaxSearch();
    }

    // ===== ASESOR EXPORT MODAL =====
    function openAsesorExportModal() {
        // Reset skema checkboxes
        document.querySelectorAll('.asesor-export-skema-option').forEach(cb => cb.checked = false);
        var allCb = document.getElementById('asesor-export-skema-all-cb');
        if (allCb) allCb.checked = true;
        var searchEl = document.getElementById('asesor-export-skema-search');
        if (searchEl) searchEl.value = '';
        applyAsesorExportSkemaSearch();
        renderAsesorExportBadges();
        document.getElementById('asesor-export-modal').style.display = 'flex';
        // close skema menu if open
        var menu = document.getElementById('asesor-export-skema-menu');
        if (menu) menu.style.display = 'none';
    }

    function closeAsesorExportModal() {
        document.getElementById('asesor-export-modal').style.display = 'none';
        var menu = document.getElementById('asesor-export-skema-menu');
        if (menu) menu.style.display = 'none';
    }

    function applyAsesorExportSkemaSearch() {
        var q = (document.getElementById('asesor-export-skema-search')?.value || '').toLowerCase();
        document.querySelectorAll('.asesor-export-skema-item').forEach(function(item) {
            var lbl = item.getAttribute('data-label') || '';
            item.style.display = lbl.includes(q) ? '' : 'none';
        });
    }

    function renderAsesorExportBadges() {
        var container = document.getElementById('asesor-export-skema-badges');
        if (!container) return;
        container.innerHTML = '';
        var checked = Array.from(document.querySelectorAll('.asesor-export-skema-option:checked'));
        if (checked.length === 0) {
            var toggleText = document.getElementById('asesor-export-skema-toggle-text');
            if (toggleText) toggleText.textContent = 'Semua skema';
        } else {
            var toggleText = document.getElementById('asesor-export-skema-toggle-text');
            if (toggleText) toggleText.textContent = checked.length + ' skema dipilih';
            checked.forEach(function(cb) {
                var badge = document.createElement('span');
                badge.style.cssText = 'background:#dbeafe;color:#1d4ed8;border-radius:20px;padding:3px 10px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:5px;';
                badge.innerHTML = (cb.getAttribute('data-label') || cb.value) + ' <span style="cursor:pointer;font-size:14px;" data-val="' + cb.value + '">&times;</span>';
                badge.querySelector('span').addEventListener('click', function() {
                    var val = this.getAttribute('data-val');
                    var el = document.querySelector('.asesor-export-skema-option[value="' + val + '"]');
                    if (el) el.checked = false;
                    var allCb = document.getElementById('asesor-export-skema-all-cb');
                    if (document.querySelectorAll('.asesor-export-skema-option:checked').length === 0 && allCb) {
                        allCb.checked = true;
                    }
                    renderAsesorExportBadges();
                });
                container.appendChild(badge);
            });
        }
    }

    // Toggle skema dropdown
    var asesorExportSkemaToggle = document.getElementById('asesor-export-skema-toggle');
    var asesorExportSkemaMenu   = document.getElementById('asesor-export-skema-menu');
    var asesorExportSkemaSearch = document.getElementById('asesor-export-skema-search');

    if (asesorExportSkemaToggle && asesorExportSkemaMenu) {
        asesorExportSkemaToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = asesorExportSkemaMenu.style.display === 'block';
            asesorExportSkemaMenu.style.display = isOpen ? 'none' : 'block';
            if (!isOpen && asesorExportSkemaSearch) asesorExportSkemaSearch.focus();
        });
    }

    if (asesorExportSkemaSearch) {
        asesorExportSkemaSearch.addEventListener('input', function() {
            applyAsesorExportSkemaSearch();
        });
    }

    // Individual skema checkboxes
    document.querySelectorAll('.asesor-export-skema-option').forEach(function(el) {
        el.addEventListener('change', function() {
            var allCb = document.getElementById('asesor-export-skema-all-cb');
            if (allCb) allCb.checked = false;
            if (document.querySelectorAll('.asesor-export-skema-option:checked').length === 0 && allCb) {
                allCb.checked = true;
            }
            renderAsesorExportBadges();
        });
    });

    // "Semua Skema" checkbox
    var asesorExportSkemaAllCb = document.getElementById('asesor-export-skema-all-cb');
    if (asesorExportSkemaAllCb) {
        asesorExportSkemaAllCb.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.asesor-export-skema-option').forEach(function(cb) { cb.checked = false; });
            }
            renderAsesorExportBadges();
        });
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#asesor-export-skema-dropdown')) {
            if (asesorExportSkemaMenu) asesorExportSkemaMenu.style.display = 'none';
        }
    });

    // Close export modal on overlay click
    document.getElementById('asesor-export-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeAsesorExportModal();
    });

    // Close export modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeAsesorExportModal();
    });

    // Loading state on submit
    var asesorExportForm = document.getElementById('asesor-export-form');
    if (asesorExportForm) {
        asesorExportForm.addEventListener('submit', function() {
            var btn = document.getElementById('asesor-export-submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
                setTimeout(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-file-earmark-excel"></i> Export XLSX';
                    closeAsesorExportModal();
                }, 3000);
            }
        });
    }

    // ===== ASESOR IMPORT MODAL =====
    function openAsesorImportModal() {
        var fileInput = document.getElementById('asesor-import-file');
        if (fileInput) fileInput.value = '';
        var label = document.getElementById('asesor-import-file-label');
        if (label) {
            label.textContent = '';
            label.style.display = 'none';
        }
        document.getElementById('asesor-import-modal').style.display = 'flex';
    }

    function closeAsesorImportModal() {
        document.getElementById('asesor-import-modal').style.display = 'none';
    }

    function onAsesorImportFileChange(input) {
        var label = document.getElementById('asesor-import-file-label');
        if (input.files && input.files.length > 0) {
            label.textContent = 'File terpilih: ' + input.files[0].name;
            label.style.display = 'block';
        } else {
            label.textContent = '';
            label.style.display = 'none';
        }
    }

    // Drag and drop for Asesor Import Modal
    var asesorDropZone = document.getElementById('asesor-import-drop');
    if (asesorDropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            asesorDropZone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                asesorDropZone.style.background = '#e0f2fe';
                asesorDropZone.style.borderColor = '#0073bd';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            asesorDropZone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                asesorDropZone.style.background = '#fafafa';
                asesorDropZone.style.borderColor = '#d1d5db';
            }, false);
        });

        asesorDropZone.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            var fileInput = document.getElementById('asesor-import-file');
            if (fileInput && files.length > 0) {
                fileInput.files = files;
                onAsesorImportFileChange(fileInput);
            }
        }, false);
    }

    // Submit loading state
    var asesorImportForm = document.getElementById('asesor-import-form');
    if (asesorImportForm) {
        asesorImportForm.addEventListener('submit', function() {
            var btn = document.getElementById('asesor-import-submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            }
        });
    }

    // Close import modal on overlay click
    document.getElementById('asesor-import-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeAsesorImportModal();
    });
</script>
@endsection
