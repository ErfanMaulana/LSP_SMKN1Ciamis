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
        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'pending']) }}" 
           class="stat-card {{ $status === 'pending' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">MENUNGGU</div>
                <div class="stat-value">{{ $counts['pending'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'approved']) }}" 
           class="stat-card {{ $status === 'approved' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DISETUJUI</div>
                <div class="stat-value">{{ $counts['approved'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'rejected']) }}" 
           class="stat-card {{ $status === 'rejected' ? 'stat-card-active' : '' }}">
            <div class="stat-icon blue">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DITOLAK</div>
                <div class="stat-value">{{ $counts['rejected'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi') }}" 
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
                    <input type="text" id="searchInput" autocomplete="off" placeholder="Cari nama, NIK, atau email..." style="flex:1;">
                </div>
                
                <div class="filter-controls">
                    <select id="jurusanFilter" class="filter-select">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList as $jurusan)
                            <option value="{{ $jurusan->ID_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                    
                    <select id="sortFilter" class="filter-select">
                        <option value="">Terbaru</option>
                        <option value="asc">Nama A-Z</option>
                        <option value="desc">Nama Z-A</option>
                    </select>
                </div>
            </div>

            @if($asesi->count() > 0)
                <!-- Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ASESI</th>
                                <th>NIK</th>
                                <th>JURUSAN</th>
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
                    <div class="pagination-info">
                        Showing {{ $asesi->firstItem() }} to {{ $asesi->lastItem() }} of {{ $asesi->total() }} entries
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

<script>
    // AJAX Search Implementation
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const jurusanFilter = document.getElementById('jurusanFilter');
    const sortFilter = document.getElementById('sortFilter');
    const tableBody = document.getElementById('verifikasiTableBody');
    const currentStatus = '{{ $status }}';

    function performSearch() {
        const searchValue = searchInput.value;
        const jurusanValue = jurusanFilter.value;
        const sortValue = sortFilter.value;
        
        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (currentStatus) params.append('status', currentStatus);
        if (jurusanValue) params.append('jurusan', jurusanValue);
        if (sortValue) params.append('sort', sortValue);
        
        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center; padding:40px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="margin-top:12px; color:#64748b;">Mencari data...</p>
                </td>
            </tr>
        `;
        
        // Perform AJAX request
        fetch(`{{ route('admin.asesi.verifikasi') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px;">
                        <i class="bi bi-exclamation-triangle" style="font-size:48px; color:#ef4444;"></i>
                        <p style="margin-top:12px; color:#64748b;">Terjadi kesalahan saat memuat data</p>
                    </td>
                </tr>
            `;
        });
    }

    // Debounced search on input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Immediate search on filter change
    jurusanFilter.addEventListener('change', performSearch);
    sortFilter.addEventListener('change', performSearch);

    // Dropdown Toggle Function
    function toggleDropdown(event) {
        event.stopPropagation();
        const button = event.currentTarget;
        const dropdown = button.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        
        // Close all other dropdowns
        allDropdowns.forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.remove('show');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-action')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>

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

    /* Filter Controls */
    .filter-controls {
        display: flex;
        gap: 12px;
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

    /* Empty State Row (for AJAX) */
    .empty-state-row {
        text-align: center;
        padding: 60px 20px !important;
    }

    .empty-state-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .empty-state-content i {
        font-size: 48px;
        color: #d1d5db;
    }

    .empty-state-content p {
        color: #9ca3af;
        font-size: 14px;
        margin: 0;
    }

    /* Spinner for loading state */
    .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    .spinner-border.text-primary {
        color: #0073bd;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* Dropdown Action Menu */
    .dropdown-action {
        position: relative;
        display: inline-block;
    }

    .btn-dropdown {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        color: #64748b;
        border-radius: 6px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-dropdown:hover {
        background: #f1f5f9;
        color: #0F172A;
    }

    .btn-dropdown i {
        font-size: 18px;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 4px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        min-width: 160px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-item:first-child {
        border-radius: 8px 8px 0 0;
    }

    .dropdown-item:last-child {
        border-radius: 0 0 8px 8px;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .dropdown-item i {
        font-size: 16px;
    }

    .dropdown-item.danger {
        color: #dc2626;
    }

    .dropdown-item.danger:hover {
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

        .filter-controls {
            width: 100%;
        }

        .filter-select {
            flex: 1;
            min-width: 0;
        }

        .table-container {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endsection
