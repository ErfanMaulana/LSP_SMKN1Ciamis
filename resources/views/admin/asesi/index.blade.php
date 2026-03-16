@extends('admin.layout')

@section('title', 'Manajemen Asesi')
@section('page-title', 'Manajemen Asesi')

@section('content')
<div class="asesi-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Manajemen Asesi</h2>
            <p class="subtitle">Kelola dan pantau semua kandidat dalam sistem sertifikasi.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.asesi.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Asesi Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <a href="{{ route('admin.asesi.index') }}" class="stat-card {{ $cardFilter == '' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL ASESI</div>
                <div class="stat-value">{{ number_format($totalAsesi) }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.index', ['card_filter' => 'this_year']) }}" class="stat-card {{ $cardFilter == 'this_year' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-person-plus"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TERDAFTAR TAHUN INI</div>
                <div class="stat-value">{{ number_format($registeredThisYear) }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.index', ['card_filter' => 'in_assessment']) }}" class="stat-card {{ $cardFilter == 'in_assessment' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DALAM PENILAIAN</div>
                <div class="stat-value">{{ number_format($inAssessment) }} <span class="stat-subtitle">Aktif</span></div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.index', ['card_filter' => 'certified']) }}" class="stat-card {{ $cardFilter == 'certified' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-award"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TERSERTIFIKASI</div>
                <div class="stat-value">{{ number_format($certified) }} <span class="stat-subtitle">Total</span></div>
            </div>
        </a>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('tab-asesi', this)">
            <i class="bi bi-people"></i> Data Asesi
        </button>
        <button class="tab-btn" onclick="switchTab('tab-akun-orphan', this)">
            <i class="bi bi-person-x"></i> Belum Aktivasi
            @if($akunTanpaAsesi->count() > 0)
                <span class="tab-badge">{{ $akunTanpaAsesi->count() }}</span>
            @endif
        </button>
    </div>

    <!-- Tab 1: Data Asesi -->
    <div id="tab-asesi" class="tab-pane">
    @if(session('import_errors_activated') && count(session('import_errors_activated')))
        <div style="background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;margin-bottom:20px;border-left:4px solid #f59e0b;">
            <div style="display:flex;align-items:start;gap:12px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:18px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;color:#1e293b;font-size:13px;margin-bottom:6px;">Catatan Import Data Aktivasi</div>
                    <ul style="margin:0;padding-left:16px;line-height:1.7;color:#64748b;font-size:12px;max-height:220px;overflow:auto;">
                        @foreach(session('import_errors_activated') as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.asesi.index') }}" id="filterForm">
            @if($cardFilter)
                <input type="hidden" name="card_filter" value="{{ $cardFilter }}">
            @endif
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Cari berdasarkan nama atau NIK..."
                           value="{{ request('search') }}" autocomplete="off">
                </div>
                <div class="filter-group">
                    <select class="filter-select" name="jurusan" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList as $jur)
                            <option value="{{ $jur->ID_jurusan }}"
                                {{ request('jurusan') == $jur->ID_jurusan ? 'selected' : '' }}>
                                {{ $jur->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                    <select class="filter-select" name="status" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="button" class="btn-filter-search" onclick="performAjaxSearch()">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search') || request('jurusan') || request('status'))
                    <button type="button" class="btn-filter-reset" title="Reset filter" onclick="resetFilters()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    @endif
                </div>
            </div>
            </form>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;">
                <button type="button" id="toggle-bulk-mode-data"
                    style="padding:7px 12px;background:#fff;color:#64748b;border:1px solid #cbd5e1;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-ui-checks-grid"></i> Bulk
                </button>
                <button type="button" class="btn btn-outline" onclick="openAsesiExportModal()">
                    <i class="bi bi-download"></i> Export Data Asesi
                </button>
            </div>

            <!-- Bulk Action Bar -->
            <div id="bulk-action-bar-data" style="display:none;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-bottom:16px;align-items:center;gap:12px;flex-wrap:wrap;">
                <span id="bulk-count-text-data" style="font-size:14px;color:#0073bd;font-weight:600;">0 item dipilih</span>
                <div style="flex:1;"></div>
                <button type="button" onclick="openBulkDeleteModalData()"
                    style="padding:8px 18px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-trash"></i> Hapus Pilihan
                </button>
                <button type="button" onclick="clearBulkSelectionData()"
                    style="padding:8px 14px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">
                    Batal
                </button>
            </div>

            <!-- Bulk Delete Hidden Form -->
            <form id="bulk-delete-form-data" method="POST" action="{{ route('admin.asesi.bulk-delete') }}" style="display:none;">
                @csrf
                <div id="bulk-delete-niks-data"></div>
            </form>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="bulk-col-data" style="display:none;width:44px;text-align:center;">
                                <input type="checkbox" id="bulk-select-all-data" title="Pilih semua"
                                    style="width:16px;height:16px;cursor:pointer;accent-color:#0073bd;">
                            </th>
                            <th>NAMA</th>
                            <th>SKEMA/PROGRAM</th>
                            <th>AKUN</th>
                            <th>STATUS PENILAIAN</th>
                            <th>TANGGAL TERDAFTAR</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asesi as $item)
                        <tr>
                            <td class="bulk-col-data" style="display:none;text-align:center;">
                                <input type="checkbox" class="bulk-checkbox-data" value="{{ $item->NIK }}"
                                    style="width:16px;height:16px;cursor:pointer;accent-color:#0073bd;">
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar-initials">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $item->nama }}</div>
                                        <div class="user-id">{{ $item->NIK }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="scheme-text">{{ $item->jurusan->nama_jurusan ?? 'Belum Ditentukan' }}</span>
                            </td>
                            <td>
                                @if($item->account)
                                    <div style="font-size:12px;font-weight:600;color:#1e293b;font-family:monospace;">{{ $item->NIK }}</div>
                                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Password awal: NIK</div>
                                @else
                                    <span style="font-size:11px;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;">Belum ada akun</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($item->status ?? '') {
                                        'approved' => 'badge-success',
                                        'pending'  => 'badge-warning',
                                        'rejected' => 'badge-danger',
                                        default    => 'badge-info',
                                    };
                                    $statusLabel = match($item->status ?? '') {
                                        'approved' => 'Disetujui',
                                        'pending'  => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default    => 'Dalam Proses',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <span class="date-text">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(event, this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('admin.asesi.edit', $item->NIK) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.asesi.destroy', $item->NIK) }}" method="POST" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus asesi {{ addslashes($item->nama) }}?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data asesi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $asesi->firstItem() ?? 0 }} sampai {{ $asesi->lastItem() ?? 0 }} dari {{ $asesi->total() }} entri
                </div>
                <div class="pagination">
                    @if($asesi->currentPage() > 1)
                        <a href="{{ $asesi->previousPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif
                    
                    @for($i = 1; $i <= min($asesi->lastPage(), 5); $i++)
                        <a href="{{ $asesi->url($i) }}" class="page-link {{ $i == $asesi->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($asesi->lastPage() > 5)
                        <span class="page-dots">...</span>
                        <a href="{{ $asesi->url($asesi->lastPage()) }}" class="page-link">{{ $asesi->lastPage() }}</a>
                    @endif
                    
                    @if($asesi->hasMorePages())
                        <a href="{{ $asesi->nextPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
                <form method="GET" action="{{ route('admin.asesi.index') }}" style="display:flex;align-items:center;gap:6px;">
                    @foreach(request()->except('page', 'per_page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <span style="font-size:12px;color:#64748b;">Per halaman</span>
                    <input type="number" id="per-page-input" name="per_page" min="5" max="100" value="{{ request('per_page', 10) }}"
                        style="width:72px;height:34px;border:1px solid #e2e8f0;border-radius:8px;padding:0 8px;font-size:13px;color:#334155;">
                </form>
            </div>
        </div>
    </div><!-- /.card -->
    </div><!-- /#tab-asesi.tab-pane -->

    <!-- Tab 2: Akun Tanpa Data Asesi -->
    <div id="tab-akun-orphan" class="tab-pane" style="display:none;">

    {{-- Flash notifications (shown when redirected here after import) --}}
    @if(session('success') && request('tab') === 'akun')
        <div id="toast-import-success" style="display:flex;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;align-items:center;gap:12px;margin-bottom:20px;border-left:4px solid #10b981;">
            <div style="width:36px;height:36px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:18px;"></i>
            </div>
            <div style="flex:1;font-size:13px;color:#065f46;">{{ session('success') }}</div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;line-height:1;"><i class="bi bi-x"></i></button>
        </div>
    @endif
    @if(session('error') && request('tab') === 'akun')
        <div id="toast-import-error" style="display:flex;background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;align-items:center;gap:12px;margin-bottom:20px;border-left:4px solid #ef4444;">
            <div style="width:36px;height:36px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-exclamation-circle-fill" style="color:#ef4444;font-size:18px;"></i>
            </div>
            <div style="flex:1;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            <button onclick="this.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;line-height:1;"><i class="bi bi-x"></i></button>
        </div>
    @endif
    @if(session('import_errors') && count(session('import_errors')) && request('tab') === 'akun')
        <div id="toast-import-warn" style="background:#fff;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);padding:16px 20px;margin-bottom:20px;border-left:4px solid #f59e0b;">
            <div style="display:flex;align-items:start;gap:12px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;font-size:18px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;color:#1e293b;font-size:13px;margin-bottom:6px;">Catatan Import</div>
                    <ul style="margin:0;padding-left:16px;line-height:1.7;color:#64748b;font-size:12px;">
                        @foreach(session('import_errors') as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;line-height:1;"><i class="bi bi-x"></i></button>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <p style="font-size:14px;color:#64748b;margin:0;">Akun berikut memiliki role <strong>asesi</strong> namun tidak memiliki data profil asesi yang terdaftar.</p>
                <div style="display:flex;gap:10px;">
                    <button class="btn btn-outline" onclick="openAsesiCreateModal()" style="white-space:nowrap;">
                        <i class="bi bi-person-plus"></i> Tambah Akun
                    </button>
                    <button class="btn btn-primary" onclick="openAsesiImportModal()" style="white-space:nowrap;">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import Excel/CSV
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NAMA</th>
                            <th>NIK / LOGIN ID</th>
                            <th>TANGGAL DIBUAT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akunTanpaAsesi as $akun)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar-initials" style="background:#fce7f3;color:#9d174d;">
                                        {{ strtoupper(substr($akun->nama ?? '?', 0, 2)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $akun->nama }}</div>
                                        <div class="user-id">ID: {{ $akun->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:600;color:#1e293b;font-family:monospace;">{{ $akun->NIK ?? $akun->id }}</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Password awal: NIK</div>
                            </td>
                            <td>
                                <span class="date-text">{{ $akun->created_at ? \Carbon\Carbon::parse($akun->created_at)->locale('id')->translatedFormat('d M Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(event, this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.asesi.create') }}?nik={{ urlencode($akun->NIK ?? $akun->id) }}&nama={{ urlencode($akun->nama) }}">
                                            <i class="bi bi-person-plus"></i> Buat Data Asesi
                                        </a>
                                        <form action="{{ route('admin.akun-asesi.destroy', $akun->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus akun {{ addslashes($akun->nama) }}?')">
                                                <i class="bi bi-trash"></i> Hapus Akun
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Semua akun sudah memiliki data asesi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div><!-- /#tab-akun-orphan.tab-pane -->

</div><!-- /.asesi-management -->

<!-- BULK DELETE MODAL -->
<div id="bulk-delete-modal-data" class="bulk-modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:560px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0 0 8px 0;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-trash" style="color:#dc2626;"></i>Hapus Asesi Terpilih
        </h3>
        <p style="margin:8px 0 16px 0;color:#94a3b8;font-size:13px;">
            <span id="bulk-delete-count-text-data">0 asesi akan dihapus</span>
        </p>
        <p style="color:#e11d48;font-size:13px;background:#fee2e2;padding:12px;border-radius:6px;margin-bottom:16px;">
            <i class="bi bi-exclamation-triangle"></i> Perhatian: Tindakan ini tidak dapat dibatalkan. Data asesi dan akun akan dihapus sepenuhnya.
        </p>
        <form id="bulk-delete-form-modal-data" method="POST" action="{{ route('admin.asesi.bulk-delete') }}">
            @csrf
            <div id="bulk-delete-niks-modal-data"></div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeBulkDeleteModalData()"
                    style="padding:9px 20px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;">Batal</button>
                <button type="submit"
                    style="padding:9px 20px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-trash"></i> Konfirmasi Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- IMPORT DATA AKTIVASI MODAL -->
<div id="asesi-activated-import-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:560px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-file-earmark-arrow-up" style="color:#0073bd;"></i> Import Data Asesi Aktivasi
            </h3>
            <button onclick="closeAsesiActivatedImportModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="asesi-activated-import-form" action="{{ route('admin.asesi.import-activated') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="asesi-activated-import-drop" onclick="document.getElementById('asesi-activated-import-file').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px;">
                <i class="bi bi-cloud-upload" style="font-size:36px;color:#9ca3af;display:block;margin-bottom:8px;"></i>
                <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Klik atau seret file ke sini</p>
                <p style="font-size:11px;color:#9ca3af;">.xlsx atau .csv - Maks 5 MB</p>
                <p id="asesi-activated-import-file-label" style="margin-top:8px;font-size:12px;font-weight:600;color:#16a34a;display:none;"></p>
            </div>

            <input type="file" id="asesi-activated-import-file" name="file" accept=".xlsx,.csv" style="display:none;" onchange="onAsesiActivatedImportFileChange(this)" required>

            <div style="background:#eff6ff;border-left:3px solid #0073bd;padding:10px 14px;border-radius:6px;margin-bottom:12px;font-size:12px;color:#0f3a5f;line-height:1.7;">
                <strong>Template kolom wajib:</strong><br>
                no, nama asesi, nik, tempat lahir, tanggal lahir, jenis kelamin, tempat tinggal, telp, email<br>
                <a href="{{ route('admin.asesi.template-activated') }}" style="color:#0073bd;font-weight:700;text-decoration:underline;">
                    <i class="bi bi-download"></i> Download template XLSX (header biru #0073bd)
                </a>
            </div>

            <div style="background:#fef3c7;border-left:3px solid #f59e0b;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:12px;color:#92400e;line-height:1.6;">
                Hanya data asesi yang sudah aktivasi yang akan diproses.
                Jika NIK belum punya akun asesi aktif atau belum ada data asesi, baris akan dilewati.
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesiActivatedImportModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button id="asesi-activated-import-submit-btn" type="submit" style="flex:2;padding:10px;border-radius:8px;border:none;background:#0073bd;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-upload"></i> Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EXPORT DATA ASESI MODAL -->
<div id="asesi-export-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:560px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-download" style="color:#0073bd;"></i> Export Data Asesi Aktivasi
            </h3>
            <button onclick="closeAsesiExportModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="asesi-export-form" action="{{ route('admin.asesi.export-activated') }}" method="GET">
            <div id="export-hidden-inputs"></div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Filter Jurusan (bisa pilih lebih dari satu)</label>
                <div id="export-jurusan-dropdown" style="position:relative;">
                    <button type="button" id="export-jurusan-toggle" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;text-align:left;display:flex;align-items:center;justify-content:space-between;">
                        <span id="export-jurusan-toggle-text">Pilih jurusan</span>
                        <i class="bi bi-chevron-down" style="color:#64748b;"></i>
                    </button>
                    <div id="export-jurusan-menu" style="display:none;position:absolute;left:0;right:0;top:calc(100% + 6px);z-index:20;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:6px;box-shadow:0 8px 24px rgba(15,23,42,.15);min-width:100%;">
                        <div style="padding:4px 4px 6px;">
                            <input type="text" id="export-jurusan-search" placeholder="Cari jurusan..." autocomplete="off"
                                   style="width:100%;padding:8px 10px;border:1px solid #dbe3ee;border-radius:7px;font-size:13px;outline:none;">
                        </div>
                        <label id="export-jurusan-all-label" style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;background:#f0f9ff;">
                            <input type="checkbox" id="export-jurusan-all-cb" style="width:16px;height:16px;accent-color:#0073bd;" checked>
                            <span style="font-size:13px;font-weight:600;color:#0073bd;">Semua Jurusan</span>
                        </label>
                        <div style="height:1px;background:#e2e8f0;margin:4px 0;"></div>
                        <div id="export-jurusan-options" style="max-height:145px;overflow-y:auto;">
                            @foreach($jurusanList as $jur)
                                <label class="export-jurusan-item" data-label="{{ strtolower($jur->nama_jurusan) }}" style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;width:100%;box-sizing:border-box;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <input type="checkbox" class="export-jurusan-option" value="{{ $jur->ID_jurusan }}" data-label="{{ $jur->nama_jurusan }}" style="flex-shrink:0;">
                                    <span style="font-size:13px;color:#1e293b;overflow:hidden;text-overflow:ellipsis;">{{ $jur->nama_jurusan }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">Klik untuk pilih lebih dari satu jurusan.</small>
                <div id="export-jurusan-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Filter Skema (bisa pilih lebih dari satu)</label>
                <div id="export-skema-dropdown" style="position:relative;">
                    <button type="button" id="export-skema-toggle" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;text-align:left;display:flex;align-items:center;justify-content:space-between;">
                        <span id="export-skema-toggle-text">Pilih skema</span>
                        <i class="bi bi-chevron-down" style="color:#64748b;"></i>
                    </button>
                    <div id="export-skema-menu" style="display:none;position:absolute;left:0;right:0;top:calc(100% + 6px);z-index:20;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:6px;box-shadow:0 8px 24px rgba(15,23,42,.15);min-width:100%;">
                        <div style="padding:4px 4px 6px;">
                            <input type="text" id="export-skema-search" placeholder="Cari skema..." autocomplete="off"
                                   style="width:100%;padding:8px 10px;border:1px solid #dbe3ee;border-radius:7px;font-size:13px;outline:none;">
                        </div>
                        <label id="export-skema-all-label" style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;background:#f0f9ff;">
                            <input type="checkbox" id="export-skema-all-cb" style="width:16px;height:16px;accent-color:#0073bd;" checked>
                            <span style="font-size:13px;font-weight:600;color:#0073bd;">Semua Skema</span>
                        </label>
                        <div style="height:1px;background:#e2e8f0;margin:4px 0;"></div>
                        <div id="export-skema-options" style="max-height:150px;overflow-y:auto;">
                            @foreach($skemaList as $skema)
                                <label class="export-skema-item" style="display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:6px;cursor:pointer;width:100%;box-sizing:border-box;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" data-jurusan="{{ $skema->jurusan_id ?? '' }}" data-label="{{ strtolower($skema->nama_skema) }}">
                                    <input type="checkbox" class="export-skema-option" value="{{ $skema->id }}" data-label="{{ $skema->nama_skema }}" data-jurusan="{{ $skema->jurusan_id ?? '' }}" style="flex-shrink:0;">
                                    <span style="font-size:13px;color:#1e293b;overflow:hidden;text-overflow:ellipsis;">{{ $skema->nama_skema }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">Klik untuk pilih lebih dari satu skema.</small>
                <div id="export-skema-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
            </div>

            <div style="margin-bottom:14px;">
                <label for="export-status-asesmen" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Filter Status Asesmen</label>
                <select id="export-status-asesmen" name="status_asesmen" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;color:#1e293b;">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">Bisa ditumpuk dengan filter jurusan dan skema.</small>
                <div id="export-status-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Filter Tanggal Daftar</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label for="export-tanggal-dari" style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;">Dari</label>
                        <input type="date" id="export-tanggal-dari" name="tanggal_dari" style="width:100%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;color:#1e293b;">
                    </div>
                    <div>
                        <label for="export-tanggal-sampai" style="display:block;font-size:12px;color:#64748b;margin-bottom:4px;">Sampai</label>
                        <input type="date" id="export-tanggal-sampai" name="tanggal_sampai" style="width:100%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;background:#fff;color:#1e293b;">
                    </div>
                </div>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">Bisa isi salah satu tanggal saja atau keduanya.</small>
                <div id="export-tanggal-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
            </div>

            <div style="background:#eff6ff;border-left:3px solid #0073bd;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:12px;color:#0f3a5f;line-height:1.6;">
                Export memakai template yang sama: no, nama asesi, nik, tempat lahir, tanggal lahir, jenis kelamin, tempat tinggal, telp, email.
                Filter jurusan, skema, status asesmen, dan tanggal daftar dapat dipakai bersamaan (ditumpuk).
            </div>

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesiExportModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button id="asesi-export-submit-btn" type="submit" style="flex:2;padding:10px;border-radius:8px;border:none;background:#0073bd;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-file-earmark-excel"></i> Export XLSX
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CREATE AKUN MODAL (from asesi tab) -->
<div id="asesi-create-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:480px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-person-plus" style="color:#2563eb;"></i> Tambah Akun Asesi
            </h3>
            <button onclick="closeAsesiCreateModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <form action="{{ route('admin.akun-asesi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="source" value="asesi_tab">
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">NIK <span style="color:#ef4444;">*</span></label>
                <input type="text" id="asesi-create-nik" name="NIK" required maxlength="16" minlength="16" pattern="\d{16}" inputmode="numeric"
                       placeholder="Masukkan 16 digit NIK"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:'Courier New',monospace;letter-spacing:1px;outline:none;box-sizing:border-box;">
                <small id="asesi-create-nik-error" style="display:none;color:#dc2626;font-size:12px;margin-top:6px;">NIK harus terdiri dari 16 digit angka.</small>
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#374151;">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama" required maxlength="255"
                       placeholder="Masukkan nama lengkap"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;outline:none;box-sizing:border-box;">
            </div>
            <div style="background:#eff6ff;border-left:3px solid #2563eb;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:12px;color:#1e40af;line-height:1.6;">
                <i class="bi bi-info-circle"></i>
                Password default = NIK. Asesi bisa login menggunakan NIK sebagai username dan password.
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesiCreateModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;border-radius:8px;border:none;background:#2563eb;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-check-lg"></i> Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- IMPORT MODAL (from asesi tab) -->
<div id="asesi-import-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:14px;padding:28px;width:100%;max-width:520px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;">
                <i class="bi bi-file-earmark-arrow-up" style="color:#16a34a;"></i> Import Akun Asesi
            </h3>
            <button onclick="closeAsesiImportModal()" style="background:none;border:none;font-size:20px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <form id="asesi-import-form" action="{{ route('admin.akun-asesi.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="source" value="asesi_tab">
            <div id="asesi-import-drop" onclick="document.getElementById('asesi-import-file').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px;">
                <i class="bi bi-cloud-upload" style="font-size:36px;color:#9ca3af;display:block;margin-bottom:8px;"></i>
                <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Klik atau seret file ke sini</p>
                <p style="font-size:11px;color:#9ca3af;">.xlsx atau .csv &bull; Maks 5 MB</p>
                <p id="asesi-import-file-label" style="margin-top:8px;font-size:12px;font-weight:600;color:#16a34a;display:none;"></p>
            </div>
            <input type="file" id="asesi-import-file" name="file" accept=".xlsx,.csv" style="display:none;" onchange="onAsesiImportFileChange(this)" required>
            <div style="background:#f0fdf4;border-left:3px solid #14532d;padding:10px 14px;border-radius:6px;margin-bottom:12px;font-size:12px;color:#14532d;line-height:1.7;">
                <strong>Format kolom:</strong> Kolom A = NIK (16 digit) &bull; Kolom B = Nama<br>
                Password default akun = NIK.
                <a href="{{ route('admin.akun-asesi.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">
                    <i class="bi bi-download"></i> Download template XLSX
                </a>
            </div>
            <div style="background:#eff6ff;border-left:3px solid #0061A5;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:12px;color:#1e40af;line-height:1.7;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-lightbulb" style="font-size:16px;color:#0061A5;flex-shrink:0;"></i>
                <span>NIK berubah jadi angka aneh (1.23E+15)?
                    <a href="javascript:void(0)" onclick="closeAsesiImportModal();openAsesiTutorialModal()" style="color:#0061A5;font-weight:700;text-decoration:underline;">
                        Lihat tutorial format Text di Excel <i class="bi bi-arrow-right"></i>
                    </a>
                </span>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAsesiImportModal()" style="flex:1;padding:10px;border-radius:8px;border:1px solid #e2e8f0;background:white;font-size:14px;font-weight:600;cursor:pointer;color:#64748b;">Batal</button>
                <button id="asesi-import-submit-btn" type="submit" style="flex:2;padding:10px;border-radius:8px;border:none;background:#14532d;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="bi bi-upload"></i> Upload &amp; Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TUTORIAL MODAL (from asesi tab) -->
<div id="asesi-tutorial-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:10000;align-items:center;justify-content:center;overflow-y:auto;padding:20px 0;">
    <div style="background:white;border-radius:16px;width:100%;max-width:580px;margin:20px auto;box-shadow:0 25px 60px rgba(0,0,0,.25);max-height:90vh;display:flex;flex-direction:column;">
        <div style="padding:24px 28px 16px;border-bottom:1px solid #e2e8f0;flex-shrink:0;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:17px;font-weight:700;color:#1e293b;margin:0;display:flex;align-items:center;gap:10px;">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#eff6ff;border-radius:10px;">
                    <i class="bi bi-mortarboard" style="font-size:18px;color:#0061A5;"></i>
                </span>
                Tutorial: Format NIK sebagai Text
            </h3>
            <button onclick="closeAsesiTutorialModal()" style="background:none;border:none;font-size:22px;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:20px 28px;overflow-y:auto;flex:1;">
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;margin-bottom:16px;display:flex;gap:10px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:18px;flex-shrink:0;"></i>
                <div style="font-size:12px;color:#14532d;line-height:1.6;"><strong>Cara paling mudah:</strong> Gunakan <a href="{{ route('admin.akun-asesi.template') }}" style="color:#14532d;font-weight:700;text-decoration:underline;">template XLSX</a> — kolom NIK sudah otomatis diformat sebagai Text.</div>
            </div>
            <p style="font-size:12px;font-weight:700;color:#475569;margin:0 0 12px;text-transform:uppercase;letter-spacing:0.5px;">Jika membuat file sendiri:</p>
            @foreach([['1','Seleksi kolom NIK','Klik header kolom A untuk menyeleksi seluruh kolom NIK.'],['2','Klik kanan → Format Cells','Klik kanan pada kolom yang dipilih, pilih "Format Cells..."'],['3','Pilih kategori "Text"','Pada tab Number, pilih kategori Text lalu klik OK.'],['4','Ketik ulang NIK','NIK yang sudah menjadi angka harus diketik ulang agar tersimpan sebagai teks.']] as [$n,$title,$desc])
            <div style="display:flex;gap:12px;margin-bottom:16px;">
                <div style="flex-shrink:0;width:28px;height:28px;background:#0061A5;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">{{ $n }}</div>
                <div><h4 style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 4px;">{{ $title }}</h4><p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">{{ $desc }}</p></div>
            </div>
            @endforeach
            <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:12px;font-size:12px;color:#92400e;line-height:1.6;">
                <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i>
                <strong>Trik cepat:</strong> Awali NIK dengan tanda petik satu <code style="background:#dbeafe;color:#1e40af;padding:1px 6px;border-radius:4px;">'1234567898765430</code> — Excel akan menyimpannya sebagai teks. Tanda petik tidak masuk ke data.
            </div>
        </div>
        <div style="padding:16px 28px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
            <a href="{{ route('admin.akun-asesi.template') }}" style="font-size:13px;color:#0061A5;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-download"></i> Download Template XLSX
            </a>
            <button onclick="closeAsesiTutorialModal()" style="padding:10px 24px;border-radius:8px;border:none;background:#0061A5;color:white;font-size:14px;font-weight:600;cursor:pointer;">
                <i class="bi bi-check-lg"></i> Mengerti
            </button>
        </div>
    </div>
</div>

<style>
    .asesi-management {
        padding: 0;
    }

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

    .header-actions {
        display: flex;
        gap: 12px;
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

    .btn-outline {
        background: #0073bd;
        color: white;
    }

    .btn-outline:hover {
        background: #003961;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
        flex-shrink: 0;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.yellow { background: linear-gradient(135deg, #eab308, #ca8a04); }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 10px;
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
        flex-wrap: wrap;
    }

    .stat-change {
        font-size: 12px;
        font-weight: 500;
    }

    .stat-change.positive {
        color: #10b981;
    }

    .stat-change.negative {
        color: #ef4444;
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
        flex-wrap: wrap;
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

    .user-avatar-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        flex-shrink: 0;
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

    .scheme-text {
        font-size: 14px;
        color: #475569;
    }

    .date-text {
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
        text-transform: capitalize;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #dbeafe;
        color: #004a7a;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
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
        overflow: show;
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
        flex-wrap: wrap;
        gap: 12px;
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

    /* Tabs */
    .tab-nav {
        display: flex;
        gap: 4px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0;
    }

    .tab-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        border-radius: 6px 6px 0 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: #0073bd;
        background: #f0f9ff;
    }

    .tab-btn.active {
        color: #0073bd;
        border-bottom-color: #0073bd;
        font-weight: 600;
        background: #f0f9ff;
    }

    .tab-badge {
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 20px;
        min-width: 20px;
        text-align: center;
    }

    .tab-pane {
        /* visible by default for tab-asesi; hidden via inline style for others */
    }
</style>

<script>
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

    // Close dropdown on scroll (so it doesn't float away from button)
    window.addEventListener('scroll', function() {
        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.style.top = '';
            d.style.left = '';
        });
    }, true);

    // Submit filter form on Enter key in search input
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performAjaxSearch();
            }
        });
        // Add real-time search on input
        searchInput.addEventListener('input', function(e) {
            performAjaxSearch();
        });
    }

    // Perform AJAX search and filter
    function performAjaxSearch() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        
        fetch('{{ route("admin.asesi.index") }}?' + params.toString(), {
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

    // Apply per-page using AJAX and refresh table + pagination area
    function applyPerPageAjax(rawValue) {
        const perPage = Math.max(5, Math.min(100, parseInt(rawValue, 10) || 10));
        const form = document.getElementById('filterForm');
        if (!form) return;

        var hiddenPerPage = form.querySelector('input[name="per_page"]');
        if (hiddenPerPage) hiddenPerPage.value = perPage;

        const params = new URLSearchParams(new FormData(form));
        params.set('per_page', perPage);
        params.set('page', '1');

        fetch('{{ route("admin.asesi.index") }}?' + params.toString(), {
            headers: { 'Accept': 'text/html' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTbody = doc.querySelector('.data-table tbody');
            const oldTbody = document.querySelector('.data-table tbody');
            if (newTbody && oldTbody) {
                oldTbody.innerHTML = newTbody.innerHTML;
            }

            const newPagination = doc.querySelector('.pagination-container');
            const oldPagination = document.querySelector('.pagination-container');
            if (newPagination && oldPagination) {
                oldPagination.outerHTML = newPagination.outerHTML;
            }

            history.replaceState({}, '', '{{ route("admin.asesi.index") }}?' + params.toString());

            attachActionMenuListeners();
            attachBulkListenersData();
            bindPerPageInputAjax();
            updateBulkBarData();
        })
        .catch(error => console.error('Per-page AJAX error:', error));
    }

    function bindPerPageInputAjax() {
        const perPageInput = document.getElementById('per-page-input');
        if (!perPageInput || perPageInput.dataset.bound === '1') return;

        let timer = null;
        perPageInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => applyPerPageAjax(this.value), 300);
        });

        perPageInput.addEventListener('change', function() {
            applyPerPageAjax(this.value);
        });

        perPageInput.dataset.bound = '1';
    }

    // Update filter on dropdown change (jurusan and status)
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            performAjaxSearch();
        });
    });

    // Attach click handlers to action menus
    function attachActionMenuListeners() {
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.removeEventListener('click', toggleMenu);
            btn.addEventListener('click', function() { toggleMenu(this); });
        });
    }

    // Initial attachment
    attachActionMenuListeners();
    bindPerPageInputAjax();

    // Reset filters
    function resetFilters() {
        document.querySelector('input[name="search"]').value = '';
        document.querySelector('select[name="jurusan"]').value = '';
        document.querySelector('select[name="status"]').value = '';
        performAjaxSearch();
    }

    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).style.display = 'block';
        btn.classList.add('active');
    }

    // Keep active tab on page reload if query param present
    (function() {
        const params = new URLSearchParams(window.location.search);
        if (params.has('tab') && params.get('tab') === 'akun') {
            switchTab('tab-akun-orphan', document.querySelectorAll('.tab-btn')[1]);
        }
    })();

    // Import modal functions
    function openAsesiImportModal() {
        document.getElementById('asesi-import-modal').style.display = 'flex';
    }
    function closeAsesiImportModal() {
        document.getElementById('asesi-import-modal').style.display = 'none';
        document.getElementById('asesi-import-file').value = '';
        document.getElementById('asesi-import-file-label').style.display = 'none';
    }
    function onAsesiImportFileChange(input) {
        var label = document.getElementById('asesi-import-file-label');
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.style.display = 'block';
        } else {
            label.style.display = 'none';
        }
    }
    function openAsesiTutorialModal() {
        document.getElementById('asesi-tutorial-modal').style.display = 'flex';
    }
    function closeAsesiTutorialModal() {
        document.getElementById('asesi-tutorial-modal').style.display = 'none';
    }

    function openAsesiActivatedImportModal() {
        document.getElementById('asesi-activated-import-modal').style.display = 'flex';
    }
    function closeAsesiActivatedImportModal() {
        document.getElementById('asesi-activated-import-modal').style.display = 'none';
        document.getElementById('asesi-activated-import-file').value = '';
        document.getElementById('asesi-activated-import-file-label').style.display = 'none';
    }
    function onAsesiActivatedImportFileChange(input) {
        var label = document.getElementById('asesi-activated-import-file-label');
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.style.display = 'block';
        } else {
            label.style.display = 'none';
        }
    }

    function openAsesiExportModal() {
        // Reset to "semua" state each time modal opens
        document.querySelectorAll('.export-jurusan-option').forEach(function(cb) { cb.checked = false; });
        document.querySelectorAll('.export-skema-option').forEach(function(cb) { cb.checked = false; });
        var allJurCb = document.getElementById('export-jurusan-all-cb');
        if (allJurCb) allJurCb.checked = true;
        var allSkemaCb = document.getElementById('export-skema-all-cb');
        if (allSkemaCb) allSkemaCb.checked = true;
        var statusSelect = document.getElementById('export-status-asesmen');
        if (statusSelect) statusSelect.value = '';
        var tanggalDariInput = document.getElementById('export-tanggal-dari');
        if (tanggalDariInput) tanggalDariInput.value = '';
        var tanggalSampaiInput = document.getElementById('export-tanggal-sampai');
        if (tanggalSampaiInput) tanggalSampaiInput.value = '';
        var jurusanSearchInput = document.getElementById('export-jurusan-search');
        if (jurusanSearchInput) jurusanSearchInput.value = '';
        var skemaSearchInput = document.getElementById('export-skema-search');
        if (skemaSearchInput) skemaSearchInput.value = '';
        document.getElementById('asesi-export-modal').style.display = 'flex';
        closeAllExportDropdowns();
        applyExportJurusanSearch();
        syncExportSkemaByJurusan();
        renderExportBadges();
    }
    function closeAsesiExportModal() {
        document.getElementById('asesi-export-modal').style.display = 'none';
        closeAllExportDropdowns();
    }

    function closeAllExportDropdowns() {
        var jurMenu = document.getElementById('export-jurusan-menu');
        var skemaMenu = document.getElementById('export-skema-menu');
        if (jurMenu) jurMenu.style.display = 'none';
        if (skemaMenu) skemaMenu.style.display = 'none';
    }

    function getCheckedValues(selector) {
        return Array.from(document.querySelectorAll(selector + ':checked')).map(function(el) { return el.value; });
    }

    function getCheckedLabels(selector) {
        return Array.from(document.querySelectorAll(selector + ':checked')).map(function(el) { return el.getAttribute('data-label') || ''; }).filter(Boolean);
    }

    function updateExportToggleText() {
        var jurText = document.getElementById('export-jurusan-toggle-text');
        var skemaText = document.getElementById('export-skema-toggle-text');
        var jurusanLabels = getCheckedLabels('.export-jurusan-option');
        var skemaLabels = getCheckedLabels('.export-skema-option');

        if (jurText) jurText.textContent = jurusanLabels.length ? (jurusanLabels.length + ' jurusan dipilih') : 'Semua jurusan';
        if (skemaText) skemaText.textContent = skemaLabels.length ? (skemaLabels.length + ' skema dipilih') : 'Semua skema';
    }

    function rebuildExportHiddenInputs() {
        var container = document.getElementById('export-hidden-inputs');
        if (!container) return;

        container.innerHTML = '';

        getCheckedValues('.export-jurusan-option').forEach(function(value) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'jurusan[]';
            input.value = value;
            container.appendChild(input);
        });

        getCheckedValues('.export-skema-option').forEach(function(value) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skema[]';
            input.value = value;
            container.appendChild(input);
        });
    }

    function renderBadgeList(targetId, values, allLabel) {
        var container = document.getElementById(targetId);
        if (!container) return;

        if (!values.length) {
            container.innerHTML = '<span style="background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;padding:4px 10px;border-radius:999px;">' + (allLabel || 'Semua') + '</span>';
            return;
        }

        container.innerHTML = values.map(function(label) {
            return '<span style="background:#e0f2fe;color:#075985;font-size:11px;font-weight:600;padding:4px 10px;border-radius:999px;">' + label + '</span>';
        }).join('');
    }

    function renderExportBadges() {
        var jurusanLabels = getCheckedLabels('.export-jurusan-option');
        var skemaLabels = getCheckedLabels('.export-skema-option');
        var statusSelect = document.getElementById('export-status-asesmen');
        var tanggalDariInput = document.getElementById('export-tanggal-dari');
        var tanggalSampaiInput = document.getElementById('export-tanggal-sampai');

        var statusLabelMap = {
            pending: 'Menunggu',
            approved: 'Disetujui',
            rejected: 'Ditolak'
        };

        var statusValue = statusSelect ? statusSelect.value : '';
        var statusLabels = statusValue ? [statusLabelMap[statusValue] || statusValue] : [];

        var tanggalDari = tanggalDariInput ? tanggalDariInput.value : '';
        var tanggalSampai = tanggalSampaiInput ? tanggalSampaiInput.value : '';
        var tanggalLabels = [];
        if (tanggalDari && tanggalSampai) {
            tanggalLabels = ['Dari ' + tanggalDari + ' s/d ' + tanggalSampai];
        } else if (tanggalDari) {
            tanggalLabels = ['Dari ' + tanggalDari];
        } else if (tanggalSampai) {
            tanggalLabels = ['Sampai ' + tanggalSampai];
        }

        renderBadgeList('export-jurusan-badges', jurusanLabels, 'Semua Jurusan');
        renderBadgeList('export-skema-badges', skemaLabels, 'Semua Skema');
        renderBadgeList('export-status-badges', statusLabels, 'Semua Status');
        renderBadgeList('export-tanggal-badges', tanggalLabels, 'Semua Tanggal');
        updateExportToggleText();
        rebuildExportHiddenInputs();
    }

    function applyExportJurusanSearch() {
        var searchInput = document.getElementById('export-jurusan-search');
        var keyword = ((searchInput ? searchInput.value : '') || '').trim().toLowerCase();

        Array.from(document.querySelectorAll('.export-jurusan-item')).forEach(function(item) {
            var label = (item.getAttribute('data-label') || '').toLowerCase();
            var visible = !keyword || label.indexOf(keyword) !== -1;
            item.style.display = visible ? '' : 'none';
        });
    }

    function syncExportSkemaByJurusan() {
        var selectedJurusan = getCheckedValues('.export-jurusan-option');
        var useFilter = selectedJurusan.length > 0;
        var skemaSearchInput = document.getElementById('export-skema-search');
        var keyword = ((skemaSearchInput ? skemaSearchInput.value : '') || '').trim().toLowerCase();

        // Sync "Semua Jurusan" visual state
        var allJurCb = document.getElementById('export-jurusan-all-cb');
        if (allJurCb) allJurCb.checked = !useFilter;

        Array.from(document.querySelectorAll('.export-skema-item')).forEach(function(item) {
            var checkbox = item.querySelector('.export-skema-option');
            if (!checkbox) return;

            var jurusanId = checkbox.getAttribute('data-jurusan') || '';
            var label = (item.getAttribute('data-label') || '').toLowerCase();
            var allowedByJurusan = !useFilter || (jurusanId !== '' && selectedJurusan.indexOf(jurusanId) !== -1);
            var allowedBySearch = !keyword || label.indexOf(keyword) !== -1;
            var visible = allowedByJurusan && allowedBySearch;

            item.style.display = visible ? '' : 'none';

            // If hidden because selected jurusan doesn't match, uncheck to keep filter valid.
            if (!allowedByJurusan) checkbox.checked = false;
        });

        // Sync "Semua Skema" visual state
        var allSkemaCb = document.getElementById('export-skema-all-cb');
        if (allSkemaCb) allSkemaCb.checked = getCheckedValues('.export-skema-option').length === 0;

        renderExportBadges();
    }

    // Close modals on backdrop click
    ['asesi-import-modal', 'asesi-tutorial-modal', 'asesi-create-modal', 'asesi-activated-import-modal', 'asesi-export-modal'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
    });

    function validateAsesiCreateNik(showWhenEmpty) {
        var input = document.getElementById('asesi-create-nik');
        var error = document.getElementById('asesi-create-nik-error');
        if (!input || !error) return true;

        var val = (input.value || '').trim();
        var valid = /^\d{16}$/.test(val);

        if (!val && !showWhenEmpty) {
            error.style.display = 'none';
            return false;
        }

        if (!valid) {
            error.style.display = 'block';
            return false;
        }

        error.style.display = 'none';
        return true;
    }

    function openAsesiCreateModal() {
        document.getElementById('asesi-create-modal').style.display = 'flex';
        validateAsesiCreateNik(false);
    }
    function closeAsesiCreateModal() {
        document.getElementById('asesi-create-modal').style.display = 'none';
    }

    var asesiCreateNikInput = document.getElementById('asesi-create-nik');
    if (asesiCreateNikInput) {
        asesiCreateNikInput.addEventListener('blur', function() {
            validateAsesiCreateNik(false);
        });

        asesiCreateNikInput.addEventListener('input', function() {
            var sanitized = this.value.replace(/\D/g, '').slice(0, 16);
            if (this.value !== sanitized) this.value = sanitized;
            if ((this.value || '').length === 16) {
                validateAsesiCreateNik(false);
            }
        });
    }

    var asesiCreateForm = document.querySelector('#asesi-create-modal form');
    if (asesiCreateForm) {
        asesiCreateForm.addEventListener('submit', function(e) {
            if (!validateAsesiCreateNik(true)) {
                e.preventDefault();
                var nikInput = document.getElementById('asesi-create-nik');
                if (nikInput) nikInput.focus();
            }
        });
    }

    // Drag-and-drop on import drop zone
    var dropZone = document.getElementById('asesi-import-drop');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor='#0073bd'; });
        dropZone.addEventListener('dragleave', function() { this.style.borderColor='#d1d5db'; });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault(); this.style.borderColor='#d1d5db';
            var file = e.dataTransfer.files[0];
            if (file) { document.getElementById('asesi-import-file').files = e.dataTransfer.files; onAsesiImportFileChange(document.getElementById('asesi-import-file')); }
        });
    }

    // Loading state on import submit
    var importForm = document.getElementById('asesi-import-form');
    if (importForm) {
        importForm.addEventListener('submit', function() {
            var btn = document.getElementById('asesi-import-submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        });
    }

    var activatedDropZone = document.getElementById('asesi-activated-import-drop');
    if (activatedDropZone) {
        activatedDropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor='#0073bd'; });
        activatedDropZone.addEventListener('dragleave', function() { this.style.borderColor='#d1d5db'; });
        activatedDropZone.addEventListener('drop', function(e) {
            e.preventDefault(); this.style.borderColor='#d1d5db';
            var file = e.dataTransfer.files[0];
            if (file) {
                document.getElementById('asesi-activated-import-file').files = e.dataTransfer.files;
                onAsesiActivatedImportFileChange(document.getElementById('asesi-activated-import-file'));
            }
        });
    }

    var activatedImportForm = document.getElementById('asesi-activated-import-form');
    if (activatedImportForm) {
        activatedImportForm.addEventListener('submit', function() {
            var btn = document.getElementById('asesi-activated-import-submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        });
    }

    var exportForm = document.getElementById('asesi-export-form');
    if (exportForm) {
        exportForm.addEventListener('submit', function() {
            var btn = document.getElementById('asesi-export-submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyiapkan file...';
            setTimeout(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-file-earmark-excel"></i> Export XLSX';
            }, 3000);
        });
    }

    var exportJurusanToggle = document.getElementById('export-jurusan-toggle');
    var exportJurusanMenu = document.getElementById('export-jurusan-menu');
    var exportSkemaToggle = document.getElementById('export-skema-toggle');
    var exportSkemaMenu = document.getElementById('export-skema-menu');
    var exportJurusanSearch = document.getElementById('export-jurusan-search');
    var exportSkemaSearch = document.getElementById('export-skema-search');

    if (exportJurusanToggle && exportJurusanMenu) {
        exportJurusanToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = exportJurusanMenu.style.display === 'block';
            closeAllExportDropdowns();
            exportJurusanMenu.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) {
                applyExportJurusanSearch();
                if (exportJurusanSearch) exportJurusanSearch.focus();
            }
        });
    }

    if (exportSkemaToggle && exportSkemaMenu) {
        exportSkemaToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = exportSkemaMenu.style.display === 'block';
            closeAllExportDropdowns();
            exportSkemaMenu.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) {
                syncExportSkemaByJurusan();
                if (exportSkemaSearch) exportSkemaSearch.focus();
            }
        });
    }

    if (exportJurusanSearch) {
        exportJurusanSearch.addEventListener('input', function() {
            applyExportJurusanSearch();
        });
    }

    if (exportSkemaSearch) {
        exportSkemaSearch.addEventListener('input', function() {
            syncExportSkemaByJurusan();
        });
    }

    Array.from(document.querySelectorAll('.export-jurusan-option')).forEach(function(el) {
        el.addEventListener('change', function() {
            if (this.checked) {
                var allCb = document.getElementById('export-jurusan-all-cb');
                if (allCb) allCb.checked = false;
            }
            syncExportSkemaByJurusan();
        });
    });

    Array.from(document.querySelectorAll('.export-skema-option')).forEach(function(el) {
        el.addEventListener('change', function() {
            if (this.checked) {
                var allCb = document.getElementById('export-skema-all-cb');
                if (allCb) allCb.checked = false;
            }
            if (getCheckedValues('.export-skema-option').length === 0) {
                var allCb = document.getElementById('export-skema-all-cb');
                if (allCb) allCb.checked = true;
            }
            renderExportBadges();
        });
    });

    // "Semua Jurusan" toggle
    var exportJurusanAllCb = document.getElementById('export-jurusan-all-cb');
    if (exportJurusanAllCb) {
        exportJurusanAllCb.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.export-jurusan-option').forEach(function(cb) { cb.checked = false; });
            } else {
                this.checked = true; // can't uncheck without selecting individual
            }
            syncExportSkemaByJurusan();
        });
    }

    // "Semua Skema" toggle
    var exportSkemaAllCb = document.getElementById('export-skema-all-cb');
    if (exportSkemaAllCb) {
        exportSkemaAllCb.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.export-skema-option').forEach(function(cb) { cb.checked = false; });
            } else {
                this.checked = true; // can't uncheck without selecting individual
            }
            renderExportBadges();
        });
    }

    var exportStatusAsesmen = document.getElementById('export-status-asesmen');
    if (exportStatusAsesmen) {
        exportStatusAsesmen.addEventListener('change', function() {
            renderExportBadges();
        });
    }

    var exportTanggalDari = document.getElementById('export-tanggal-dari');
    if (exportTanggalDari) {
        exportTanggalDari.addEventListener('change', function() {
            renderExportBadges();
        });
    }

    var exportTanggalSampai = document.getElementById('export-tanggal-sampai');
    if (exportTanggalSampai) {
        exportTanggalSampai.addEventListener('change', function() {
            renderExportBadges();
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#export-jurusan-dropdown') && !e.target.closest('#export-skema-dropdown')) {
            closeAllExportDropdowns();
        }
    });

    // Initial badges state
    syncExportSkemaByJurusan();

    // Bulk Delete Functions for Data Asesi Table
    var isBulkModeDataActive = false;

    function setBulkModeData(active) {
        isBulkModeDataActive = !!active;
        document.querySelectorAll('.bulk-col-data').forEach(function(el) {
            el.style.display = isBulkModeDataActive ? '' : 'none';
        });

        var toggleBtn = document.getElementById('toggle-bulk-mode-data');
        if (toggleBtn) {
            if (isBulkModeDataActive) {
                toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Tutup';
                toggleBtn.style.background = '#f8fafc';
                toggleBtn.style.color = '#475569';
                toggleBtn.style.border = '1px solid #94a3b8';
            } else {
                toggleBtn.innerHTML = '<i class="bi bi-ui-checks-grid"></i> Bulk';
                toggleBtn.style.background = '#fff';
                toggleBtn.style.color = '#64748b';
                toggleBtn.style.border = '1px solid #cbd5e1';
                clearBulkSelectionData();
            }
        }
    }

    function attachBulkListenersData() {
        document.querySelectorAll('.bulk-checkbox-data').forEach(function(cb) {
            cb.addEventListener('change', updateBulkBarData);
        });
        var selectAll = document.getElementById('bulk-select-all-data');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.bulk-checkbox-data').forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
                updateBulkBarData();
            });
        }
    }

    function updateBulkBarData() {
        if (!isBulkModeDataActive) {
            var barHidden = document.getElementById('bulk-action-bar-data');
            if (barHidden) barHidden.style.display = 'none';
            return;
        }
        var checked = document.querySelectorAll('.bulk-checkbox-data:checked');
        var all = document.querySelectorAll('.bulk-checkbox-data');
        var bar = document.getElementById('bulk-action-bar-data');
        if (bar) bar.style.display = checked.length > 0 ? 'flex' : 'none';
        var countEl = document.getElementById('bulk-count-text-data');
        if (countEl) countEl.textContent = checked.length + ' item dipilih';
        var selectAll = document.getElementById('bulk-select-all-data');
        if (selectAll) {
            selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
            selectAll.checked = all.length > 0 && checked.length === all.length;
        }
    }

    function getSelectedNiksData() {
        return Array.from(document.querySelectorAll('.bulk-checkbox-data:checked')).map(function(cb) { return cb.value; });
    }

    window.openBulkDeleteModalData = function() {
        if (!isBulkModeDataActive) return;
        var niks = getSelectedNiksData();
        if (niks.length === 0) return;
        var container = document.getElementById('bulk-delete-niks-modal-data');
        container.innerHTML = '';
        niks.forEach(function(nik) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'niks[]'; inp.value = nik;
            container.appendChild(inp);
        });
        var countEl = document.getElementById('bulk-delete-count-text-data');
        if (countEl) countEl.textContent = niks.length + ' asesi akan dihapus';
        document.getElementById('bulk-delete-modal-data').style.display = 'flex';
    };

    window.closeBulkDeleteModalData = function() {
        document.getElementById('bulk-delete-modal-data').style.display = 'none';
    };

    window.clearBulkSelectionData = function() {
        document.querySelectorAll('.bulk-checkbox-data').forEach(function(cb) { cb.checked = false; });
        var selectAll = document.getElementById('bulk-select-all-data');
        if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
        updateBulkBarData();
    };

    // Close modal on backdrop click
    document.getElementById('bulk-delete-modal-data').addEventListener('click', function(e) {
        if (e.target === this) closeBulkDeleteModalData();
    });

    var toggleBulkModeDataBtn = document.getElementById('toggle-bulk-mode-data');
    if (toggleBulkModeDataBtn) {
        toggleBulkModeDataBtn.addEventListener('click', function() {
            setBulkModeData(!isBulkModeDataActive);
        });
    }

    setBulkModeData(false);
    attachBulkListenersData();
</script>

@endsection
