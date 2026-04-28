@extends('admin.layout')

@section('title', 'Log Activity User')

@section('content')
<div class="page-header">
    <div>
        <h2>Log Activity User</h2>
        <p>Riwayat aktivitas user (login, logout, APL 1, APL 2).</p>
    </div>
</div>

<form method="GET" class="toolbar" style="margin-bottom:16px; display:flex; gap:10px; align-items:center;">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, ID, atau aktivitas..." class="input-search">

    <select name="module" style="padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; background:#fff;">
        <option value="">Semua Modul</option>
        @if(!empty($modules))
            @foreach($modules as $m)
                <option value="{{ $m }}" @if(isset($module) && $module === $m) selected @endif>{{ ucwords(str_replace('-', ' ', $m)) }}</option>
            @endforeach
        @endif
    </select>

    <select name="action" style="padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; background:#fff;">
        <option value="">Semua Aksi</option>
        <option value="create" @if(isset($action) && $action === 'create') selected @endif>Menambah</option>
        <option value="update" @if(isset($action) && $action === 'update') selected @endif>Memperbarui</option>
        <option value="delete" @if(isset($action) && $action === 'delete') selected @endif>Menghapus</option>
        <option value="verify" @if(isset($action) && $action === 'verify') selected @endif>Verifikasi</option>
        <option value="login" @if(isset($action) && $action === 'login') selected @endif>Login</option>
        <option value="logout" @if(isset($action) && $action === 'logout') selected @endif>Logout</option>
    </select>

    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="{{ route('admin.log-activity.user.export', ['q' => $search, 'module' => $module ?? '', 'action' => $action ?? '']) }}" class="btn btn-export">Export CSV</a>
    @if($search !== '' || (isset($module) && $module) || (isset($action) && $action))
        <a href="{{ route('admin.log-activity.user') }}" class="btn btn-secondary">Reset</a>
    @endif
</form>

<div class="table-card">
    <table class="log-table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Nama User</th>
                <th>ID User</th>
                <th>Aktivitas</th>
                <th>Route</th>
                <th>Method</th>
                <th>Deskripsi</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                @php($meta = is_array($log->meta) ? $log->meta : [])
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                    <td>{{ $log->actor_name ?? '-' }}</td>
                    <td>{{ $log->actor_id ?? '-' }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $meta['route'] ?? '-' }}</td>
                    <td>{{ $meta['method'] ?? '-' }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty-row">Belum ada data log user.</td>
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
