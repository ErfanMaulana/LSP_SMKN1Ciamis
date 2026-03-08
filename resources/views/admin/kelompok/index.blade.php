@extends('admin.layout')

@section('title', 'Kelompok')
@section('page-title', 'Kelompok')

@section('content')
<style>
    .kelompok-management {
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
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }

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
        margin-bottom: 24px;
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
        padding: 14px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #374151;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-green {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-gray {
        background: #f1f5f9;
        color: #94a3b8;
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
        color: #ef4444;
    }

    .dropdown-item.danger:hover {
        background: #fee2e2;
        color: #ef4444;
    }

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

    .text-primary {
        color: #0073bd;
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
        border-width: 0;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>

<div class="kelompok-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Kelola Kelompok</h2>
            <p class="subtitle">Kelola kelompok uji kompetensi, asesor, dan asesi</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kelompok
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-collection"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL KELOMPOK</div>
                <div class="stat-value">{{ number_format($stats['total_kelompok']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">KELOMPOK AKTIF</div>
                <div class="stat-value">{{ number_format($stats['kelompok_aktif']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">ASESI DITUGASKAN</div>
                <div class="stat-value">{{ number_format($stats['total_asesi']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-person-dash"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">ASESI BELUM DITUGASKAN</div>
                <div class="stat-value">{{ number_format($stats['belum_ditugaskan']) }}</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari berdasarkan kelompok atau asesor..." autocomplete="off">
                </div>
                <div class="filter-controls">
                    <select class="filter-select" id="skemaFilter">
                        <option value="">Semua Skema</option>
                        @foreach($skemaList as $skema)
                            <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>KELOMPOK</th>
                            <th>SKEMA</th>
                            <th>ASESOR</th>
                            <th>JUMLAH ASESI</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="kelompokTableBody">
                        @include('admin.kelompok.partials.table-rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // AJAX Search dengan Debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const skemaFilter = document.getElementById('skemaFilter');
    const tableBody = document.getElementById('kelompokTableBody');

    function performSearch() {
        const searchValue = searchInput.value;
        const skemaValue = skemaFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (skemaValue) params.append('skema', skemaValue);

        // Show loading indicator
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">
                    <div style="padding: 40px 20px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p style="color: #64748b; margin: 0; margin-top: 12px;">Mencari data...</p>
                    </div>
                </td>
            </tr>
        `;

        // Perform AJAX request
        fetch(`{{ route('admin.kelompok.index') }}?${params.toString()}`, {
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
                    <td colspan="6" class="text-center">
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
    skemaFilter.addEventListener('change', performSearch);

    // Dropdown toggle function
    function toggleDropdown(button, event) {
        event.stopPropagation();
        const menu = button.nextElementSibling;

        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu.show').forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });

        // Toggle current dropdown
        menu.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-action')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>

@endsection
