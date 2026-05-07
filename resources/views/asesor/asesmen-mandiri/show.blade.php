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
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .hero-card {
        background: linear-gradient(135deg, #0b4b83, #1574c1);
        border-radius: 16px;
        padding: 22px 24px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .hero-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .hero-sub {
        font-size: 13px;
        opacity: 0.9;
    }

    .hero-meta {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .meta-item {
        background: rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: 10px 12px;
    }

    .meta-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 600;
    }

    .hero-badges {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-end;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(255,255,255,0.18);
        color: #ffffff;
    }

    .badge.status-selesai { background: rgba(34,197,94,0.18); }
    .badge.status-sedang { background: rgba(251,191,36,0.25); }
    .badge.status-belum { background: rgba(148,163,184,0.25); }

    .badge.rekom-lanjut { background: rgba(34,197,94,0.25); }
    .badge.rekom-tidak { background: rgba(239,68,68,0.25); }
    .badge.rekom-pending { background: rgba(148,163,184,0.25); }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .summary-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .unit-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .unit-header {
        background: #f8fafc;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        border-bottom: 1px solid #e5e7eb;
    }

    .unit-header h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }

    .unit-code {
        font-size: 12px;
        color: #64748b;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    thead th {
        background: #f9fafb;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
        text-align: left;
        padding: 10px 14px;
    }

    tbody td {
        padding: 10px 14px;
        border-top: 1px solid #f1f5f9;
        vertical-align: top;
        color: #374151;
    }

    .status-chip {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .status-chip.k { background: #d1fae5; color: #059669; }
    .status-chip.bk { background: #fee2e2; color: #dc2626; }
    .status-chip.empty { background: #f1f5f9; color: #94a3b8; }

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
        height: 180px;
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

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .hero-meta {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr;
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
@endphp

<a href="{{ route('asesor.asesmen-mandiri.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke daftar
</a>

<div class="hero-card">
    <div>
        <div class="hero-title">Asesmen Mandiri</div>
        <div class="hero-sub">Skema: {{ $skema->nama_skema ?? '-' }} ({{ $skema->nomor_skema ?? '-' }})</div>
        <div class="hero-meta">
            <div class="meta-item">
                <div class="meta-label">Nama Asesi</div>
                <div class="meta-value">{{ $asesi->nama }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">NIK</div>
                <div class="meta-value">{{ $asesi->NIK }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Periode</div>
                <div class="meta-value">
                    @if($pivot->tanggal_mulai)
                        {{ \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                    @if($pivot->tanggal_selesai)
                        s/d {{ \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d/m/Y H:i') }}
                    @endif
                </div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Jurusan</div>
                <div class="meta-value">{{ $asesi->jurusan?->nama_jurusan ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="hero-badges">
        <span class="badge {{ $statusClass }}">Status: {{ $statusLabel }}</span>
        @if($pivot->rekomendasi === 'lanjut')
            <span class="badge rekom-lanjut">Rekomendasi: Lanjut</span>
        @elseif($pivot->rekomendasi === 'tidak_lanjut')
            <span class="badge rekom-tidak">Rekomendasi: Tidak Lanjut</span>
        @else
            <span class="badge rekom-pending">Rekomendasi: Belum</span>
        @endif
    </div>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-value">{{ $totalElemen }}</div>
        <div class="summary-label">Total Elemen</div>
    </div>
    <div class="summary-card">
        <div class="summary-value">{{ $kCount }}</div>
        <div class="summary-label">Kompeten</div>
    </div>
    <div class="summary-card">
        <div class="summary-value">{{ $bkCount }}</div>
        <div class="summary-label">Belum Kompeten</div>
    </div>
    <div class="summary-card">
        <div class="summary-value">{{ $totalBelum }}</div>
        <div class="summary-label">Belum Dijawab</div>
    </div>
</div>

<div>
    <div class="section-title"><i class="bi bi-journal-text"></i> Ringkasan Jawaban Asesmen Mandiri</div>
    @if($skema->units->count())
        @foreach($skema->units as $unit)
            <div class="unit-card">
                <div class="unit-header">
                    <div>
                        <h3>{{ $unit->judul_unit ?? '-' }}</h3>
                        <div class="unit-code">{{ $unit->kode_unit ?? '-' }}</div>
                    </div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Elemen</th>
                                <th>Status</th>
                                <th>Nilai / Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unit->elemens as $elemen)
                                @php $jawaban = $answers->get($elemen->id); @endphp
                                <tr>
                                    <td>{{ $elemen->nama_elemen ?? '-' }}</td>
                                    <td>
                                        @if($jawaban && $jawaban->status === 'K')
                                            <span class="status-chip k">Kompeten</span>
                                        @elseif($jawaban && $jawaban->status === 'BK')
                                            <span class="status-chip bk">Belum Kompeten</span>
                                        @else
                                            <span class="status-chip empty">Belum Dijawab</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($jawaban && $jawaban->nilai)
                                            <strong>{{ $jawaban->nilai }}</strong>
                                        @else
                                            -
                                        @endif
                                        @if($jawaban && $jawaban->catatan)
                                            <div class="recommend-note">{{ $jawaban->catatan }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <div class="recommend-note">Belum ada unit kompetensi pada skema ini.</div>
    @endif
</div>

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
