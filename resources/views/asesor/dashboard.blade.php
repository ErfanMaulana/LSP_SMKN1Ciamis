@extends('asesor.layout')

@section('title', 'Dashboard Asesor')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 14px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    .welcome-card h2 { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
    .welcome-card p  { font-size: 14px; opacity: 0.85; margin: 0; }
    .welcome-icon { font-size: 64px; opacity: 0.25; }

    .skema-info {
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        padding: 10px 16px;
        margin-top: 14px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 22px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon.blue   { background: #dbeafe; color: #2563eb; }
    .stat-icon.green  { background: #d1fae5; color: #059669; }
    .stat-icon.amber  { background: #fef3c7; color: #d97706; }
    .stat-icon.gray   { background: #f1f5f9; color: #64748b; }

    .stat-card h3 { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
    .stat-card p  { font-size: 12px; color: #64748b; font-weight: 500; margin: 0; }

    .section-title {
        font-size: 16px; font-weight: 700; color: #1e3a5f;
        margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-card tbody td {
        padding: 13px 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #64748b; }

    .btn-review {
        display: inline-flex; align-items: center; gap: 5px;
        background: #2563eb; color: white;
        padding: 5px 14px; border-radius: 6px;
        font-size: 12px; font-weight: 500; text-decoration: none;
        transition: background 0.2s;
    }
    .btn-review:hover { background: #1d4ed8; color: white; }

    .empty-state {
        text-align: center; padding: 50px 20px; color: #94a3b8;
    }
    .empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 14px; }
</style>
@endsection

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-card">
    <div>
        <h2>Selamat datang, {{ $asesor->nama ?? 'Asesor' }}! 👋</h2>
        <p>Pantau progres asesmen mandiri para asesi pada skema yang Anda ampuh.</p>
        @if($asesor?->skema)
            <div class="skema-info">
                <i class="bi bi-award"></i>
                <strong>{{ $asesor->skema->nama_skema }}</strong>
                &bull; {{ $asesor->skema->nomor_skema }}
            </div>
        @elseif(!$asesor)
            <div class="skema-info" style="background:rgba(239,68,68,0.2);">
                <i class="bi bi-exclamation-triangle"></i>
                Profil asesor belum dikaitkan ke akun ini. Hubungi admin.
            </div>
        @else
            <div class="skema-info">
                <i class="bi bi-info-circle"></i>
                Belum ada skema yang ditugaskan.
            </div>
        @endif
    </div>
    <div class="welcome-icon"><i class="bi bi-patch-check-fill"></i></div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div>
            <h3>{{ $stats['totalAsesi'] }}</h3>
            <p>Total Asesi</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-check2-circle"></i></div>
        <div>
            <h3>{{ $stats['selesai'] }}</h3>
            <p>Selesai Asesmen</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-pencil-square"></i></div>
        <div>
            <h3>{{ $stats['sedang'] }}</h3>
            <p>Sedang Dikerjakan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gray"><i class="bi bi-hourglass"></i></div>
        <div>
            <h3>{{ $stats['belum'] }}</h3>
            <p>Belum Mulai</p>
        </div>
    </div>
</div>

{{-- Asesi Terbaru Selesai --}}
<div class="section-title">
    <i class="bi bi-clock-history" style="color:#2563eb;"></i>
    Asesi Terbaru Selesai Asesmen
</div>

<div class="table-card">
    @if($recentCompleted->count())
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Asesi</th>
                <th>NIK</th>
                <th>No. Reg</th>
                <th>Selesai Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentCompleted as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div style="font-weight:600;color:#1e3a5f;">{{ $row->asesi?->nama ?? '-' }}</div>
                </td>
                <td><code style="font-size:12px;">{{ $row->asesi_nik }}</code></td>
                <td>{{ $row->asesi?->no_reg ?? '-' }}</td>
                <td>
                    @if($row->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($row->tanggal_selesai)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}
                    @else — @endif
                </td>
                <td>
                    <a href="{{ route('asesor.asesi.review', $row->asesi_nik) }}" class="btn-review">
                        <i class="bi bi-eye"></i> Review
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Belum ada asesi yang menyelesaikan asesmen mandiri.</p>
    </div>
    @endif
</div>

@endsection
