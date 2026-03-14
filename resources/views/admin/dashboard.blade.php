@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Welcome Card ─────────────────────────────────────────────────── --}}
<div class="welcome-card">
    <div class="welcome-left">
        <div class="welcome-greeting">Selamat Datang, {{ Auth::guard('admin')->user()->name }}!</div>
        <div class="welcome-sub">Administrator &middot; LSP SMKN 1 Ciamis</div>
    </div>
    <div class="welcome-right">
        <div class="welcome-date" id="dashClock"></div>
    </div>
</div>

{{-- ── Stat Cards (row 1) ───────────────────────────────────────────── --}}
<div class="stats-grid">
    <a href="{{ route('admin.asesi.index') }}" class="stat-card stat-blue">
        <div class="stat-icon-wrap"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">TOTAL ASESI</div>
            <div class="stat-number">{{ $stats['totalAsesi'] }}</div>
            @if($verifikasi['pending'] > 0)
            <div class="stat-badge badge-warn">
                <i class="bi bi-clock-fill"></i> {{ $verifikasi['pending'] }} menunggu verifikasi
            </div>
            @endif
        </div>
    </a>

    <a href="{{ route('admin.asesor.index') }}" class="stat-card stat-indigo">
        <div class="stat-icon-wrap"><i class="bi bi-person-badge-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">TOTAL ASESOR</div>
            <div class="stat-number">{{ $stats['totalAsesor'] }}</div>
        </div>
    </a>

    <a href="{{ route('admin.jurusan.index') }}" class="stat-card stat-teal">
        <div class="stat-icon-wrap"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">TOTAL JURUSAN</div>
            <div class="stat-number">{{ $stats['totalJurusan'] }}</div>
        </div>
    </a>

    <a href="{{ route('admin.skema.index') }}" class="stat-card stat-purple">
        <div class="stat-icon-wrap"><i class="bi bi-patch-check-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">TOTAL SKEMA</div>
            <div class="stat-number">{{ $stats['totalSkema'] }}</div>
        </div>
    </a>

    <div class="stat-card stat-orange">
        <div class="stat-icon-wrap"><i class="bi bi-building-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">TOTAL MITRA</div>
            <div class="stat-number">{{ $stats['totalMitra'] }}</div>
        </div>
    </div>
</div>

