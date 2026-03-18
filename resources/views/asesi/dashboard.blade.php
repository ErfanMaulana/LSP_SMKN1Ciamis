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

    .rek-item.lanjut .rek-status { background: #dbeafe; color: #0c4a6e; }
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
        color: #64748b;
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
        color: #1d4ed8;
        white-space: nowrap;
    }

    .approved-banner {
        margin-bottom: 12px;
        padding: 8px 14px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        font-size: 12.5px;
        color: #0c4a6e;
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
    <h3><i class="bi bi-person-circle"></i> Data Pendaftaran</h3>
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
@endif
@endsection
