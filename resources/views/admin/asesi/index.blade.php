@extends('admin.layout')

@section('title', 'Asesi Management')
@section('page-title', 'Asesi Management')

@section('content')
<div class="asesi-management">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>Asesi Management</h2>
            <p class="subtitle">Manage and monitor all candidates in the certification system.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline">
                <i class="bi bi-file-earmark-excel"></i> Import Asesi (Excel)
            </button>
            <a href="{{ route('admin.asesi.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Asesi
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL ASESI</div>
                <div class="stat-value">{{ $asesi->total() }}</div>
            </div>
        </div>

        <!-- <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-infinity"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">LIFETIME</div>
                <div class="stat-value">{{ $asesi->total() }}</div>
            </div>
        </div> -->

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-person-plus"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">REGISTERED THIS MONTH</div>
                <div class="stat-value">142 <span class="stat-change positive">+78%</span></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">IN ASSESSMENT</div>
                <div class="stat-value">85 <span class="stat-subtitle">Active</span></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-award"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">CERTIFIED</div>
                <div class="stat-value">2,914 <span class="stat-subtitle">Total</span></div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search by name or ID...">
                </div>
                <div class="filter-group">
                    <select class="filter-select">
                        <option>Filter: All Schemes</option>
                        <option>Software Engineering</option>
                        <option>Cloud Infrastructure</option>
                        <option>Data Analyst</option>
                        <option>Network Systems</option>
                    </select>
                    <select class="filter-select">
                        <option>All Status</option>
                        <option>Completed</option>
                        <option>In Progress</option>
                        <option>Scheduled</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>SCHEME/PROGRAM</th>
                            <th>ASSESSMENT STATUS</th>
                            <th>DATE REGISTERED</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asesi as $item)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar-initials">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $item->nama }}</div>
                                        <div class="user-id">{{ $item->NIK }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="scheme-text">{{ $item->jurusan->nama_jurusan ?? 'Not Assigned' }}</span>
                            </td>
                            <td>
                                @php
                                    $statuses = ['Completed', 'In Progress', 'Scheduled'];
                                    $status = $statuses[array_rand($statuses)];
                                    $badgeClass = $status === 'Completed' ? 'badge-success' : ($status === 'In Progress' ? 'badge-info' : 'badge-warning');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
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
                                        <a href="{{ route('admin.asesi.edit', $item->NIK) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="#">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        <form action="{{ route('admin.asesi.destroy', $item->NIK) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No asesi data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $asesi->firstItem() ?? 0 }} to {{ $asesi->lastItem() ?? 0 }} of {{ $asesi->total() }} entries
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
        </div>
    </div>
</div>

<style>
    .asesi-management {
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

    .btn-outline {
        background: white;
        color: #0F172A;
        border: 1px solid #e2e8f0;
    }

    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
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
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
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

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #dbeafe;
        color: #004a7a;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
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
