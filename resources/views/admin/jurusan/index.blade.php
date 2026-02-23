@extends('admin.layout')

@section('title', 'Manajemen Jurusan')
@section('page-title', 'Manajemen Jurusan')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        border: 1px solid #e5e7eb;
    }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0073bd); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .stat-icon.orange { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .search-form { display: flex; gap: 8px; flex: 1; min-width: 260px; }
    .search-form input {
        flex: 1; padding: 9px 14px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 13px; outline: none; transition: border-color .2s;
    }
    .search-form input:focus { border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,.1); }
    .search-form button {
        padding: 9px 16px; background: #0073bd; color: #fff; border: none;
        border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .search-form button:hover { background: #0073bd; }

    .sort-form { display: flex; align-items: center; gap: 8px; }
    .sort-form select {
        padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; background: #fff; cursor: pointer; outline: none;
    }
    .sort-form label { font-size: 13px; color: #6b7280; white-space: nowrap; }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; border-radius: 8px; font-size: 13px;
        font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all .2s;
    }
    .btn-primary { background: #0F172A; color: #fff; }
    .btn-primary:hover { background: #1e293b; color: #fff; }
    .btn-xs { padding: 5px 12px; font-size: 12px; border-radius: 6px; }
    .btn-edit { background: #eff6ff; color: #0073bd; }
    .btn-edit:hover { background: #dbeafe; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #fee2e2; }

    .card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; overflow: hidden;
    }
    .card-header {
        padding: 14px 20px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h3 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0; }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead th {
        background: #f8fafc; padding: 11px 16px; text-align: left;
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .data-table thead th a { color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .data-table thead th a:hover { color: #1e293b; }
    .data-table tbody td {
        padding: 13px 16px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }

    .jurusan-name { font-weight: 600; color: #0F172A; }
    .jurusan-code {
        display: inline-block; padding: 3px 10px; border-radius: 6px;
        font-size: 12px; font-weight: 600; background: #f1f5f9; color: #475569; font-family: monospace;
    }
    .visi-text { font-size: 12px; color: #6b7280; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .asesi-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        background: #dbeafe; color: #1d4ed8;
    }
    .asesi-badge.empty { background: #f1f5f9; color: #94a3b8; }
    .action-cell { position: relative; text-align: center; }
    .kebab-btn {
        background: none; border: none; font-size: 18px; color: #6b7280;
        cursor: pointer; padding: 4px; transition: color .2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .kebab-btn:hover { color: #1e293b; }
    .dropdown-menu {
        display: none; position: absolute; right: 0; top: 28px;
        background: #fff; border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,.1);
        border: 1px solid #e5e7eb;
        min-width: 180px; z-index: 100;
        overflow: hidden;
    }
    .dropdown-menu.show { display: block; }
    .dropdown-item {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 14px; font-size: 13px; color: #374151;
        text-decoration: none; border: none; background: none; cursor: pointer;
        transition: all .2s; width: 100%; text-align: left;
    }
    .dropdown-item:hover {
        background: #f3f4f6; color: #1e293b;
    }
    .dropdown-item.delete {
        color: #dc2626; border-top: 1px solid #e5e7eb;
    }
    .dropdown-item.delete:hover {
        background: #fef2f2;
    }
    .dropdown-item i { font-size: 15px; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state h4 { font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px; }
    .empty-state p { font-size: 13px; color: #9ca3af; margin: 0; }

    .pagination-row {
        padding: 14px 20px; border-top: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }
    .pagination-info { font-size: 12px; color: #6b7280; }

    /* Delete Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.5); z-index: 9999; align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px; padding: 28px;
        width: 100%; max-width: 420px; margin: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-box h3 { font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 8px; }
    .modal-box p  { font-size: 13px; color: #6b7280; margin: 0 0 20px; line-height: 1.6; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .btn-cancel {
        padding: 9px 18px; background: #f3f4f6; color: #374151;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;
    }
    .btn-cancel:hover { background: #e5e7eb; }
    .btn-confirm-delete {
        padding: 9px 18px; background: #ef4444; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-confirm-delete:hover { background: #dc2626; }

    @media (max-width: 800px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
        .data-table { display: block; overflow-x: auto; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .toolbar { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2><i class="bi bi-mortarboard" style="color:#0073bd;margin-right:8px;"></i>Manajemen Jurusan</h2>
        <p>Kelola semua program keahlian yang tersedia di LSP.</p>
    </div>
    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Jurusan
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-mortarboard"></i></div>
        <div><div class="stat-label">Total Jurusan</div><div class="stat-value">{{ $stats['total'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-people"></i></div>
        <div><div class="stat-label">Total Asesi</div><div class="stat-value">{{ $stats['total_asesi'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-person-check"></i></div>
        <div><div class="stat-label">Jurusan Aktif</div><div class="stat-value">{{ $stats['with_asesi'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-graph-up"></i></div>
        <div><div class="stat-label">Rata-rata Asesi</div><div class="stat-value">{{ $stats['avg_asesi'] }}</div></div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" action="{{ route('admin.jurusan.index') }}" class="search-form">
        <input type="hidden" name="sort"  value="{{ $sort }}">
        <input type="hidden" name="order" value="{{ $order }}">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode jurusan...">
        <button type="submit"><i class="bi bi-search"></i> Cari</button>
        @if($search)
            <a href="{{ route('admin.jurusan.index') }}" class="btn" style="background:#f3f4f6;color:#374151;padding:9px 14px;">
                <i class="bi bi-x"></i>
            </a>
        @endif
    </form>

    <form method="GET" action="{{ route('admin.jurusan.index') }}" class="sort-form" id="sortForm">
        <input type="hidden" name="search" value="{{ $search }}">
        <label>Urutkan:</label>
        <select name="sort" onchange="document.getElementById('sortForm').submit()">
            <option value="nama_jurusan"  {{ $sort === 'nama_jurusan'  ? 'selected' : '' }}>Nama</option>
            <option value="kode_jurusan"  {{ $sort === 'kode_jurusan'  ? 'selected' : '' }}>Kode</option>
            <option value="asesi_count"   {{ $sort === 'asesi_count'   ? 'selected' : '' }}>Jumlah Asesi</option>
            <option value="created_at"    {{ $sort === 'created_at'    ? 'selected' : '' }}>Tanggal Dibuat</option>
        </select>
        <select name="order" onchange="document.getElementById('sortForm').submit()">
            <option value="asc"  {{ $order === 'asc'  ? 'selected' : '' }}>A → Z</option>
            <option value="desc" {{ $order === 'desc' ? 'selected' : '' }}>Z → A</option>
        </select>
    </form>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h3>
            Daftar Jurusan
            @if($search) &mdash; hasil pencarian "<strong>{{ $search }}</strong>" @endif
        </h3>
        <span style="font-size:12px;color:#6b7280;">{{ $jurusan->total() }} jurusan</span>
    </div>

    @if($jurusan->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'nama_jurusan','order'=>$sort==='nama_jurusan'&&$order==='asc'?'desc':'asc']) }}">
                        Nama Jurusan
                        @if($sort==='nama_jurusan') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'kode_jurusan','order'=>$sort==='kode_jurusan'&&$order==='asc'?'desc':'asc']) }}">
                        Kode
                        @if($sort==='kode_jurusan') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>Visi</th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'asesi_count','order'=>$sort==='asesi_count'&&$order==='asc'?'desc':'asc']) }}">
                        Asesi
                        @if($sort==='asesi_count') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'created_at','order'=>$sort==='created_at'&&$order==='asc'?'desc':'asc']) }}">
                        Dibuat
                        @if($sort==='created_at') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurusan as $i => $item)
            <tr>
                <td>{{ $jurusan->firstItem() + $i }}</td>
                <td class="jurusan-name">{{ $item->nama_jurusan }}</td>
                <td><span class="jurusan-code">{{ $item->kode_jurusan ?? '-' }}</span></td>
                <td><span class="visi-text" title="{{ $item->visi }}">{{ $item->visi ? Str::limit($item->visi, 60) : '-' }}</span></td>
                <td>
                    <span class="asesi-badge {{ $item->asesi_count == 0 ? 'empty' : '' }}">
                        <i class="bi bi-people"></i> {{ $item->asesi_count }}
                    </span>
                </td>
                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                <td>
                    <div class="action-cell">
                        <button type="button" class="kebab-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.jurusan.edit', $item->id_jurusan) }}" class="dropdown-item">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <button type="button" class="dropdown-item delete"
                                onclick="confirmDelete({{ $item->id_jurusan }}, '{{ addslashes($item->nama_jurusan) }}', {{ $item->asesi_count }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-row">
        <div class="pagination-info">
            Menampilkan {{ $jurusan->firstItem() }}–{{ $jurusan->lastItem() }} dari {{ $jurusan->total() }} jurusan
        </div>
        {{ $jurusan->links() }}
    </div>

    @else
    <div class="empty-state">
        <i class="bi bi-mortarboard"></i>
        <h4>{{ $search ? 'Tidak ada hasil untuk "' . $search . '"' : 'Belum ada data jurusan' }}</h4>
        <p>{{ $search ? 'Coba kata kunci lain.' : 'Klik "Tambah Jurusan" untuk mulai menambahkan data.' }}</p>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3><i class="bi bi-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Hapus Jurusan</h3>
        <p id="deleteModalText"></p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-confirm-delete"><i class="bi bi-trash"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleMenu(btn) {
    const menu = btn.nextElementSibling;
    const allMenus = document.querySelectorAll('.dropdown-menu');
    allMenus.forEach(m => {
        if (m !== menu) m.classList.remove('show');
    });
    menu.classList.toggle('show');
}

function confirmDelete(id, nama, asesiCount) {
    closeMenus();
    if (asesiCount > 0) {
        alert('Jurusan "' + nama + '" tidak dapat dihapus karena masih memiliki ' + asesiCount + ' data asesi terdaftar.');
        return;
    }
    document.getElementById('deleteModalText').innerHTML =
        'Anda yakin ingin menghapus jurusan <strong>' + nama + '</strong>?<br>Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteForm').action = '{{ url("admin/jurusan") }}/' + id;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

function closeMenus() {
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-cell')) {
        closeMenus();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeMenus();
    }
});
</script>
@endsection
