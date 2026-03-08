@extends('admin.layout')

@section('title', 'Manajemen Asesor')
@section('page-title', 'Manajemen Asesor')

@section('content')
<div class="asesor-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Kelola Asesor</h2>
            <p class="subtitle">Kelola dan awasi semua asesor yang terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('admin.asesor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Asesor Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL ASESOR</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">PUNYA AKUN</div>
                <div class="stat-value">{{ $stats['with_account'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DENGAN SKEMA</div>
                <div class="stat-value">{{ $stats['with_skema'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TANPA SKEMA</div>
                <div class="stat-value">{{ $stats['without_skema'] }}</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau ID..." autocomplete="off">
                </div>
                <div class="filter-controls">
                    <select class="filter-select" id="keahlianFilter">
                        <option value="">Keahlian: Semua</option>
                        @foreach(\App\Models\Skema::orderBy('nama_skema')->get() as $sk)
                            <option value="{{ $sk->id }}">{{ $sk->nama_skema }}</option>
                        @endforeach
                    </select>
                    <select class="filter-select" id="sortFilter">
                        <option value="">Terbaru</option>
                        <option value="asc">A - Z</option>
                        <option value="desc">Z - A</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NAMA ASESOR</th>
                            <th>KEAHLIAN</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="asesorTableBody">
                        @include('admin.asesor.partials.table-rows')
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
        background: #003961;
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
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 4px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        min-width: 160px;
        z-index: 10;
        overflow: hidden;
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

    .action-dropdown button[type="submit"]:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Dropdown Action */
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
</style>

<script>
    function toggleDropdown(button) {
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

    // AJAX Search dengan Debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const keahlianFilter = document.getElementById('keahlianFilter');
    const sortFilter = document.getElementById('sortFilter');
    const tableBody = document.getElementById('asesorTableBody');

    function performSearch() {
        const searchValue = searchInput.value;
        const keahlianValue = keahlianFilter.value;
        const sortValue = sortFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (keahlianValue) params.append('keahlian', keahlianValue);
        if (sortValue) params.append('sort', sortValue);

        // Show loading indicator
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center">
                    <div style="padding: 40px 20px;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; margin-bottom: 12px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p style="color: #64748b; margin: 0;">Mencari data...</p>
                    </div>
                </td>
            </tr>
        `;

        // Perform AJAX request
        fetch(`{{ route('admin.asesor.index') }}?${params.toString()}`, {
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
                    <td colspan="4" class="text-center">
                        <div style="padding: 40px 20px;">
                            <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #ef4444; display: block; margin-bottom: 12px;"></i>
                            <p style="color: #64748b; margin: 0;">Terjadi kesalahan saat memuat data</p>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    // Search input with debounce (delay 500ms)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Filter changes trigger immediate search
    keahlianFilter.addEventListener('change', performSearch);
    sortFilter.addEventListener('change', performSearch);
</script>
@endsection
