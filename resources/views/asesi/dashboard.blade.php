@extends('asesi.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Asesi')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        border-radius: 12px;
        padding: 32px;
        color: white;
        margin-bottom: 24px;
    }

    .welcome-card h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .welcome-card p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.yellow { background: #fef3c7; color: #d97706; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-icon.rose { background: #ffe4e6; color: #e11d48; }

    .rekomendasi-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        margin-top: 24px;
    }

    .rekomendasi-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rek-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border-radius: 10px;
        border: 1px solid;
        margin-bottom: 12px;
    }

    .rek-item:last-child { margin-bottom: 0; }

    .rek-item.lanjut { background: #f0fdf4; border-color: #bbf7d0; }
    .rek-item.tidak_lanjut { background: #fff1f2; border-color: #fecdd3; }

    .rek-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .rek-item.lanjut .rek-icon { background: #dcfce7; color: #16a34a; }
    .rek-item.tidak_lanjut .rek-icon { background: #ffe4e6; color: #e11d48; }

    .rek-body { flex: 1; }

    .rek-body .rek-skema {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .rek-body .rek-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .rek-item.lanjut .rek-status { background: #dcfce7; color: #15803d; }
    .rek-item.tidak_lanjut .rek-status { background: #ffe4e6; color: #be123c; }

    .rek-body .rek-meta {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .rek-body .rek-catatan {
        font-size: 12px;
        color: #475569;
        font-style: italic;
        background: rgba(255,255,255,0.6);
        padding: 8px 12px;
        border-radius: 6px;
        border-left: 3px solid;
    }

    .rek-item.lanjut .rek-catatan { border-color: #22c55e; }
    .rek-item.tidak_lanjut .rek-catatan { border-color: #f43f5e; }

    .stat-info h3 {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-info .value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .quick-actions h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .action-item:hover {
        background: #f0fdf4;
        border-color: #22c55e;
        transform: translateX(4px);
    }

    .action-item .icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .action-item .text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .action-item .text p {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        margin-top: 24px;
    }

    .info-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .info-item label {
        display: block;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-item span {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <h2>Selamat Datang, {{ $asesi->nama ?? 'Asesi' }}! 👋</h2>
    <p>Anda berhasil masuk ke sistem LSP SMKN 1 Ciamis. Mulailah persiapan sertifikasi Anda dengan mengerjakan Asesmen Mandiri.</p>
</div>

@php
    $skemaCount      = $asesi ? $asesi->skemas()->count() : 0;
    $selesaiCount    = $asesi ? $asesi->skemas()->wherePivot('status', 'selesai')->count() : 0;
    $sedangCount     = $asesi ? $asesi->skemas()->wherePivot('status', 'sedang_mengerjakan')->count() : 0;
    $rekomendasiList = $asesi
        ? $asesi->skemas()->wherePivotNotNull('rekomendasi')->withPivot('rekomendasi','catatan_asesor','reviewed_at','reviewed_by')->get()
        : collect();
    $rekomendasiCount = $rekomendasiList->count();
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="bi bi-patch-check"></i>
        </div>
        <div class="stat-info">
            <h3>Total Skema Diambil</h3>
            <div class="value">{{ $skemaCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <h3>Sedang Dikerjakan</h3>
            <div class="value">{{ $sedangCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Asesmen Selesai</h3>
            <div class="value">{{ $selesaiCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $rekomendasiCount > 0 ? 'rose' : 'purple' }}">
            <i class="bi bi-person-check"></i>
        </div>
        <div class="stat-info">
            <h3>Direkomendasikan</h3>
            <div class="value">{{ $rekomendasiCount }}</div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h3><i class="bi bi-lightning"></i> Aksi Cepat</h3>
    <div class="action-list">
        <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="action-item">
            <div class="icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="text">
                <h4>Asesmen Mandiri</h4>
                <p>Isi form penilaian diri untuk persiapan sertifikasi</p>
            </div>
        </a>
        <a href="#" class="action-item" style="opacity:0.5;pointer-events:none;">
            <div class="icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="text">
                <h4>Lihat Hasil Asesmen</h4>
                <p>Cek hasil penilaian asesmen mandiri Anda</p>
            </div>
        </a>
    </div>
</div>

@if($rekomendasiList->count() > 0)
<div class="rekomendasi-card">
    <h3><i class="bi bi-person-check-fill" style="color:#16a34a;"></i> Rekomendasi Asesor</h3>
    @foreach($rekomendasiList as $skemaRek)
    @php
        $rek      = $skemaRek->pivot->rekomendasi;
        $rekLabel = $rek === 'lanjut' ? 'Dapat Dilanjutkan' : 'Tidak Dapat Dilanjutkan';
        $rekIcon  = $rek === 'lanjut' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
    @endphp
    <div class="rek-item {{ $rek }}">
        <div class="rek-icon">
            <i class="bi {{ $rekIcon }}"></i>
        </div>
        <div class="rek-body">
            <div class="rek-skema">{{ $skemaRek->nama_skema }}</div>
            <span class="rek-status">
                <i class="bi {{ $rekIcon }}"></i> {{ $rekLabel }}
            </span>
            <div class="rek-meta">
                Ditinjau oleh: <strong>{{ $skemaRek->pivot->reviewed_by ?? 'Asesor' }}</strong>
                @if($skemaRek->pivot->reviewed_at)
                    &bull; {{ \Carbon\Carbon::parse($skemaRek->pivot->reviewed_at)->translatedFormat('d F Y, H:i') }} WIB
                @endif
            </div>
            @if($skemaRek->pivot->catatan_asesor)
            <div class="rek-catatan">
                <i class="bi bi-chat-quote"></i> {{ $skemaRek->pivot->catatan_asesor }}
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@if($asesi)
<div class="info-card">
    <h3><i class="bi bi-person-circle"></i> Informasi Akun</h3>
    <div class="info-grid">
        <div class="info-item">
            <label>Nomor Registrasi</label>
            <span>{{ $account->id }}</span>
        </div>
        <div class="info-item">
            <label>NIK</label>
            <span>{{ $asesi->NIK }}</span>
        </div>
        <div class="info-item">
            <label>Nama Lengkap</label>
            <span>{{ $asesi->nama }}</span>
        </div>
        <div class="info-item">
            <label>Email</label>
            <span>{{ $asesi->email ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Jurusan</label>
            <span>{{ $asesi->jurusan->nama_jurusan ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Status Akun</label>
            <span style="color:#16a34a;font-weight:600;">{{ ucfirst($asesi->status) }}</span>
        </div>
    </div>
</div>
@endif
@endsection
