@extends('admin.layout')

@section('title', 'Log Activity Admin')

@section('content')
<div class="page-header">
    <div>
        <h2>Log Activity Admin</h2>
        <p>Riwayat aktivitas admin (login dan logout).</p>
    </div>
</div>

<form method="GET" class="toolbar" style="margin-bottom:16px; display:flex; gap:10px; align-items:center;">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, username, atau aktivitas..." class="input-search">
    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="{{ route('admin.log-activity.admin.export', ['q' => $search]) }}" class="btn btn-export">Export CSV</a>
    @if($search !== '')
        <a href="{{ route('admin.log-activity.admin') }}" class="btn btn-secondary">Reset</a>
    @endif
</form>

<div class="table-card">
    <table class="log-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Nama Admin</th>
                <th>Username</th>
                <th>Aktivitas</th>
                <th>Deskripsi</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                    <td>{{ $log->actor_name ?? '-' }}</td>
                    <td>{{ $log->actor_id ?? '-' }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-row">Belum ada data log admin.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
    <div class="pagination-wrap">
        @if($logs->onFirstPage())
            <span class="page-btn disabled">Sebelumnya</span>
        @else
            <a class="page-btn" href="{{ $logs->previousPageUrl() }}">Sebelumnya</a>
        @endif

        <span class="page-info">Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</span>

        @if($logs->hasMorePages())
            <a class="page-btn" href="{{ $logs->nextPageUrl() }}">Berikutnya</a>
        @else
            <span class="page-btn disabled">Berikutnya</span>
        @endif
    </div>
@endif

<style>
.page-header h2 {
    margin: 0 0 4px;
    font-size: 24px;
    color: #0f172a;
}
.page-header p {
    color: #64748b;
    margin: 0 0 18px;
}
.input-search {
    min-width: 320px;
    max-width: 520px;
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 10px 12px;
}
.table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow-x: auto;
}
.log-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 920px;
}
.log-table th,
.log-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
    font-size: 13px;
    vertical-align: top;
}
.log-table th {
    background: #f8fafc;
    color: #334155;
    font-weight: 700;
}
.empty-row {
    text-align: center;
    color: #64748b;
}
.pagination-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
}
.page-btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    color: #0f172a;
    background: #fff;
}
.page-btn.disabled {
    color: #94a3b8;
    pointer-events: none;
}
.page-info {
    color: #64748b;
    font-size: 13px;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    padding: 10px 14px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: 600;
}
.btn-primary {
    background: #0061a5;
    color: #fff;
}
.btn-secondary {
    background: #e2e8f0;
    color: #0f172a;
}
.btn-export {
    background: #16a34a;
    color: #fff;
}
@media (max-width: 768px) {
    .toolbar {
        flex-wrap: wrap;
    }
    .input-search {
        min-width: 100%;
    }
}
</style>
@endsection
