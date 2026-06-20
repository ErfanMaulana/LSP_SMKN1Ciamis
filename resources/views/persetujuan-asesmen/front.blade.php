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
@endphp

@extends($layout)

@section('title', 'Detail Persetujuan Asesmen')
@section('page-title', 'Detail Persetujuan Asesmen')

@section('styles')
<style>
    .top-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .notice {
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 14px;
        font-size: 13px;
        border: 1px solid transparent;
    }

    .notice.success {
        background: #ecfdf5;
        color: #166534;
        border-color: #bbf7d0;
    }

    .notice.warning {
        background: #fffbeb;
        color: #92400e;
        border-color: #fde68a;
    }

    .hero-card {
        background: #0073bd;
        color: #ffffff;
        border-radius: 16px;
        padding: 22px 24px;
        margin-bottom: 18px;
    }

    .hero-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }

    .hero-card h2 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 800;
    }

    .hero-card p {
        margin: 0;
        font-size: 13px;
        line-height: 1.6;
        opacity: 0.92;
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 12px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 700;
    }

    .card,
    .panel-card,
    .signature-spot,
    .signature-info-card,
    .statement-card,
    .doc-wrap {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
    }

    .card-header,
    .panel-title {
        padding: 14px 16px;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .card-body,
    .panel-body {
        padding: 16px;
    }

    .stack {
        display: grid;
        gap: 16px;
    }

    .work-grid {
        display: grid;
        grid-template-columns: 1fr 0.82fr;
        gap: 16px;
        align-items: start;
    }

    .checklist-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 14px;
    }

    .checklist-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        color: #0f172a;
        font-size: 13px;
        line-height: 1.45;
    }

    .checklist-item input {
        margin-top: 2px;
        flex: 0 0 auto;
    }

    .checklist-note {
        margin-top: 12px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    .statement-list {
        display: grid;
        gap: 10px;
    }

    .statement-item {
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #334155;
        font-size: 13px;
        line-height: 1.6;
    }

    .statement-role {
        display: block;
        margin-bottom: 4px;
        font-weight: 700;
        color: #0f172a;
    }

    .statement-card {
        margin-top: 16px;
        padding: 18px;
    }

    .signature-form-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        align-items: stretch;
    }

    .signature-spot,
    .signature-info-card {
        padding: 18px;
    }

    .signature-title {
        margin: 0 0 12px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
    }

    .signature-canvas-wrapper {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        overflow: hidden;
        width: 100%;
        max-width: 260px;
        margin: 0 auto 12px auto;
        aspect-ratio: 1 / 1;
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
        display: grid;
        place-items: center;
        text-align: center;
        pointer-events: none;
        color: #cbd5e1;
        z-index: 1;
    }

    .signature-placeholder i {
        font-size: 38px;
        display: block;
        margin-bottom: 6px;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .signature-date {
        font-size: 13px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .field {
        margin-top: 12px;
    }

    .field label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .field input {
        width: 100%;
        height: 42px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        color: #0f172a;
        background: #ffffff;
    }

    .btn-submit,
    .btn-clear-signature {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-submit {
        border: none;
        background: #0073bd;
        color: #ffffff;
        height: 42px;
        padding: 0 16px;
        font-size: 14px;
        margin-top: 12px;
    }

    .btn-clear-signature {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
    }

    .signature-info-list {
        display: grid;
        gap: 10px;
    }

    .summary-panel-top {
        margin-bottom: 16px;
    }

    .signature-info-row {
        display: grid;
        grid-template-columns: 140px 12px 1fr;
        gap: 8px;
        align-items: start;
        font-size: 13px;
        color: #334155;
    }

    .signature-info-row .label {
        font-weight: 600;
        color: #0f172a;
    }

    .doc-wrap {
        padding: 18px;
        overflow-x: auto;
        margin-bottom: 16px;
    }

    .doc {
        min-width: 760px;
        border: 1px solid #111827;
        font-size: 13px;
        color: #111827;
        border-collapse: collapse;
        width: 100%;
    }

    .doc td {
        border: 1px solid #111827;
        padding: 6px;
        vertical-align: top;
    }

    .doc .title {
        border: none;
        padding: 0 0 10px;
        font-weight: 700;
        font-size: 14px;
    }

    .doc .no-border { border: none; }

    .check {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #111827;
        text-align: center;
        line-height: 10px;
        margin-right: 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .notes {
        margin-top: 8px;
        font-size: 12px;
        color: #334155;
    }

    .panel-card {
        margin-top: 16px;
        overflow: hidden;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .signature-box {
        text-align: center;
    }

    .signature-box img {
        width: 220px;
        height: 220px;
        object-fit: contain;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        display: block;
        margin: 0 auto 8px auto;
    }

    .signature-box .no-img-placeholder {
        width: 220px;
        height: 220px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px auto;
        font-size: 12px;
        color: #94a3b8;
    }

    .signature-box .meta {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.35;
    }

    .signature-preview {
        display: grid;
        gap: 12px;
    }

    .signature-spot {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .signature-spot > * {
        width: min(100%, 440px);
    }

    .signature-summary-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .summary-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .summary-card-title {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .summary-card-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    .summary-card-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        color: #334155;
    }

    .summary-card-table td {
        padding: 8px 0;
        vertical-align: top;
        border-bottom: 1px solid #eef2f7;
    }

    .summary-card-table td:first-child {
        width: 44%;
        font-weight: 700;
        color: #0f172a;
        padding-right: 10px;
    }

    .summary-card-table tr:last-child td {
        border-bottom: none;
    }

    .summary-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #0073bd;
        font-size: 12px;
        font-weight: 700;
    }

    .small-note {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    @media (max-width: 960px) {
        .work-grid,
        .signature-form-layout,
        .signature-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .checklist-grid {
            grid-template-columns: 1fr;
        }

        .signature-info-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .hero-card {
            padding: 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    @if($role === 'asesor')
        <a href="{{ route('asesor.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        @if(!empty($item->ttd_asesi_file))
            <a href="{{ route('asesor.persetujuan.front.asesor.export', ['asesiNik' => $asesiNik, 'skemaId' => $skema->id]) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-download"></i> Export FR.AK.01 (.doc)
            </a>
        @else
            <button class="btn btn-primary" disabled title="Asesi belum menandatangani, tidak dapat mengekspor"><i class="bi bi-download"></i> Export FR.AK.01 (.doc)</button>
        @endif
    @else
        <a href="{{ route('asesi.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    @endif
</div>

@if(session('success'))
    <div class="notice success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="notice warning">{{ session('error') }}</div>
@endif

@if($role === 'asesor')
    <div class="hero-card">
        <div class="hero-card-top">
            <div>
                <h2>Detail Persetujuan Asesmen</h2>
                <p>Lengkapi ceklis bukti, pastikan jadwal sudah benar, lalu simpan tanda tangan asesor.</p>
            </div>
            @if(!empty($item->ttd_asesi_file))
                <a href="{{ route('asesor.persetujuan.front.asesor.export', ['asesiNik' => $asesiNik, 'skemaId' => $skema->id]) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-download"></i> Export FR.AK.01 (.doc)
                </a>
            @else
                <button class="btn btn-primary" disabled title="Asesi belum menandatangani, tidak dapat mengekspor">
                    <i class="bi bi-download"></i> Export FR.AK.01 (.doc)
                </button>
            @endif
        </div>
        <div class="hero-meta">
            <span class="hero-chip"><i class="bi bi-person-badge"></i> {{ $item->nama_asesi }}</span>
            <span class="hero-chip"><i class="bi bi-collection"></i> {{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</span>
            <span class="hero-chip"><i class="bi bi-clock"></i> {{ $item->hari_tanggal ?: '-' }}</span>
        </div>
    </div>

    <div class="signature-info-card signature-summary-card summary-panel-top">
        <div class="summary-card-head">
            <div>
                <h3 class="summary-card-title">Informasi Persetujuan</h3>
                <p class="summary-card-subtitle">Data utama untuk pengecekan sebelum tanda tangan.</p>
            </div>
            <span class="summary-badge"><i class="bi bi-eye"></i> Preview Asesor</span>
        </div>

        <div class="summary-badges">
            <span class="summary-badge"><i class="bi bi-person-badge"></i> {{ $item->nama_asesi }}</span>
            <span class="summary-badge"><i class="bi bi-collection"></i> {{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</span>
        </div>

        <table class="summary-card-table">
            <tr>
                <td>Skema</td>
    
                <td>TUK</td>
                <td>{{ $item->tuk }}</td>
            </tr>
            <tr>
                <td>Asesor</td>
                <td>{{ $item->nama_asesor }}</td>
            </tr>
            <tr>
                <td>Asesi</td>
                <td>{{ $item->nama_asesi }}</td>
            </tr>
            <tr>
                <td>Hari / Tanggal</td>
                <td>{{ $item->hari_tanggal ?: '-' }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>{{ $item->waktu ?: '-' }}</td>
            </tr>
            <tr>
                <td>TUK Pelaksanaan</td>
                <td>{{ $item->tuk_pelaksanaan ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="card" style="margin-bottom:16px;">
        <div class="card-header"><i class="bi bi-clipboard-check"></i> Ceklis Bukti yang Sudah Dikumpulkan</div>
        <div class="card-body">
            @if(!$item->ttd_asesor_file)
            <form method="POST" action="{{ route('asesor.persetujuan.front.asesor.sign', $item->id) }}" id="formTandaTanganAsesor">
                @csrf
            @endif
            <div class="checklist-grid" style="margin-top:12px;">
                <label class="checklist-item"><input type="checkbox" name="bukti_verifikasi_portofolio" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_verifikasi_portofolio ? 'checked' : '' }}><span>Hasil Verifikasi Portofolio</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_reviu_produk" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_reviu_produk ? 'checked' : '' }}><span>Hasil Reviu Produk</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_observasi_langsung" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_observasi_langsung ? 'checked' : '' }}><span>Hasil Observasi Langsung</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_kegiatan_terstruktur" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_kegiatan_terstruktur ? 'checked' : '' }}><span>Hasil Kegiatan Terstruktur</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_pertanyaan_lisan" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_pertanyaan_lisan ? 'checked' : '' }}><span>Hasil Pertanyaan Lisan</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_pertanyaan_tertulis" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_pertanyaan_tertulis ? 'checked' : '' }}><span>Hasil Pertanyaan Tertulis</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_pertanyaan_wawancara" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_pertanyaan_wawancara ? 'checked' : '' }}><span>Hasil Pertanyaan Wawancara</span></label>
                <label class="checklist-item"><input type="checkbox" name="bukti_lainnya" value="1" {{ $item->ttd_asesor_file ? 'disabled' : '' }} {{ $item->bukti_lainnya ? 'checked' : '' }} id="buktiLainnyaCheckbox"><span>Lainnya {{ $item->bukti_lainnya_keterangan ? ': ' . $item->bukti_lainnya_keterangan : '' }}</span></label>
            </div>
            @if(!$item->ttd_asesor_file)
                <div id="buktiLainnyaKeteranganDiv" style="margin-top:12px; display:{{ $item->bukti_lainnya ? 'block' : 'none' }};">
                    <label style="display:block; margin-bottom:6px; font-size:13px; color:#475569; font-weight:600;">Keterangan Lainnya</label>
                    <input type="text" name="bukti_lainnya_keterangan" placeholder="Jelaskan bukti lainnya" value="{{ $item->bukti_lainnya_keterangan }}" style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; background:#fff;">
                </div>
            @endif
        </div>
    </div>

    <div class="statement-card">
        <div class="statement-list">
            <div class="statement-item"><span class="statement-role">Asesi</span>{{ $item->pernyataan_asesi_1 }}</div>
            <div class="statement-item"><span class="statement-role">Asesor</span>{{ $item->pernyataan_asesor }}</div>
            <div class="statement-item"><span class="statement-role">Asesi</span>{{ $item->pernyataan_asesi_2 }}</div>
        </div>
    </div>

    <div class="panel-card" style="margin-top:16px;">
        <div class="panel-title"><i class="bi bi-pen"></i> Tanda Tangan</div>
        <div class="panel-body">
            @if($item->ttd_asesor_file)
                @if(!empty($item->ttd_asesi_file))
                    {{-- Kedua pihak sudah menandatangani --}}
                    <div class="notice success" style="margin-bottom:20px;">
                        <i class="bi bi-check-circle-fill"></i>
                        Persetujuan asesmen telah ditandatangani oleh asesor dan asesi.
                    </div>
                    <div class="signature-grid">
                        {{-- TTD Asesor --}}
                        <div class="signature-box">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesor</p>
                            <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Tanda Tangan Asesor">
                            <p class="meta">
                                <strong>{{ $item->ttd_asesor_nama ?: $item->nama_asesor }}</strong><br>
                                {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: '-' }}
                            </p>
                        </div>
                        {{-- TTD Asesi --}}
                        <div class="signature-box">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesi</p>
                            <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Tanda Tangan Asesi">
                            <p class="meta">
                                <strong>{{ $item->ttd_asesi_nama }}</strong><br>
                                {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: '-' }}
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Hanya asesor yang sudah TTD, menunggu asesi --}}
                    <div class="notice warning" style="margin-bottom:20px;">
                        <i class="bi bi-hourglass-split"></i>
                        Tanda tangan asesor sudah tersimpan. Menunggu tanda tangan dari asesi.
                    </div>
                    <div class="signature-grid">
                        <div class="signature-box">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesor</p>
                            <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Tanda Tangan Asesor">
                            <p class="meta">
                                <strong>{{ $item->ttd_asesor_nama ?: $item->nama_asesor }}</strong><br>
                                {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: '-' }}
                            </p>
                        </div>
                        <div class="signature-box">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesi</p>
                            <div class="no-img-placeholder" style="color:#94a3b8;flex-direction:column;gap:8px;">
                                <i class="bi bi-hourglass-split" style="font-size:28px;"></i>
                                <span>Menunggu tanda tangan asesi</span>
                            </div>
                            <p class="meta" style="color:#94a3b8;">Belum ditandatangani</p>
                        </div>
                    </div>
                @endif
            @else
                {{-- Asesor belum TTD, tampilkan form canvas atau pilihan TTD tersimpan --}}
                <div class="signature-form-layout">
                    <div class="signature-spot">
                        <form method="POST" action="{{ route('asesor.persetujuan.front.asesor.sign', $item->id) }}" id="formTandaTanganAsesor">
                            @csrf
                            @if(isset($savedSignature) && $savedSignature)
                                {{-- Ada TTD tersimpan di profil: tampilkan pilihan --}}
                                <div id="sigChoiceWrapAsesor" style="margin-bottom:14px; text-align: left;">
                                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;margin-bottom:8px;" id="optSavedAsesorLabel">
                                        <input type="radio" name="sig_choice_asesor" value="saved" checked id="optSavedAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#10b981;">
                                        <div>
                                            <div style="font-size:13px;font-weight:600;color:#166534;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Gunakan tanda tangan tersimpan</div>
                                            <div style="font-size:12px;color:#64748b;">Menggunakan TTD yang sudah disimpan di profil Anda</div>
                                        </div>
                                    </label>
                                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;background:#f8fafc;" id="optNewAsesorLabel">
                                        <input type="radio" name="sig_choice_asesor" value="new" id="optNewAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#0073bd;">
                                        <div>
                                            <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda tangan baru</div>
                                            <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk persetujuan ini</div>
                                        </div>
                                    </label>
                                </div>

                                {{-- Preview TTD tersimpan --}}
                                <div id="savedAsesorSigPreview" style="margin-bottom: 12px; text-align: center;">
                                    <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;margin-bottom:8px;">
                                        <img src="{{ $savedSignature }}" alt="TTD Tersimpan" style="max-width:260px;height:auto;display:block;">
                                    </div>
                                    <div style="font-size:11px;color:#94a3b8;">Tanda tangan tersimpan dari profil Anda</div>
                                </div>

                                {{-- Canvas tanda tangan baru (tersembunyi) --}}
                                <div id="newAsesorSigDraw" style="display:none;">
                                    <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                                        <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                                        <div class="signature-placeholder">
                                            <i class="bi bi-pen"></i>
                                            <span>Tanda tangan di sini</span>
                                        </div>
                                    </div>
                                    <div class="signature-actions">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <input type="checkbox" name="simpan_tanda_tangan" value="1" id="saveAsesorSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                                            <label for="saveAsesorSigCheck" style="font-size:12px;color:#475569;cursor:pointer;margin:0;">Simpan sebagai tanda tangan saya</label>
                                        </div>
                                        <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                                            <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- Langsung canvas --}}
                                <div class="signature-title">Gambar tanda tangan di bawah</div>
                                <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                                    <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                                    <div class="signature-placeholder">
                                        <i class="bi bi-pen"></i>
                                        <span>Tanda tangan di sini</span>
                                    </div>
                                </div>
                                <div class="signature-actions">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <input type="checkbox" name="simpan_tanda_tangan" value="1" id="saveAsesorSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                                        <label for="saveAsesorSigCheck" style="font-size:12px;color:#475569;cursor:pointer;margin:0;">Simpan sebagai tanda tangan saya</label>
                                    </div>
                                    <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                                    </button>
                                </div>
                            @endif

                            <div class="signature-actions" style="margin-top:12px;">
                                <div class="signature-date">
                                    <i class="bi bi-calendar3"></i>
                                    Tanggal: <strong id="signatureDateAsesor">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                                </div>
                            </div>
                            <input type="hidden" name="ttd_asesor_nama" value="{{ $item->ttd_asesor_nama ?: $item->nama_asesor }}">
                            <input type="hidden" name="ttd_asesor_tanggal" value="{{ $item->ttd_asesor_tanggal?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                            <input type="hidden" name="ttd_asesor_file" id="ttdAsesorFileInput">
                            <button class="btn-submit" type="submit"><i class="bi bi-check2-circle"></i> Simpan Tanda Tangan Asesor</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

