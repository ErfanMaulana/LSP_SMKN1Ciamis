@extends('admin.layout')

@section('title', 'Jadwal Ujikom')
@section('page-title', 'Jadwal Uji Kompetensi')

@section('content')
<style>
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

    .badge.dijadwalkan { background: #dbeafe; color: #1e40af; }
    .badge.berlangsung { background: #fef3c7; color: #92400e; }
    .badge.selesai     { background: #d1fae5; color: #065f46; }
    .badge.dibatalkan  { background: #fee2e2; color: #991b1b; }

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

<!-- Header -->
<div class="page-header">
    <div>
        <h2>Kelola Jadwal Uji Kompetensi</h2>
        <p class="subtitle">Kelola jadwal pelaksanaan uji kompetensi dan penempatan TUK</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.jadwal-ujikom.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Jadwal
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-calendar3"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">TOTAL JADWAL</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-clock-history"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">DIJADWALKAN</div>
            <div class="stat-value">{{ number_format($stats['dijadwalkan']) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-play-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">BERLANGSUNG</div>
            <div class="stat-value">{{ number_format($stats['berlangsung']) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">SELESAI</div>
            <div class="stat-value">{{ number_format($stats['selesai']) }}</div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card">
    <div class="card-body">
        <div class="filter-section">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari judul, TUK, skema..." autocomplete="off">
            </div>
            <div class="filter-controls">
                <input type="month" id="bulanFilter" value="{{ $bulan }}">
                <select class="filter-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan">Dijadwalkan</option>
                    <option value="berlangsung">Berlangsung</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>JADWAL</th>
                        <th>SKEMA</th>
                        <th>TUK</th>
                        <th>WAKTU</th>
                        <th>KUOTA</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody id="jadwalTableBody">
                    @include('admin.jadwal-ujikom.partials.table-rows')
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // AJAX Search dengan Debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const bulanFilter = document.getElementById('bulanFilter');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('jadwalTableBody');

    function performSearch() {
        const searchValue = searchInput.value;
        const bulanValue = bulanFilter.value;
        const statusValue = statusFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (bulanValue) params.append('bulan', bulanValue);
        if (statusValue) params.append('status', statusValue);

        // Show loading indicator
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
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
        fetch(`{{ route('admin.jadwal-ujikom.index') }}?${params.toString()}`, {
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
                    <td colspan="7" class="text-center">
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
    bulanFilter.addEventListener('change', performSearch);
    statusFilter.addEventListener('change', performSearch);

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
