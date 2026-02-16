@extends('admin.layout')

@section('title', 'Jurusan Management')
@section('page-title', 'Jurusan Management')

@section('content')
<div class="jurusan-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Jurusan Management</h2>
            <p class="subtitle">Manage and organize all majors and academic programs.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Jurusan
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL JURUSAN</div>
                <div class="stat-value">{{ $jurusan->total() }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">ACTIVE PROGRAMS</div>
                <div class="stat-value">{{ $jurusan->where('created_at', '>=', now()->subYear())->count() }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL STUDENTS</div>
                <div class="stat-value">{{ $jurusan->sum('asesi_count') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">AVERAGE STUDENTS</div>
                <div class="stat-value">{{ $jurusan->total() > 0 ? round($jurusan->sum('asesi_count') / $jurusan->total()) : 0 }}</div>
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

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search by name or code...">
                </div>
                <div class="filter-group">
                    <select class="filter-select">
                        <option>Sort By: Name</option>
                        <option>Sort By: Code</option>
                        <option>Sort By: Students</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>JURUSAN NAME</th>
                            <th>CODE</th>
                            <th>TOTAL STUDENTS</th>
                            <th>DATE CREATED</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurusan as $item)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="program-icon">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $item->nama_jurusan }}</div>
                                        <div class="user-id">{{ $item->kode_jurusan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="code-badge">{{ $item->kode_jurusan }}</span>
                            </td>
                            <td>
                                <span class="student-count">
                                    <i class="bi bi-people"></i> {{ $item->asesi_count }} Students
                                </span>
                            </td>
                            <td>
                                <span class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(this)">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.jurusan.edit', $item->ID_jurusan) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="#">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        <form action="{{ route('admin.jurusan.destroy', $item->ID_jurusan) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure? This will delete the jurusan if there are no related students.')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No jurusan data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $jurusan->firstItem() ?? 0 }} to {{ $jurusan->lastItem() ?? 0 }} of {{ $jurusan->total() }} entries
                </div>
                <div class="pagination">
                    @if($jurusan->currentPage() > 1)
                        <a href="{{ $jurusan->previousPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif
                    
                    @for($i = 1; $i <= min($jurusan->lastPage(), 5); $i++)
                        <a href="{{ $jurusan->url($i) }}" class="page-link {{ $i == $jurusan->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($jurusan->lastPage() > 5)
                        <span class="page-dots">...</span>
                        <a href="{{ $jurusan->url($jurusan->lastPage()) }}" class="page-link">{{ $jurusan->lastPage() }}</a>
                    @endif
                    
                    @if($jurusan->hasMorePages())
                        <a href="{{ $jurusan->nextPageUrl() }}" class="page-link">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .jurusan-management {
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
        background: #0F172A;
        color: white;
    }

    .btn-primary:hover {
        background: #1e293b;
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

    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
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
        display: flex;
        align-items: baseline;
        gap: 8px;
        flex-wrap: wrap;
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

    .program-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
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

    .code-badge {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }

    .student-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #475569;
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
