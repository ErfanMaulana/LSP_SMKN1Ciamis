@extends('admin.layout')

@section('title', 'Verifikasi Asesi')
@section('page-title', 'Verifikasi Pendaftaran Asesi')

@section('content')
<div class="asesi-verifikasi">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Verifikasi Pendaftaran Asesi</h2>
            <p class="subtitle">Kelola dan verifikasi pendaftaran calon asesi baru.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'pending', 'per_page' => $perPage]) }}" 
           class="stat-card {{ $status === 'pending' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">MENUNGGU</div>
                <div class="stat-value">{{ $counts['pending'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'approved', 'per_page' => $perPage]) }}" 
           class="stat-card {{ $status === 'approved' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DISETUJUI</div>
                <div class="stat-value">{{ $counts['approved'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'rejected', 'per_page' => $perPage]) }}" 
           class="stat-card {{ $status === 'rejected' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DITOLAK</div>
                <div class="stat-value">{{ $counts['rejected'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['per_page' => $perPage]) }}" 
           class="stat-card {{ $status === '' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL</div>
                <div class="stat-value">{{ $counts['total'] }}</div>
            </div>
        </a>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <form method="GET" action="{{ route('admin.asesi.verifikasi') }}" id="verifikasiSearchForm" style="display:flex;gap:8px;width:100%;">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="reject_type" value="{{ $rejectType }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau email..." style="flex:1;">
                        
                    </form>
                </div>
                @if($status === 'rejected')
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <label style="font-size:13px;font-weight:500;color:#64748b;white-space:nowrap;">Filter Penolakan:</label>
                        <form method="GET" action="{{ route('admin.asesi.verifikasi') }}" style="display:flex;gap:8px;">
                            <input type="hidden" name="status" value="rejected">
                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                            <select name="reject_type" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:13px;background:#fff;color:#475569;cursor:pointer;min-width:200px;" onchange="this.form.submit();">
                                <option value="">Semua Penolakan ({{ $counts['rejected'] }})</option>
                                <option value="temporary" {{ $rejectType === 'temporary' ? 'selected' : '' }}>Ditolak Sementara ({{ $counts['rejected_temporary'] }})</option>
                                <option value="permanent" {{ $rejectType === 'permanent' ? 'selected' : '' }}>Ditolak Permanen ({{ $counts['rejected_permanent'] }})</option>
                            </select>
                        </form>
                    </div>
                @endif
            </div>

            @if($asesi->count() > 0)
                <div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
                    <button type="button" id="toggle-bulk-mode"
                        style="padding:7px 12px;background:#fff;color:#64748b;border:1px solid #cbd5e1;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-ui-checks-grid"></i> Bulk
                    </button>
                </div>

                <!-- Bulk Action Bar -->
                <div id="bulk-action-bar" style="display:none;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px 16px;margin-bottom:16px;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span id="bulk-count-text" style="font-size:14px;color:#0073bd;font-weight:600;">0 item dipilih</span>
                    <div style="flex:1;"></div>
                    <button type="button" onclick="submitBulkApprove()"
                        style="padding:8px 18px;background:#16a34a;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-check-lg"></i> Setujui Pilihan
                    </button>
                    <button type="button" onclick="openBulkRejectModal()"
                        style="padding:8px 18px;background:#e11d48;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-x-lg"></i> Tolak Pilihan
                    </button>
                    <button type="button" onclick="openBulkDeleteModal()"
                        style="padding:8px 18px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash"></i> Hapus Pilihan
                    </button>
                    <button type="button" onclick="clearBulkSelection()"
                        style="padding:8px 14px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">
                        Batal
                    </button>
                </div>

                <!-- Bulk Approve Hidden Form -->
                <form id="bulk-approve-form" method="POST" action="{{ route('admin.asesi.bulk-approve') }}" style="display:none;">
                    @csrf
                    <div id="bulk-approve-niks"></div>
                </form>

                <!-- Bulk Delete Hidden Form -->
                <form id="bulk-delete-form" method="POST" action="{{ route('admin.asesi.bulk-delete') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="from_verifikasi" value="1">
                    <div id="bulk-delete-niks"></div>
                </form>

                <!-- Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="bulk-col" style="display:none;width:44px;text-align:center;">
                                    <input type="checkbox" id="bulk-select-all" title="Pilih semua"
                                        style="width:16px;height:16px;cursor:pointer;accent-color:#0073bd;">
                                </th>
                                <th>ASESI</th>
                                <th>NIK</th>
                               
                                <th>TANGGAL DAFTAR</th>
                                <th>STATUS</th>
                                <th style="text-align:center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="verifikasiTableBody">
                            @foreach($asesi as $item)
                            <tr>
                                <td class="bulk-col" style="display:none;text-align:center;">
                                    <input type="checkbox" class="bulk-checkbox" value="{{ $item->NIK }}"
                                        style="width:16px;height:16px;cursor:pointer;accent-color:#0073bd;">
                                </td>
                                <td>
                                    <div class="user-info">
                                        @if($item->pas_foto)
                                            <img src="{{ asset('storage/' . $item->pas_foto) }}" alt="Foto" class="user-avatar-img">
                                        @else
                                            <div class="user-avatar-initials">
                                                {{ strtoupper(substr($item->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="user-details">
                                            <div class="user-name">{{ $item->nama }}</div>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="nik-text">{{ $item->NIK }}</span>
                                </td>
                                
                                <td>
                                    <span class="date-text">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                                </td>
                                <td>
                                    @if($item->status === 'pending')
                                        <span class="badge badge-pending">Menunggu</span>
                                    @elseif($item->status === 'approved')
                                        <span class="badge badge-approved">Disetujui</span>
                                    @elseif($item->status === 'banned')
                                        <span class="badge badge-rejected">Ditolak Permanen</span>
                                    @else
                                        <span class="badge badge-rejected">Ditolak Sementara</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <div class="action-menu">
                                        <button class="action-btn" onclick="toggleMenu(this)">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div class="action-dropdown">
                                            <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}" title="Review Detail">
                                                <i class="bi bi-eye" style="font-size: 16px;"></i> Lihat Detail
                                            </a>
                                            @if($item->status === 'pending')
                                            <form action="{{ route('admin.asesi.approve', $item->NIK) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button type="submit" title="Setujui" onclick="return confirm('Setujui pendaftaran {{ addslashes($item->nama) }}?')">
                                                    <i class="bi bi-check-lg" style="font-size: 16px;"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.asesi.reject', $item->NIK) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button type="submit" title="Tolak" onclick="return confirm('Tolak pendaftaran {{ addslashes($item->nama) }}?')">
                                                    <i class="bi bi-x-lg" style="font-size: 16px;"></i> Tolak
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="pagination-info">Showing {{ $asesi->firstItem() }} to {{ $asesi->lastItem() }} of {{ $asesi->total() }} entries</span>
                        <form method="GET" action="{{ route('admin.asesi.verifikasi') }}" style="display:flex;align-items:center;gap:6px;">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <label style="font-size:13px;color:#64748b;white-space:nowrap;">Tampilkan</label>
                            <input type="number" name="per_page" value="{{ $perPage }}" min="1" max="200"
                                style="width:68px;padding:6px 8px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;text-align:center;color:#374151;"
                                onkeydown="if(event.key==='Enter'){this.form.submit();}"
                                onblur="this.form.submit();">
                            <label style="font-size:13px;color:#64748b;white-space:nowrap;">baris</label>
                        </form>
                    </div>
                    <div class="pagination">
                        @php
                            $currentPage = $asesi->currentPage();
                            $lastPage    = $asesi->lastPage();
                            $window      = 2; // pages around current
                            $start       = max(1, $currentPage - $window);
                            $end         = min($lastPage, $currentPage + $window);
                        @endphp
                        @if($currentPage > 1)
                            <a href="{{ $asesi->previousPageUrl() }}" class="page-link">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        @if($start > 1)
                            <a href="{{ $asesi->url(1) }}" class="page-link">1</a>
                            @if($start > 2)<span class="page-dots">…</span>@endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <a href="{{ $asesi->url($i) }}" class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)<span class="page-dots">…</span>@endif
                            <a href="{{ $asesi->url($lastPage) }}" class="page-link">{{ $lastPage }}</a>
                        @endif

                        @if($asesi->hasMorePages())
                            <a href="{{ $asesi->nextPageUrl() }}" class="page-link">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>Tidak ada data</h4>
                    <p>Belum ada asesi dengan status ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .asesi-verifikasi {
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
        color: inherit;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-card.stat-card-active {
        border: 2px solid #0073bd;
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
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

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
    }

    .search-box {
        position: relative;
    }

    .search-box > form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        z-index: 1;
    }

    .search-box button i {
        position: static;
        left: auto;
        top: auto;
        transform: none;
        color: inherit;
        font-size: 16px;
        z-index: auto;
    }

    .search-box input {
        flex: 1;
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
        font-size: 14px;
        color: #475569;
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

    .user-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        flex-shrink: 0;
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

    .nik-text {
        font-size: 13px;
        font-family: 'Courier New', monospace;
        color: #475569;
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

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Button */
    .btn-sm {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .btn-view {
        background: #eff6ff;
        color: #0073bd;
    }

    .btn-view:hover {
        background: #dbeafe;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h4 {
        color: #6b7280;
        font-size: 16px;
        font-weight: 500;
        margin: 0 0 6px;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 13px;
        margin: 0;
    }

    /* Action Menu */
    .action-menu {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;

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
    .action-dropdown button,
    .action-dropdown form button {
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
    .action-dropdown button:hover,
    .action-dropdown form button:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .action-dropdown form:last-child button:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-section {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .table-container {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 640px) {
        .page-header {
            margin-bottom: 18px;
            gap: 10px;
        }

        .page-header h2 {
            font-size: 20px;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 12px;
        }

        .card-body {
            padding: 12px;
        }

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

        .search-box > form {
            flex-direction: column;
            align-items: stretch;
            position: relative;
        }

        .search-box input {
            height: 42px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .search-box i {
            top: 21px;
            transform: translateY(-50%);
        }

        .search-box > form button {
            width: 100%;
        }

        .data-table thead th,
        .data-table tbody td {
            padding: 10px 12px;
        }

        .pagination-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .pagination-info {
            font-size: 12px;
        }

        #bulk-action-bar {
            padding: 10px 12px !important;
            gap: 8px !important;
        }

        #bulk-action-bar > div[style*="flex:1"] {
            display: none;
        }

        #bulk-action-bar button {
            width: 100%;
            justify-content: center;
        }

        .bulk-modal-box {
            padding: 18px;
        }

        .bulk-modal-actions {
            flex-direction: column-reverse;
        }

        .bulk-modal-actions button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Bulk Reject Modal */
    .bulk-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1050;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    .bulk-modal-overlay.active { display: flex; }
    .bulk-modal-box {
        background: #fff;
        border-radius: 14px;
        padding: 28px 32px;
        width: 480px;
        max-width: 95vw;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .bulk-modal-box h3 {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px;
    }
    .bulk-modal-box .modal-sub {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 20px;
    }
    .bulk-modal-field { margin-bottom: 16px; }
    .bulk-modal-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .bulk-modal-field textarea,
    .bulk-modal-field select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: border 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }
    .bulk-modal-field textarea:focus,
    .bulk-modal-field select:focus { border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,0.1); }
    .bulk-modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
</style>

<!-- Bulk Reject Modal -->
<div id="bulk-reject-modal" class="bulk-modal-overlay">
    <div class="bulk-modal-box">
        <h3><i class="bi bi-x-octagon" style="color:#e11d48;margin-right:8px;"></i>Tolak Asesi Terpilih</h3>
        <p class="modal-sub" id="bulk-reject-count-text">0 asesi akan ditolak</p>
        <form id="bulk-reject-form" method="POST" action="{{ route('admin.asesi.bulk-reject') }}">
            @csrf
            <div id="bulk-reject-niks"></div>
            <div class="bulk-modal-field">
                <label>Catatan Penolakan <span style="color:#e11d48;">*</span></label>
                <textarea name="catatan_admin" rows="3" required
                    placeholder="Tuliskan alasan penolakan..."></textarea>
            </div>
            <div class="bulk-modal-field">
                <label>Jenis Penolakan <span style="color:#e11d48;">*</span></label>
                <select name="reject_type" required>
                    <option value="rejected">Ditolak (dapat mendaftar ulang)</option>
                    <option value="banned">Ditolak Permanen (banned)</option>
                </select>
            </div>
            <div class="bulk-modal-actions">
                <button type="button" onclick="closeBulkRejectModal()"
                    style="padding:9px 20px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;">Batal</button>
                <button type="submit"
                    style="padding:9px 20px;background:#e11d48;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-x-lg"></i> Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulk-delete-modal" class="bulk-modal-overlay">
    <div class="bulk-modal-box">
        <h3><i class="bi bi-trash" style="color:#dc2626;margin-right:8px;"></i>Hapus Asesi Terpilih</h3>
        <p class="modal-sub" id="bulk-delete-count-text">0 asesi akan dihapus</p>
        <p style="color:#e11d48;font-size:13px;background:#fee2e2;padding:12px;border-radius:6px;margin-bottom:16px;">
            <i class="bi bi-exclamation-triangle"></i> Perhatian: Tindakan ini tidak dapat dibatalkan. Data asesi dan akun akan dihapus sepenuhnya.
        </p>
        <form id="bulk-delete-form-modal" method="POST" action="{{ route('admin.asesi.bulk-delete') }}">
            @csrf
            <input type="hidden" name="from_verifikasi" value="1">
            <div id="bulk-delete-niks-modal"></div>
            <div class="bulk-modal-actions">
                <button type="button" onclick="closeBulkDeleteModal()"
                    style="padding:9px 20px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;">Batal</button>
                <button type="submit"
                    style="padding:9px 20px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-trash"></i> Konfirmasi Hapus
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    // Action Menu Toggle with Fixed Positioning
    function toggleMenu(button) {
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

    function initVerifikasiBulkActions() {
    var isBulkModeActive = false;

    function setBulkMode(active) {
        isBulkModeActive = !!active;
        document.querySelectorAll('.bulk-col').forEach(function(el) {
            el.style.display = isBulkModeActive ? '' : 'none';
        });

        var toggleBtn = document.getElementById('toggle-bulk-mode');
        if (toggleBtn) {
            if (isBulkModeActive) {
                toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Tutup';
                toggleBtn.style.background = '#f8fafc';
                toggleBtn.style.color = '#475569';
                toggleBtn.style.border = '1px solid #94a3b8';
            } else {
                toggleBtn.innerHTML = '<i class="bi bi-ui-checks-grid"></i> Bulk';
                toggleBtn.style.background = '#fff';
                toggleBtn.style.color = '#64748b';
                toggleBtn.style.border = '1px solid #cbd5e1';
                clearBulkSelection();
            }
        }
    }

    // Checkbox listeners
    function attachBulkListeners() {
        document.querySelectorAll('.bulk-checkbox').forEach(function(cb) {
            cb.onchange = updateBulkBar;
        });
        var selectAll = document.getElementById('bulk-select-all');
        if (selectAll) {
            selectAll.onchange = function() {
                document.querySelectorAll('.bulk-checkbox').forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
                updateBulkBar();
            };
        }
    }

    function updateBulkBar() {
        if (!isBulkModeActive) {
            var barHidden = document.getElementById('bulk-action-bar');
            if (barHidden) barHidden.style.display = 'none';
            return;
        }
        var checked = document.querySelectorAll('.bulk-checkbox:checked');
        var all     = document.querySelectorAll('.bulk-checkbox');
        var bar     = document.getElementById('bulk-action-bar');
        if (bar) bar.style.display = checked.length > 0 ? 'flex' : 'none';
        var countEl = document.getElementById('bulk-count-text');
        if (countEl) countEl.textContent = checked.length + ' item dipilih';
        var selectAll = document.getElementById('bulk-select-all');
        if (selectAll) {
            selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
            selectAll.checked = all.length > 0 && checked.length === all.length;
        }
    }

    function getSelectedNiks() {
        return Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(function(cb) { return cb.value; });
    }

    function closeMenus() {
        document.querySelectorAll('.action-dropdown').forEach(m => m.classList.remove('show'));
    }

    window.submitBulkApprove = function() {
        closeMenus();
        if (!isBulkModeActive) return;
        var niks = getSelectedNiks();
        if (niks.length === 0) return;
        if (!confirm('Setujui ' + niks.length + ' asesi terpilih?')) return;
        var container = document.getElementById('bulk-approve-niks');
        container.innerHTML = '';
        niks.forEach(function(nik) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'niks[]'; inp.value = nik;
            container.appendChild(inp);
        });
        document.getElementById('bulk-approve-form').submit();
    };

    window.openBulkRejectModal = function() {
        closeMenus();
        if (!isBulkModeActive) return;
        var niks = getSelectedNiks();
        if (niks.length === 0) return;
        var container = document.getElementById('bulk-reject-niks');
        container.innerHTML = '';
        niks.forEach(function(nik) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'niks[]'; inp.value = nik;
            container.appendChild(inp);
        });
        var countEl = document.getElementById('bulk-reject-count-text');
        if (countEl) countEl.textContent = niks.length + ' asesi akan ditolak';
        document.getElementById('bulk-reject-modal').classList.add('active');
        document.getElementById('bulk-reject-modal').querySelector('textarea').value = '';
    };

    window.closeBulkRejectModal = function() {
        document.getElementById('bulk-reject-modal').classList.remove('active');
    };

    window.clearBulkSelection = function() {
        document.querySelectorAll('.bulk-checkbox').forEach(function(cb) { cb.checked = false; });
        var selectAll = document.getElementById('bulk-select-all');
        if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
        updateBulkBar();
    };

    window.openBulkDeleteModal = function() {
        closeMenus();
        if (!isBulkModeActive) return;
        var niks = getSelectedNiks();
        if (niks.length === 0) return;
        var container = document.getElementById('bulk-delete-niks-modal');
        container.innerHTML = '';
        niks.forEach(function(nik) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'niks[]'; inp.value = nik;
            container.appendChild(inp);
        });
        var countEl = document.getElementById('bulk-delete-count-text');
        if (countEl) countEl.textContent = niks.length + ' asesi akan dihapus';
        document.getElementById('bulk-delete-modal').classList.add('active');
    };

    window.closeBulkDeleteModal = function() {
        document.getElementById('bulk-delete-modal').classList.remove('active');
    };

    // Close modal on backdrop click
    var rejectModal = document.getElementById('bulk-reject-modal');
    if (rejectModal) {
        rejectModal.onclick = function(e) {
            if (e.target === rejectModal) closeBulkRejectModal();
        };
    }

    var deleteModal = document.getElementById('bulk-delete-modal');
    if (deleteModal) {
        deleteModal.onclick = function(e) {
            if (e.target === deleteModal) closeBulkDeleteModal();
        };
    }

    var toggleBulkModeBtn = document.getElementById('toggle-bulk-mode');
    if (toggleBulkModeBtn) {
        toggleBulkModeBtn.onclick = function() {
            setBulkMode(!isBulkModeActive);
        };
    }

    setBulkMode(false);
    attachBulkListeners();

    window.refreshVerifikasiBulkActions = function() {
        setBulkMode(false);
        attachBulkListeners();
        updateBulkBar();
    };
}

function initVerifikasiAjaxSearch() {
    var searchForm = document.getElementById('verifikasiSearchForm');
    if (!searchForm) return;

    var searchInput = searchForm.querySelector('input[name="search"]');
    if (!searchInput) return;

    function performAjaxSearch() {
        var tableBody = document.getElementById('verifikasiTableBody');
        if (!tableBody) return;

        var formData = new FormData(searchForm);
        var params = new URLSearchParams(formData);

        fetch(searchForm.action + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableBody.innerHTML = html;
            if (typeof window.refreshVerifikasiBulkActions === 'function') {
                window.refreshVerifikasiBulkActions();
            }
            window.history.replaceState({}, '', searchForm.action + '?' + params.toString());
        })
        .catch(error => console.error('Search error:', error));
    }

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        performAjaxSearch();
    });

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            performAjaxSearch();
        }
    });

    searchInput.addEventListener('input', function () {
        performAjaxSearch();
    });
}

initVerifikasiBulkActions();
initVerifikasiAjaxSearch();
</script>
@endsection
