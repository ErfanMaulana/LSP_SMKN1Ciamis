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
            <!-- Toolbar -->
            <div class="toolbar">
                <form method="GET" action="{{ route('admin.skema.index') }}" class="search-form">
                    <input type="hidden" name="jenis_skema" value="{{ request('jenis_skema', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                    <input type="hidden" name="order" value="{{ request('order', 'desc') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor skema...">
                    <button type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if(request('search'))
                        <a href="{{ route('admin.skema.index') }}" class="btn-clear" title="Clear">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </form>

                <div class="filters">
                    <form method="GET" action="{{ route('admin.skema.index') }}" class="filter-form" id="filterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="jenis_skema" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ request('jenis_skema', 'all') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="KKNI" {{ request('jenis_skema') === 'KKNI' ? 'selected' : '' }}>KKNI</option>
                            <option value="Okupasi" {{ request('jenis_skema') === 'Okupasi' ? 'selected' : '' }}>Okupasi</option>
                            <option value="Klaster" {{ request('jenis_skema') === 'Klaster' ? 'selected' : '' }}>Klaster</option>
                        </select>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()">
                            <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="nama_skema" {{ request('sort') === 'nama_skema' ? 'selected' : '' }}>Nama Skema</option>
                            <option value="nomor_skema" {{ request('sort') === 'nomor_skema' ? 'selected' : '' }}>Nomor Skema</option>
                        </select>
                        <select name="order" onchange="document.getElementById('filterForm').submit()">
                            <option value="asc" {{ request('order', 'desc') === 'asc' ? 'selected' : '' }}>A → Z</option>
                            <option value="desc" {{ request('order', 'desc') === 'desc' ? 'selected' : '' }}>Z → A</option>
                        </select>
                    </form>
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
                            <th>TANGGAL DIBUAT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <span class="date-text">{{ $skema->created_at ? $skema->created_at->format('d M Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(this)">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.skema.edit', $skema->id) }}">
                                            <i class="bi bi-eye"></i> View
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
                            <td colspan="5" class="text-center">Belum ada data skema sertifikasi</td>
                        </tr>
                        @endforelse
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
        font-size: 28px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-form {
        display: flex;
        gap: 8px;
        flex: 1;
        min-width: 300px;
    }

    .search-form input[type="text"] {
        flex: 1;
        padding: 9px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-form input[type="text"]:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .search-form button {
        padding: 9px 16px;
        background: #0073bd;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }

    .search-form button:hover {
        background: #0073bd;
    }

    .btn-clear {
        padding: 9px;
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none;
        width: 38px;
        height: 38px;
    }

    .btn-clear:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .filters {
        display: flex;
        gap: 8px;
    }

    .filter-form {
        display: flex;
        gap: 8px;
    }

    .filter-form select {
        padding: 8px 32px 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        background: white;
        cursor: pointer;
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        transition: border-color 0.2s;
    }

    .filter-form select:focus {
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
        font-size: 14px;
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
        font-size: 14px;
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
        padding: 40px 20px;
        color: #64748b;
    }
</style>

<script>
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
