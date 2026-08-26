@extends('admin.layout')

@section('title', 'Log Activity User')

@section('content')
<div class="page-header">
    <div>
        <h2>Log Activity User</h2>
        <p>Riwayat aktivitas user (login, logout, APL 1, APL 2).</p>
    </div>
</div>

<form method="GET" class="toolbar">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, ID, atau aktivitas..." class="input-search">

    <select name="module" class="select-filter">
        <option value="">Semua Modul</option>
        @if(!empty($modules))
            @foreach($modules as $m)
                <option value="{{ $m }}" @if(isset($module) && $module === $m) selected @endif>{{ ucwords(str_replace('-', ' ', $m)) }}</option>
            @endforeach
        @endif
    </select>

    <select name="action" class="select-filter">
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
                <th class="col-waktu">Waktu</th>
                <th class="col-nama">Nama User</th>
                <th class="col-id">ID User</th>
                <th class="col-aktivitas">Aktivitas</th>
                <th class="col-route">Route</th>
                <th class="col-method">Method</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-ip">IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                @php($meta = is_array($log->meta) ? $log->meta : [])
                <tr>
                    <td class="col-waktu">{{ $log->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                    <td class="col-nama">{{ $log->actor_name ?? '-' }}</td>
                    <td class="col-id">{{ $log->actor_id ?? '-' }}</td>
                    <td class="col-aktivitas">
                        <span class="activity-badge">{{ $log->activity }}</span>
                    </td>
                    <td class="col-route">{{ $meta['route'] ?? '-' }}</td>
                    <td class="col-method">
                        @if(!empty($meta['method']) && $meta['method'] !== '-')
                            <span class="method-badge method-{{ strtolower($meta['method']) }}">{{ strtoupper($meta['method']) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="col-deskripsi">{{ $log->description ?? '-' }}</td>
                    <td class="col-ip">{{ $log->ip_address ?? '-' }}</td>
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
.toolbar {
    margin-bottom: 16px;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.input-search {
    min-width: 240px;
    max-width: 480px;
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
}
.select-filter {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #fff;
    color: #334155;
    font-size: 14px;
}
.table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow-x: auto;
    width: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.log-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}
.log-table th,
.log-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
    font-size: 13px;
    vertical-align: middle;
}
.log-table th {
    background: #f8fafc;
    color: #334155;
    font-weight: 700;
    white-space: nowrap;
}
.col-waktu {
    white-space: nowrap;
    width: 140px;
}
.col-nama {
    min-width: 110px;
    max-width: 180px;
    word-break: break-word;
}
.col-id {
    min-width: 110px;
    max-width: 180px;
    word-break: break-all;
    font-family: monospace;
    font-size: 12px;
}
.col-aktivitas {
    min-width: 110px;
}
.col-route {
    min-width: 90px;
    max-width: 180px;
    word-break: break-all;
    font-family: monospace;
    font-size: 12px;
    color: #475569;
}
.col-method {
    width: 75px;
    text-align: center;
}
.col-deskripsi {
    min-width: 180px;
    word-break: break-word;
}
.col-ip {
    white-space: nowrap;
    width: 110px;
    font-family: monospace;
    font-size: 12px;
}
.activity-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    background: #eff6ff;
    color: #1e40af;
}
.method-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    font-family: monospace;
}
.method-get { background: #dbeafe; color: #1e40af; }
.method-post { background: #dcfce7; color: #166534; }
.method-put, .method-patch { background: #fef3c7; color: #92400e; }
.method-delete { background: #fee2e2; color: #991b1b; }
.text-muted { color: #94a3b8; }
.empty-row {
    text-align: center;
    color: #64748b;
    padding: 24px 14px;
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
    background: #0073bd;
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
