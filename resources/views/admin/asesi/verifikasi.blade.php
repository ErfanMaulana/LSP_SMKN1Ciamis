@extends('admin.layout')

@section('title', 'Permohonan Sertifikasi')
@section('page-title', 'Permohonan Sertifikasi')

@section('content')
<div class="asesi-verifikasi">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Permohonan Sertifikasi</h2>
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
            <form method="GET" action="{{ route('admin.asesi.verifikasi') }}" id="verifikasiSearchForm">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                
                <div class="filter-section">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau email..." autocomplete="off">
                    </div>
                    <div class="filter-group">
                        <select name="jurusan" id="filter-jurusan" class="filter-select">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanList as $j)
                                <option value="{{ $j->ID_jurusan }}" {{ (string)($jurusanFilter ?? request('jurusan')) === (string)$j->ID_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>

                        <select name="kelas" id="filter-kelas" class="filter-select">
                            <option value="">Semua Kelas</option>
                        </select>

                        @if($status === 'rejected')
                            <select name="reject_type" id="filter-reject-type" class="filter-select">
                                <option value="">Semua Penolakan ({{ $counts['rejected'] }})</option>
                                <option value="temporary" {{ $rejectType === 'temporary' ? 'selected' : '' }}>Ditolak Sementara ({{ $counts['rejected_temporary'] }})</option>
                                <option value="permanent" {{ $rejectType === 'permanent' ? 'selected' : '' }}>Ditolak Permanen ({{ $counts['rejected_permanent'] }})</option>
                            </select>
                        @endif

                        <!-- <button type="submit" class="btn-filter-search">Cari</button> -->
                    </div>
                </div>
            </form>

            @if($asesi->count() > 0)
                    <!-- bulk feature removed -->

                <!-- Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>

                                <th>ASESI</th>
                                <th>NIK</th>
                                <th>JURUSAN / KELAS</th>
                                <th>TANGGAL DAFTAR</th>
                                <th>STATUS</th>
                                <th style="text-align:center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="verifikasiTableBody">
                            @include('admin.asesi.partials.verifikasi-table-rows')
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
                <script>
                    // Prepare jurusan -> kelas mapping
                    const jurusanMap = {
                        @foreach($jurusanList as $j)
                            '{{ $j->ID_jurusan }}': [
                                @foreach($j->kelasItems as $k)
                                    { id: '{{ $k->id ?? $k->ID_kelas ?? $k->id }}', name: '{{ addslashes($k->nama_kelas) }}' },
                                @endforeach
                            ],
                        @endforeach
                    };

                    const jurusanSelect = document.getElementById('filter-jurusan');
                    const kelasSelect = document.getElementById('filter-kelas');

                    function populateKelas(selectedJurusanId, preselectedKelas) {
                        // Clear
                        kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                        if (!selectedJurusanId) return;
                        const items = jurusanMap[selectedJurusanId] || [];
                        items.forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.id;
                            opt.textContent = k.name;
                            if (preselectedKelas && preselectedKelas.toString() === k.id.toString()) opt.selected = true;
                            kelasSelect.appendChild(opt);
                        });
                    }

                    // Initialize on page load with any selected filters
                    document.addEventListener('DOMContentLoaded', function() {
                        const initJurusan = '{{ $jurusanFilter ?? request('jurusan') }}';
                        const initKelas = '{{ $kelasFilter ?? request('kelas') }}';
                        if (initJurusan) {
                            populateKelas(initJurusan, initKelas);
                        }
                    });

                    jurusanSelect && jurusanSelect.addEventListener('change', function(e) {
                        populateKelas(e.target.value, null);
                    });
                </script>
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
        position: relative;
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

        .filter-group {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select,
        .btn-filter-search {
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

    .verify-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 10010;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .verify-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .verify-confirm-modal {
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

    .verify-confirm-overlay.show .verify-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .verify-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .verify-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .verify-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .verify-confirm-btn-cancel,
    .verify-confirm-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
    }

    .verify-confirm-btn-cancel {
        background: #0073bd;
    }
    .verify-confirm-btn-cancel:hover {
        background: #005fa3;
    }

    .verify-confirm-btn-submit {
        background: #0073bd;
    }
    .verify-confirm-btn-submit:hover {
        background: #005fa3;
    }

    @media (prefers-reduced-motion: reduce) {
        .verify-confirm-overlay,
        .verify-confirm-modal {
            transition: none;
        }
    }
</style>

<div id="verify-confirm-overlay" class="verify-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="verifyConfirmTitle" aria-hidden="true">
    <div class="verify-confirm-modal">
        <h3 id="verifyConfirmTitle" class="verify-confirm-title">Konfirmasi</h3>
        <p id="verifyConfirmText" class="verify-confirm-text">Apakah Anda yakin?</p>
        <div class="verify-confirm-actions">
            <button type="button" id="verifyConfirmCancel" class="verify-confirm-btn-cancel">Batal</button>
            <button type="button" id="verifyConfirmSubmit" class="verify-confirm-btn-submit">Lanjutkan</button>
        </div>
    </div>
</div>

<!-- bulk-reject removed: verifikasi bulk is delete-only now -->

<!-- bulk-delete modal removed -->
<script>
    let pendingVerifikasiConfirmAction = null;

    function openVerifikasiConfirmModal(message, onConfirm) {
        const overlay = document.getElementById('verify-confirm-overlay');
        const text = document.getElementById('verifyConfirmText');
        if (!overlay || !text) return false;

        pendingVerifikasiConfirmAction = typeof onConfirm === 'function' ? onConfirm : null;
        text.textContent = message || 'Apakah Anda yakin?';
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');

        return false;
    }

    function closeVerifikasiConfirmModal() {
        const overlay = document.getElementById('verify-confirm-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        pendingVerifikasiConfirmAction = null;
    }

    function openVerifikasiFormConfirm(event, form, message) {
        if (event) {
            event.preventDefault();
        }

        return openVerifikasiConfirmModal(message, function () {
            form.submit();
        });
    }

    const verifyConfirmOverlay = document.getElementById('verify-confirm-overlay');
    const verifyConfirmCancel = document.getElementById('verifyConfirmCancel');
    const verifyConfirmSubmit = document.getElementById('verifyConfirmSubmit');

    verifyConfirmCancel?.addEventListener('click', closeVerifikasiConfirmModal);

    verifyConfirmOverlay?.addEventListener('click', function(event) {
        if (event.target === verifyConfirmOverlay) {
            closeVerifikasiConfirmModal();
        }
    });

    verifyConfirmSubmit?.addEventListener('click', function() {
        if (!pendingVerifikasiConfirmAction) return;
        const action = pendingVerifikasiConfirmAction;
        closeVerifikasiConfirmModal();
        action();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVerifikasiConfirmModal();
        }
    });

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

    // bulk feature removed from this view

function initVerifikasiAjaxSearch() {
    var searchForm = document.getElementById('verifikasiSearchForm');
    if (!searchForm) return;

    var searchInput = searchForm.querySelector('input[name="search"]');
    var jurusanSelect = document.getElementById('filter-jurusan');
    var kelasSelect = document.getElementById('filter-kelas');
    var rejectTypeSelect = document.getElementById('filter-reject-type');

    function performAjaxSearch(pageUrl = null) {
        var tableBody = document.getElementById('verifikasiTableBody');
        if (!tableBody) return;

        var formData = new FormData(searchForm);
        var params = new URLSearchParams(formData);

        var url = pageUrl ? pageUrl : (searchForm.action + '?' + params.toString());

        fetch(url, {
            headers: {
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');

            var newTbody = doc.getElementById('verifikasiTableBody');
            var oldTbody = document.getElementById('verifikasiTableBody');
            if (newTbody && oldTbody) {
                oldTbody.innerHTML = newTbody.innerHTML;
            }

            var newPagination = doc.querySelector('.pagination-container');
            var oldPagination = document.querySelector('.pagination-container');
            if (newPagination && oldPagination) {
                oldPagination.outerHTML = newPagination.outerHTML;
            } else if (oldPagination) {
                oldPagination.innerHTML = '';
            }

            window.history.replaceState({}, '', url);

            bindPaginationLinks();
            bindPerPageInputAjax();
        })
        .catch(error => console.error('Search error:', error));
    }

    function bindPaginationLinks() {
        document.querySelectorAll('.pagination-container .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                performAjaxSearch(this.href);
            });
        });
    }

    function bindPerPageInputAjax() {
        var perPageInput = document.querySelector('.pagination-container input[name="per_page"]');
        if (!perPageInput || perPageInput.dataset.bound === '1') return;

        var perPageForm = perPageInput.closest('form');
        if (perPageForm) {
            perPageForm.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        }

        var timer = null;
        perPageInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                var mainPerPage = searchForm.querySelector('input[name="per_page"]');
                if (mainPerPage) mainPerPage.value = this.value;
                performAjaxSearch();
            }, 300);
        });

        perPageInput.addEventListener('change', function() {
            var mainPerPage = searchForm.querySelector('input[name="per_page"]');
            if (mainPerPage) mainPerPage.value = this.value;
            performAjaxSearch();
        });

        perPageInput.dataset.bound = '1';
    }

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        performAjaxSearch();
    });

    if (searchInput) {
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                performAjaxSearch();
            }
        });

        var searchTimer = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(performAjaxSearch, 300);
        });
    }

    if (jurusanSelect) {
        jurusanSelect.addEventListener('change', function () {
            setTimeout(performAjaxSearch, 50);
        });
    }

    if (kelasSelect) {
        kelasSelect.addEventListener('change', function () {
            performAjaxSearch();
        });
    }

    if (rejectTypeSelect) {
        rejectTypeSelect.addEventListener('change', function () {
            performAjaxSearch();
        });
    }

    bindPaginationLinks();
    bindPerPageInputAjax();
}

initVerifikasiAjaxSearch();
</script>
@endsection
