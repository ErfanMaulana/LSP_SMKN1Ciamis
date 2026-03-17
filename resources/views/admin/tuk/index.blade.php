@extends('admin.layout')

@section('title', 'Manajemen TUK')
@section('page-title', 'TUK (Tempat Uji Kompetensi)')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card { background: white; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .stat-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0061a5); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.red    { background: linear-gradient(135deg,#ef4444,#dc2626); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.5px; }
    .stat-value { font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .filter-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; }
    .filter-form input, .filter-form select {
        padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; outline: none; transition: border-color .2s;
    }
    .filter-form input { flex: 1; min-width: 220px; }
    .filter-form input:focus, .filter-form select:focus { border-color: #0061a5; }
    .btn-search {
        padding: 9px 18px; background: #0061a5; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .btn-add {
        padding: 9px 18px; background: #0061a5; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .btn-add:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b;
         text-transform: uppercase; letter-spacing: .5px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.aktif    { background: #d1fae5; color: #065f46; }
    .badge.nonaktif { background: #fee2e2; color: #991b1b; }
    .badge.sewaktu  { background: #dbeafe; color: #1e40af; }
    .badge.tempat_kerja { background: #fef3c7; color: #92400e; }
    .badge.mandiri  { background: #ede9fe; color: #5b21b6; }

    .action-menu { position: relative; }
    .action-btn { width: 32px; height: 32px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .action-btn:hover { background: #f1f5f9; }
    .action-dropdown { display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; background: white; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.15); min-width: 170px; z-index: 10; overflow: hidden; }
    .action-dropdown.show { display: block; }
    .action-dropdown a, .action-dropdown button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 14px; color: #475569; cursor: pointer; transition: all .2s; text-decoration: none; }
    .action-dropdown a:hover, .action-dropdown button:hover { background: #f8fafc; color: #0F172A; }
    .action-dropdown button[type="submit"]:last-child:hover { background: #fef2f2; color: #dc2626; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p  { font-size: 14px; }

    .pagination-wrap { padding: 16px; }

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
@endsection

@section('content')

<!-- Header -->
<div class="page-header">
    <div>
        <h2>Tempat Uji Kompetensi (TUK)</h2>
        <p>Kelola data tempat uji kompetensi untuk pelaksanaan ujian sertifikasi</p>
    </div>
    <a href="{{ route('admin.tuk.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah TUK
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-building"></i></div>
        <div>
            <div class="stat-label">Total TUK</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">TUK Aktif</div>
            <div class="stat-value">{{ $stats['aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-label">Non-Aktif</div>
            <div class="stat-value">{{ $stats['nonaktif'] }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" class="toolbar">
    <div class="filter-form">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama TUK, kode, kota...">
        <select name="status">
            <option value="all"     {{ $status === 'all'     ? 'selected' : '' }}>Semua Status</option>
            <option value="aktif"   {{ $status === 'aktif'   ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif"{{ $status === 'nonaktif'? 'selected' : '' }}>Non-Aktif</option>
        </select>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
        @if($search || $status !== 'all')
        <a href="{{ route('admin.tuk.index') }}" style="padding:9px 14px;color:#64748b;text-decoration:none;font-size:13px;">Reset</a>
        @endif
    </div>
</form>

<!-- Table -->
<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama TUK</th>
                <th>Tipe</th>
                <th>Kota</th>
                <th>Kapasitas</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tuks as $i => $tuk)
            <tr>
                <td style="color:#94a3b8;font-weight:600;">{{ $tuks->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;color:#0F172A;">{{ $tuk->nama_tuk }}</div>                   
                </td>
                <td>
                    @php
                        $tipeMap = ['sewaktu'=>'TUK Sewaktu','tempat_kerja'=>'Tempat Kerja','mandiri'=>'TUK Mandiri'];
                    @endphp
                    <span class="badge {{ $tuk->tipe_tuk }}">{{ $tipeMap[$tuk->tipe_tuk] ?? $tuk->tipe_tuk }}</span>
                </td>
                <td>{{ $tuk->kota ?? '-' }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span>{{ number_format($tuk->kapasitas) }} peserta</span>
                    </div>
                </td>
                <td>
                    <span style="font-weight:600;color:#0061a5;">{{ $tuk->jadwal_ujikom_count }}</span>
                    <span style="font-size:11px;color:#94a3b8;"></span>
                </td>
                <td>
                    <span class="badge {{ $tuk->status }}">
                        <i class="bi bi-{{ $tuk->status === 'aktif' ? 'check-circle' : 'x-circle' }}"></i>
                        {{ $tuk->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </td>
                <td>
                    <div class="action-menu">
                        <button class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.tuk.edit', $tuk->id) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.tuk.toggle', $tuk->id) }}" style="margin:0;">
                                @csrf @method('PATCH')
                                <button type="submit">
                                    <i class="bi bi-{{ $tuk->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                                    {{ $tuk->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.tuk.destroy', $tuk->id) }}" style="margin:0;"
                                  onsubmit="return confirm('Hapus TUK {{ addslashes($tuk->nama_tuk) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="bi bi-building-x"></i>
                        <p>Belum ada data TUK{{ $search ? ' yang cocok dengan pencarian' : '' }}.</p>
                        <a href="{{ route('admin.tuk.create') }}" class="btn-add" style="display:inline-flex;margin-top:12px;">
                            <i class="bi bi-plus-lg"></i> Tambah TUK Sekarang
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tuks->hasPages())
    <div class="pagination-wrap">{{ $tuks->links() }}</div>
    @endif
</div>

@endsection

@section('scripts')
<script>
function toggleMenu(button) {
    document.querySelectorAll('.action-dropdown.show').forEach(d => {
        if (d !== button.nextElementSibling) d.classList.remove('show');
    });
    button.nextElementSibling.classList.toggle('show');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));
    }
});
</script>
@endsection
