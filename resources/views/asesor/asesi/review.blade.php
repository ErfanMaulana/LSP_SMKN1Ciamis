@extends('asesor.layout')

@section('title', 'Review Asesmen - ' . $asesi->nama)
@section('page-title', 'Review Asesmen Mandiri')

@section('styles')
<style>
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: #2563eb; text-decoration: none; font-size: 14px;
        font-weight: 500; margin-bottom: 18px;
    }
    .back-btn:hover { color: #1d4ed8; }

    .header-card {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 14px; padding: 24px 28px; color: white; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;
        flex-wrap: wrap;
    }
    .header-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .header-card .meta { font-size: 13px; opacity: 0.85; }
    .header-card .meta span { margin-right: 16px; }

    .summary-row {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(150px,1fr));
        gap: 14px; margin-bottom: 24px;
    }
    .summary-card {
        background: white; border-radius: 10px; padding: 16px;
        text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .summary-card .num { font-size: 28px; font-weight: 700; }
    .summary-card .lbl { font-size: 12px; color: #64748b; font-weight: 500; }
    .k-color  { color: #059669; }
    .bk-color { color: #dc2626; }

    .print-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: white; color: #0073bd;
        border: 1.5px solid #d1d5db; padding: 8px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none;
        transition: all 0.2s;
    }
    .print-btn:hover { background: #f8fafc; color: #0073bd; }

    .unit-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; margin-bottom: 20px; overflow: hidden;
    }
    .unit-header {
        background: #f8fafc; padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 12px;
    }
    .unit-number {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; flex-shrink: 0;
    }
    .unit-header h3 { font-size: 15px; font-weight: 700; color: #0073bd; margin-bottom: 2px; }
    .unit-header small { font-size: 12px; color: #64748b; font-family: monospace; }

    .elemen-row {
        padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
    }
    .elemen-row:last-child { border-bottom: none; }

    .elemen-top {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: 12px; margin-bottom: 10px;
    }
    .elemen-name {
        font-size: 14px; font-weight: 600; color: #0073bd;
    }
    .elemen-label { font-size: 11px; color: #64748b; font-weight: 500; margin-bottom: 2px; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;
        flex-shrink: 0;
    }
    .status-K  { background: #d1fae5; color: #059669; }
    .status-BK { background: #fee2e2; color: #dc2626; }
    .status-na { background: #f1f5f9; color: #94a3b8; }

    .kriteria-list {
        list-style: none; padding: 0; margin: 0 0 10px;
    }
    .kriteria-list li {
        display: flex; gap: 8px; font-size: 12px; color: #64748b;
        padding: 3px 0;
    }
    .kriteria-list li::before {
        content: counter(krit);
        counter-increment: krit;
        background: #dbeafe; color: #2563eb;
        width: 18px; height: 18px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
    }
    .kriteria-list { counter-reset: krit; }

    .bukti-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 10px 14px; font-size: 13px; color: #475569;
        display: flex; gap: 8px; align-items: flex-start;
    }
    .bukti-box i { color: #3b82f6; margin-top: 2px; flex-shrink: 0; }
    .bukti-empty { color: #94a3b8; font-style: italic; }

    @media print {
        .back-btn, .print-btn, aside, .topbar { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .unit-card { break-inside: avoid; }
    }
</style>
@endsection

@section('content')

<a href="{{ route('asesor.asesi.index') }}" class="back-btn">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Asesi
</a>

{{-- Header Info --}}
<div class="header-card">
    <div>
        <h2><i class="bi bi-person-lines-fill"></i> {{ $asesi->nama }}</h2>
        <div class="meta">
            <span><i class="bi bi-credit-card"></i> NIK: {{ $asesi->NIK }}</span>
            <span><i class="bi bi-hash"></i> No. Reg: {{ $asesi->no_reg ?? '—' }}</span>
            <span><i class="bi bi-mortarboard"></i> {{ $asesi->jurusan?->nama_jurusan ?? '—' }}</span>
        </div>
        <div class="meta" style="margin-top:6px;">
            <span><i class="bi bi-award"></i> {{ $skema->nama_skema }}</span>
        </div>
        <div class="meta" style="margin-top:4px; opacity:0.75; font-size:12px;">
            <span>Mulai: {{ $pivot->tanggal_mulai ? \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d/m/Y H:i') : '—' }}</span>
            <span>Selesai: {{ $pivot->tanggal_selesai ? \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d/m/Y H:i') : '—' }}</span>
        </div>
    </div>
    <a href="javascript:window.print()" class="print-btn" style="color:#1e3a5f;background:white;">
        <i class="bi bi-printer"></i> Cetak
    </a>
</div>

{{-- Summary --}}
<div class="summary-row">
    <div class="summary-card">
        <div class="num" style="color:#0073bd;">{{ $skema->units->count() }}</div>
        <div class="lbl">Unit Kompetensi</div>
    </div>
    <div class="summary-card">
        <div class="num" style="color:#2563eb;">{{ $answers->count() }}</div>
        <div class="lbl">Elemen Dijawab</div>
    </div>
    <div class="summary-card">
        <div class="num k-color">{{ $kCount }}</div>
        <div class="lbl">Kompeten (K)</div>
    </div>
    <div class="summary-card">
        <div class="num bk-color">{{ $bkCount }}</div>
        <div class="lbl">Belum Kompeten (BK)</div>
    </div>
    <div class="summary-card">
        @php $pct = $answers->count() > 0 ? round($kCount / $answers->count() * 100) : 0; @endphp
        <div class="num" style="color:{{ $pct >= 70 ? '#059669' : '#d97706' }};">{{ $pct }}%</div>
        <div class="lbl">Tingkat Kompeten</div>
    </div>
</div>

{{-- Per-Unit Detail --}}
@foreach($skema->units as $unitIdx => $unit)
<div class="unit-card">
    <div class="unit-header">
        <div class="unit-number">{{ $unitIdx + 1 }}</div>
        <div>
            <h3>{{ $unit->judul_unit }}</h3>
            <small>{{ $unit->kode_unit }}</small>
        </div>
    </div>

    @foreach($unit->elemens as $elIdx => $elemen)
    @php $jawaban = $answers->get($elemen->id); @endphp
    <div class="elemen-row">
        <div class="elemen-top">
            <div>
                <div class="elemen-label">Elemen {{ $elIdx + 1 }}</div>
                <div class="elemen-name">{{ $elemen->nama_elemen }}</div>
            </div>
            @if($jawaban)
                <span class="status-badge status-{{ $jawaban->status }}">
                    {{ $jawaban->status === 'K' ? '✓ Kompeten' : '✗ Belum Kompeten' }}
                </span>
            @else
                <span class="status-badge status-na">— Belum Dijawab</span>
            @endif
        </div>

        {{-- Kriteria --}}
        @if($elemen->kriteria->count())
        <ul class="kriteria-list">
            @foreach($elemen->kriteria as $k)
            <li>{{ $k->deskripsi_kriteria }}</li>
            @endforeach
        </ul>
        @endif

        {{-- Bukti --}}
        <div class="bukti-box">
            <i class="bi bi-file-earmark-text"></i>
            @if($jawaban?->bukti)
                <span>{{ $jawaban->bukti }}</span>
            @else
                <span class="bukti-empty">Tidak ada bukti yang diisikan.</span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endforeach

{{-- ═══════════════════════════════════════════════════════
     FR.APL.02 — Rekomendasi Asesor
════════════════════════════════════════════════════════ --}}
<div class="unit-card" style="margin-top:28px;" id="rekomendasi-section">
    <div class="unit-header" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);color:white;">
        <div class="unit-number" style="background:rgba(255,255,255,0.2);">
            <i class="bi bi-patch-check-fill" style="font-size:16px;"></i>
        </div>
        <div>
            <h3 style="color:white;margin:0;">Rekomendasi Asesor <span style="font-size:12px;font-weight:400;opacity:0.8;">(FR.APL.02)</span></h3>
            <small style="color:rgba(255,255,255,0.7);">Tentukan apakah asesi dapat melanjutkan ke tahap asesmen berikutnya</small>
        </div>
    </div>

    @if($pivot->rekomendasi)
    <div style="padding:16px 22px;background:{{ $pivot->rekomendasi === 'lanjut' ? '#d1fae5' : '#fee2e2' }};border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:12px;">
        <i class="bi bi-{{ $pivot->rekomendasi === 'lanjut' ? 'check-circle-fill' : 'x-circle-fill' }}"
           style="font-size:22px;color:{{ $pivot->rekomendasi === 'lanjut' ? '#059669' : '#dc2626' }};"></i>
        <div>
            <div style="font-weight:700;font-size:15px;color:{{ $pivot->rekomendasi === 'lanjut' ? '#065f46' : '#991b1b' }};">
                Asesmen {{ $pivot->rekomendasi === 'lanjut' ? 'dapat' : 'tidak dapat' }} dilanjutkan
            </div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">
                Ditinjau pada {{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                &bull; oleh <strong>{{ $pivot->reviewed_by ?? '-' }}</strong>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('asesor.asesi.recommend', $asesi->NIK) }}" style="padding:22px 24px;">
        @csrf

        {{-- Tabel Rekomendasi — format FR.APL.02 --}}
        <table style="width:100%;border-collapse:collapse;margin-bottom:20px;font-size:14px;">
            <tr>
                <td style="width:42%;border:1px solid #d1d5db;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                    <strong>Rekomendasi Untuk Asesi:</strong>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">Asesmen dapat / tidak dapat dilanjutkan</div>
                </td>
                <td style="border:1px solid #d1d5db;padding:14px 20px;">
                    <label id="label-lanjut" onclick="selectRekom('lanjut')"
                           style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border-radius:8px;border:2px solid #e2e8f0;margin-bottom:10px;transition:all 0.2s;">
                        <input type="radio" name="rekomendasi" value="lanjut"
                               {{ old('rekomendasi', $pivot->rekomendasi) === 'lanjut' ? 'checked' : '' }}
                               style="accent-color:#059669;width:16px;height:16px;">
                        <span style="font-weight:600;color:#065f46;">✓ &nbsp;Asesmen <u>dapat</u> dilanjutkan</span>
                    </label>
                    <label id="label-tidak" onclick="selectRekom('tidak_lanjut')"
                           style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border-radius:8px;border:2px solid #e2e8f0;transition:all 0.2s;">
                        <input type="radio" name="rekomendasi" value="tidak_lanjut"
                               {{ old('rekomendasi', $pivot->rekomendasi) === 'tidak_lanjut' ? 'checked' : '' }}
                               style="accent-color:#dc2626;width:16px;height:16px;">
                        <span style="font-weight:600;color:#991b1b;">✗ &nbsp;Asesmen <u>tidak dapat</u> dilanjutkan</span>
                    </label>
                    @error('rekomendasi')
                        <div style="color:#dc2626;font-size:12px;margin-top:6px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #d1d5db;border-top:none;padding:12px 16px;vertical-align:top;background:#f8fafc;">
                    <strong>Catatan Asesor:</strong>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">Opsional</div>
                </td>
                <td style="border:1px solid #d1d5db;border-top:none;padding:12px 14px;">
                    <textarea name="catatan_asesor" rows="3"
                              placeholder="Tuliskan catatan atau alasan rekomendasi (opsional)..."
                              style="width:100%;border:1px solid #e2e8f0;border-radius:6px;padding:8px 12px;font-size:13px;resize:vertical;font-family:inherit;color:#374151;">{{ old('catatan_asesor', $pivot->catatan_asesor) }}</textarea>
                </td>
            </tr>
        </table>

        {{-- Tabel Tanda Tangan — format FR.APL.02 --}}
        <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:22px;">
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
                                         style="max-width:200px;max-height:80px;border:1px solid #e2e8f0;border-radius:4px;">
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
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;">{{ $asesor->nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;color:#64748b;">No. Reg:</td>
                            <td style="padding:9px 14px;border-bottom:1px solid #eff0f1;font-weight:500;font-family:monospace;">{{ $asesor->no_met }}</td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;color:#64748b;vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                            <td style="padding:9px 14px;">
                                {{-- Tampilkan tanda tangan tersimpan jika sudah pernah rekomendasi --}}
                                @if($pivot->tanda_tangan_asesor)
                                    <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda tangan asesor"
                                         style="max-width:200px;max-height:80px;border:1px solid #e2e8f0;border-radius:4px;">
                                    @if($pivot->tanggal_tanda_tangan_asesor)
                                    <div style="font-size:11px;color:#64748b;margin-top:4px;">
                                        {{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan_asesor)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                                    </div>
                                    @endif
                                @endif

                                {{-- Signature Pad Area --}}
                                <div id="signatureSection" style="margin-top:8px;">
                                    <input type="hidden" name="tanda_tangan_asesor" id="tandaTanganAsesorInput">

                                    {{-- Opsi gunakan tanda tangan tersimpan --}}
                                    @if($savedSignature)
                                    <div id="savedSignatureOption" style="border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:10px;background:#f0fdf4;">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                            <input type="radio" name="signature_mode" id="mode_saved" value="saved" checked
                                                   style="accent-color:#059669;width:15px;height:15px;">
                                            <label for="mode_saved" style="font-weight:600;color:#065f46;font-size:12px;cursor:pointer;">
                                                <i class="bi bi-bookmark-check-fill"></i> Gunakan tanda tangan tersimpan
                                            </label>
                                        </div>
                                        <img id="savedSignatureImg" src="{{ $savedSignature }}" alt="Tanda tangan tersimpan"
                                             style="max-width:200px;max-height:70px;border:1px solid #d1d5db;border-radius:4px;background:white;display:block;">
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                        <input type="radio" name="signature_mode" id="mode_manual" value="manual"
                                               style="accent-color:#2563eb;width:15px;height:15px;">
                                        <label for="mode_manual" style="font-weight:600;color:#1e3a5f;font-size:12px;cursor:pointer;">
                                            <i class="bi bi-pencil-square"></i> Tanda tangan manual (baru)
                                        </label>
                                    </div>
                                    @endif

                                    {{-- Canvas Signature Pad --}}
                                    <div id="canvasWrapper" style="{{ $savedSignature ? 'display:none;' : '' }}">
                                        <canvas id="signatureCanvas"
                                                style="border:2px dashed #cbd5e1;border-radius:8px;cursor:crosshair;background:white;width:100%;height:100px;touch-action:none;"></canvas>
                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px;">
                                            <span style="font-size:11px;color:#94a3b8;"><i class="bi bi-pen"></i> Tanda tangan di area atas</span>
                                            <button type="button" id="clearCanvas"
                                                    style="font-size:11px;color:#dc2626;background:none;border:none;cursor:pointer;font-weight:500;">
                                                <i class="bi bi-eraser"></i> Hapus
                                            </button>
                                        </div>

                                        {{-- Opsi simpan tanda tangan --}}
                                        <label id="saveSignatureLabel" style="display:flex;align-items:center;gap:8px;margin-top:8px;cursor:pointer;padding:8px 10px;border:1px solid #e2e8f0;border-radius:6px;background:#f8fafc;">
                                            <input type="checkbox" name="simpan_tanda_tangan" id="simpanTandaTangan" value="1"
                                                   style="accent-color:#2563eb;width:15px;height:15px;">
                                            <span style="font-size:12px;color:#475569;">
                                                <i class="bi bi-bookmark-plus"></i> Simpan tanda tangan ini untuk rekomendasi selanjutnya
                                            </span>
                                        </label>
                                    </div>

                                    @error('tanda_tangan_asesor')
                                    <div style="color:#dc2626;font-size:12px;margin-top:6px;">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('asesor.asesi.index') }}"
               style="padding:10px 20px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;color:#475569;font-size:14px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit"
                    style="padding:10px 26px;border-radius:8px;border:none;background:linear-gradient(135deg,#1e3a5f,#2563eb);color:white;font-size:14px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                <i class="bi bi-send-check-fill"></i>
                {{ $pivot->rekomendasi ? 'Perbarui Rekomendasi' : 'Simpan Rekomendasi' }}
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
function selectRekom(val) {
    const lanjut = document.getElementById('label-lanjut');
    const tidak  = document.getElementById('label-tidak');
    lanjut.style.borderColor = val === 'lanjut'       ? '#059669' : '#e2e8f0';
    lanjut.style.background  = val === 'lanjut'       ? '#f0fdf4' : 'white';
    tidak.style.borderColor  = val === 'tidak_lanjut' ? '#dc2626' : '#e2e8f0';
    tidak.style.background   = val === 'tidak_lanjut' ? '#fff1f2' : 'white';
}

document.addEventListener('DOMContentLoaded', function () {
    // --- Rekomendasi radio highlight ---
    const checked = document.querySelector('input[name="rekomendasi"]:checked');
    if (checked) selectRekom(checked.value);

    // --- Signature Pad ---
    const canvas  = document.getElementById('signatureCanvas');
    const ctx     = canvas.getContext('2d');
    const hiddenInput   = document.getElementById('tandaTanganAsesorInput');
    const clearBtn      = document.getElementById('clearCanvas');
    const canvasWrapper = document.getElementById('canvasWrapper');
    const modeSaved     = document.getElementById('mode_saved');
    const modeManual    = document.getElementById('mode_manual');
    const savedImg      = document.getElementById('savedSignatureImg');
    const saveLabel     = document.getElementById('saveSignatureLabel');
    const simpanCheckbox = document.getElementById('simpanTandaTangan');

    let drawing = false;
    let hasDrawn = false;

    // Setup canvas resolution
    function setupCanvas() {
        const rect = canvas.getBoundingClientRect();
        const dpr  = window.devicePixelRatio || 1;
        canvas.width  = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth   = 2;
        ctx.lineCap     = 'round';
        ctx.lineJoin    = 'round';
    }
    setupCanvas();

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches ? e.touches[0] : e;
        return { x: touch.clientX - rect.left, y: touch.clientY - rect.top };
    }

    function startDraw(e) {
        e.preventDefault();
        drawing = true;
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        hasDrawn = true;
        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function endDraw(e) {
        if (!drawing) return;
        e.preventDefault();
        drawing = false;
        ctx.closePath();
        updateHiddenInput();
    }

    canvas.addEventListener('mousedown',  startDraw);
    canvas.addEventListener('mousemove',  draw);
    canvas.addEventListener('mouseup',    endDraw);
    canvas.addEventListener('mouseleave', endDraw);
    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove',  draw, { passive: false });
    canvas.addEventListener('touchend',   endDraw);

    clearBtn.addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
        hiddenInput.value = '';
    });

    function updateHiddenInput() {
        if (hasDrawn) {
            hiddenInput.value = canvas.toDataURL('image/png');
        }
    }

    // --- Mode switching (saved vs manual) ---
    function updateMode() {
        if (modeSaved && modeSaved.checked) {
            // Gunakan tanda tangan tersimpan
            canvasWrapper.style.display = 'none';
            hiddenInput.value = savedImg ? savedImg.src : '';
        } else {
            // Mode manual / tidak ada saved
            canvasWrapper.style.display = '';
            if (hasDrawn) {
                hiddenInput.value = canvas.toDataURL('image/png');
            } else {
                hiddenInput.value = '';
            }
        }
    }

    if (modeSaved) {
        modeSaved.addEventListener('change', updateMode);
        modeManual.addEventListener('change', updateMode);
    }

    // Set initial value
    updateMode();

    // --- Form validation ---
    const form = canvas.closest('form');
    form.addEventListener('submit', function (e) {
        if (!hiddenInput.value) {
            e.preventDefault();
            alert('Silakan tanda tangan terlebih dahulu sebelum menyimpan rekomendasi.');
            document.getElementById('signatureSection').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endsection
