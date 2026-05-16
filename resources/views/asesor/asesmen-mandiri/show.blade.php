@extends('asesor.layout')

@section('title', 'Asesmen Mandiri - ' . $asesi->nama)
@section('page-title', 'Asesmen Mandiri')

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

    .top-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .btn-export {
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        background: #0073bd;
        color: #ffffff;
        font-weight: 600;
    }

    .btn-export:hover {
        background: #003961;
        color: #ffffff;
    }

    .back-link:hover {
        color: #0061A5;
    }

    .result-header {
        background: #0061A5;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
        color: white;
    }

    .result-header-top {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .result-info h2 {
        font-size: 20px;
        margin-bottom: 4px;
    }

    .result-info .skema-number {
        font-size: 14px;
        opacity: 0.8;
        font-family: monospace;
    }

    .result-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.8;
        text-transform: uppercase;
    }

    .stat-item.kompeten .stat-value { color: #86efac; }
    .stat-item.belum .stat-value { color: #fecaca; }

    .completion-badge {
        background: #dbeafe;
        color: #0c4a6e;
        padding: 16px 24px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .completion-badge i {
        font-size: 24px;
    }

    .completion-badge .text h3 {
        font-size: 15px;
        margin-bottom: 2px;
    }

    .completion-badge .text p {
        font-size: 13px;
        opacity: 0.8;
        margin: 0;
    }

    .completion-badge.info-badge {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #0f172a;
    }

    .completion-badge.info-badge .text p {
        color: #475569;
        opacity: 1;
    }

    .unit-result {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .unit-result-header {
        background: #f8fafc;
        padding: 18px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .unit-result-header h3 {
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .unit-result-header .unit-code {
        font-size: 13px;
        color: #64748b;
        font-family: monospace;
    }

    .elemen-result {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        gap: 16px;
    }

    .elemen-result:last-child {
        border-bottom: none;
    }

    .elemen-status {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.kompeten {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .status-badge.belum {
        background: #fee2e2;
        color: #991b1b;
    }

    .elemen-content {
        flex: 1;
    }

    .elemen-content h4 {
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .bukti-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
    }

    .bukti-text.empty {
        color: #9ca3af;
        font-style: italic;
    }

    .recommend-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 20px;
        margin-top: 20px;
    }

    .recommend-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .recommend-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .recommend-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .recommend-field label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .recommend-field input,
    .recommend-field textarea {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13px;
    }

    .recommend-field textarea {
        min-height: 90px;
        resize: vertical;
    }

    .radio-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .radio-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .rekom-content {
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .rekom-lanjut {
        background: #d1fae5;
        color: #065f46;
    }

    .rekom-tidak {
        background: #fee2e2;
        color: #991b1b;
    }

    .recommend-note {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
    }

    .signature-section {
        margin-top: 14px;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
        width: 100%;
        max-width: 210px;
        margin-left: auto;
        margin-right: auto;
        aspect-ratio: 1 / 1;
    }

    .signature-canvas-wrapper.active {
        border-color: #0073bd;
        background: #ffffff;
    }

    .signature-canvas-wrapper.has-signature {
        border-style: solid;
        border-color: #0073bd;
    }

    .signature-canvas-wrapper.disabled {
        opacity: 0.6;
        pointer-events: none;
    }

    .signature-canvas {
        width: 100%;
        height: 100%;
        cursor: crosshair;
        display: block;
    }

    @media (max-width: 640px) {
        .signature-canvas-wrapper {
            max-width: 320px;
        }
    }

    .signature-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
        color: #9ca3af;
    }

    .signature-placeholder i {
        font-size: 24px;
        display: block;
        margin-bottom: 6px;
    }

    .signature-canvas-wrapper.has-signature .signature-placeholder {
        opacity: 0;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-clear-signature {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #64748b;
        cursor: pointer;
    }

    .signature-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 8px;
        display: none;
        align-items: center;
        gap: 6px;
    }

    .saved-signature {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .saved-signature img,
    .signature-preview img {
        max-width: 200px;
        max-height: 80px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #ffffff;
        padding: 6px;
    }

    .recommend-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
        gap: 10px;
    }

    .btn-primary {
        background: #0073bd;
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #003961;
    }

    @media (max-width: 768px) {
        .result-header {
            padding: 16px;
            margin-bottom: 16px;
        }

        .result-header-top {
            gap: 12px;
            margin-bottom: 14px;
        }

        .result-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            font-size: 22px;
        }

        .result-info h2 {
            font-size: 16px;
            line-height: 1.35;
        }

        .result-stats {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding-top: 14px;
        }

        .stat-value {
            font-size: 22px;
        }

        .completion-badge {
            padding: 12px 14px;
            margin-bottom: 16px;
            gap: 10px;
            align-items: flex-start;
        }

        .completion-badge i {
            font-size: 18px;
        }

        .unit-result-header,
        .elemen-result {
            padding: 12px 14px;
        }

        .elemen-result {
            flex-direction: column;
            gap: 8px;
        }

        .recommend-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $statusLabel = match($pivot->status) {
        'selesai' => 'Selesai',
        'sedang_mengerjakan' => 'Sedang Dikerjakan',
        default => 'Belum Mulai',
    };
    $statusClass = match($pivot->status) {
        'selesai' => 'status-selesai',
        'sedang_mengerjakan' => 'status-sedang',
        default => 'status-belum',
    };
    $rekomLabel = match($pivot->rekomendasi) {
        'lanjut' => 'Lanjut',
        'tidak_lanjut' => 'Tidak Lanjut',
        default => 'Belum',
    };
    $periode = '-';
    if (!empty($pivot->tanggal_mulai)) {
        $periode = \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d/m/Y H:i');
        if (!empty($pivot->tanggal_selesai)) {
            $periode .= ' s/d ' . \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d/m/Y H:i');
        }
    }
    $totalKompeten = $kCount;
    $totalBelumKompeten = $bkCount;
    $totalAnswered = $answers->count();
@endphp

<div class="top-actions">
    <a href="{{ route('asesor.asesmen-mandiri.index') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar
    </a>
    <a href="{{ route('asesor.asesmen-mandiri.export', ['asesiNik' => $asesi->NIK, 'skemaId' => $skema->id]) }}" class="btn-export" target="_blank">
        <i class="bi bi-download"></i> Export FR.APL.02 (.doc)
    </a>
</div>

<div class="result-header">
    <div class="result-header-top">
        <div class="result-icon">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="result-info">
            <h2>{{ $skema->nama_skema ?? '-' }}</h2>
            <div class="skema-number">{{ $skema->nomor_skema ?? '-' }}</div>
        </div>
    </div>
    <div class="result-stats">
        <div class="stat-item kompeten">
            <div class="stat-value">{{ $totalKompeten }}</div>
            <div class="stat-label">Kompeten (K)</div>
        </div>
        <div class="stat-item belum">
            <div class="stat-value">{{ $totalBelumKompeten }}</div>
            <div class="stat-label">Belum Kompeten (BK)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalAnswered }}</div>
            <div class="stat-label">Total Jawaban</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $skema->units->count() }}</div>
            <div class="stat-label">Unit Kompetensi</div>
        </div>
    </div>
</div>

<div class="completion-badge info-badge">
    <i class="bi bi-person-badge"></i>
    <div class="text">
        <h3>{{ $asesi->nama }} ({{ $asesi->NIK }})</h3>
        <p>Jurusan: {{ $asesi->jurusan?->nama_jurusan ?? '-' }} • Periode: {{ $periode }}</p>
        <p>Status: @include('components.asesi-status', ['pivot' => $pivot]) • Rekomendasi: {{ $rekomLabel }}</p>
    </div>
</div>

@if($pivot && $pivot->status === 'selesai')
<div class="completion-badge">
    <i class="bi bi-check-circle-fill"></i>
    <div class="text">
        <h3>Asesmen Mandiri Selesai</h3>
        <p>Diselesaikan pada {{ \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d M Y, H:i') }} WIB</p>
    </div>
</div>
@endif

@if($skema->units->count())
    @foreach($skema->units as $unitIndex => $unit)
    <div class="unit-result">
        <div class="unit-result-header">
            <h3>Unit {{ $unitIndex + 1 }}: {{ $unit->judul_unit ?? '-' }}</h3>
            <div class="unit-code">{{ $unit->kode_unit ?? '-' }}</div>
        </div>

        @foreach($unit->elemens as $elemenIndex => $elemen)
        @php
            $answer = $answers->get($elemen->id);
        @endphp
        <div class="elemen-result">
            <div class="elemen-status">
                @if($answer)
                    <span class="status-badge {{ $answer->status === 'K' ? 'kompeten' : 'belum' }}">
                        {{ $answer->status === 'K' ? 'Kompeten' : 'Belum Kompeten' }}
                    </span>
                @else
                    <span class="status-badge belum">Belum Dijawab</span>
                @endif
            </div>
            <div class="elemen-content">
                <h4>Elemen {{ $elemenIndex + 1 }}: {{ $elemen->nama_elemen }}</h4>
                @if($answer && $answer->bukti)
                    <div class="bukti-text">
                        <strong>Bukti:</strong> {{ $answer->bukti }}
                    </div>
                @else
                    <div class="bukti-text empty">Tidak ada bukti yang dilampirkan</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
@else
    <div class="recommend-note">Belum ada unit kompetensi pada skema ini.</div>
@endif

@if($pivot->rekomendasi)
    <div class="recommend-card">
        <div class="recommend-title"><i class="bi bi-patch-check"></i> Rekomendasi Asesor</div>
        <div class="rekom-content {{ $pivot->rekomendasi === 'lanjut' ? 'rekom-lanjut' : 'rekom-tidak' }}">
            {{ $pivot->rekomendasi === 'lanjut' ? 'Asesmen dapat dilanjutkan.' : 'Asesmen tidak dapat dilanjutkan.' }}
        </div>
        @if($pivot->catatan_asesor)
            <div class="recommend-note">Catatan Asesor: {{ $pivot->catatan_asesor }}</div>
        @endif
        <div class="signature-preview" style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;">
            <div>
                <div class="recommend-note">Tanda Tangan Asesi</div>
                @if($pivot->tanda_tangan)
                    <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi">
                @else
                    <div class="recommend-note">Belum ada tanda tangan asesi.</div>
                @endif
            </div>
            <div>
                <div class="recommend-note">Tanda Tangan Asesor</div>
                @if($pivot->tanda_tangan_asesor)
                    <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda Tangan Asesor">
                @else
                    <div class="recommend-note">Belum ada tanda tangan asesor.</div>
                @endif
            </div>
        </div>
    </div>
@elseif($pivot->status === 'selesai')
    <form method="POST" action="{{ route('asesor.asesmen-mandiri.recommend', ['asesiNik' => $asesi->NIK, 'skemaId' => $skema->id]) }}" id="recommendForm">
        @csrf
        <div class="recommend-card">
            <div class="recommend-title"><i class="bi bi-clipboard-check"></i> Rekomendasi Asesor</div>
            <div class="recommend-grid">
                <div class="recommend-field">
                    <label>Rekomendasi <span style="color:#dc2626;">*</span></label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="rekomendasi" value="lanjut" {{ old('rekomendasi') === 'lanjut' ? 'checked' : '' }}>
                            Asesmen dapat dilanjutkan
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="rekomendasi" value="tidak_lanjut" {{ old('rekomendasi') === 'tidak_lanjut' ? 'checked' : '' }}>
                            Asesmen tidak dapat dilanjutkan
                        </label>
                    </div>
                    @error('rekomendasi')<div class="signature-error" style="display:flex;">{{ $message }}</div>@enderror
                </div>
                <div class="recommend-field">
                    <label>Catatan Asesor (opsional)</label>
                    <textarea name="catatan_asesor" maxlength="1000" placeholder="Catatan tambahan untuk asesi">{{ old('catatan_asesor') }}</textarea>
                </div>
            </div>

            <div class="signature-section">
                <label>Tanda Tangan Asesor <span style="color:#dc2626;">*</span></label>
                @if($savedSignature)
                    <div class="saved-signature">
                        <img src="{{ $savedSignature }}" alt="Tanda Tangan Tersimpan">
                        <label style="font-size:13px;color:#475569;">
                            <input type="checkbox" id="useSavedSignature" checked>
                            Gunakan tanda tangan tersimpan
                        </label>
                    </div>
                @endif
                <div class="signature-canvas-wrapper" id="signatureWrapper">
                    <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                    <div class="signature-placeholder" id="signaturePlaceholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>
                <input type="hidden" name="tanda_tangan_asesor" id="tandaTanganInput" value="">
                <div class="signature-error" id="signatureError">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Tanda tangan wajib diisi sebelum menyimpan rekomendasi.</span>
                </div>
                @error('tanda_tangan_asesor')<div class="signature-error" style="display:flex;">{{ $message }}</div>@enderror
                <div class="signature-actions">
                    <div class="recommend-note"><i class="bi bi-calendar3"></i> Tanggal: {{ now()->format('d/m/Y') }}</div>
                    <button type="button" class="btn-clear-signature" id="clearSignature">
                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                    </button>
                </div>
            </div>

            <div class="recommend-field" style="margin-top:12px;">
                <label style="font-weight:500;display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="simpan_tanda_tangan" value="1" {{ old('simpan_tanda_tangan') ? 'checked' : '' }}>
                    Simpan tanda tangan ke profil asesor
                </label>
            </div>

            <div class="recommend-actions">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-save"></i> Simpan Rekomendasi
                </button>
            </div>
        </div>
    </form>
@else
    <div class="recommend-card">
        <div class="recommend-title"><i class="bi bi-hourglass-split"></i> Menunggu Asesmen Mandiri</div>
        <div class="recommend-note">Asesmen mandiri belum selesai. Rekomendasi dapat diberikan setelah status selesai.</div>
    </div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('recommendForm');
    if (!form) {
        return;
    }

    const savedSignature = @json($savedSignature);
    const useSavedCheckbox = document.getElementById('useSavedSignature');
    const canvas = document.getElementById('signatureCanvas');
    const wrapper = document.getElementById('signatureWrapper');
    const hiddenInput = document.getElementById('tandaTanganInput');
    const clearBtn = document.getElementById('clearSignature');
    const errorEl = document.getElementById('signatureError');

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let hasCanvasSignature = false;

    const ctx = canvas ? canvas.getContext('2d') : null;

    function resizeCanvas() {
        if (!canvas || !ctx) {
            return;
        }

        const rect = canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#1e293b';
    }

    function setUseSaved(enabled) {
        if (!savedSignature || !hiddenInput || !wrapper) {
            return;
        }

        if (enabled) {
            hiddenInput.value = savedSignature;
            wrapper.classList.add('disabled', 'has-signature');
            if (errorEl) {
                errorEl.style.display = 'none';
            }
        } else {
            wrapper.classList.remove('disabled');
            if (hasCanvasSignature && canvas) {
                hiddenInput.value = canvas.toDataURL('image/png');
                wrapper.classList.add('has-signature');
            } else {
                hiddenInput.value = '';
                wrapper.classList.remove('has-signature');
            }
        }
    }

    if (useSavedCheckbox && savedSignature) {
        useSavedCheckbox.addEventListener('change', function () {
            setUseSaved(this.checked);
        });
        setUseSaved(true);
    }

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches ? e.touches[0] : e;
        return {
            x: touch.clientX - rect.left,
            y: touch.clientY - rect.top
        };
    }

    function startDrawing(e) {
        if (!canvas || !ctx || (wrapper && wrapper.classList.contains('disabled'))) {
            return;
        }
        e.preventDefault();
        isDrawing = true;
        const pos = getPos(e);
        lastX = pos.x;
        lastY = pos.y;
        if (wrapper) {
            wrapper.classList.add('active');
        }
    }

    function draw(e) {
        if (!isDrawing || !canvas || !ctx) {
            return;
        }
        e.preventDefault();
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        lastX = pos.x;
        lastY = pos.y;
        if (!hasCanvasSignature) {
            hasCanvasSignature = true;
            if (wrapper) {
                wrapper.classList.add('has-signature');
            }
        }
    }

    function stopDrawing() {
        if (!isDrawing) {
            return;
        }
        isDrawing = false;
        if (wrapper) {
            wrapper.classList.remove('active');
        }
        if (canvas && hiddenInput) {
            hiddenInput.value = canvas.toDataURL('image/png');
        }
        if (errorEl) {
            errorEl.style.display = 'none';
        }
        if (useSavedCheckbox && useSavedCheckbox.checked) {
            useSavedCheckbox.checked = false;
            setUseSaved(false);
        }
    }

    if (canvas && ctx) {
        resizeCanvas();
        window.addEventListener('resize', function () {
            const snapshot = hasCanvasSignature ? canvas.toDataURL('image/png') : '';
            resizeCanvas();
            if (snapshot) {
                const img = new Image();
                img.onload = function () {
                    ctx.drawImage(img, 0, 0, canvas.getBoundingClientRect().width, canvas.getBoundingClientRect().height);
                };
                img.src = snapshot;
            }
        });

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);
    }

    if (clearBtn && canvas && ctx) {
        clearBtn.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasCanvasSignature = false;
            if (hiddenInput && !(useSavedCheckbox && useSavedCheckbox.checked)) {
                hiddenInput.value = '';
            }
            if (wrapper) {
                wrapper.classList.remove('has-signature');
            }
            if (errorEl) {
                errorEl.style.display = 'none';
            }
        });
    }

    form.addEventListener('submit', function (e) {
        if (!hiddenInput || !hiddenInput.value) {
            e.preventDefault();
            if (errorEl) {
                errorEl.style.display = 'flex';
            }
            if (wrapper) {
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
@endsection
