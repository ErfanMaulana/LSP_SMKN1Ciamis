@php
    $item = $item ?? null;
    $role = $role ?? 'asesi';
    $skema = $skema ?? null;
    $layout = $role === 'asesor' ? 'asesor.layout' : 'asesi.layout';
    $hasChecklist = (bool) (
        ($item->bukti_verifikasi_portofolio ?? false) ||
        ($item->bukti_reviu_produk ?? false) ||
        ($item->bukti_observasi_langsung ?? false) ||
        ($item->bukti_kegiatan_terstruktur ?? false) ||
        ($item->bukti_pertanyaan_lisan ?? false) ||
        ($item->bukti_pertanyaan_tertulis ?? false) ||
        ($item->bukti_pertanyaan_wawancara ?? false) ||
        ($item->bukti_lainnya ?? false)
    );

    $typeStr = strtolower(trim((string)($item->kategori_skema ?? $skema?->jenis_skema ?? 'Okupasi')));
    $isKKNI = str_contains($typeStr, 'kkni');
    $isOkupasi = str_contains($typeStr, 'okupasi');
    $isKlaster = str_contains($typeStr, 'klaster');
    $jenisSkemaBadge = $isKKNI ? 'KKNI' : ($isKlaster ? 'Klaster' : 'Okupasi');

    $isAsesorSigned = !empty($item->ttd_asesor_file);
    $isAsesiSigned = !empty($item->ttd_asesi_file);
    $isBothSigned = $isAsesorSigned && $isAsesiSigned;
@endphp

@extends($layout)

@section('title', 'Persetujuan Asesmen - ' . ($item->judul_skema ?: ($skema->nama_skema ?? 'FR.AK.01')))
@section('page-title', 'Persetujuan Asesmen dan Kerahasiaan')

