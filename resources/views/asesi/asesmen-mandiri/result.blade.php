@extends('asesi.layout')

@section('title', 'Hasil Asesmen Mandiri - ' . $skema->nama_skema)
@section('page-title', 'Hasil Asesmen Mandiri')

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
        color: #0073bd;
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
        background: #0073bd;
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
        background: #f0f5fd;
        border: 1px solid #bbd5f7;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .instructions-box h3 {
        font-size: 15px;
        color: #0073bd;
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
        color: #0073bd;
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
        background: #0073bd;
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
        flex-wrap: wrap;
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
        color: #0073bd;
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
        min-width: 220px;
    }

    .radio-group {
        display: flex;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: default;
    }

    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #16a34a;
        cursor: default;
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
        background: #f8fafc;
        color: #334155;
        resize: vertical;
        min-height: 70px;
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

    .waiting-review-box {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 18px 24px;
        margin-bottom: 24px;
    }

    .waiting-review-box i {
        font-size: 28px;
        color: #16a34a;
        flex-shrink: 0;
    }

    .waiting-review-box .waiting-title {
        font-size: 15px;
        font-weight: 700;
        color: #166534;
        margin-bottom: 2px;
    }

    .waiting-review-box .waiting-desc {
        font-size: 13px;
        color: #15803d;
    }

    /* Signature Section */
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

    .signature-saved-display {
        text-align: center;
        padding: 16px;
    }

    .signature-saved-display img {
        max-width: 320px;
        width: 100%;
        height: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
    }

    .signature-saved-meta {
        margin-top: 10px;
        font-size: 13px;
        color: #0073bd;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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
        margin-top: 24px;
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

    .btn-back {
        background: #0073bd;
        color: white;
    }

    .btn-back:hover {
        background: #005f9a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.3);
    }

    @media (max-width: 768px) {
        .skema-header,
        .instructions-box,
        .signature-section,
        .form-actions {
            padding: 16px;
            margin-bottom: 16px;
        }

        .skema-header-top {
            margin-bottom: 14px;
            padding-bottom: 14px;
            gap: 10px;
        }

        .skema-header-icon {
            width: 44px;
            height: 44px;
            font-size: 22px;
        }

        .skema-header-info h2 {
            font-size: 17px;
            line-height: 1.35;
        }

        .skema-header-meta {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .unit-header {
            padding: 14px;
        }

        .unit-meta {
            gap: 10px;
            font-size: 13px;
        }

        .unit-title-row {
            margin-bottom: 6px;
        }

        .unit-header h3 {
            font-size: 15px;
            line-height: 1.35;
        }

        .unit-question {
            padding: 12px 14px;
            font-size: 14px;
        }

        .elemen-header {
            flex-direction: column;
            padding: 12px 14px;
            gap: 10px;
        }

        .elemen-controls {
            width: 100%;
            min-width: auto;
        }

        .kriteria-list {
            padding: 12px 14px;
        }

        .bukti-section {
            padding: 0 14px 14px 14px;
        }

        .rekomendasi-banner {
            padding: 14px;
            gap: 10px;
        }

        .form-actions {
            flex-direction: column;
            padding: 14px;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
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
        <div class="meta-item">
            <span class="meta-label">Status Formulir</span>
            <span class="meta-value" style="color:#0073bd;font-weight:700;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Selesai Diisi
            </span>
        </div>
    </div>
</div>

<!-- Instructions Box -->
<div class="instructions-box">
    <h3><i class="bi bi-info-circle"></i> Hasil Pengisian Asesmen Mandiri</h3>
    <ul>
        <li>Berikut adalah data asesmen mandiri yang telah Anda isi dan tandatangani.</li>
        <li>Tanda <strong>K (Kompeten)</strong> / <strong>BK (Belum Kompeten)</strong> dan bukti yang telah Anda lampirkan telah tersimpan ke sistem.</li>
        <li>Asesor akan meninjau jawaban dan bukti yang Anda berikan sebelum pelaksanaan asesmen.</li>
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
                &nbsp;&bull;&nbsp; {{ \Carbon\Carbon::parse($pivot->reviewed_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
            @endif
        </div>
        @if($pivot->catatan_asesor)
        <div class="banner-catatan">
            <i class="bi bi-chat-quote"></i> {{ $pivot->catatan_asesor }}
        </div>
        @endif
    </div>
</div>
@else
<div class="waiting-review-box">
    <i class="bi bi-hourglass-split"></i>
    <div>
        <div class="waiting-title">Menunggu Peninjauan Asesor</div>
        <div class="waiting-desc">Asesmen mandiri Anda telah dikirim dan sedang menunggu tinjauan serta rekomendasi dari Asesor.</div>
    </div>
</div>
@endif

<!-- Unit Kompetensi Cards -->
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
        {{ $unit->pertanyaan_unit }}
    </div>
    @endif

    <div class="unit-body">
        @foreach($unit->elemens as $elemenIndex => $elemen)
        @php
            $existingAnswer = $answers->get($elemen->id);
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
                                   value="K" 
                                   {{ ($existingAnswer && $existingAnswer->status === 'K') ? 'checked' : '' }}
                                   disabled>
                            <span>K (Kompeten)</span>
                        </label>
                        <label class="radio-option belum">
                            <input type="radio" 
                                   value="BK"
                                   {{ ($existingAnswer && $existingAnswer->status === 'BK') ? 'checked' : '' }}
                                   disabled>
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
                <textarea class="bukti-input" readonly placeholder="Tidak ada bukti yang dilampirkan">{{ $existingAnswer->bukti ?? '' }}</textarea>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

<!-- Tanda Tangan Asesi -->
<div class="signature-section">
    <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
    <p class="signature-subtitle">Pernyataan asesi bahwa semua data dan jawaban di atas telah diisi sesuai dengan kompetensi yang dimiliki.</p>

    @if($pivot && $pivot->tanda_tangan)
    <div class="signature-saved-display">
        <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi">
        <div style="font-size:12px;color:#475569;margin-top:6px;font-weight:600;">
            {{ $asesi->nama }}@if($pivot->tanggal_tanda_tangan), {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan)->locale('id')->isoFormat('D MMMM YYYY') }}@endif
        </div>
        @if($pivot->tanggal_tanda_tangan)
        <div class="signature-saved-meta">
            <i class="bi bi-check-circle-fill"></i>
            Ditandatangani pada: {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </div>
        @endif
    </div>
    @elseif($asesi->tanda_tangan)
    <div class="signature-saved-display">
        <img src="{{ $asesi->tanda_tangan }}" alt="Tanda Tangan Asesi">
        <div style="font-size:12px;color:#475569;margin-top:6px;font-weight:600;">{{ $asesi->nama }}</div>
    </div>
    @else
    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">
        <i class="bi bi-pen" style="font-size:24px;display:block;margin-bottom:4px;"></i>
        Tanda tangan tersimpan
    </div>
    @endif
</div>
@endsection
