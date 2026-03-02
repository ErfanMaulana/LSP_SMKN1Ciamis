@extends('admin.layout')

@section('title', 'Skema Sertifikasi Management')
@section('page-title', 'Skema Sertifikasi Management')

@section('content')
<div class="skema-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Skema Sertifikasi</h2>
            <p class="subtitle">Kelola dan organisasi semua skema sertifikasi kompetensi.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.skema.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Skema Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL SKEMA</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-award"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">SKEMA KKNI</div>
                <div class="stat-value">{{ $stats['kkni'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-briefcase"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">SKEMA OKUPASI</div>
                <div class="stat-value">{{ $stats['okupasi'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-diagram-3"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">SKEMA KLASTER</div>
                <div class="stat-value">{{ $stats['klaster'] }}</div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama atau nomor skema..." autocomplete="off">
                </div>
                <div class="filter-controls">
                    <select class="filter-select" id="jenisFilter">
                        <option value="all">Semua Jenis</option>
                        <option value="KKNI">KKNI</option>
                        <option value="Okupasi">Okupasi</option>
                        <option value="Klaster">Klaster</option>
                    </select>
                    <select class="filter-select" id="sortFilter">
                        <option value="created_at">Tanggal Dibuat</option>
                        <option value="nama_skema">Nama Skema</option>
                        <option value="nomor_skema">Nomor Skema</option>
                    </select>
                    <select class="filter-select" id="orderFilter">
                        <option value="asc">A → Z</option>
                        <option value="desc">Z → A</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NOMOR SKEMA</th>
                            <th>NAMA SKEMA</th>
                            <th>JENIS SKEMA</th>
                            <th>JURUSAN</th>
                            <th>TANGGAL DIBUAT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="skemaTableBody">
                        @include('admin.skema.partials.table-rows')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($skemas->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $skemas->firstItem() ?? 0 }} sampai {{ $skemas->lastItem() ?? 0 }} dari {{ $skemas->total() }} data
                </div>
                <div class="pagination">
                    @if($skemas->currentPage() > 1)
                        <a href="{{ $skemas->previousPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif
                    
                    @for($i = 1; $i <= min($skemas->lastPage(), 5); $i++)
                        <a href="{{ $skemas->url($i) }}" class="page-link {{ $i == $skemas->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($skemas->lastPage() > 5)
                        <span class="page-dots">...</span>
                        <a href="{{ $skemas->url($skemas->lastPage()) }}" class="page-link">{{ $skemas->lastPage() }}</a>
                    @endif
                    
                    @if($skemas->hasMorePages())
                        <a href="{{ $skemas->nextPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .skema-management {
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

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        flex-shrink: 0;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.yellow { background: linear-gradient(135deg, #eab308, #ca8a04); }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 12px;
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

    /* Alert Messages */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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
        font-size: 12px;
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

    .skema-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .skema-icon.kkni {
        background: #dbeafe;
        color: #004a7a;
    }

    .skema-icon.okupasi {
        background: #fef3c7;
        color: #92400e;
    }

    .skema-icon.klaster {
        background: #e9d5ff;
        color: #6b21a8;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 12px;
        font-weight: 600;
        color: #0F172A;
    }

    .user-id {
        font-size: 12px;
        color: #64748b;
    }

    .code-badge {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }

    .badge {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-kkni {
        background: #dbeafe;
        color: #004a7a;
    }

    .badge-okupasi {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-klaster {
        background: #e9d5ff;
        color: #6b21a8;
    }

    .date-text {
        font-size: 12px;
        color: #475569;
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
        padding: 16px;
        color: #94a3b8;
        font-size: 14px;
        font-weight: 500;
    }

    /* Spinner/Loading */
    .spinner-border {
        display: inline-block;
        width: 3rem;
        height: 3rem;
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

    /* Responsive Design */
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

<script>
    // AJAX Search Implementation
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const jenisFilter = document.getElementById('jenisFilter');
    const sortFilter = document.getElementById('sortFilter');
    const orderFilter = document.getElementById('orderFilter');
    const tableBody = document.getElementById('skemaTableBody');

    // Set initial values from request
    jenisFilter.value = '{{ request("jenis_skema", "all") }}';
    sortFilter.value = '{{ request("sort", "created_at") }}';
    orderFilter.value = '{{ request("order", "desc") }}';

    function performSearch() {
        const searchValue = searchInput.value;
        const jenisValue = jenisFilter.value;
        const sortValue = sortFilter.value;
        const orderValue = orderFilter.value;
        
        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (jenisValue && jenisValue !== 'all') params.append('jenis_skema', jenisValue);
        if (sortValue) params.append('sort', sortValue);
        if (orderValue) params.append('order', orderValue);
        
        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center; padding:40px;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; margin-bottom: 12px;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="color: #64748b; margin: 0;">Mencari data...</p>
                </td>
            </tr>
        `;
        
        // Perform AJAX request
        fetch(`{{ route('admin.skema.index') }}?${params.toString()}`, {
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
                        <i class="bi bi-exclamation-triangle" style="font-size:48px; color:#ef4444; display:block; margin-bottom:12px;"></i>
                        <p style="color: #64748b; margin: 0;">Terjadi kesalahan saat memuat data</p>
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
    jenisFilter.addEventListener('change', performSearch);
    sortFilter.addEventListener('change', performSearch);
    orderFilter.addEventListener('change', performSearch);

    // Toggle menu functionality
    function toggleMenu(button) {
        // Close all other dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
            if (dropdown !== button.nextElementSibling) {
                dropdown.classList.remove('show');
            }
        });
        
        // Toggle current dropdown
        button.nextElementSibling.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
</script>
@endsection