@section('styles')
<style>
    .ak-container {
        max-width: 1080px;
        margin: 0 auto 40px;
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .btn-custom {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-custom-secondary {
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .btn-custom-secondary:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .btn-custom-primary {
        background: #0073bd;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 115, 189, 0.25);
    }
    .btn-custom-primary:hover {
        background: #005f9a;
        color: #ffffff;
    }

    /* Main Card */
    .ak-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .ak-card-header {
        padding: 24px 28px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .ak-card-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .ak-icon-badge {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #0073bd, #0284c7);
        color: #ffffff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.2);
        flex-shrink: 0;
    }

    .ak-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px;
    }

    .ak-badge-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .ak-card-body {
        padding: 28px;
    }

    /* Status Banner */
    .status-banner {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .status-banner-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    .status-banner-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .status-banner i {
        font-size: 22px;
        flex-shrink: 0;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .info-tile {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.2s;
    }
    .info-tile:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .info-tile-label {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-tile-value {
        font-size: 14.5px;
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
    }

    .info-tile-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Section Headers */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #0073bd;
        font-size: 18px;
    }

    /* Checklist Cards */
    .evidence-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 12px;
        margin-bottom: 28px;
    }

    .evidence-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s;
    }

    .evidence-card.active {
        background: #f0f9ff;
        border-color: #bae6fd;
    }

    .evidence-card input[type="checkbox"] {
        margin-top: 2px;
        width: 18px;
        height: 18px;
        accent-color: #0073bd;
        cursor: pointer;
        flex-shrink: 0;
    }

    .evidence-card-label {
        font-size: 13.5px;
        font-weight: 500;
        color: #1e293b;
        cursor: pointer;
        user-select: none;
    }

    .evidence-card-readonly {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .evidence-card-readonly.checked {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .evidence-status-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .evidence-status-icon.checked {
        background: #dcfce7;
        color: #16a34a;
    }

    .evidence-status-icon.unchecked {
        background: #e2e8f0;
        color: #94a3b8;
    }

    /* Agreements & Clauses Cards */
    .clauses-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 28px;
    }

    .clause-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #0073bd;
        border-radius: 10px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .clause-card.asesor-clause {
        border-left-color: #6366f1;
    }

    .clause-role {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .clause-role.asesi { color: #0073bd; }
    .clause-role.asesor { color: #4f46e5; }

    .clause-text {
        font-size: 13.5px;
        color: #334155;
        line-height: 1.5;
        margin: 0;
    }

    /* Signature Section (2-Column Cards) */
    .sig-section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 10px;
    }

    .sig-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        position: relative;
    }

    .sig-card.signed {
        border-color: #bbf7d0;
        background: #fcfdfd;
    }

    .sig-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .sig-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sig-badge-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .sig-badge-status.signed {
        background: #dcfce7;
        color: #166534;
    }

    .sig-badge-status.waiting {
        background: #fef3c7;
        color: #92400e;
    }

    .sig-badge-status.ready {
        background: #e0f2fe;
        color: #0369a1;
    }

    .sig-preview-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        margin-bottom: 14px;
    }

    .sig-preview-img {
        max-width: 220px;
        max-height: 80px;
        object-fit: contain;
        display: block;
    }

    .sig-signer-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-top: 8px;
        text-align: center;
    }

    .sig-date-info {
        font-size: 12px;
        color: #64748b;
        text-align: center;
        margin-top: 2px;
    }

    /* Signature Canvas Pad */
    .signature-canvas-wrapper {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        overflow: hidden;
        width: 100%;
        margin: 10px 0 8px;
        aspect-ratio: 16 / 8;
    }

    .signature-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        cursor: crosshair;
        display: block;
        z-index: 3;
        background: transparent;
    }

    .signature-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        pointer-events: none;
        color: #94a3b8;
        font-size: 12px;
        z-index: 1;
    }

    .signature-placeholder i {
        font-size: 24px;
        margin-bottom: 4px;
    }

    .sig-pad-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .btn-clear-sig {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .btn-clear-sig:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .btn-save-signature {
        width: 100%;
        padding: 10px;
        background: #0073bd;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(0, 115, 189, 0.25);
        transition: background 0.2s;
    }

    .btn-save-signature:hover {
        background: #005f9a;
    }

    .sig-option-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        margin-bottom: 6px;
        font-size: 12.5px;
        font-weight: 600;
        transition: all 0.2s;
    }

    @media (max-width: 768px) {
        .ak-card-header {
            padding: 18px 20px;
        }
        .ak-card-body {
            padding: 20px;
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
        .sig-section-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="ak-container">
    {{-- Top Action Navigation --}}
    <div class="top-actions">
        @if($role === 'asesor')
            @php
                $backToVal = $backTo ?? request()->get('back_to', '');
            @endphp
            @if($backToVal === 'detail_asesi' && !empty($asesiNik))
                <a href="{{ route('asesor.asesi.show', $asesiNik) }}" class="btn-custom btn-custom-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Asesi
                </a>
            @else
                <a href="{{ route('asesor.persetujuan-asesmen.index') }}" class="btn-custom btn-custom-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Persetujuan
                </a>
            @endif

            @if($isBothSigned)
                <a href="{{ route('asesor.persetujuan.front.asesor.export-word', ['asesiNik' => $asesiNik, 'skemaId' => $skema->id]) }}" class="btn-custom btn-custom-primary" target="_blank">
                    <i class="bi bi-file-earmark-word"></i> Unduh Dokumen (FR.AK.01.docx)
                </a>
            @endif
        @else
            <a href="{{ route('asesi.persetujuan-asesmen.index') }}" class="btn-custom btn-custom-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Persetujuan Asesmen
            </a>
        @endif
    </div>

    {{-- Main Web Card --}}
    <div class="ak-card">
        {{-- Card Header --}}
        <div class="ak-card-header">
            <div class="ak-card-title-group">
                <div class="ak-icon-badge">
                    <i class="bi bi-file-earmark-check-fill"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                        <span class="ak-badge-code">{{ $item->kode_form ?: 'FR.AK.01' }}</span>
                        <span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:700; border-radius:20px; padding:3px 10px; font-size:12px;">Skema {{ $jenisSkemaBadge }}</span>
                    </div>
                    <h2 class="ak-card-title">{{ $item->judul_skema ?: ($skema->nama_skema ?? 'Persetujuan Asesmen dan Kerahasiaan') }}</h2>
                    <div style="font-size: 13px; color: #64748b; font-family: monospace;">
                        Nomor Skema: {{ $item->nomor_skema ?: ($skema->nomor_skema ?? '-') }}
                    </div>
                </div>
            </div>
            <div>
                @if($isBothSigned)
                    <span class="badge" style="background: #dcfce7; color: #15803d; padding: 8px 14px; border-radius: 30px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-check-all" style="font-size: 16px;"></i> Selesai Ditandatangani
                    </span>
                @elseif($isAsesorSigned)
                    <span class="badge" style="background: #fef3c7; color: #b45309; padding: 8px 14px; border-radius: 30px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-hourglass-split"></i> Menunggu Tanda Tangan Asesi
                    </span>
                @else
                    <span class="badge" style="background: #f1f5f9; color: #475569; padding: 8px 14px; border-radius: 30px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-pen"></i> Menunggu Tanda Tangan Asesor
                    </span>
                @endif
            </div>
        </div>

        <div class="ak-card-body">
            {{-- Status Banner Arahan --}}
            <div class="status-banner {{ $isBothSigned ? 'status-banner-success' : 'status-banner-info' }}">
                <i class="bi {{ $isBothSigned ? 'bi-check-circle-fill' : 'bi-info-circle-fill' }}"></i>
                <div style="font-size: 13.5px; line-height: 1.45;">
                    <strong>Petunjuk &amp; Arahan:</strong>
                    {{ $item->pengantar ?: 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen.' }}
                </div>
            </div>

            {{-- Info Grid (Participants & Schedule) --}}
            <div class="info-grid">
                <div class="info-tile">
                    <div class="info-tile-label"><i class="bi bi-person-fill"></i> Nama Asesi</div>
                    <div class="info-tile-value">{{ $item->nama_asesi }}</div>
                    @if(!empty($item->asesi_nik))
                        <div class="info-tile-sub">NIK: {{ $item->asesi_nik }}</div>
                    @endif
                </div>
                <div class="info-tile">
                    <div class="info-tile-label"><i class="bi bi-person-badge-fill"></i> Nama Asesor</div>
                    <div class="info-tile-value">{{ $item->nama_asesor }}</div>
                    <div class="info-tile-sub">Asesor Kompetensi</div>
                </div>
                <div class="info-tile">
                    <div class="info-tile-label"><i class="bi bi-calendar-event"></i> Waktu Asesmen</div>
                    <div class="info-tile-value">{{ $item->hari_tanggal ?: '-' }}</div>
                    <div class="info-tile-sub">Pukul: {{ $item->waktu ?: '-' }}</div>
                </div>
                <div class="info-tile">
                    <div class="info-tile-label"><i class="bi bi-geo-alt-fill"></i> Tempat Uji (TUK)</div>
                    <div class="info-tile-value">{{ $item->tuk_pelaksanaan ?: ($item->tuk ?: '-') }}</div>
                    <div class="info-tile-sub">Tipe: {{ $item->tuk ?: 'Sewaktu' }}</div>
                </div>
            </div>

            {{-- Form Wrapper if Asesor is filling evidence --}}
            @if($role === 'asesor' && empty($item->ttd_asesi_file))
            <form method="POST" action="{{ route('asesor.persetujuan.front.asesor.sign', $item->id) }}" id="formTandaTanganAsesor">
                @csrf
                @if(!empty($backTo))
                    <input type="hidden" name="back_to" value="{{ $backTo }}">
                @endif
            @endif

            {{-- Evidence Checklist Section --}}
            <div class="section-title">
                <i class="bi bi-check2-square"></i> Bukti yang Akan Dikumpulkan
            </div>

            @if($role === 'asesor' && empty($item->ttd_asesi_file))
                {{-- Editable evidence checklist for Asesor --}}
                <div class="evidence-grid-modern">
                    <label class="evidence-card {{ $item->bukti_verifikasi_portofolio ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_verifikasi_portofolio" value="1" {{ $item->bukti_verifikasi_portofolio ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Verifikasi Portofolio</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_reviu_produk ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_reviu_produk" value="1" {{ $item->bukti_reviu_produk ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Reviu Produk</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_observasi_langsung ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_observasi_langsung" value="1" {{ $item->bukti_observasi_langsung ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Observasi Langsung</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_kegiatan_terstruktur ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_kegiatan_terstruktur" value="1" {{ $item->bukti_kegiatan_terstruktur ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Kegiatan Terstruktur</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_pertanyaan_lisan ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_pertanyaan_lisan" value="1" {{ $item->bukti_pertanyaan_lisan ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Pertanyaan Lisan</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_pertanyaan_tertulis ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_pertanyaan_tertulis" value="1" {{ $item->bukti_pertanyaan_tertulis ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Pertanyaan Tertulis</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_pertanyaan_wawancara ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_pertanyaan_wawancara" value="1" {{ $item->bukti_pertanyaan_wawancara ? 'checked' : '' }}>
                        <span class="evidence-card-label">Hasil Pertanyaan Wawancara</span>
                    </label>
                    <label class="evidence-card {{ $item->bukti_lainnya ? 'active' : '' }}">
                        <input type="checkbox" name="bukti_lainnya" value="1" {{ $item->bukti_lainnya ? 'checked' : '' }} id="buktiLainnyaCheckbox">
                        <span class="evidence-card-label">Lainnya...</span>
                    </label>
                </div>
                <div id="buktiLainnyaKeteranganDiv" style="margin-top: -16px; margin-bottom: 24px; display: {{ $item->bukti_lainnya ? 'block' : 'none' }};">
                    <input type="text" name="bukti_lainnya_keterangan" placeholder="Ketik rincian bukti lainnya..." value="{{ $item->bukti_lainnya_keterangan }}" style="width:100%; padding:9px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:13px;">
                </div>
            @else
                {{-- Modern Readonly Checklist Badges --}}
                <div class="evidence-grid-modern">
                    <div class="evidence-card-readonly {{ $item->bukti_verifikasi_portofolio ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_verifikasi_portofolio ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_verifikasi_portofolio ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Verifikasi Portofolio</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_reviu_produk ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_reviu_produk ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_reviu_produk ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Reviu Produk</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_observasi_langsung ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_observasi_langsung ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_observasi_langsung ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Observasi Langsung</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_kegiatan_terstruktur ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_kegiatan_terstruktur ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_kegiatan_terstruktur ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Kegiatan Terstruktur</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_pertanyaan_lisan ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_pertanyaan_lisan ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_pertanyaan_lisan ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Pertanyaan Lisan</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_pertanyaan_tertulis ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_pertanyaan_tertulis ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_pertanyaan_tertulis ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Pertanyaan Tertulis</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_pertanyaan_wawancara ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_pertanyaan_wawancara ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_pertanyaan_wawancara ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Hasil Pertanyaan Wawancara</span>
                    </div>
                    <div class="evidence-card-readonly {{ $item->bukti_lainnya ? 'checked' : '' }}">
                        <div class="evidence-status-icon {{ $item->bukti_lainnya ? 'checked' : 'unchecked' }}">
                            <i class="bi {{ $item->bukti_lainnya ? 'bi-check-lg' : 'bi-dash' }}"></i>
                        </div>
                        <span class="evidence-card-label">Lainnya {{ $item->bukti_lainnya_keterangan ? '('.$item->bukti_lainnya_keterangan.')' : '' }}</span>
                    </div>
                </div>
            @endif

            {{-- Klausul Persetujuan & Kerahasiaan --}}
            <div class="section-title">
                <i class="bi bi-shield-lock-fill"></i> Pernyataan Persetujuan &amp; Kerahasiaan
            </div>

            <div class="clauses-container">
                <div class="clause-card">
                    <div class="clause-role asesi"><i class="bi bi-chat-quote-fill"></i> Pernyataan Asesi (Hak &amp; Prosedur Banding)</div>
                    <p class="clause-text">{{ $item->pernyataan_asesi_1 ?: 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.' }}</p>
                </div>
                <div class="clause-card asesor-clause">
                    <div class="clause-role asesor"><i class="bi bi-shield-check"></i> Komitmen Kerahasiaan Asesor</div>
                    <p class="clause-text">{{ $item->pernyataan_asesor ?: 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.' }}</p>
                </div>
                <div class="clause-card">
                    <div class="clause-role asesi"><i class="bi bi-person-check-fill"></i> Kesepakatan Asesi (Pengembangan Profesional)</div>
                    <p class="clause-text">{{ $item->pernyataan_asesi_2 ?: 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.' }}</p>
                </div>
            </div>

            {{-- Signature Digital Grid --}}
            <div class="section-title">
                <i class="bi bi-pen-fill"></i> Tanda Tangan Digital
            </div>

            <div class="sig-section-grid">
                {{-- Card Asesor --}}
                <div class="sig-card {{ $isAsesorSigned ? 'signed' : '' }}">
                    <div>
                        <div class="sig-card-header">
                            <div class="sig-card-title">
                                <i class="bi bi-person-badge text-primary"></i> Asesor Kompetensi
                            </div>
                            @if($isAsesorSigned)
                                <span class="sig-badge-status signed"><i class="bi bi-check-circle-fill"></i> Terverifikasi</span>
                            @else
                                <span class="sig-badge-status waiting"><i class="bi bi-clock"></i> Belum Tanda Tangan</span>
                            @endif
                        </div>

                        @if($isAsesorSigned)
                            <div class="sig-preview-box">
                                <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="TTD Asesor" class="sig-preview-img">
                                <div class="sig-signer-name">{{ $item->ttd_asesor_nama ?: $item->nama_asesor }}</div>
                                <div class="sig-date-info">Ditandatangani pada: {{ \Carbon\Carbon::parse($item->ttd_asesor_tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                            </div>
                        @elseif($role === 'asesor')
                            {{-- Interactive signing pad for Asesor --}}
                            @if(isset($savedSignature) && $savedSignature)
                                <div id="sigChoiceWrapAsesor" style="margin-bottom: 8px;">
                                    <label class="sig-option-pill" id="optSavedAsesorLabel" style="border-color:#bbf7d0; background:#f0fdf4;">
                                        <input type="radio" name="sig_choice_asesor" value="saved" checked id="optSavedAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#16a34a;">
                                        <span style="color:#166534;"><i class="bi bi-check-circle-fill"></i> Gunakan Tanda Tangan Profil</span>
                                    </label>
                                    <label class="sig-option-pill" id="optNewAsesorLabel">
                                        <input type="radio" name="sig_choice_asesor" value="new" id="optNewAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#0073bd;">
                                        <span><i class="bi bi-pen"></i> Buat Tanda Tangan Baru</span>
                                    </label>
                                </div>
                                <div id="savedAsesorSigPreview" class="sig-preview-box">
                                    <img src="{{ $savedSignature }}" alt="TTD Profil" class="sig-preview-img">
                                    <div class="sig-signer-name">{{ $item->nama_asesor }}</div>
                                </div>
                                <div id="newAsesorSigDraw" style="display:none;">
                                    <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                                        <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                                        <div class="signature-placeholder">
                                            <i class="bi bi-pen"></i>
                                            <span>Goreskan tanda tangan di sini</span>
                                        </div>
                                    </div>
                                    <div class="sig-pad-actions">
                                        <button type="button" class="btn-clear-sig" id="clearSignatureAsesor">
                                            <i class="bi bi-eraser"></i> Hapus
                                        </button>
                                        <span style="font-size:11.5px; color:#64748b;">Tanggal: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                                    <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                                    <div class="signature-placeholder">
                                        <i class="bi bi-pen"></i>
                                        <span>Goreskan tanda tangan di sini</span>
                                    </div>
                                </div>
                                <div class="sig-pad-actions">
                                    <button type="button" class="btn-clear-sig" id="clearSignatureAsesor">
                                        <i class="bi bi-eraser"></i> Hapus
                                    </button>
                                    <span style="font-size:11.5px; color:#64748b;">Tanggal: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                </div>
                            @endif

                            <input type="hidden" name="ttd_asesor_nama" value="{{ $item->nama_asesor }}">
                            <input type="hidden" name="ttd_asesor_tanggal" value="{{ now()->format('Y-m-d') }}">
                            <input type="hidden" name="ttd_asesor_file" id="ttdAsesorFileInput">
                            <button type="submit" class="btn-save-signature">
                                <i class="bi bi-check-circle-fill"></i> Simpan &amp; Tanda Tangani Sebagai Asesor
                            </button>
                        @else
                            <div class="sig-preview-box">
                                <i class="bi bi-hourglass-split" style="font-size: 32px; color: #94a3b8; margin-bottom: 6px;"></i>
                                <div style="font-size: 13px; color: #64748b;">Menunggu tanda tangan Asesor</div>
                                <div class="sig-signer-name">{{ $item->nama_asesor }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card Asesi --}}
                <div class="sig-card {{ $isAsesiSigned ? 'signed' : '' }}">
                    <div>
                        <div class="sig-card-header">
                            <div class="sig-card-title">
                                <i class="bi bi-person text-primary"></i> Asesi (Peserta)
                            </div>
                            @if($isAsesiSigned)
                                <span class="sig-badge-status signed"><i class="bi bi-check-circle-fill"></i> Terverifikasi</span>
                            @elseif(!$isAsesorSigned)
                                <span class="sig-badge-status waiting"><i class="bi bi-hourglass"></i> Menunggu Asesor</span>
                            @else
                                <span class="sig-badge-status ready"><i class="bi bi-pen"></i> Siap Ditandatangani</span>
                            @endif
                        </div>

                        @if($isAsesiSigned)
                            <div class="sig-preview-box">
                                <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="TTD Asesi" class="sig-preview-img">
                                <div class="sig-signer-name">{{ $item->ttd_asesi_nama ?: $item->nama_asesi }}</div>
                                <div class="sig-date-info">Ditandatangani pada: {{ \Carbon\Carbon::parse($item->ttd_asesi_tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                            </div>
                        @elseif($role === 'asesi' && !empty($item->ttd_asesor_file))
                            {{-- Interactive signing pad for Asesi --}}
                            <form method="POST" action="{{ route('asesi.persetujuan.front.asesi.sign', $item->id) }}" id="formTandaTanganAsesi">
                                @csrf
                                @if(isset($savedSignature) && $savedSignature)
                                    <div id="sigChoiceWrapAsesi" style="margin-bottom: 8px;">
                                        <label class="sig-option-pill" id="optSavedAsesiLabel" style="border-color:#bbf7d0; background:#f0fdf4;">
                                            <input type="radio" name="sig_choice_asesi" value="saved" checked id="optSavedAsesi" onchange="toggleAsesiSigChoice()" style="accent-color:#16a34a;">
                                            <span style="color:#166534;"><i class="bi bi-check-circle-fill"></i> Gunakan Tanda Tangan Profil</span>
                                        </label>
                                        <label class="sig-option-pill" id="optNewAsesiLabel">
                                            <input type="radio" name="sig_choice_asesi" value="new" id="optNewAsesi" onchange="toggleAsesiSigChoice()" style="accent-color:#0073bd;">
                                            <span><i class="bi bi-pen"></i> Buat Tanda Tangan Baru</span>
                                        </label>
                                    </div>
                                    <div id="savedAsesiSigPreview" class="sig-preview-box">
                                        <img src="{{ $savedSignature }}" alt="TTD Profil" class="sig-preview-img">
                                        <div class="sig-signer-name">{{ $item->nama_asesi }}</div>
                                    </div>
                                    <div id="newAsesiSigDraw" style="display:none;">
                                        <div class="signature-canvas-wrapper" id="signatureWrapperAsesi">
                                            <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                                            <div class="signature-placeholder">
                                                <i class="bi bi-pen"></i>
                                                <span>Goreskan tanda tangan di sini</span>
                                            </div>
                                        </div>
                                        <div class="sig-pad-actions">
                                            <button type="button" class="btn-clear-sig" id="clearSignatureAsesi">
                                                <i class="bi bi-eraser"></i> Hapus
                                            </button>
                                            <span style="font-size:11.5px; color:#64748b;">Tanggal: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="signature-canvas-wrapper" id="signatureWrapperAsesi">
                                        <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                                        <div class="signature-placeholder">
                                            <i class="bi bi-pen"></i>
                                            <span>Goreskan tanda tangan di sini</span>
                                        </div>
                                    </div>
                                    <div class="sig-pad-actions">
                                        <button type="button" class="btn-clear-sig" id="clearSignatureAsesi">
                                            <i class="bi bi-eraser"></i> Hapus
                                        </button>
                                        <span style="font-size:11.5px; color:#64748b;">Tanggal: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                    </div>
                                @endif

                                <input type="hidden" name="ttd_asesi_nama" value="{{ $item->nama_asesi }}">
                                <input type="hidden" name="ttd_asesi_tanggal" value="{{ now()->format('Y-m-d') }}">
                                <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput">
                                <button type="submit" class="btn-save-signature">
                                    <i class="bi bi-check-circle-fill"></i> Simpan &amp; Tanda Tangani Sebagai Asesi
                                </button>
                            </form>
                        @else
                            <div class="sig-preview-box">
                                <i class="bi bi-hourglass-split" style="font-size: 32px; color: #94a3b8; margin-bottom: 6px;"></i>
                                <div style="font-size: 13px; color: #64748b;">
                                    @if(empty($item->ttd_asesor_file))
                                        Menunggu persetujuan Asesor terlebih dahulu
                                    @else
                                        Belum ditandatangani oleh Asesi
                                    @endif
                                </div>
                                <div class="sig-signer-name">{{ $item->nama_asesi }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($role === 'asesor' && empty($item->ttd_asesi_file))
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function initSignatureCanvas(config) {
        const canvas = document.getElementById(config.canvasId);
        const clearBtn = document.getElementById(config.clearBtnId);
        const hiddenInput = document.getElementById(config.hiddenInputId);
        const wrapper = document.getElementById(config.wrapperId);
        const form = document.getElementById(config.formId);

        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let drawing = false;
        let hasSignature = false;
        const placeholder = wrapper ? wrapper.querySelector('.signature-placeholder') : null;

        function resize() {
            const rect = canvas.getBoundingClientRect();
            if (rect.width <= 0 || rect.height <= 0) return;
            const ratio = window.devicePixelRatio || 1;
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            ctx.scale(ratio, ratio);
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
        }

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function start(e) {
            e.preventDefault();
            drawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            if (placeholder) placeholder.style.display = 'none';
        }

        function move(e) {
            if (!drawing) return;
            e.preventDefault();
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            hasSignature = true;
        }

        function stop(e) {
            if (!drawing) return;
            e.preventDefault();
            drawing = false;
            if (hasSignature && hiddenInput) {
                hiddenInput.value = canvas.toDataURL('image/png');
            }
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                if (placeholder) placeholder.style.display = 'flex';
                if (hiddenInput) hiddenInput.value = '';
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                if (hasSignature && hiddenInput) {
                    hiddenInput.value = canvas.toDataURL('image/png');
                }
            });
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        canvas.addEventListener('mouseup', stop);
        canvas.addEventListener('mouseleave', stop);
        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove', move, { passive: false });
        canvas.addEventListener('touchend', stop);
        window.addEventListener('resize', resize);
        resize();
    }

    initSignatureCanvas({
        canvasId: 'signatureCanvasAsesor',
        clearBtnId: 'clearSignatureAsesor',
        hiddenInputId: 'ttdAsesorFileInput',
        wrapperId: 'signatureWrapperAsesor',
        formId: 'formTandaTanganAsesor',
    });

    initSignatureCanvas({
        canvasId: 'signatureCanvasAsesi',
        clearBtnId: 'clearSignatureAsesi',
        hiddenInputId: 'ttdAsesiFileInput',
        wrapperId: 'signatureWrapperAsesi',
        formId: 'formTandaTanganAsesi',
    });

    const buktiLainnyaCheckbox = document.getElementById('buktiLainnyaCheckbox');
    const buktiLainnyaKeteranganDiv = document.getElementById('buktiLainnyaKeteranganDiv');
    if (buktiLainnyaCheckbox && buktiLainnyaKeteranganDiv) {
        buktiLainnyaCheckbox.addEventListener('change', function() {
            buktiLainnyaKeteranganDiv.style.display = this.checked ? 'block' : 'none';
        });
    }

    const savedSignature = @json($savedSignature ?? null);
    window.toggleAsesorSigChoice = function() {
        const optSaved = document.getElementById('optSavedAsesor');
        const savedPreview = document.getElementById('savedAsesorSigPreview');
        const newDraw = document.getElementById('newAsesorSigDraw');
        const optSavedLabel = document.getElementById('optSavedAsesorLabel');
        const optNewLabel = document.getElementById('optNewAsesorLabel');
        const hiddenInput = document.getElementById('ttdAsesorFileInput');

        if (!optSaved) return;

        if (optSaved.checked) {
            if (savedPreview) savedPreview.style.display = '';
            if (newDraw) newDraw.style.display = 'none';
            if (optSavedLabel) {
                optSavedLabel.style.borderColor = '#bbf7d0'; optSavedLabel.style.background = '#f0fdf4';
            }
            if (optNewLabel) {
                optNewLabel.style.borderColor = '#e2e8f0'; optNewLabel.style.background = '#f8fafc';
            }
            if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
        } else {
            if (savedPreview) savedPreview.style.display = 'none';
            if (newDraw) newDraw.style.display = 'block';
            if (optSavedLabel) {
                optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc';
            }
            if (optNewLabel) {
                optNewLabel.style.borderColor = '#bfdbfe'; optNewLabel.style.background = '#eff6ff';
            }
            if (hiddenInput) hiddenInput.value = '';
            setTimeout(function() {
                window.dispatchEvent(new Event('resize'));
            }, 50);
        }
    };

    window.toggleAsesiSigChoice = function() {
        const optSaved = document.getElementById('optSavedAsesi');
        const savedPreview = document.getElementById('savedAsesiSigPreview');
        const newDraw = document.getElementById('newAsesiSigDraw');
        const optSavedLabel = document.getElementById('optSavedAsesiLabel');
        const optNewLabel = document.getElementById('optNewAsesiLabel');
        const hiddenInput = document.getElementById('ttdAsesiFileInput');

        if (!optSaved) return;

        if (optSaved.checked) {
            if (savedPreview) savedPreview.style.display = '';
            if (newDraw) newDraw.style.display = 'none';
            if (optSavedLabel) {
                optSavedLabel.style.borderColor = '#bbf7d0'; optSavedLabel.style.background = '#f0fdf4';
            }
            if (optNewLabel) {
                optNewLabel.style.borderColor = '#e2e8f0'; optNewLabel.style.background = '#f8fafc';
            }
            if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
        } else {
            if (savedPreview) savedPreview.style.display = 'none';
            if (newDraw) newDraw.style.display = 'block';
            if (optSavedLabel) {
                optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc';
            }
            if (optNewLabel) {
                optNewLabel.style.borderColor = '#bfdbfe'; optNewLabel.style.background = '#eff6ff';
            }
            if (hiddenInput) hiddenInput.value = '';
            setTimeout(function() {
                window.dispatchEvent(new Event('resize'));
            }, 50);
        }
    };

    // Initialize saved signature default choice
    const optSaved = document.getElementById('optSavedAsesor');
    if (optSaved && optSaved.checked) {
        const hiddenInput = document.getElementById('ttdAsesorFileInput');
        if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
    }

    const optSavedAsesi = document.getElementById('optSavedAsesi');
    if (optSavedAsesi && optSavedAsesi.checked) {
        const hiddenInput = document.getElementById('ttdAsesiFileInput');
        if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
    }
});
</script>
@endsection