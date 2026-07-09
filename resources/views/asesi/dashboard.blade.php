@extends('asesi.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Asesi')

@section('styles')
<style>
    .welcome-card {
        background: #0073bd;
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

    .stat-icon.blue { background: #dbeafe; color: #0073bd; }
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

    .rek-item.lanjut { background: #eff6ff; border-color: #bfdbfe; }
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

    .rek-item.lanjut .rek-icon { background: #dbeafe; color: #0073bd; }
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

    .rek-item.lanjut .rek-status { background: #dbeafe; color: #0073bd; }
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

    .rek-item.lanjut .rek-catatan { border-color: #3b82f6; }
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
        background: #eff6ff;
        border-color: #3b82f6;
        transform: translateX(4px);
    }

    .action-item .icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #3b82f6 0%, #0073bd 100%);
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
        min-width: 0;
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
        display: block;
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .asesor-assigned-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
    }

    .asesor-assigned-meta {
        font-size: 12px;
        color: #0073bd;
        margin-top: 2px;
        line-height: 1.45;
        word-break: break-word;
    }

    .asesor-assigned-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        background: #dbeafe;
        color: #0073bd;
        white-space: nowrap;
    }

    .approved-banner {
        margin-bottom: 12px;
        padding: 8px 14px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        font-size: 12.5px;
        color: #0073bd;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.45;
    }

    @media (max-width: 768px) {
        .welcome-card,
        .quick-actions,
        .rekomendasi-card,
        .info-card {
            padding: 16px;
            margin-top: 16px;
        }

        .welcome-card {
            margin-bottom: 16px;
        }

        .welcome-card h2 {
            font-size: 18px;
            line-height: 1.3;
        }

        .stats-grid,
        .action-list,
        .info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
            gap: 12px;
        }

        .stat-info .value {
            font-size: 20px;
        }

        .action-item {
            padding: 14px;
            gap: 12px;
        }

        .action-item:hover {
            transform: none;
        }

        .rek-item {
            padding: 12px;
            gap: 10px;
        }

        .rek-body .rek-skema {
            font-size: 13px;
        }

        .asesor-assigned-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 12px;
        }

        .asesor-assigned-badge {
            align-self: flex-start;
        }

        .approved-banner {
            align-items: flex-start;
            font-size: 12px;
            padding: 10px 12px;
        }

        .info-item {
            padding: 10px 12px;
        }

        .info-item span {
            font-size: 13px;
            line-height: 1.45;
        }
    }

    /* Steps Timeline & Stepper Styles */
    .skema-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 30px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .skema-section:hover {
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.06);
    }

    .skema-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 24px;
        gap: 16px;
    }

    .skema-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    .skema-code {
        display: inline-block;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        color: #0073bd;
        background: rgba(0, 115, 189, 0.08);
        padding: 4px 10px;
        border-radius: 6px;
        margin-top: 6px;
    }

    .overall-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .overall-badge.completed {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .overall-badge.progressing {
        background: #eff6ff;
        color: #0073bd;
        border: 1px solid #bfdbfe;
    }

    /* Result Card Styles */
    .result-banner {
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .result-banner.kompeten {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1px solid #10b981;
        color: #065f46;
    }

    .result-banner.belum-kompeten {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #ef4444;
        color: #991b1b;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .result-banner.kompeten .result-icon {
        background: #10b981;
        color: #ffffff;
    }

    .result-banner.belum-kompeten .result-icon {
        background: #ef4444;
        color: #ffffff;
    }

    .result-info {
        flex: 1;
    }

    .result-status-title {
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 6px 0;
    }

    .result-status-desc {
        font-size: 14px;
        line-height: 1.5;
        opacity: 0.9;
        margin: 0;
    }

    .result-meta {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        font-size: 13px;
    }

    .result-meta-item strong {
        font-weight: 700;
    }

    /* Steps Timeline Styles */
    .timeline-container {
        position: relative;
        padding-left: 32px;
        margin-top: 10px;
    }

    .timeline-line {
        position: absolute;
        left: 12px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #e2e8f0;
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        margin-bottom: 24px;
        z-index: 2;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .step-marker {
        position: absolute;
        left: -32px;
        top: 2px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.25s ease;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        color: #94a3b8;
    }

    .timeline-step.completed .step-marker {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }

    .timeline-step.pending .step-marker {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
    }

    .step-content {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease;
    }

    .step-content:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .step-text {
        flex: 1;
    }

    .step-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px 0;
    }

    .step-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .step-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        white-space: nowrap;
    }

    .step-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .step-badge.pending {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .step-badge.warning {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        width: fit-content;
        margin-top: 10px;
    }

    .btn-primary-action {
        color: #ffffff;
        background: #0073bd;
        border: 1px solid #0073bd;
        box-shadow: 0 2px 6px rgba(0, 115, 189, 0.15);
    }

    .btn-primary-action:hover {
        background: #00568e;
        border-color: #00568e;
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        color: #0073bd;
        background: #ffffff;
        border: 1px solid #0073bd;
    }

    .btn-outline-primary:hover {
        background: #eff6ff;
        color: #00568e;
        border-color: #00568e;
    }

    .progress-bar-container {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #0073bd;
        transition: width 0.5s ease-out;
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <h2>Selamat Datang, {{ $asesi->nama ?? 'Asesi' }}!</h2>
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

@if($rekomendasiList->count() > 0)
<div class="rekomendasi-card">
    <h3><i class="bi bi-person-check-fill" style="color:#0073bd;"></i> Rekomendasi Asesor</h3>
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
{{-- ─── Asesor Penguji ─── --}}
@php
    // Asesor bisa dari assignment langsung (ID_asesor) atau dari kelompok
    $asesorTampil = $asesi->asesor
        ?? $asesi->kelompok?->asesors?->first();
@endphp
<div class="info-card" style="margin-top:24px;">
    <h3><i class="bi bi-person-badge-fill" style="color:#0073bd;"></i> Asesor Penguji</h3>
    @if($asesorTampil)
    <div class="asesor-assigned-card">
        <div style="width:48px;height:48px;border-radius:50%;background:#0073bd;color:white;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;flex-shrink:0;">
            {{ strtoupper(substr($asesorTampil->nama, 0, 1)) }}
        </div>
        <div style="flex:1;">
            <div style="font-size:15px;font-weight:700;color:#1e293b;">{{ $asesorTampil->nama }}</div>
            <div class="asesor-assigned-meta">
                @if($asesorTampil->skemas->count())
                    <i class="bi bi-patch-check-fill" style="color:#0073bd;"></i>
                    {{ $asesorTampil->skemas->pluck('nama_skema')->join(', ') }}
                @endif
                @if($asesorTampil->no_met)
                    &nbsp;·&nbsp; NO MET: {{ $asesorTampil->no_met }}
                @endif
            </div>
        </div>
        <span class="asesor-assigned-badge"><i class="bi bi-diagram-3-fill"></i> Ditugaskan</span>
    </div>
    @else
    <div style="padding:20px;text-align:center;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;color:#94a3b8;font-size:13px;">
        <i class="bi bi-person-dash" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        Belum ada asesor yang ditugaskan untuk Anda.
    </div>
    @endif
</div>

{{-- ─── Data Pendaftaran ─── --}}
<div class="info-card" style="margin-top:24px;">
    <h3><i class="bi bi-person-circle" style="color:#0073bd;"></i> Data Pendaftaran</h3>
    <div class="approved-banner">
        <i class="bi bi-check-circle-fill"></i>
        Pendaftaran Anda telah disetujui oleh admin.
        @if($asesi->verified_at)
            &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($asesi->verified_at)->translatedFormat('d F Y, H:i') }} WIB
        @endif
    </div>

    <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin:16px 0 10px;">Informasi Akun</div>
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
            <label>Kelas</label>
            <span>{{ $asesi->kelas ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Status Akun</label>
            <span style="color:#0073bd;font-weight:600;"><i class="bi bi-patch-check-fill"></i> Disetujui</span>
        </div>
        <div class="info-item">
            <label>Telepon / HP</label>
            <span>{{ $asesi->telepon_hp ?? $asesi->telepon_rumah ?? '-' }}</span>
        </div>
    </div>

    @if($asesi->tempat_lahir || $asesi->tanggal_lahir || $asesi->jenis_kelamin || $asesi->alamat)
    <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin:20px 0 10px;">Data Pribadi</div>
    <div class="info-grid">
        @if($asesi->tempat_lahir)
        <div class="info-item">
            <label>Tempat Lahir</label>
            <span>{{ $asesi->tempat_lahir }}</span>
        </div>
        @endif
        @if($asesi->tanggal_lahir)
        <div class="info-item">
            <label>Tanggal Lahir</label>
            <span>{{ \Carbon\Carbon::parse($asesi->tanggal_lahir)->translatedFormat('d F Y') }}</span>
        </div>
        @endif
        @if($asesi->jenis_kelamin)
        <div class="info-item">
            <label>Jenis Kelamin</label>
            <span>{{ $asesi->jenis_kelamin }}</span>
        </div>
        @endif
        @if($asesi->kebangsaan)
        <div class="info-item">
            <label>Kebangsaan</label>
            <span>{{ $asesi->kebangsaan }}</span>
        </div>
        @endif
        @if($asesi->alamat)
        <div class="info-item" style="grid-column:1/-1;">
            <label>Alamat</label>
            <span>{{ $asesi->alamat }}</span>
        </div>
        @endif
    </div>
    @endif

    @if($asesi->pendidikan_terakhir || $asesi->pekerjaan || $asesi->nama_lembaga || $asesi->jabatan)
    <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin:20px 0 10px;">Pekerjaan & Lembaga</div>
    <div class="info-grid">
        @if($asesi->pendidikan_terakhir)
        <div class="info-item">
            <label>Pendidikan Terakhir</label>
            <span>{{ $asesi->pendidikan_terakhir }}</span>
        </div>
        @endif
        @if($asesi->pekerjaan)
        <div class="info-item">
            <label>Pekerjaan</label>
            <span>{{ $asesi->pekerjaan }}</span>
        </div>
        @endif
        @if($asesi->jabatan)
        <div class="info-item">
            <label>Jabatan</label>
            <span>{{ $asesi->jabatan }}</span>
        </div>
        @endif
        @if($asesi->nama_lembaga)
        <div class="info-item">
            <label>Nama Lembaga</label>
            <span>{{ $asesi->nama_lembaga }}</span>
        </div>
        @endif
        @if($asesi->alamat_lembaga)
        <div class="info-item">
            <label>Alamat Lembaga</label>
            <span>{{ $asesi->alamat_lembaga }}</span>
        </div>
        @endif
        @if($asesi->unit_lembaga)
        <div class="info-item">
            <label>Unit / Bagian</label>
            <span>{{ $asesi->unit_lembaga }}</span>
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Status & Perkembangan Asesmen --}}
@if(isset($hasilUjikom) && $hasilUjikom->count())
    <div style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 32px 0 16px; text-transform: uppercase; letter-spacing: 0.5px;">
        <i class="bi bi-file-earmark-text" style="color: #0073bd; margin-right: 6px;"></i> Perkembangan Asesmen Kompetensi
    </div>
    @foreach($hasilUjikom as $row)
        <div class="skema-section">
            <div class="skema-header">
                <div>
                    <h3 class="skema-title" style="font-size:16px;">{{ $row->nama_skema }}</h3>
                    <span class="skema-code">{{ $row->nomor_skema }}</span>
                </div>
                <span class="overall-badge {{ $row->all_completed ? 'completed' : 'progressing' }}">
                    <i class="bi {{ $row->all_completed ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                    {{ $row->all_completed ? 'Tahapan Selesai' : 'Sedang Berlangsung' }}
                </span>
            </div>

            {{-- 1. Final Result Banner if completed --}}
            @if($row->all_completed)
                @if($row->rekomendasi === 'kompeten')
                    <div class="result-banner kompeten">
                        <div class="result-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="result-info">
                            <h4 class="result-status-title">Selamat! Anda Dinyatakan KOMPETEN</h4>
                            <p class="result-status-desc">
                                Berdasarkan evaluasi akhir dan bukti observasi langsung, tim asesor menyatakan bahwa kompetensi Anda pada skema sertifikasi ini memenuhi standar kompetensi kerja nasional.
                            </p>
                            <div class="result-meta">
                                <div class="result-meta-item">
                                    Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong>
                                </div>
                                <div class="result-meta-item">
                                    Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="result-banner belum-kompeten">
                        <div class="result-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="result-info">
                            <h4 class="result-status-title">Anda Dinyatakan BELUM KOMPETEN</h4>
                            <p class="result-status-desc">
                                Berdasarkan penilaian observasi langsung, terdapat kriteria unjuk kerja yang masih memerlukan pengembangan lebih lanjut untuk mencapai kualifikasi kompetensi penuh.
                            </p>
                            <div class="result-meta">
                                <div class="result-meta-item">
                                    Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong>
                                </div>
                                <div class="result-meta-item">
                                    Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong>
                                </div>
                            </div>
                            <div style="margin-top: 14px; display: flex; gap: 10px;">
                                @if(Route::has('asesi.banding.index'))
                                <a href="{{ route('asesi.banding.index') }}" class="btn-action btn-primary-action" style="margin-top:0;">
                                    <i class="bi bi-pencil-square"></i> Ajukan Banding Asesmen
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- Show progress bar based on steps completed --}}
                @php
                    $completedSteps = collect($row->steps)->where('status', 'completed')->count();
                    $totalSteps = count($row->steps);
                    $progressPercentage = ($completedSteps / $totalSteps) * 100;
                @endphp
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $progressPercentage }}%;"></div>
                </div>

                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px 20px; color: #0073bd; margin-bottom: 25px; font-size: 13px; line-height: 1.5; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="bi bi-info-circle-fill" style="font-size: 18px; color: #0073bd; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <strong>Hasil Uji Kompetensi Belum Tersedia.</strong>
                        <p style="margin: 4px 0 0 0; opacity: 0.95;">
                            Silakan selesaikan seluruh tahapan asesmen yang ditandai dengan status "Belum Selesai" atau "Menunggu Tindakan" di bawah ini agar keputusan akhir kompetensi dapat diproses.
                        </p>
                    </div>
                </div>
            @endif

            {{-- 2. Step Progression Timeline --}}
            <h4 style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                Tahapan Sertifikasi
            </h4>
            
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                @foreach($row->steps as $index => $step)
                    <div class="timeline-step {{ $step['status'] === 'completed' ? 'completed' : 'pending' }}">
                        <div class="step-marker">
                            @if($step['status'] === 'completed')
                                <i class="bi bi-check-lg"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="step-content">
                            <div class="step-text">
                                <h5 class="step-name">{{ $step['name'] }}</h5>
                                <p class="step-desc">{{ $step['description'] }}</p>
                                
                                {{-- Provide direct links/buttons for pending actions --}}
                                @if($step['status'] !== 'completed')
                                    @if($index === 1) {{-- Asesmen Mandiri --}}
                                        <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="btn-action btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Isi Asesmen Mandiri
                                        </a>
                                    @elseif($index === 3) {{-- Persetujuan Asesmen --}}
                                        @if(!empty($step['is_ready']))
                                            <a href="{{ route('asesi.persetujuan-asesmen.index') }}" class="btn-action btn-outline-primary">
                                                <i class="bi bi-file-earmark-check"></i> Buka Persetujuan Asesmen
                                            </a>
                                        @endif
                                    @elseif($index === 4 && $row->ceklis && empty($row->ceklis->ttd_asesi_file)) {{-- Ceklis Observasi --}}
                                        @if(($row->steps[3]['status'] ?? '') === 'completed')
                                            <a href="{{ route('asesi.ceklis-observasi.view', $row->ceklis->id) }}" class="btn-action btn-outline-primary">
                                                <i class="bi bi-pen"></i> Tanda Tangani Ceklis Observasi
                                            </a>
                                        @endif
                                    @elseif($index === 5 && $row->rekaman && empty($row->rekaman->ttd_asesi_file)) {{-- Rekaman Asesmen --}}
                                        @if(($row->steps[4]['status'] ?? '') === 'completed')
                                            <a href="{{ route('asesi.rekaman-asesmen.view', $row->rekaman->id) }}" class="btn-action btn-outline-primary">
                                                <i class="bi bi-pen"></i> Tanda Tangani Rekaman Asesmen
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            
                            @php
                                $badgeClass = 'pending';
                                if ($step['status'] === 'completed') {
                                    $badgeClass = 'completed';
                                } elseif ($step['label'] === 'Menunggu Tanda Tangan Anda') {
                                    $badgeClass = 'warning';
                                }
                            @endphp
                            <span class="step-badge {{ $badgeClass }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif
@endif
@endsection
