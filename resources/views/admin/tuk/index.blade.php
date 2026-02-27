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
    .stat-card {
        background: white; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
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
        padding: 9px 18px; background: #16a34a; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .btn-add:hover { background: #15803d; color: #fff; }

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

    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 14px; transition: all .2s;
        text-decoration: none;
    }
    .btn-icon.edit   { background: #eff6ff; color: #2563eb; }
    .btn-icon.edit:hover { background: #dbeafe; }
    .btn-icon.toggle { background: #fef3c7; color: #d97706; }
    .btn-icon.toggle:hover { background: #fde68a; }
    .btn-icon.del    { background: #fff1f2; color: #e11d48; }
    .btn-icon.del:hover { background: #ffe4e6; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p  { font-size: 14px; }

    .pagination-wrap { padding: 16px; }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
@endif

<!-- Header -->
<div class="page-header">
    <div>
        <h2><i class="bi bi-building" style="color:#0061a5;"></i> Tempat Uji Kompetensi (TUK)</h2>
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
        <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">TUK Aktif</div>
            <div class="stat-value">{{ $stats['aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
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
                    @if($tuk->kode_tuk)
                    <div style="font-size:11px;color:#94a3b8;font-family:monospace;">{{ $tuk->kode_tuk }}</div>
                    @endif
                    @if($tuk->alamat)
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;"><i class="bi bi-geo-alt"></i> {{ Str::limit($tuk->alamat, 50) }}</div>
                    @endif
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
                        <i class="bi bi-people" style="color:#64748b;"></i>
                        <span>{{ number_format($tuk->kapasitas) }} peserta</span>
                    </div>
                </td>
                <td>
                    <span style="font-weight:600;color:#0061a5;">{{ $tuk->jadwal_ujikom_count }}</span>
                    <span style="font-size:11px;color:#94a3b8;"> jadwal</span>
                </td>
                <td>
                    <span class="badge {{ $tuk->status }}">
                        <i class="bi bi-{{ $tuk->status === 'aktif' ? 'check-circle' : 'x-circle' }}"></i>
                        {{ $tuk->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.tuk.edit', $tuk->id) }}" class="btn-icon edit" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tuk.toggle', $tuk->id) }}" style="margin:0;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-icon toggle" title="{{ $tuk->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi bi-{{ $tuk->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tuk.destroy', $tuk->id) }}" style="margin:0;"
                              onsubmit="return confirm('Hapus TUK {{ addslashes($tuk->nama_tuk) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
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