@else
    <div class="doc-wrap">
        <table class="doc">
            <tr>
                <td class="title no-border" colspan="4">{{ $item->kode_form }} &nbsp;&nbsp; {{ $item->judul_form }}</td>
            </tr>
            <tr>
                <td colspan="4">{{ $item->pengantar }}</td>
            </tr>
            <tr>
                <td style="width:30%; vertical-align:middle; text-align:left;" rowspan="2">Skema Sertifikasi<br>{{ $item->kategori_skema }}</td>
                <td style="width:12%; border-right:none;">Judul</td>
                <td style="width:2%; border-left:none;">:</td>
                <td>{{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">Nomor</td>
                <td style="border-left:none;">:</td>
                <td>{{ $item->nomor_skema ?: ($skema->nomor_skema ?? '-') }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">TUK</td>
                <td colspan="2" style="text-align:right; border-left:none;">:</td>
                <td>{{ $item->tuk }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">Nama Asesor</td>
                <td colspan="2" style="text-align:right; border-left:none;">:</td>
                <td>{{ $item->nama_asesor }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">Nama Asesi</td>
                <td colspan="2" style="text-align:right; border-left:none;">:</td>
                <td>{{ $item->nama_asesi }}</td>
            </tr>
            <tr>
                <td style="vertical-align:middle;">Bukti yang akan dikumpulkan :</td>
                <td colspan="3" style="padding:8px 10px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;">
                        <div><span class="check">{{ $item->bukti_verifikasi_portofolio ? 'V' : '' }}</span>Hasil Verifikasi Portofolio</div>
                        <div><span class="check">{{ $item->bukti_reviu_produk ? 'V' : '' }}</span>Hasil Reviu Produk</div>
                        <div><span class="check">{{ $item->bukti_observasi_langsung ? 'V' : '' }}</span>Hasil Observasi Langsung</div>
                        <div><span class="check">{{ $item->bukti_kegiatan_terstruktur ? 'V' : '' }}</span>Hasil Kegiatan Terstruktur</div>
                        <div><span class="check">{{ $item->bukti_pertanyaan_lisan ? 'V' : '' }}</span>Hasil Pertanyaan Lisan</div>
                        <div><span class="check">{{ $item->bukti_pertanyaan_tertulis ? 'V' : '' }}</span>Hasil Pertanyaan Tertulis</div>
                        <div><span class="check">{{ $item->bukti_lainnya ? 'V' : '' }}</span>Lainnya {{ $item->bukti_lainnya_keterangan ?: '......' }}</div>
                        <div><span class="check">{{ $item->bukti_pertanyaan_wawancara ? 'V' : '' }}</span>Hasil Pertanyaan Wawancara</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="3">Pelaksanaan asesmen disepakati pada:</td>
                <td style="border-right:none;">Hari / Tanggal</td>
                <td style="border-left:none;">:</td>
                <td>{{ $item->hari_tanggal }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">Waktu</td>
                <td style="border-left:none;">:</td>
                <td>{{ $item->waktu }}</td>
            </tr>
            <tr>
                <td style="border-right:none;">TUK</td>
                <td style="border-left:none;">:</td>
                <td>{{ $item->tuk_pelaksanaan }}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Asesi:</strong><br>{{ $item->pernyataan_asesi_1 }}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Asesor:</strong><br>{{ $item->pernyataan_asesor }}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Asesi:</strong><br>{{ $item->pernyataan_asesi_2 }}</td>
            </tr>
            <tr>
                <td colspan="2">Tanda tangan Asesor : {{ $item->ttd_asesor_nama ?: '............................' }}</td>
                <td colspan="2">Tanggal : {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: '............................' }}</td>
            </tr>
            <tr>
                <td colspan="2">Tanda tangan Asesi : {{ $item->ttd_asesi_nama ?: '............................' }}</td>
                <td colspan="2">Tanggal : {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: '............................' }}</td>
            </tr>
        </table>

        @if($item->catatan_footer)
            <div class="notes"><em>{{ $item->catatan_footer }}</em></div>
        @endif
    </div>

    <div class="panel-card">
        <div class="panel-title"><i class="bi bi-pen"></i> Tanda Tangan</div>
        <div class="panel-body">
            @if(!empty($item->ttd_asesi_file))
                {{-- Kedua pihak sudah menandatangani – tampilkan hasil tanda tangan --}}
                <div class="notice success" style="margin-bottom:20px;">
                    <i class="bi bi-check-circle-fill"></i>
                    Persetujuan asesmen telah ditandatangani oleh asesor dan asesi.
                </div>
                <div class="signature-grid">
                    {{-- Tanda Tangan Asesor --}}
                    <div class="signature-box">
                        <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesor</p>
                        @if(!empty($item->ttd_asesor_file))
                            <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Tanda Tangan Asesor">
                        @else
                            <div class="no-img-placeholder">Tidak ada gambar</div>
                        @endif
                        <p class="meta">
                            <strong>{{ $item->ttd_asesor_nama ?: $item->nama_asesor }}</strong><br>
                            {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: '-' }}
                        </p>
                    </div>
                    {{-- Tanda Tangan Asesi --}}
                    <div class="signature-box">
                        <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0 0 10px;">Tanda Tangan Asesi</p>
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Tanda Tangan Asesi">
                        <p class="meta">
                            <strong>{{ $item->ttd_asesi_nama }}</strong><br>
                            {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: '-' }}
                        </p>
                    </div>
                </div>
            @elseif(empty($item->ttd_asesor_file) && (empty($item->ttd_asesor_nama) || empty($item->ttd_asesor_tanggal)))
                {{-- Asesor belum tanda tangan --}}
                <div class="notice warning">Asesor belum menandatangani form ini. Tanda tangan asesi belum dapat dilakukan.</div>
            @else
                {{-- Asesor sudah tanda tangan, tampilkan form tanda tangan asesi --}}
                <form method="POST" action="{{ route('asesi.persetujuan.front.asesi.sign', $item->id) }}" id="formTandaTanganAsesi">
                    @csrf
                    <div class="signature-spot" style="max-width:560px; margin:0 auto;">
                        <div class="signature-title">Tanda Tangan Asesi</div>
                        <div class="signature-canvas-wrapper" id="signatureWrapperAsesi">
                            <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                            <div class="signature-placeholder">
                                <i class="bi bi-pen"></i>
                                <span>Tanda tangan di sini</span>
                            </div>
                        </div>
                        <div class="signature-actions">
                            <div class="signature-date">
                                <i class="bi bi-calendar3"></i>
                                Tanggal: <strong id="signatureDateAsesi">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                            </div>
                            <button type="button" class="btn-clear-signature" id="clearSignatureAsesi">
                                <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                            </button>
                        </div>
                        <div class="field">
                            <label>Tanggal Tanda Tangan</label>
                            <input type="date" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ old('ttd_asesi_tanggal', $item->ttd_asesi_tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                            @error('ttd_asesi_tanggal')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput">
                        <button class="btn-submit" type="submit"><i class="bi bi-check2-circle"></i> Simpan Tanda Tangan Asesi</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function initSignatureCanvas(config) {
        const canvas = document.getElementById(config.canvasId);
        if (!canvas) return;

        const clearBtn = document.getElementById(config.clearBtnId);
        const hiddenInput = document.getElementById(config.hiddenInputId);
        const dateInput = config.dateInputId ? document.getElementById(config.dateInputId) : null;
        const form = document.getElementById(config.formId);
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let lastX = 0;
        let lastY = 0;
        const wrapper = config.wrapperId ? document.getElementById(config.wrapperId) : canvas.parentElement;
        const placeholder = wrapper ? wrapper.querySelector('.signature-placeholder') : null;
        let hasSignature = false;

        const resize = () => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(ratio, ratio);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 2;
        };

        const pos = (event) => {
            const rect = canvas.getBoundingClientRect();
            const point = event.touches && event.touches[0] ? event.touches[0] : event;
            return { x: point.clientX - rect.left, y: point.clientY - rect.top };
        };

        const start = (event) => { event.preventDefault(); drawing = true; const p = pos(event); lastX = p.x; lastY = p.y; };
        const move = (event) => {
            event.preventDefault();
            if (!drawing) return;
            const p = pos(event);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            lastX = p.x;
            lastY = p.y;

            if (!hasSignature) {
                hasSignature = true;
                if (wrapper) wrapper.classList.add('has-signature');
                if (placeholder) placeholder.style.display = 'none';
            }
        };
        const stop = () => { drawing = false; };

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                if (hiddenInput) hiddenInput.value = '';
                if (dateInput) dateInput.value = '';
                if (wrapper) wrapper.classList.remove('has-signature');
                if (placeholder) placeholder.style.display = '';
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                if (config.canvasId === 'signatureCanvasAsesor') {
                    const optSaved = document.getElementById('optSavedAsesor');
                    if (optSaved && optSaved.checked) {
                        return;
                    }
                }

                if (hiddenInput) {
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

        // If hidden input already has a signature (e.g., re-render), show/hide placeholder
        if (hiddenInput && hiddenInput.value) {
            hasSignature = true;
            if (wrapper) wrapper.classList.add('has-signature');
            if (placeholder) placeholder.style.display = 'none';
        }
    }

    initSignatureCanvas({
        canvasId: 'signatureCanvasAsesor',
        clearBtnId: 'clearSignatureAsesor',
        hiddenInputId: 'ttdAsesorFileInput',
        wrapperId: 'signatureWrapperAsesor',
        dateInputId: null,
        formId: 'formTandaTanganAsesor',
    });

    initSignatureCanvas({
        canvasId: 'signatureCanvasAsesi',
        clearBtnId: 'clearSignatureAsesi',
        hiddenInputId: 'ttdAsesiFileInput',
        wrapperId: 'signatureWrapperAsesi',
        dateInputId: 'ttdAsesiTanggalInput',
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
                optSavedLabel.style.borderColor = '#d1fae5'; optSavedLabel.style.background = '#f0fdf4';
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
        }
    };

    // Initialize saved signature default choice
    const optSaved = document.getElementById('optSavedAsesor');
    if (optSaved && optSaved.checked) {
        const hiddenInput = document.getElementById('ttdAsesorFileInput');
        if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
    }
});
</script>
@endsection