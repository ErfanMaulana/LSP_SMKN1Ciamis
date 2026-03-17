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

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    table { width: 100%; border-collapse: collapse; overflow: visible; }
    .card > table { overflow: visible; }
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

    .action-menu { position: relative; }
    .action-btn { width: 32px; height: 32px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .action-btn:hover { background: #f1f5f9; }
    .action-dropdown { display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; background: white; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.15); min-width: 160px; z-index: 10; overflow: hidden; }
    .action-dropdown.show { display: block; }
    .action-dropdown a, .action-dropdown button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 14px; color: #475569; cursor: pointer; transition: all .2s; text-decoration: none; }
    .action-dropdown a:hover, .action-dropdown button:hover { background: #f8fafc; color: #0F172A; }
    .action-dropdown button[type="submit"]:hover { background: #fef2f2; color: #dc2626; }

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
                <th>Tanggal</th>
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
                        @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                            @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                                {{ $jadwal->tanggal_mulai->translatedFormat('d F Y') }}
                            @else
                                {{ $jadwal->tanggal_mulai->translatedFormat('d M') }} - {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                        @else
                            -
                        @endif
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
                    @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                        @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                            <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
                        @else
                            <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
                            <div style="font-size:12px;color:#64748b;">s/d {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}</div>
                        @endif
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $jadwal->status }}">
                        {{ $jadwal->status_label }}
                    </span>
                </td>
                <td>
                    <div class="action-menu">
                        <button class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.jadwal-ujikom.edit', $jadwal->id) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal-ujikom.destroy', $jadwal->id) }}" style="margin:0;"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
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
