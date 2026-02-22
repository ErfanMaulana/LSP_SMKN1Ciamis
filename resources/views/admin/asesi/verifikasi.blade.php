@extends('admin.layout')

@section('title', 'Verifikasi Asesi')
@section('page-title', 'Verifikasi Pendaftaran Asesi')

@section('styles')
<style>
    .verifikasi-page .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .verifikasi-page .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .verifikasi-page .stat-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59,130,246,0.1);
    }

    .verifikasi-page .stat-card.active-pending {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .verifikasi-page .stat-card.active-approved {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .verifikasi-page .stat-card.active-rejected {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .stat-icon-box.pending { background: #fef3c7; color: #d97706; }
    .stat-icon-box.approved { background: #d1fae5; color: #059669; }
    .stat-icon-box.rejected { background: #fee2e2; color: #dc2626; }
    .stat-icon-box.total { background: #dbeafe; color: #2563eb; }

    .stat-info .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .card-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .search-form {
        display: flex;
        gap: 8px;
    }

    .search-form input {
        padding: 8px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        width: 260px;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-form input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .search-form button {
        padding: 8px 16px;
        background: #3b82f6;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }

    .search-form button:hover {
        background: #2563eb;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
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
        color: #2563eb;
    }

    .btn-view:hover {
        background: #dbeafe;
    }

    .pagination-wrapper {
        padding: 16px 24px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav p {
        font-size: 13px;
        color: #6b7280;
    }

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

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    .user-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }

    .user-name {
        font-weight: 600;
        color: #1e293b;
    }

    .user-email {
        font-size: 12px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .verifikasi-page .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-form input {
            width: 180px;
        }

        .data-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="verifikasi-page">
    <!-- Stats Row -->
    <div class="stats-row">
        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'pending']) }}" 
           class="stat-card {{ $status === 'pending' ? 'active-pending' : '' }}">
            <div class="stat-icon-box pending">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Menunggu</div>
                <div class="stat-value">{{ $counts['pending'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'approved']) }}" 
           class="stat-card {{ $status === 'approved' ? 'active-approved' : '' }}">
            <div class="stat-icon-box approved">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value">{{ $counts['approved'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi', ['status' => 'rejected']) }}" 
           class="stat-card {{ $status === 'rejected' ? 'active-rejected' : '' }}">
            <div class="stat-icon-box rejected">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $counts['rejected'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.asesi.verifikasi') }}" 
           class="stat-card">
            <div class="stat-icon-box total">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $counts['total'] }}</div>
            </div>
        </a>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="bi bi-list-check" style="margin-right:6px;"></i>
                Daftar Asesi - 
                @if($status === 'pending') Menunggu Verifikasi
                @elseif($status === 'approved') Disetujui
                @elseif($status === 'rejected') Ditolak
                @else Semua
                @endif
            </h3>
            <form class="search-form" method="GET" action="{{ route('admin.asesi.verifikasi') }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, atau email...">
                <button type="submit"><i class="bi bi-search"></i> Cari</button>
            </form>
        </div>

        @if($asesi->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Asesi</th>
                        <th>NIK</th>
                        <th>Jurusan</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asesi as $index => $item)
                    <tr>
                        <td>{{ $asesi->firstItem() + $index }}</td>
                        <td>
                            <div class="user-cell">
                                @if($item->pas_foto)
                                    <img src="{{ asset('storage/' . $item->pas_foto) }}" alt="Foto" class="user-avatar-sm">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="user-name">{{ $item->nama }}</div>
                                    <div class="user-email">{{ $item->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:12px;">{{ $item->NIK }}</td>
                        <td>{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                        <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
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

            <div class="pagination-wrapper">
                {{ $asesi->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Tidak ada data</h4>
                <p>Belum ada asesi dengan status ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
