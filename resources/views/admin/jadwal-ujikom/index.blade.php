@extends('admin.layout')

@section('title', 'Jadwal Ujikom')
@section('page-title', 'Jadwal Uji Kompetensi')

@section('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
        gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: white; border-radius: 12px; padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        transition: all 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0061a5); }
    .stat-icon.yellow { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.orange { background: linear-gradient(135deg,#f97316,#ea580c); }
    .stat-icon.purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.5px; }
    .stat-value { font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 2px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .filter-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; }
    .filter-form input, .filter-form select {
        padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; outline: none; transition: border-color .2s;
    }
    .filter-form input[type=text] { flex: 1; min-width: 200px; }
    .filter-form input:focus, .filter-form select:focus { border-color: #0061a5; }
    .btn-search {
        padding: 9px 18px; background: #0061a5; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .btn-add {
        padding: 9px 18px; background: #0061a5; color: #fff; border: none;
        border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
    }
    .btn-add:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing:.5px;
        background: #f8fafc; border-bottom: 1px solid #e5e7eb;
    }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.dijadwalkan { background: #dbeafe; color: #1e40af; }
    .badge.berlangsung { background: #fef3c7; color: #92400e; }
    .badge.selesai     { background: #d1fae5; color: #065f46; }
    .badge.dibatalkan  { background: #fee2e2; color: #991b1b; }

    .kuota-bar {
        width: 100%; background: #f1f5f9; border-radius: 20px; height: 6px; margin-top: 4px; overflow: hidden;
    }
    .kuota-fill { height: 100%; border-radius: 20px; background: #0061a5; transition: width .3s; }

    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 14px; transition: all .2s;
        text-decoration: none;
    }
    .btn-icon.edit  { background: #eff6ff; color: #2563eb; }
    .btn-icon.edit:hover  { background: #dbeafe; }
    .btn-icon.del   { background: #fff1f2; color: #e11d48; }
    .btn-icon.del:hover   { background: #ffe4e6; }

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
        <h2>Jadwal Uji Kompetensi</h2>
        <p>Kelola jadwal pelaksanaan uji kompetensi dan penempatan TUK</p>
    </div>
    <a href="{{ route('admin.jadwal-ujikom.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar3"></i></div>
        <div>
            <div class="stat-label">Total Jadwal</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-label">Dijadwalkan</div>
            <div class="stat-value">{{ $stats['dijadwalkan'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-play-circle"></i></div>
        <div>
            <div class="stat-label">Berlangsung</div>
            <div class="stat-value">{{ $stats['berlangsung'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats['selesai'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
        <div>
            <div class="stat-label">Bulan Ini</div>
            <div class="stat-value">{{ $stats['bulan_ini'] }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" class="toolbar">
    <div class="filter-form">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul, TUK, skema...">
        <input type="month" name="bulan" value="{{ $bulan }}" title="Filter bulan">
        <select name="status">
            <option value="all"         {{ $status === 'all'         ? 'selected' : '' }}>Semua Status</option>
            <option value="dijadwalkan" {{ $status === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
            <option value="berlangsung" {{ $status === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
            <option value="selesai"     {{ $status === 'selesai'     ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan"  {{ $status === 'dibatalkan'  ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
        @if($search || $status !== 'all' || $bulan !== now()->format('Y-m'))
        <a href="{{ route('admin.jadwal-ujikom.index') }}" style="padding:9px 14px;color:#64748b;text-decoration:none;font-size:13px;">Reset</a>
        @endif
    </div>
</form>

<!-- Table -->
<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jadwal</th>
                <th>Skema</th>
                <th>TUK</th>
                <th>Waktu</th>
                <th>Kuota</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $i => $jadwal)
            <tr>
                <td style="color:#94a3b8;font-weight:600;">{{ $jadwals->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;color:#0F172A;">{{ $jadwal->judul_jadwal }}</div>
                    <div style="font-size:12px;color:#0061a5;margin-top:2px;font-weight:600;">
                        <i class="bi bi-calendar3"></i>
                        {{ $jadwal->tanggal ? $jadwal->tanggal->translatedFormat('d F Y') : '-' }}
                    </div>
                </td>
                <td>
                    @if($jadwal->skema)
                    <div style="font-size:13px;font-weight:500;">{{ Str::limit($jadwal->skema->nama_skema, 40) }}</div>
                    <div style="font-size:11px;color:#94a3b8;font-family:monospace;">{{ $jadwal->skema->nomor_skema }}</div>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    @if($jadwal->tuk)
                    <div style="font-size:13px;font-weight:500;">{{ $jadwal->tuk->nama_tuk }}</div>
                    <div style="font-size:11px;color:#64748b;">{{ $jadwal->tuk->kota ?? '' }}</div>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    <div style="font-size:13px;"><i class="bi bi-clock" style="color:#64748b;"></i> {{ substr($jadwal->waktu_mulai,0,5) }} – {{ substr($jadwal->waktu_selesai,0,5) }}</div>
                </td>
                <td>
                    @php $pct = $jadwal->kuota > 0 ? min(100, round($jadwal->peserta_terdaftar / $jadwal->kuota * 100)) : 0; @endphp
                    <div style="font-size:13px;font-weight:600;">{{ $jadwal->peserta_terdaftar }} / {{ $jadwal->kuota }}</div>
                    <div class="kuota-bar">
                        <div class="kuota-fill" style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#0061a5') }};"></div>
                    </div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Sisa: {{ $jadwal->sisa_kuota }}</div>
                </td>
                <td>
                    <span class="badge {{ $jadwal->status }}">
                        {{ $jadwal->status_label }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.jadwal-ujikom.edit', $jadwal->id) }}" class="btn-icon edit" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.jadwal-ujikom.destroy', $jadwal->id) }}" style="margin:0;"
                              onsubmit="return confirm('Hapus jadwal ini?')">
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
                        <i class="bi bi-calendar-x"></i>
                        <p>Belum ada jadwal ujikom{{ $search ? ' yang cocok' : '' }}.</p>
                        <a href="{{ route('admin.jadwal-ujikom.create') }}" class="btn-add" style="display:inline-flex;margin-top:12px;">
                            <i class="bi bi-plus-lg"></i> Tambah Jadwal Sekarang
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($jadwals->hasPages())
    <div class="pagination-wrap">{{ $jadwals->links() }}</div>
    @endif
</div>

@endsection
