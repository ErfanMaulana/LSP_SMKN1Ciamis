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

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-body">
            <!-- Toolbar -->
            <form method="GET" action="{{ route('admin.skema.index') }}" class="toolbar" id="filterForm">
                <div class="filter-section">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor skema..." autocomplete="off">
                    </div>
                    <div class="filter-group">
                        <select class="filter-select" name="jenis_skema" id="jenisSkemaFilter">
                            <option value="all" {{ request('jenis_skema', 'all') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="KKNI" {{ request('jenis_skema') === 'KKNI' ? 'selected' : '' }}>KKNI</option>
                            <option value="Okupasi" {{ request('jenis_skema') === 'Okupasi' ? 'selected' : '' }}>Okupasi</option>
                            <option value="Klaster" {{ request('jenis_skema') === 'Klaster' ? 'selected' : '' }}>Klaster</option>
                        </select>
                        <select class="filter-select" name="sort" id="sortFilter">
                            <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="nama_skema" {{ request('sort') === 'nama_skema' ? 'selected' : '' }}>Nama Skema</option>
                            <option value="nomor_skema" {{ request('sort') === 'nomor_skema' ? 'selected' : '' }}>Nomor Skema</option>
                        </select>
                        <select class="filter-select" name="order" id="orderFilter">
                            <option value="asc" {{ request('order', 'desc') === 'asc' ? 'selected' : '' }}>A → Z</option>
                            <option value="desc" {{ request('order', 'desc') === 'desc' ? 'selected' : '' }}>Z → A</option>
                        </select>
                        <button type="submit" class="btn-filter-search" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search') || request('jenis_skema', 'all') !== 'all' || request('sort', 'created_at') !== 'created_at' || request('order', 'desc') !== 'desc')
                        <a href="{{ route('admin.skema.index') }}" class="btn-filter-reset" title="Reset filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>

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
                        @forelse($skemas as $skema)
                        <tr>
                            <td>
                                <span class="code-badge">{{ $skema->nomor_skema }}</span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="skema-icon {{ strtolower($skema->jenis_skema) }}">
                                        <i class="bi {{ $skema->jenis_skema === 'KKNI' ? 'bi-patch-check' : ($skema->jenis_skema === 'Okupasi' ? 'bi-briefcase' : 'bi-diagram-3') }}"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $skema->nama_skema }}</div>
                                        <div class="user-id">{{ $skema->nomor_skema }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($skema->jenis_skema) }}">
                                    {{ $skema->jenis_skema }}
                                </span>
                            </td>
                            <td>
                                @if($skema->jurusan)
                                    <span class="text-xs font-medium">{{ $skema->jurusan->nama_jurusan }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="date-text">{{ $skema->created_at ? $skema->created_at->format('d M Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button type="button" class="action-btn" onclick="toggleMenu(event, this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.skema.show', $skema->id) }}">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                        <a href="{{ route('admin.skema.edit', $skema->id) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.skema.destroy', $skema->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus skema ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data skema sertifikasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container" id="skemaPaginationContainer">
                <div class="pagination-info" id="skemaPaginationInfo">
                    Menampilkan {{ $skemas->firstItem() ?? 0 }} sampai {{ $skemas->lastItem() ?? 0 }} dari {{ $skemas->total() }} data
                </div>
                <div class="pagination" id="skemaPaginationLinks">
                    {{ $skemas->links() }}
                </div>
            </div>
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

    /* Toolbar */
    .toolbar {
        margin-bottom: 20px;
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        gap: 16px;
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

    .btn-filter-search:hover {
        background: #005f99;
    }

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

    .btn-filter-reset:hover {
        background: #fecaca;
    }

    /* Table */
    .table-container {
        overflow: visible;
    }

    .table-container > .admin-table-scroll {
        overflow: visible !important;
        max-height: none !important;
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
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('.table-container .data-table');
        const wrapper = table ? table.closest('.admin-table-scroll') : null;

        if (table && wrapper && wrapper.parentNode) {
            wrapper.parentNode.insertBefore(table, wrapper);
            wrapper.remove();
        }
    });

    function toggleMenu(event, button) {
        if (event) event.stopPropagation();
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

    if (!window.__skemaAjaxInitialized) {
        window.__skemaAjaxInitialized = true;

        const filterForm = document.getElementById('filterForm');
        const tableBody = document.getElementById('skemaTableBody');
        const paginationInfo = document.getElementById('skemaPaginationInfo');
        const paginationLinks = document.getElementById('skemaPaginationLinks');
        const autoFilters = ['jenisSkemaFilter', 'sortFilter', 'orderFilter'];
        const searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;

        async function fetchSkemaData(page = null) {
            if (!filterForm || !tableBody || !paginationLinks || !paginationInfo) return;

            const params = new URLSearchParams(new FormData(filterForm));
            if (page) params.set('page', page);

            const requestUrl = `${filterForm.action}?${params.toString()}`;

            try {
                const response = await fetch(requestUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Gagal memuat data skema');

                const data = await response.json();
                tableBody.innerHTML = data.rows || '';
                paginationInfo.textContent = data.pagination_info || 'Menampilkan 0 sampai 0 dari 0 data';
                paginationLinks.innerHTML = data.pagination_links || '';

                const url = new URL(window.location.href);
                url.search = params.toString();
                window.history.replaceState({}, '', url.toString());
            } catch (error) {
                console.error('AJAX skema error:', error);
            }
        }

        if (filterForm) {
            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                fetchSkemaData();
            });
        }

        autoFilters.forEach(function (id) {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', function () {
                    fetchSkemaData();
                });
            }
        });

        let searchDebounce;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(function () {
                    fetchSkemaData();
                }, 350);
            });
        }

        paginationLinks.addEventListener('click', function (event) {
            const link = event.target.closest('a');
            if (!link) return;

            event.preventDefault();
            const linkUrl = new URL(link.href, window.location.origin);
            fetchSkemaData(linkUrl.searchParams.get('page'));
        });
    }
</script>
@endsection
