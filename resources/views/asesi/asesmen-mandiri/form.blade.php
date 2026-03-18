@extends('asesi.layout')

@section('title', 'Asesmen Mandiri - ' . $skema->nama_skema)
@section('page-title', 'Form Asesmen Mandiri')

@section('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #16a34a;
    }

    .skema-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .skema-header-top {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skema-header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .skema-header-info h2 {
        font-size: 18px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .skema-header-info .skema-number {
        font-size: 13px;
        color: #64748b;
        font-family: monospace;
    }

    .skema-header-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .meta-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
    }

    .meta-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    .instructions-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .instructions-box h3 {
        font-size: 15px;
        color: #166534;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instructions-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .instructions-box li {
        font-size: 13px;
        color: #15803d;
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .instructions-box li:last-child {
        margin-bottom: 0;
    }

    .unit-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .unit-header {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        padding: 20px 24px;
        color: white;
    }

    .unit-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .unit-number {
        background: rgba(255,255,255,0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .unit-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .unit-meta {
        display: flex;
        gap: 24px;
        font-size: 13px;
        opacity: 0.9;
    }

    .unit-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .unit-question {
        background: #fef3c7;
        padding: 14px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        border-bottom: 1px solid #e5e7eb;
    }

    .unit-body {
        padding: 0;
    }

    .elemen-item {
        border-bottom: 1px solid #f1f5f9;
    }

    .elemen-item:last-child {
        border-bottom: none;
    }

    .elemen-header {
        background: #f8fafc;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .elemen-info {
        flex: 1;
    }

    .elemen-number {
        font-size: 12px;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 4px;
    }

    .elemen-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
    }

    .elemen-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 200px;
    }

    .radio-group {
        display: flex;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #16a34a;
    }

    .radio-option.kompeten span {
        font-size: 13px;
        font-weight: 600;
        color: #16a34a;
    }

    .radio-option.belum span {
        font-size: 13px;
        font-weight: 600;
        color: #dc2626;
    }

    .kriteria-list {
        padding: 16px 24px 16px 48px;
    }

    .kriteria-title {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kriteria-item {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .kriteria-item:last-child {
        margin-bottom: 0;
    }

    .kriteria-item .num {
        color: #94a3b8;
        font-weight: 500;
        flex-shrink: 0;
    }

    .bukti-section {
        padding: 0 24px 20px 24px;
    }

    .bukti-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .bukti-input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
        transition: all 0.2s;
    }

    .bukti-input:focus {
        outline: none;
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .bukti-input::placeholder {
        color: #9ca3af;
    }

    .form-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        position: sticky;
        bottom: 20px;
        margin-top: 24px;
    }

    .progress-info {
        font-size: 14px;
        color: #64748b;
    }

    .progress-info strong {
        color: #16a34a;
    }

    .btn-group {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-save {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-save:hover {
        background: #e2e8f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
    }

    /* Rekomendasi Banner */
    .rekomendasi-banner {
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        border: 2px solid;
    }

    .rekomendasi-banner.lanjut {
        background: #f0fdf4;
        border-color: #22c55e;
    }

    .rekomendasi-banner.tidak_lanjut {
        background: #fff1f2;
        border-color: #f43f5e;
    }

    .rekomendasi-banner .banner-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .rekomendasi-banner.lanjut .banner-icon {
        background: #dcfce7;
        color: #16a34a;
    }

    .rekomendasi-banner.tidak_lanjut .banner-icon {
        background: #ffe4e6;
        color: #e11d48;
    }

    .rekomendasi-banner .banner-body { flex: 1; }

    .rekomendasi-banner .banner-title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .rekomendasi-banner.lanjut .banner-title { color: #15803d; }
    .rekomendasi-banner.tidak_lanjut .banner-title { color: #be123c; }

    .rekomendasi-banner .banner-meta {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .rekomendasi-banner .banner-catatan {
        font-size: 13px;
        background: rgba(255,255,255,0.7);
        border-radius: 8px;
        padding: 10px 14px;
        color: #374151;
        font-style: italic;
        border-left: 3px solid;
    }

    .rekomendasi-banner.lanjut .banner-catatan { border-color: #22c55e; }
    .rekomendasi-banner.tidak_lanjut .banner-catatan { border-color: #f43f5e; }

    .rekomendasi-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .rekomendasi-badge-pill.lanjut { background: #dcfce7; color: #15803d; }
    .rekomendasi-badge-pill.tidak_lanjut { background: #ffe4e6; color: #be123c; }

    /* Signature Pad */
    .signature-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-top: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .signature-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .signature-section .signature-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
    }

    .signature-canvas-wrapper.active {
        border-color: #16a34a;
        background: #fff;
    }

    .signature-canvas-wrapper.has-signature {
        border-style: solid;
        border-color: #16a34a;
    }

    .signature-canvas {
        width: 100%;
        height: 200px;
        cursor: crosshair;
        display: block;
    }

    .signature-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
        color: #9ca3af;
        transition: opacity 0.2s;
    }

    .signature-placeholder i {
        font-size: 28px;
        display: block;
        margin-bottom: 6px;
    }

    .signature-placeholder span {
        font-size: 13px;
    }

    .signature-canvas-wrapper.has-signature .signature-placeholder {
        opacity: 0;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
    }

    .signature-date {
        font-size: 13px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .signature-date strong {
        color: #1e293b;
    }

    .btn-clear-signature {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-clear-signature:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .signature-saved-display {
        text-align: center;
        padding: 16px;
    }

    .signature-saved-display img {
        max-width: 400px;
        width: 100%;
        height: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
    }

    .signature-saved-meta {
        margin-top: 10px;
        font-size: 13px;
        color: #16a34a;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .signature-error {
        color: #dc2626;
        font-size: 13px;
        margin-top: 8px;
        display: none;
        align-items: center;
        gap: 6px;
    }

    @media (max-width: 768px) {
        .elemen-header {
            flex-direction: column;
        }

        .elemen-controls {
            width: 100%;
            min-width: auto;
        }

        .form-actions {
            flex-direction: column;
            position: relative;
            bottom: auto;
        }

        .btn-group {
            width: 100%;
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }

        .signature-canvas {
            height: 160px;
        }

        .signature-actions {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endsection

@section('content')
<a href="{{ route('asesi.asesmen-mandiri.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Skema
</a>

<!-- Skema Header -->
<div class="skema-header">
    <div class="skema-header-top">
        <div class="skema-header-icon">
            <i class="bi bi-patch-check"></i>
        </div>
        <div class="skema-header-info">
            <h2>{{ $skema->nama_skema }}</h2>
            <div class="skema-number">{{ $skema->nomor_skema }}</div>
        </div>
    </div>
    <div class="skema-header-meta">
        <div class="meta-item">
            <span class="meta-label">Jenis Skema</span>
            <span class="meta-value">{{ $skema->jenis_skema ?? 'KKNI/Okupasi/Klaster' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Jumlah Unit</span>
            <span class="meta-value">{{ $skema->units->count() }} Unit Kompetensi</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total Elemen</span>
            <span class="meta-value">{{ $skema->units->sum(fn($u) => $u->elemens->count()) }} Elemen</span>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="instructions-box">
    <h3><i class="bi bi-info-circle"></i> Panduan Asesmen Mandiri</h3>
    <ul>
        <li>Baca setiap pertanyaan/elemen di kolom sebelah kiri dengan teliti.</li>
        <li>Pilih <strong>K (Kompeten)</strong> jika Anda yakin dapat melakukan tugas yang dijelaskan.</li>
        <li>Pilih <strong>BK (Belum Kompeten)</strong> jika Anda merasa belum menguasai kompetensi tersebut.</li>
        <li>Isi kolom <strong>Bukti yang Relevan</strong> dengan menuliskan bukti yang Anda miliki untuk menunjukkan bahwa Anda melakukan pekerjaan tersebut.</li>
        <li>Anda dapat menyimpan jawaban sementara dan melanjutkan di lain waktu.</li>
    </ul>
</div>

@if($pivot && $pivot->rekomendasi)
@php
    $rekLabel = $pivot->rekomendasi === 'lanjut' ? 'Asesmen Dapat Dilanjutkan' : 'Asesmen Tidak Dapat Dilanjutkan';
    $rekIcon  = $pivot->rekomendasi === 'lanjut' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
    $rekClass = $pivot->rekomendasi;
@endphp
<div class="rekomendasi-banner {{ $rekClass }}">
    <div class="banner-icon">
        <i class="bi {{ $rekIcon }}"></i>
    </div>
    <div class="banner-body">
        <div class="banner-title">
            Rekomendasi Asesor:
            <span class="rekomendasi-badge-pill {{ $rekClass }}">
                <i class="bi {{ $rekIcon }}"></i>
                {{ $rekLabel }}
            </span>
        </div>
        <div class="banner-meta">
            Ditinjau oleh: <strong>{{ $asesorReviewer->nama ?? ($pivot->reviewed_by ?? 'Asesor') }}</strong>
            @if($pivot->reviewed_at)
                &nbsp;&bull;&nbsp; {{ \Carbon\Carbon::parse($pivot->reviewed_at)->translatedFormat('d F Y, H:i') }} WIB
            @endif
        </div>
        @if($pivot->catatan_asesor)
        <div class="banner-catatan">
            <i class="bi bi-chat-quote"></i> {{ $pivot->catatan_asesor }}
        </div>
        @endif
    </div>
</div>
@endif

<form action="{{ route('asesi.asesmen-mandiri.store', $skema->id) }}" method="POST" id="asesmenForm">
    @csrf

    @foreach($skema->units as $unitIndex => $unit)
    <div class="unit-card">
        <div class="unit-header">
            <div class="unit-title-row">
                <span class="unit-number">Unit Kompetensi {{ $unitIndex + 1 }}</span>
            </div>
            <h3>{{ $unit->judul_unit }}</h3>
            <div class="unit-meta">
                <span><i class="bi bi-tag"></i> {{ $unit->kode_unit }}</span>
                <span><i class="bi bi-layers"></i> {{ $unit->elemens->count() }} Elemen</span>
            </div>
        </div>

        @if($unit->pertanyaan_unit)
        <div class="unit-question">
            <i class="bi bi-question-circle"></i> {{ $unit->pertanyaan_unit }}
        </div>
        @endif

        <div class="unit-body">
            @foreach($unit->elemens as $elemenIndex => $elemen)
            @php
                $existingAnswer = $existingAnswers->get($elemen->id);
            @endphp
            <div class="elemen-item">
                <div class="elemen-header">
                    <div class="elemen-info">
                        <div class="elemen-number">Elemen {{ $elemenIndex + 1 }}</div>
                        <div class="elemen-title">{{ $elemen->nama_elemen }}</div>
                    </div>
                    <div class="elemen-controls">
                        <div class="radio-group">
                            <label class="radio-option kompeten">
                                <input type="radio" 
                                       name="jawaban[{{ $elemen->id }}][status]" 
                                       value="K" 
                                       {{ ($existingAnswer && $existingAnswer->status === 'K') ? 'checked' : '' }}
                                       {{ ($pivot && ($pivot->status === 'selesai' || $pivot->rekomendasi)) ? 'disabled' : '' }}
                                       required>
                                <span>K (Kompeten)</span>
                            </label>
                            <label class="radio-option belum">
                                <input type="radio" 
                                       name="jawaban[{{ $elemen->id }}][status]" 
                                       value="BK"
                                       {{ ($existingAnswer && $existingAnswer->status === 'BK') ? 'checked' : '' }}
                                       {{ ($pivot && ($pivot->status === 'selesai' || $pivot->rekomendasi)) ? 'disabled' : '' }}>
                                <span>BK (Belum Kompeten)</span>
                            </label>
                        </div>
                    </div>
                </div>

                @if($elemen->kriteria->count() > 0)
                <div class="kriteria-list">
                    <div class="kriteria-title">Kriteria Unjuk Kerja:</div>
                    @foreach($elemen->kriteria->sortBy('urutan') as $kriteria)
                    <div class="kriteria-item">
                        <span class="num">{{ $elemenIndex + 1 }}.{{ $kriteria->urutan ?? $loop->iteration }}</span>
                        <span>{{ $kriteria->deskripsi_kriteria }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="bukti-section">
                    <label class="bukti-label">Bukti yang Relevan</label>
                    <textarea name="jawaban[{{ $elemen->id }}][bukti]" 
                              class="bukti-input" 
                              {{ ($pivot && ($pivot->status === 'selesai' || $pivot->rekomendasi)) ? 'readonly' : '' }}
                              placeholder="Tuliskan bukti yang menunjukkan bahwa Anda dapat melakukan kompetensi ini (contoh: sertifikat, pengalaman kerja, portofolio, dll)">{{ $existingAnswer->bukti ?? '' }}</textarea>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <!-- Tanda Tangan Asesi -->
    <div class="signature-section">
        <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
        <p class="signature-subtitle">Dengan menandatangani, saya menyatakan bahwa semua jawaban di atas adalah benar dan sesuai dengan kompetensi yang saya miliki.</p>

        @if($pivot && $pivot->tanda_tangan)
        {{-- Signature sudah tersimpan --}}
        <div class="signature-saved-display">
            <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi">
            <div class="signature-saved-meta">
                <i class="bi bi-check-circle-fill"></i>
                Ditandatangani pada: {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan)->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>
        @elseif(!($pivot && ($pivot->status === 'selesai' || $pivot->rekomendasi)))
        {{-- Form tanda tangan --}}
        <div class="signature-canvas-wrapper" id="signatureWrapper">
            <canvas class="signature-canvas" id="signatureCanvas"></canvas>
            <div class="signature-placeholder" id="signaturePlaceholder">
                <i class="bi bi-pen"></i>
                <span>Tanda tangan di sini</span>
            </div>
        </div>
        <input type="hidden" name="tanda_tangan" id="tandaTanganInput">
        <div class="signature-error" id="signatureError">
            <i class="bi bi-exclamation-circle"></i>
            <span>Tanda tangan wajib diisi sebelum menyelesaikan asesmen.</span>
        </div>
        <div class="signature-actions">
            <div class="signature-date">
                <i class="bi bi-calendar3"></i>
                Tanggal: <strong id="signatureDate">{{ now()->translatedFormat('d F Y') }}</strong>
            </div>
            <button type="button" class="btn-clear-signature" id="clearSignature">
                <i class="bi bi-eraser"></i> Hapus Tanda Tangan
            </button>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════
         FR.APL.02 — Rekomendasi Asesor (Read-Only untuk Asesi)
    ════════════════════════════════════════════════════════ --}}
    @if($pivot && $pivot->rekomendasi)
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);border:1px solid #e2e8f0;margin-top:20px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);padding:16px 20px;display:flex;align-items:center;gap:12px;">
            <div style="width:32px;height:32px;background:rgba(255,255,255,0.2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-patch-check-fill" style="font-size:16px;color:white;"></i>
            </div>
            <div>
                <h3 style="color:white;margin:0;font-size:15px;font-weight:700;">Rekomendasi Asesor <span style="font-size:12px;font-weight:400;opacity:0.8;">(FR.APL.02)</span></h3>
                <small style="color:rgba(255,255,255,0.7);">Hasil tinjauan asesor terhadap asesmen mandiri Anda</small>
            </div>
        </div>

        <div style="padding:22px 24px;">
            {{-- Tabel Rekomendasi --}}
            <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
                <tr>
                    <td style="width:42%;border:1px solid #d1d5db;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                        <strong>Rekomendasi Untuk Asesi:</strong>
                        <div style="font-size:12px;color:#64748b;margin-top:4px;">Asesmen dapat / tidak dapat dilanjutkan</div>
                    </td>
                    <td style="border:1px solid #d1d5db;padding:14px 20px;">
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;border:2px solid {{ $pivot->rekomendasi === 'lanjut' ? '#059669' : '#dc2626' }};background:{{ $pivot->rekomendasi === 'lanjut' ? '#f0fdf4' : '#fff1f2' }};">
                            <span style="font-weight:600;color:{{ $pivot->rekomendasi === 'lanjut' ? '#065f46' : '#991b1b' }};">
                                {{ $pivot->rekomendasi === 'lanjut' ? '✓' : '✗' }} &nbsp;Asesmen <u>{{ $pivot->rekomendasi === 'lanjut' ? 'dapat' : 'tidak dapat' }}</u> dilanjutkan
                            </span>
                        </div>
                    </td>
                </tr>
                @if($pivot->catatan_asesor)
                <tr>
                    <td style="border:1px solid #d1d5db;border-top:none;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                        <strong>Catatan Asesor:</strong>
                    </td>
                    <td style="border:1px solid #d1d5db;border-top:none;padding:12px 14px;">
                        <div style="font-size:13px;color:#374151;line-height:1.6;">{{ $pivot->catatan_asesor }}</div>
                    </td>
                </tr>
                @endif
            </table>

            {{-- Tabel Tanda Tangan --}}
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <tr>
                    {{-- KOLOM ASESI --}}
                    <td style="width:50%;border:1px solid #d1d5db;padding:0;vertical-align:top;">
                        <table style="width:100%;border-collapse:collapse;">
                            <tr>
                                <td colspan="2" style="padding:9px 14px;background:#f8fafc;font-weight:700;border-bottom:1px solid #d1d5db;">Asesi :</td>
                            </tr>
                            <tr>
                                <td style="padding:9px 14px;width:42%;border-bottom:1px solid #eff0f1;color:#64748b;">Nama</td>
                                <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;">{{ $asesi->nama }}</td>
                            </tr>
                            <tr>
                                <td style="padding:9px 14px;color:#64748b;vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                                <td style="padding:9px 14px;">
                                    @if($pivot->tanda_tangan)
                                        <img src="{{ $pivot->tanda_tangan }}" alt="Tanda tangan asesi"
                                             style="max-width:180px;max-height:70px;border:1px solid #e2e8f0;border-radius:4px;">
                                        @if($pivot->tanggal_tanda_tangan)
                                        <div style="font-size:11px;color:#64748b;margin-top:4px;">
                                            {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                                        </div>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8;font-style:italic;">Belum tanda tangan</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>

                    {{-- KOLOM ASESOR --}}
                    <td style="width:50%;border:1px solid #d1d5db;border-left:none;padding:0;vertical-align:top;">
                        <table style="width:100%;border-collapse:collapse;">
                            <tr>
                                <td colspan="2" style="padding:9px 14px;background:#f8fafc;font-weight:700;border-bottom:1px solid #d1d5db;">Ditinjau Oleh Asesor :</td>
                            </tr>
                            <tr>
                                <td style="padding:9px 14px;width:38%;border-bottom:1px solid #eff0f1;color:#64748b;">Nama :</td>
                                <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;">{{ $asesorReviewer->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;color:#64748b;">No. Reg:</td>
                                <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;font-family:monospace;">{{ $pivot->reviewed_by ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:9px 14px;color:#64748b;vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                                <td style="padding:9px 14px;">
                                    @if($pivot->tanda_tangan_asesor)
                                        <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda tangan asesor"
                                             style="max-width:180px;max-height:70px;border:1px solid #e2e8f0;border-radius:4px;">
                                        @if($pivot->tanggal_tanda_tangan_asesor)
                                        <div style="font-size:11px;color:#64748b;margin-top:4px;">
                                            {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan_asesor)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                                        </div>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8;font-style:italic;">Belum tanda tangan</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    <div class="form-actions">
        <div class="progress-info">
            <i class="bi bi-info-circle"></i>
            Total: <strong>{{ $skema->units->sum(fn($u) => $u->elemens->count()) }} elemen</strong> dari {{ $skema->units->count() }} unit kompetensi
        </div>
        <div class="btn-group">
            @if($pivot && $pivot->rekomendasi)
            <span style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-lock-fill" style="color:#94a3b8;"></i> Asesmen telah direkomendasikan asesor — tidak dapat diubah
            </span>
            @elseif($pivot && $pivot->status === 'selesai')
            <div style="display:flex;align-items:center;gap:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 20px;">
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-hourglass-split" style="font-size:18px;color:#065f46;"></i>
                </div>
                <div>
                    <div style="font-size:13.5px;font-weight:700;color:#065f46;">Asesmen Selesai — Menunggu Rekomendasi Asesor</div>
                    <div style="font-size:12.5px;color:#166534;margin-top:2px;">Jawaban Anda telah tersimpan. Mohon tunggu hingga asesor memberikan rekomendasi untuk melanjutkan proses sertifikasi.</div>
                </div>
            </div>
            @else
            <button type="submit" name="save_draft" class="btn btn-save">
                <i class="bi bi-save"></i> Simpan Sementara
            </button>
            <button type="submit" name="submit_final" class="btn btn-submit">
                <i class="bi bi-check-circle"></i> Selesaikan Asesmen
            </button>
            @endif
        </div>
    </div>
</form>

<div id="finalConfirmModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:1200;align-items:center;justify-content:center;padding:16px;">
    <div style="width:100%;max-width:480px;background:#ffffff;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.2);padding:22px;">
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#e0f2fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-exclamation-circle" style="color:#0369a1;font-size:18px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:16px;font-weight:700;color:#0f172a;">Selesaikan Asesmen?</div>
                <div style="font-size:13px;color:#475569;line-height:1.6;margin-top:6px;">
                    Pastikan semua jawaban sudah benar. Setelah dikirim, asesmen akan masuk proses review asesor.
                </div>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
            <button type="button" id="finalConfirmCancel" style="padding:9px 16px;background:#f1f5f9;color:#475569;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
            <button type="button" id="finalConfirmOk" style="padding:9px 16px;background:#16a34a;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Ya, Selesaikan</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-save functionality
    let saveTimeout;
    const form = document.getElementById('asesmenForm');
    
    form.addEventListener('change', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function() {
            // Visual feedback
            const saveBtn = document.querySelector('.btn-save');
            if (saveBtn) saveBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyimpan...';
        }, 2000);
    });

    // Signature Pad
    (function() {
        const canvas = document.getElementById('signatureCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const wrapper = document.getElementById('signatureWrapper');
        const placeholder = document.getElementById('signaturePlaceholder');
        const hiddenInput = document.getElementById('tandaTanganInput');
        const clearBtn = document.getElementById('clearSignature');
        const errorEl = document.getElementById('signatureError');

        let isDrawing = false;
        let hasSignature = false;
        let lastX = 0;
        let lastY = 0;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#1e293b';
        }

        resizeCanvas();
        window.addEventListener('resize', function() {
            const imageData = canvas.toDataURL();
            resizeCanvas();
            if (hasSignature) {
                const img = new Image();
                img.onload = function() {
                    ctx.drawImage(img, 0, 0, canvas.getBoundingClientRect().width, canvas.getBoundingClientRect().height);
                };
                img.src = imageData;
            }
        });

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches ? e.touches[0] : e;
            return {
                x: touch.clientX - rect.left,
                y: touch.clientY - rect.top
            };
        }

        function startDrawing(e) {
            e.preventDefault();
            isDrawing = true;
            const pos = getPos(e);
            lastX = pos.x;
            lastY = pos.y;
            wrapper.classList.add('active');
        }

        function draw(e) {
            e.preventDefault();
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
            if (!hasSignature) {
                hasSignature = true;
                wrapper.classList.add('has-signature');
                errorEl.style.display = 'none';
            }
        }

        function stopDrawing() {
            isDrawing = false;
            wrapper.classList.remove('active');
            if (hasSignature) {
                hiddenInput.value = canvas.toDataURL('image/png');
            }
        }

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        // Touch events
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        // Clear signature
        clearBtn.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSignature = false;
            hiddenInput.value = '';
            wrapper.classList.remove('has-signature');
        });

        // Validate signature on final submit
        const submitBtn = document.querySelector('[name="submit_final"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const confirmModal = document.getElementById('finalConfirmModal');

                if (!hasSignature) {
                    e.preventDefault();
                    errorEl.style.display = 'flex';
                    document.querySelector('.signature-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (submitBtn.dataset.confirmed === '1') {
                    submitBtn.dataset.confirmed = '0';
                    return true;
                }

                e.preventDefault();
                if (confirmModal) {
                    confirmModal.style.display = 'flex';
                }
                return false;
            });

            const confirmOkBtn = document.getElementById('finalConfirmOk');
            const confirmCancelBtn = document.getElementById('finalConfirmCancel');
            const closeConfirmModal = function() {
                const modal = document.getElementById('finalConfirmModal');
                if (modal) modal.style.display = 'none';
            };

            if (confirmCancelBtn) {
                confirmCancelBtn.addEventListener('click', function() {
                    closeConfirmModal();
                });
            }

            if (confirmOkBtn) {
                confirmOkBtn.addEventListener('click', function() {
                    closeConfirmModal();
                    submitBtn.dataset.confirmed = '1';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(submitBtn);
                    } else {
                        form.submit();
                    }
                });
            }

            const confirmModal = document.getElementById('finalConfirmModal');
            if (confirmModal) {
                confirmModal.addEventListener('click', function(e) {
                    if (e.target === confirmModal) {
                        closeConfirmModal();
                    }
                });
            }
        }
    })();
</script>
@endsection
