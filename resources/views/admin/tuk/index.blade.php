@extends('admin.layout')

@section('title', 'Manajemen TUK')
@section('page-title', 'TUK (Tempat Uji Kompetensi)')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card { background: white; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .stat-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0061a5); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.red    { background: linear-gradient(135deg,#ef4444,#dc2626); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.5px; }
    .stat-value { font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

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

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .btn-add {
        padding: 9px 18px; background: #0061a5; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .btn-add:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b;
         text-transform: uppercase; letter-spacing: .5px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.aktif    { background: #d1fae5; color: #065f46; }
    .badge.nonaktif { background: #fee2e2; color: #991b1b; }
    .badge.sewaktu  { background: #dbeafe; color: #1e40af; }
    .badge.tempat_kerja { background: #fef3c7; color: #92400e; }
    .badge.mandiri  { background: #ede9fe; color: #5b21b6; }

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
        opacity: 1; visibility: visible; transform: translateY(0);
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
    .dropdown-item:first-child { border-radius: 8px 8px 0 0; }
    .dropdown-item:last-child { border-radius: 0 0 8px 8px; }
    .dropdown-item:hover {
        background: #f8fafc;
    }
    .dropdown-item.danger {
        color: #dc2626;
    }
    .dropdown-item.danger:hover {
        background: #fef2f2;
    }
    .dropdown-item i {
        font-size: 14px; width: 16px;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p  { font-size: 14px; }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        color: #64748b;
        font-size: 13px;
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

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }

    .text-primary { color: #0073bd; }
    .text-center { text-align: center; }

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

    .pagination-wrap { padding: 16px; }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
@endif

<!-- Header -->
<div class="page-header">
    <div>
        <h2>Tempat Uji Kompetensi (TUK)</h2>
        <p>Kelola data tempat uji kompetensi untuk pelaksanaan ujian sertifikasi</p>
    </div>
    <a href="{{ route('admin.tuk.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah TUK
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-building"></i></div>
        <div>
            <div class="stat-label">Total TUK</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">TUK Aktif</div>
            <div class="stat-value">{{ $stats['aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-label">Non-Aktif</div>
            <div class="stat-value">{{ $stats['nonaktif'] }}</div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card">
    <div style="padding: 24px;">
        <div class="filter-section">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama TUK, kode, kota..." autocomplete="off">
            </div>
            <div class="filter-controls">
                <select class="filter-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama TUK</th>
                        <th>Tipe</th>
                        <th>Kota</th>
                        <th>Kapasitas</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tukTableBody">
                    @include('admin.tuk.partials.table-rows')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <div class="pagination-info">
                Menampilkan {{ $tuks->firstItem() ?? 0 }} sampai {{ $tuks->lastItem() ?? 0 }} dari {{ $tuks->total() }} data
            </div>
        </div>
    </div>
</div>

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
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('tukTableBody');

    function performSearch() {
        const searchValue = searchInput.value;
        const statusValue = statusFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (searchValue) params.append('search', searchValue);
        if (statusValue) params.append('status', statusValue);

        // Show loading indicator
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center">
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
        fetch(`{{ route('admin.tuk.index') }}?${params.toString()}`, {
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
                    <td colspan="8" class="text-center">
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
    statusFilter.addEventListener('change', performSearch);
</script>

@endsection