{{-- ── Verifikasi + Asesmen Progress ───────────────────────────────── --}}
<div class="section-row">

    {{-- Verifikasi Asesi --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title"><i class="bi bi-shield-check"></i> Status Verifikasi Asesi</div>
            <a href="{{ route('admin.asesi.verifikasi') }}" class="panel-link">Lihat semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="verif-grid">
            <a href="{{ route('admin.asesi.verifikasi', ['status' => 'pending']) }}" class="verif-card vc-warn">
                <i class="bi bi-hourglass-split"></i>
                <div class="vc-num">{{ $verifikasi['pending'] }}</div>
                <div class="vc-lbl">Menunggu</div>
            </a>
            <a href="{{ route('admin.asesi.verifikasi', ['status' => 'approved']) }}" class="verif-card vc-green">
                <i class="bi bi-check-circle-fill"></i>
                <div class="vc-num">{{ $verifikasi['approved'] }}</div>
                <div class="vc-lbl">Disetujui</div>
            </a>
            <a href="{{ route('admin.asesi.verifikasi', ['status' => 'rejected']) }}" class="verif-card vc-red">
                <i class="bi bi-x-circle-fill"></i>
                <div class="vc-num">{{ $verifikasi['rejected'] }}</div>
                <div class="vc-lbl">Ditolak</div>
            </a>
        </div>
    </div>

    {{-- Asesmen Mandiri Progress --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title"><i class="bi bi-clipboard2-check"></i> Progres Asesmen Mandiri</div>
        </div>
        <div class="progress-list">
            <div class="prog-item">
                <div class="prog-dot dot-gray"></div>
                <div class="prog-info">
                    <span class="prog-label">Belum Mulai</span>
                    <span class="prog-count">{{ $asesmen['belum_mulai'] }}</span>
                </div>
                <div class="prog-bar-wrap">
                    @php $total = max(1, $asesmen['belum_mulai'] + $asesmen['sedang_mengerjakan'] + $asesmen['selesai']); @endphp
                    <div class="prog-bar" style="width:{{ round($asesmen['belum_mulai']/$total*100) }}%;background:#94a3b8;"></div>
                </div>
            </div>
            <div class="prog-item">
                <div class="prog-dot dot-blue"></div>
                <div class="prog-info">
                    <span class="prog-label">Sedang Dikerjakan</span>
                    <span class="prog-count">{{ $asesmen['sedang_mengerjakan'] }}</span>
                </div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ round($asesmen['sedang_mengerjakan']/$total*100) }}%;background:#3b82f6;"></div>
                </div>
            </div>
            <div class="prog-item">
                <div class="prog-dot dot-green"></div>
                <div class="prog-info">
                    <span class="prog-label">Selesai</span>
                    <span class="prog-count">{{ $asesmen['selesai'] }}</span>
                </div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width:{{ round($asesmen['selesai']/$total*100) }}%;background:#22c55e;"></div>
                </div>
            </div>
            <hr style="border:none;border-top:1px dashed #e2e8f0;margin:8px 0;">
            <div class="prog-item">
                <div class="prog-dot dot-emerald"></div>
                <div class="prog-info">
                    <span class="prog-label">Rekomendasi: Lanjut</span>
                    <span class="prog-count" style="color:#059669;">{{ $asesmen['rekomendasi_lanjut'] }}</span>
                </div>
            </div>
            <div class="prog-item">
                <div class="prog-dot dot-rose"></div>
                <div class="prog-info">
                    <span class="prog-label">Rekomendasi: Tidak Lanjut</span>
                    <span class="prog-count" style="color:#e11d48;">{{ $asesmen['rekomendasi_tidak'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Recent Asesi (full width) ────────────────────────────────────── --}}
<div class="panel">
    <div class="panel-header">
        <div class="panel-title"><i class="bi bi-person-lines-fill"></i> Asesi Terbaru</div>
        <a href="{{ route('admin.asesi.index') }}" class="panel-link">Lihat semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <table class="dash-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentAsesi as $asesi)
            <tr>
                <td>
                    <a href="{{ route('admin.asesi.edit', $asesi->NIK) }}" class="table-name-link">
                        {{ $asesi->nama }}
                    </a>
                </td>
                <td class="mono">{{ $asesi->NIK }}</td>
                <td>{{ $asesi->jurusan?->nama_jurusan ?? '—' }}</td>
                <td>
                    @if($asesi->status === 'approved')
                        <span class="badge bg-green">Disetujui</span>
                    @elseif($asesi->status === 'rejected')
                        <span class="badge bg-red">Ditolak</span>
                    @else
                        <span class="badge bg-warn">Menunggu</span>
                    @endif
                </td>
                <td class="text-muted">{{ \Carbon\Carbon::parse($asesi->created_at)->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-row">Belum ada data asesi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
/* ── Welcome ──────────────────────────────────────────────────────── */
.welcome-card {
    background: linear-gradient(135deg, #0073bd 0%, #004f90 100%);
    padding: 28px 32px;
    border-radius: 14px;
    margin-bottom: 24px;
    color: white;
    box-shadow: 0 4px 20px rgba(0,115,189,0.25);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.welcome-greeting { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
.welcome-sub { font-size: 13px; opacity: 0.85; }
.welcome-date { font-size: 13px; opacity: 0.9; text-align: right; line-height: 1.7; }

/* ── Stat Cards ───────────────────────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: all 0.2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
.stat-icon-wrap {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: white; flex-shrink: 0;
}
.stat-blue  .stat-icon-wrap { background: linear-gradient(135deg, #0073bd, #0061a5); }
.stat-indigo .stat-icon-wrap { background: linear-gradient(135deg, #4f46e5, #3730a3); }
.stat-teal  .stat-icon-wrap { background: linear-gradient(135deg, #0d9488, #0f766e); }
.stat-purple .stat-icon-wrap { background: linear-gradient(135deg, #9333ea, #7e22ce); }
.stat-orange .stat-icon-wrap { background: linear-gradient(135deg, #f97316, #ea580c); }
.stat-card:hover.stat-blue   { border-color: #bfdbfe; }
.stat-card:hover.stat-indigo { border-color: #c7d2fe; }
.stat-card:hover.stat-teal   { border-color: #99f6e4; }
.stat-card:hover.stat-purple { border-color: #e9d5ff; }
.stat-card:hover.stat-orange { border-color: #fed7aa; }
.stat-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.stat-number { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
.stat-badge { font-size: 11px; font-weight: 500; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
.badge-warn { color: #d97706; }

/* ── Panels / Sections ────────────────────────────────────────────── */
.section-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 22px;
}
.panel {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    padding: 20px;
}
.panel-wide  { grid-column: span 1; }
.panel-narrow { grid-column: span 1; }
.panel-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
}
.panel-title { font-size: 14px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 7px; }
.panel-title i { color: #0073bd; font-size: 16px; }
.panel-link { font-size: 12px; color: #0073bd; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 3px; }
.panel-link:hover { color: #004f90; }

/* ── Verifikasi ───────────────────────────────────────────────────── */
.verif-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.verif-card {
    border-radius: 10px; padding: 16px 12px; text-align: center;
    text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 6px;
    transition: transform 0.2s;
}
.verif-card:hover { transform: translateY(-2px); }
.verif-card i { font-size: 24px; }
.vc-num { font-size: 26px; font-weight: 800; }
.vc-lbl { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
.vc-warn { background: #fffbeb; color: #d97706; }
.vc-green { background: #f0fdf4; color: #059669; }
.vc-red   { background: #fff1f2; color: #e11d48; }

/* ── Progress List ────────────────────────────────────────────────── */
.progress-list { display: flex; flex-direction: column; gap: 10px; }
.prog-item { display: flex; align-items: center; gap: 10px; }
.prog-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.dot-gray    { background: #94a3b8; }
.dot-blue    { background: #3b82f6; }
.dot-green   { background: #22c55e; }
.dot-emerald { background: #059669; }
.dot-rose    { background: #e11d48; }
.prog-info { display: flex; justify-content: space-between; align-items: center; flex: 1; gap: 8px; }
.prog-label { font-size: 13px; color: #475569; }
.prog-count { font-size: 14px; font-weight: 700; color: #1e293b; min-width: 28px; text-align: right; }
.prog-bar-wrap { width: 80px; background: #f1f5f9; border-radius: 4px; height: 6px; flex-shrink: 0; }
.prog-bar { height: 6px; border-radius: 4px; transition: width 0.4s; }

/* ── Table ────────────────────────────────────────────────────────── */
.dash-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.dash-table th {
    background: #f8fafc; padding: 9px 12px; text-align: left;
    font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;
    letter-spacing: 0.4px; border-bottom: 1px solid #e2e8f0;
}
.dash-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
.dash-table tr:last-child td { border-bottom: none; }
.dash-table tr:hover td { background: #f8faff; }
.table-name-link { color: #0073bd; text-decoration: none; font-weight: 500; }
.table-name-link:hover { text-decoration: underline; }
.mono { font-family: monospace; font-size: 12px; color: #64748b; }
.text-muted { font-size: 12px; color: #94a3b8; }
.empty-row { text-align: center; color: #94a3b8; font-style: italic; padding: 24px; }
.badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.bg-green { background: #d1fae5; color: #065f46; }
.bg-red   { background: #fee2e2; color: #991b1b; }
.bg-warn  { background: #fef3c7; color: #92400e; }

/* ── Quick Actions ────────────────────────────────────────────────── */
.quick-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.quick-card {
    border-radius: 10px; padding: 14px 12px;
    text-decoration: none; display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 600; transition: all 0.2s; position: relative;
}
.quick-card:hover { transform: translateY(-2px); filter: brightness(0.95); }
.quick-card i { font-size: 18px; flex-shrink: 0; }
.qc-warn   { background: #fffbeb; color: #b45309; }
.qc-blue   { background: #eff6ff; color: #1d4ed8; }
.qc-indigo { background: #eef2ff; color: #4338ca; }
.qc-purple { background: #faf5ff; color: #7e22ce; }
.qc-teal   { background: #f0fdfa; color: #0f766e; }
.qc-badge {
    position: absolute; top: 6px; right: 8px;
    background: #ef4444; color: white;
    font-size: 10px; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

/* ── Rekomendasi ──────────────────────────────────────────────────── */
.rek-list { display: flex; flex-direction: column; gap: 8px; }
.rek-item { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
.rek-item:last-child { border-bottom: none; }
.rek-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.rek-meta { font-size: 11px; color: #94a3b8; margin-top: 1px; }

/* ── Responsive ───────────────────────────────────────────────────── */
@media (max-width: 900px) {
    .section-row { grid-template-columns: 1fr; }
    .stats-grid  { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 520px) {
    .stats-grid { grid-template-columns: 1fr; }
    .quick-grid { grid-template-columns: 1fr; }
    .verif-grid { grid-template-columns: 1fr; }
}
</style>

@endsection

@section('scripts')
<script>
(function () {
    function tick() {
        const el = document.getElementById('dashClock');
        if (!el) return;
        const now = new Date();
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const pad = n => String(n).padStart(2, '0');
        el.innerHTML =
            '<strong>' + days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() + '</strong><br>' +
            pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds()) + ' WIB';
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endsection
