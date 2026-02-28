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
                    <form method="GET" action="{{ route('admin.asesi.verifikasi') }}" style="display:flex;gap:8px;width:100%;">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau email..." style="flex:1;">
                        <button type="submit" style="padding:10px 16px;background:#0073bd;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:500;">Cari</button>
                    </form>
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
                        <tbody>
                            @foreach($asesi as $item)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        @if($item->pas_foto)
                                            <img src="{{ asset('storage/' . $item->pas_foto) }}" alt="Foto" class="user-avatar-img">
                                        @else
                                            <div class="user-avatar-initials">
                                                {{ strtoupper(substr($item->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="user-details">
                                            <div class="user-name">{{ $item->nama }}</div>
                                            <div class="user-id">{{ $item->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="nik-text">{{ $item->NIK }}</span>
                                </td>
                                <td>
                                    <span class="scheme-text">{{ $item->jurusan->nama_jurusan ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}</span>
                                </td>
                                <td>
                                    @if($item->status === 'pending')
                                        <span class="badge badge-pending">Menunggu</span>
                                    @elseif($item->status === 'approved')
                                        <span class="badge badge-approved">Disetujui</span>
                                    @else
                                        <span class="badge badge-rejected">Ditolak</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}" class="btn-sm btn-view">
                                        <i class="bi bi-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
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

        .table-container {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endsection
