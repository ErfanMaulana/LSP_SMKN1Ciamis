@extends('asesor.layout')

@section('title', 'Asesmen Mandiri - ' . $asesi->nama)
@section('page-title', 'Asesmen Mandiri')

@section('styles')
<style>
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: #2563eb; text-decoration: none; font-size: 14px;
        font-weight: 500; margin-bottom: 18px;
    }
    .back-btn:hover { color: #1d4ed8; }

    .header-card {
        background: #0073bd;
        border-radius: 14px; padding: 24px 28px; color: white; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;
        flex-wrap: wrap;
    }
    .header-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .header-card .meta { font-size: 13px; opacity: 0.85; }
    .header-card .meta span { margin-right: 16px; }

    .info-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; margin-bottom: 24px; overflow: hidden;
    }
    .info-header {
        background: #f8fafc; padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .info-header h3 { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0; }

    .info-row {
        display: grid; grid-template-columns: 200px 1fr;
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
        align-items: start; gap: 16px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 13px; font-weight: 600; color: #64748b; }
    .info-value { font-size: 13px; color: #334155; font-weight: 500; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
    }
    .status-selesai { background: #d1fae5; color: #059669; }
    .status-sedang { background: #fef3c7; color: #b45309; }
    .status-belum { background: #fee2e2; color: #dc2626; }

    .rekom-box {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; margin-bottom: 24px;
        padding: 20px;
    }
    .rekom-title {
        font-size: 14px; font-weight: 700; color: #1e293b;
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .rekom-content {
        padding: 14px; border-radius: 8px; font-size: 13px;
    }
    .rekom-lanjut {
        background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;
    }
    .rekom-tidak {
        background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
    }
    .rekom-empty {
        background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-style: italic;
    }

    .action-row {
        display: flex; justify-content: flex-end;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .header-card {
            padding: 16px;
            margin-bottom: 16px;
        }
        .header-card h2 { font-size: 16px; }
        .header-card .meta {
            display: grid; gap: 4px;
        }
        .header-card .meta span { margin-right: 0; }

        .info-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }
        .info-label { font-size: 12px; }
    }

    @media print {
        .back-btn, aside, .topbar { display: none !important; }
        .main-content { margin-left: 0 !important; }
    }
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

    .nilai-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .nilai-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 18px;
    }
    .nilai-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }
    .nilai-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #64748b;
    }
    .nilai-table-wrap {
        overflow-x: auto;
    }
    .nilai-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .nilai-table thead th {
        text-align: left;
        background: #eff6ff;
        color: #1e3a8a;
        border-bottom: 1px solid #bfdbfe;
        padding: 10px 12px;
        font-weight: 700;
    }
    .nilai-table tbody td {
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 12px;
        color: #334155;
        vertical-align: top;
    }
    .nilai-table tfoot td {
        padding: 10px 12px;
        border-top: 2px solid #cbd5e1;
        background: #f8fafc;
        font-weight: 700;
        color: #0f172a;
    }
    .nilai-point {
        font-weight: 700;
        text-align: center;
        width: 90px;
    }
    .nilai-point.ok { color: #059669; }
    .nilai-point.no { color: #dc2626; }
    .nilai-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 12px 18px 16px;
    }
    .nilai-chip {
        background: #eef2ff;
        color: #3730a3;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 999px;
    }

    .form-table-wrap {
        overflow-x: auto;
    }

    .rekom-table,
    .sign-table {
        width: 100%;
    }

    .recommend-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 20px;
        margin-bottom: 24px;
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
        gap: 12px;
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
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .signature-section {
        margin-top: 16px;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
        width: 320px;
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
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .saved-signature img {
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

    .recommend-note {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
    }

    @media (max-width: 768px) {
        .recommend-grid {
            grid-template-columns: 1fr;
        }
    }

</style>
@endsection

@section('content')

<a href="{{ route('asesor.asesi.terkait') }}" class="back-btn">
    <i class="bi bi-arrow-left"></i> Kembali ke Asesi
</a>

{{-- Header Info Asesi --}}
<div class="header-card">
    <div style="display:flex;gap:16px;align-items:center;">
        <div style="width:96px;height:96px;border-radius:12px;overflow:hidden;border:1px solid rgba(0,0,0,0.06);background:white;flex-shrink:0;">
            @if(!empty($asesi->foto) || !empty($asesi->avatar))
                <img src="{{ $asesi->foto ?? $asesi->avatar }}" alt="Foto {{ $asesi->nama }}" style="width:100%;height:100%;object-fit:cover;display:block;">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#94a3b8;font-weight:700;font-size:20px;">{{ strtoupper(substr($asesi->nama,0,1) ?? 'A') }}</div>
            @endif
        </div>

        <div>
            <h2><i class="bi bi-person-lines-fill"></i> {{ $asesi->nama }}</h2>
            <div class="meta">
                <span><i class="bi bi-credit-card"></i> NIK: {{ $asesi->NIK }}</span>
                <span><i class="bi bi-envelope"></i> Email: {{ $asesi->asesi_email ?? '—' }}</span>
            </div>
            <div class="meta" style="margin-top:8px;">
                <span><i class="bi bi-mortarboard"></i> {{ $asesi->jurusan?->nama_jurusan ?? '—' }}</span>
                <span><i class="bi bi-award"></i> {{ $skema->nama_skema ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Data Asesi Card --}}
<div class="info-card">
    <div class="info-header">
        <h3><i class="bi bi-info-circle"></i> Informasi Asesi</h3>
    </div>
    
    <div class="info-row">
        <div class="info-label">Nama Lengkap</div>
        <div class="info-value">{{ $asesi->nama }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">NIK</div>
        <div class="info-value">{{ $asesi->NIK }}</div>
    </div>

    @if($asesi->no_reg)
    <div class="info-row">
        <div class="info-label">No. Registrasi</div>
        <div class="info-value">{{ $asesi->no_reg }}</div>
    </div>
    @endif

    @if($asesi->asesi_email)
    <div class="info-row">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $asesi->asesi_email }}</div>
    </div>
    @endif

    <div class="info-row">
        <div class="info-label">Jurusan</div>
        <div class="info-value">{{ $asesi->jurusan?->nama_jurusan ?? '—' }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">Program Keahlian</div>
        <div class="info-value">{{ $asesi->jurusan?->kode_jurusan ?? '—' }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">Skema Kompetensi</div>
        <div class="info-value">{{ $skema->nama_skema ?? '—' }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">Status Asesmen</div>
        <div class="info-value">
            @include('components.asesi-status', ['pivot' => $pivot])
        </div>
    </div>

    <div class="info-row">
        <div class="info-label">Periode Asesmen</div>
        <div class="info-value">
            @if($pivot->tanggal_mulai)
                {{ \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d/m/Y H:i') }}
            @endif
            @if($pivot->tanggal_selesai)
                s/d {{ \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d/m/Y H:i') }}
            @else
                @if($pivot->tanggal_mulai)
                    <span style="color:#94a3b8;">(Belum selesai)</span>
                @else
                    —
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Transkrip Nilai: tampilkan ringkasan per unit/elemen jika data jawaban tersedia --}}
<div class="info-card">
    <div class="info-header">
        <h3><i class="bi bi-journal-text"></i> Transkrip Nilai</h3>
    </div>

    @if(isset($skema) && $skema->units->count())
        @foreach($skema->units as $unitIdx => $unit)
            <div style="padding:12px 20px;border-bottom:1px solid #f1f5f9;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                    <div>
                        <div style="font-weight:700;color:#0f172a;">{{ ($unitIdx+1) }}. {{ $unit->judul_unit }}</div>
                        <div style="font-size:12px;color:#64748b;">{{ $unit->kode_unit }}</div>
                    </div>
                    <div style="font-size:13px;color:#64748b;">Unit skor: {{-- optional aggregate if available --}}</div>
                </div>

                @if($unit->elemens->count())
                <div style="margin-top:10px;overflow:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f8fafc;color:#0f172a;font-weight:700;">
                                <th style="text-align:left;padding:8px;border:1px solid #eef2ff;">Elemen</th>
                                <th style="text-align:left;padding:8px;border:1px solid #eef2ff;">Status</th>
                                <th style="text-align:left;padding:8px;border:1px solid #eef2ff;">Nilai / Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unit->elemens as $elIdx => $elemen)
                                @php $jawaban = $answers->get($elemen->id) ?? null; @endphp
                                <tr>
                                    <td style="padding:8px;border:1px solid #f1f5f9;vertical-align:top;">{{ $elIdx+1 }}. {{ $elemen->nama_elemen }}</td>
                                    <td style="padding:8px;border:1px solid #f1f5f9;vertical-align:top;">
                                        @if($jawaban)
                                            @if(isset($jawaban->status) && $jawaban->status === 'K')
                                                <span class="status-badge status-selesai">Kompeten</span>
                                            @elseif(isset($jawaban->status) && $jawaban->status === 'BK')
                                                <span class="status-badge status-belum">Belum Kompeten</span>
                                            @else
                                                <span class="status-badge status-sedang">{{ $jawaban->status }}</span>
                                            @endif
                                        @else
                                            <span class="status-badge status-belum">Belum Dijawab</span>
                                        @endif
                                    </td>
                                    <td style="padding:8px;border:1px solid #f1f5f9;vertical-align:top;">
                                        @if($jawaban)
                                            @if(isset($jawaban->nilai))
                                                <strong>{{ $jawaban->nilai }}</strong>
                                            @endif
                                            @if(isset($jawaban->catatan) && $jawaban->catatan)
                                                <div style="color:#64748b;margin-top:6px;white-space:pre-wrap;">{{ $jawaban->catatan }}</div>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        @endforeach
    @else
        <div style="padding:14px 20px;color:#64748b;">Belum ada skema/unit terkait untuk menampilkan transkrip nilai.</div>
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
        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;">
            <div>
                <div class="info-label">Tanda Tangan Asesi</div>
                @if($pivot->tanda_tangan)
                    <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi" class="signature-img">
                @else
                    <div class="recommend-note">Belum ada tanda tangan asesi.</div>
                @endif
            </div>
            <div>
                <div class="info-label">Tanda Tangan Asesor</div>
                @if($pivot->tanda_tangan_asesor)
                    <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda Tangan Asesor" class="signature-img">
                @else
                    <div class="recommend-note">Belum ada tanda tangan asesor.</div>
                @endif
            </div>
        </div>
    </div>
@elseif($pivot->status === 'selesai')
    <form method="POST" action="{{ route('asesor.asesi.recommend', $asesi->NIK) }}" id="recommendForm">
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
                <button type="submit" class="btn btn-primary">
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

{{-- Action Buttons --}}
<div class="action-row">
    <a href="{{ route('asesor.asesi.terkait') }}"
       style="padding: 10px 20px; border-radius: 8px; border: 1.5px solid #e2e8f0; background: white; color: #475569; font-size: 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

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
    const placeholder = document.getElementById('signaturePlaceholder');
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

