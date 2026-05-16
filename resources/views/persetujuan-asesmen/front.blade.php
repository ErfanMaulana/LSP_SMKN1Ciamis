@php
    $item = $item ?? null;
    $role = $role ?? 'asesi';
    $skema = $skema ?? null;
    $layout = $role === 'asesor' ? 'asesor.layout' : 'asesi.layout';
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

    .doc-wrap {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 18px;
        overflow-x: auto;
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
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-top: 16px;
        overflow: hidden;
    }

    .panel-title {
        margin: 0;
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-body {
        padding: 16px 18px;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .signature-box {
        text-align: center;
    }

    .signature-box .frame {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px;
        width: 220px;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        margin: 0 auto 8px auto;
    }

    .signature-box img {
        max-width: 100%;
        max-height: 120px;
    }

    .signature-box .meta {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.35;
    }

    .signature-canvas-wrapper {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 14px;
        overflow: hidden;
        width: 100%;
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
        aspect-ratio: 1 / 1;
    }

    @media (max-width: 640px) {
        .signature-canvas-wrapper {
            max-width: 320px;
        }
    }

    .signature-canvas {
        display: block;
        width: 100%;
        height: 100%;
        cursor: crosshair;
    }

    .signature-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
        color: #cbd5e1;
    }

    .signature-placeholder i {
        font-size: 42px;
        display: block;
        margin-bottom: 8px;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
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

    .error-text {
        margin-top: 6px;
        font-size: 12px;
        color: #dc2626;
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
        background: #0061A5;
        color: #ffffff;
        height: 42px;
        padding: 0 16px;
        font-size: 14px;
    }

    .btn-clear-signature {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
    }

    .notice.small {
        margin-top: 12px;
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    @if($role === 'asesor')
        <a href="{{ route('asesor.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('asesor.persetujuan.front.asesor.export', ['asesiNik' => $asesiNik, 'skemaId' => $skema->id]) }}" class="btn btn-primary" target="_blank">
            <i class="bi bi-download"></i> Export FR.AK.01 (.doc)
        </a>
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

<div class="doc-wrap">
    <table class="doc">
        <tr>
            <td class="title no-border" colspan="4">{{ $item->kode_form }} &nbsp;&nbsp; {{ $item->judul_form }}</td>
        </tr>
        <tr>
            <td colspan="4">{{ $item->pengantar }}</td>
        </tr>

        <tr>
            <td style="width:30%;">Skema Sertifikasi<br>{{ $item->kategori_skema }}</td>
            <td style="width:12%;">Judul</td>
            <td style="width:2%;">:</td>
            <td>{{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $item->nomor_skema ?: ($skema->nomor_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td colspan="2">:</td>
            <td>{{ $item->tuk }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td colspan="2">:</td>
            <td>{{ $item->nama_asesor }}</td>
        </tr>
        <tr>
            <td>Nama Asesi</td>
            <td colspan="2">:</td>
            <td>{{ $item->nama_asesi }}</td>
        </tr>

        <tr>
            <td>Bukti yang akan dikumpulkan:</td>
            <td colspan="3">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;">
                    <div><span class="check">{{ $item->bukti_verifikasi_portofolio ? 'V' : '' }}</span>Hasil Verifikasi Portofolio</div>
                    <div><span class="check">{{ $item->bukti_reviu_produk ? 'V' : '' }}</span>Hasil Reviu Produk</div>
                    <div><span class="check">{{ $item->bukti_observasi_langsung ? 'V' : '' }}</span>Hasil Observasi Langsung</div>
                    <div><span class="check">{{ $item->bukti_kegiatan_terstruktur ? 'V' : '' }}</span>Hasil Kegiatan Terstruktur</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_lisan ? 'V' : '' }}</span>Hasil Pertanyaan Lisan</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_tertulis ? 'V' : '' }}</span>Hasil Pertanyaan Tertulis</div>
                    <div><span class="check">{{ $item->bukti_lainnya ? 'V' : '' }}</span>Lainnya {{ $item->bukti_lainnya_keterangan ? ': ' . $item->bukti_lainnya_keterangan : '' }}</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_wawancara ? 'V' : '' }}</span>Hasil Pertanyaan Wawancara</div>
                </div>
            </td>
        </tr>

        <tr>
            <td rowspan="3">Pelaksanaan asesmen disepakati pada:</td>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td>{{ $item->hari_tanggal }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ $item->waktu }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td>:</td>
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
        <tr>
            <td colspan="4" style="padding: 16px; text-align: center; background: #f8fafc;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div style="text-align: center;">
                        <h4 style="margin: 0 0 12px; color: #0f172a; font-size: 14px;">Tanda Tangan Asesor</h4>
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; min-height: 120px; display: flex; align-items: center; justify-content: center; background: white;">
                            @if($item->ttd_asesor_file)
                                <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor" style="max-width: 100%; max-height: 120px;">
                            @else
                                <span style="color: #94a3b8; font-size: 13px;">Belum ditandatangani</span>
                            @endif
                        </div>
                        <p style="margin: 8px 0 0; font-size: 13px; color: #64748b;">
                            <strong>{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</strong><br>
                            {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                        </p>
                    </div>

                    <div style="text-align: center;">
                        <h4 style="margin: 0 0 12px; color: #0f172a; font-size: 14px;">Tanda Tangan Asesi</h4>
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; min-height: 120px; display: flex; align-items: center; justify-content: center; background: white;">
                            @if($item->ttd_asesi_file)
                                <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi" style="max-width: 100%; max-height: 120px;">
                            @else
                                <span style="color: #94a3b8; font-size: 13px;">Belum ditandatangani</span>
                            @endif
                        </div>
                        <p style="margin: 8px 0 0; font-size: 13px; color: #64748b;">
                            <strong>{{ $item->ttd_asesi_nama ?: 'Nama Asesi' }}</strong><br>
                            {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    @if($item->catatan_footer)
        <div class="notes"><em>{{ $item->catatan_footer }}</em></div>
    @endif
</div>

<div class="panel-card">
    <h3 class="panel-title"><i class="bi bi-pen"></i> Tanda Tangan</h3>
    <div class="panel-body">
        <div class="signature-grid">
            <div class="signature-box">
                <h4 style="margin:0 0 12px; color:#0f172a; font-size:14px;">Tanda Tangan Asesor</h4>
                <div class="frame">
                    @if($item->ttd_asesor_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor">
                    @else
                        <span style="color:#94a3b8; font-size:13px;">Belum ditandatangani</span>
                    @endif
                </div>
                <p class="meta">
                    <strong>{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</strong><br>
                    {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                </p>

                @if($role === 'asesor' && empty($item->ttd_asesor_file))
                    <form method="POST" action="{{ route('asesor.persetujuan.front.asesor.sign', $item->id) }}" class="form-grid" id="formTandaTanganAsesor">
                        @csrf
                        
                        <div style="margin-top:16px; padding:12px; background:#f0f9ff; border:1px solid #bfdbfe; border-radius:8px;">
                            <label style="display:block; margin-bottom:12px; font-weight:600; color:#1e40af;">
                                <i class="bi bi-clipboard-check"></i> Ceklis Bukti yang Sudah Dikumpulkan
                            </label>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_verifikasi_portofolio" value="1" {{ $item->bukti_verifikasi_portofolio ? 'checked' : '' }}>
                                    <span>Hasil Verifikasi Portofolio</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_reviu_produk" value="1" {{ $item->bukti_reviu_produk ? 'checked' : '' }}>
                                    <span>Hasil Reviu Produk</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_observasi_langsung" value="1" {{ $item->bukti_observasi_langsung ? 'checked' : '' }}>
                                    <span>Hasil Observasi Langsung</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_kegiatan_terstruktur" value="1" {{ $item->bukti_kegiatan_terstruktur ? 'checked' : '' }}>
                                    <span>Hasil Kegiatan Terstruktur</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_pertanyaan_lisan" value="1" {{ $item->bukti_pertanyaan_lisan ? 'checked' : '' }}>
                                    <span>Hasil Pertanyaan Lisan</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_pertanyaan_tertulis" value="1" {{ $item->bukti_pertanyaan_tertulis ? 'checked' : '' }}>
                                    <span>Hasil Pertanyaan Tertulis</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_pertanyaan_wawancara" value="1" {{ $item->bukti_pertanyaan_wawancara ? 'checked' : '' }}>
                                    <span>Hasil Pertanyaan Wawancara</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="bukti_lainnya" value="1" {{ $item->bukti_lainnya ? 'checked' : '' }} id="buktiLainnyaCheckbox">
                                    <span>Lainnya</span>
                                </label>
                            </div>
                            <div id="buktiLainnyaKeteranganDiv" style="margin-top:12px; display:{{ $item->bukti_lainnya ? 'block' : 'none' }};">
                                <label style="display:block; margin-bottom:6px; font-size:13px; color:#475569;">Keterangan Lainnya</label>
                                <input type="text" name="bukti_lainnya_keterangan" placeholder="Jelaskan bukti lainnya" value="{{ $item->bukti_lainnya_keterangan }}" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">
                            </div>
                        </div>
                        
                        <div class="signature-canvas-wrapper" id="signatureWrapperAsesor" style="margin-top:16px;">
                            <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                            <div class="signature-placeholder">
                                <i class="bi bi-pen"></i>
                                <span>Tanda tangan di sini</span>
                            </div>
                        </div>
                        <div class="signature-actions">
                            <div class="signature-date">
                                <i class="bi bi-calendar3"></i>
                                Tanggal: <strong id="signatureDateAsesor">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                            </div>
                            <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                                <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                            </button>
                        </div>
                        <div class="field">
                            <label>Nama Asesor</label>
                            <input type="text" name="ttd_asesor_nama" value="{{ old('ttd_asesor_nama', $item->ttd_asesor_nama ?: $item->nama_asesor) }}">
                            @error('ttd_asesor_nama')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Tanggal Tanda Tangan</label>
                            <input type="date" name="ttd_asesor_tanggal" value="{{ old('ttd_asesor_tanggal', $item->ttd_asesor_tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                            @error('ttd_asesor_tanggal')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <input type="hidden" name="ttd_asesor_file" id="ttdAsesorFileInput">
                        <button class="btn-submit" type="submit"><i class="bi bi-check2-circle"></i> Simpan Tanda Tangan Asesor</button>
                    </form>
                @endif
            </div>

            <div class="signature-box">
                <h4 style="margin:0 0 12px; color:#0f172a; font-size:14px;">Tanda Tangan Asesi</h4>
                <div class="frame">
                    @if($item->ttd_asesi_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi">
                    @else
                        <span style="color:#94a3b8; font-size:13px;">Belum ditandatangani</span>
                    @endif
                </div>
                <p class="meta">
                    <strong>{{ $item->ttd_asesi_nama ?: 'Nama Asesi' }}</strong><br>
                    {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                </p>

                @if($role !== 'asesor' && !$item->ttd_asesi_file)
                    @if(empty($item->ttd_asesor_file) && (empty($item->ttd_asesor_nama) || empty($item->ttd_asesor_tanggal)))
                        <div class="notice warning small">Asesor belum menandatangani form ini. Tanda tangan asesi belum dapat dilakukan.</div>
                    @elseif($item->ttd_asesor_file || (!empty($item->ttd_asesor_nama) && !empty($item->ttd_asesor_tanggal)))
                        <form method="POST" action="{{ route('asesi.persetujuan.front.asesi.sign', $item->id) }}" class="form-grid" id="formTandaTanganAsesi" style="margin-top:16px;">
                            @csrf
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
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const asesiCanvas = document.getElementById('signatureCanvasAsesi');
    if (asesiCanvas) {
        const asesiWrapper = document.getElementById('signatureWrapperAsesi');
        const asesiClear = document.getElementById('clearSignatureAsesi');
        const asesiDateInput = document.getElementById('ttdAsesiTanggalInput');
        const asesiHidden = document.getElementById('ttdAsesiFileInput');
        const asesiCtx = asesiCanvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        const resizeAsesi = () => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = asesiCanvas.getBoundingClientRect();
            asesiCanvas.width = rect.width * ratio;
            asesiCanvas.height = rect.height * ratio;
            asesiCtx.setTransform(1, 0, 0, 1, 0, 0);
            asesiCtx.scale(ratio, ratio);
            asesiCtx.lineCap = 'round';
            asesiCtx.lineJoin = 'round';
            asesiCtx.strokeStyle = '#0f172a';
            asesiCtx.lineWidth = 2;
        };

        const pos = (event) => {
            const rect = asesiCanvas.getBoundingClientRect();
            const point = event.touches && event.touches[0] ? event.touches[0] : event;
            return { x: point.clientX - rect.left, y: point.clientY - rect.top };
        };

        const start = (event) => { event.preventDefault(); isDrawing = true; const p = pos(event); lastX = p.x; lastY = p.y; };
        const move = (event) => { event.preventDefault(); if (!isDrawing) return; const p = pos(event); asesiCtx.beginPath(); asesiCtx.moveTo(lastX, lastY); asesiCtx.lineTo(p.x, p.y); asesiCtx.stroke(); lastX = p.x; lastY = p.y; };
        const stop = () => { isDrawing = false; };

        if (asesiClear) {
            asesiClear.addEventListener('click', () => {
                asesiCtx.clearRect(0, 0, asesiCanvas.width, asesiCanvas.height);
                if (asesiDateInput) asesiDateInput.value = '';
                if (asesiHidden) asesiHidden.value = '';
            });
        }

        const asesiForm = document.getElementById('formTandaTanganAsesi');
        if (asesiForm) {
            asesiForm.addEventListener('submit', function() {
                if (asesiHidden) {
                    asesiHidden.value = asesiCanvas.toDataURL('image/png');
                }
            });
        }

        asesiCanvas.addEventListener('mousedown', start);
        asesiCanvas.addEventListener('mousemove', move);
        asesiCanvas.addEventListener('mouseup', stop);
        asesiCanvas.addEventListener('mouseleave', stop);
        asesiCanvas.addEventListener('touchstart', start, { passive: false });
        asesiCanvas.addEventListener('touchmove', move, { passive: false });
        asesiCanvas.addEventListener('touchend', stop);
        window.addEventListener('resize', resizeAsesi);
        resizeAsesi();
    }

    const asesorCanvas = document.getElementById('signatureCanvasAsesor');
    if (asesorCanvas) {
        const asesorWrapper = document.getElementById('signatureWrapperAsesor');
        const asesorClear = document.getElementById('clearSignatureAsesor');
        const asesorDateInput = document.querySelector('input[name="ttd_asesor_tanggal"]');
        const asesorHidden = document.getElementById('ttdAsesorFileInput');
        const asesorCtx = asesorCanvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        const resizeAsesor = () => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = asesorCanvas.getBoundingClientRect();
            asesorCanvas.width = rect.width * ratio;
            asesorCanvas.height = rect.height * ratio;
            asesorCtx.setTransform(1, 0, 0, 1, 0, 0);
            asesorCtx.scale(ratio, ratio);
            asesorCtx.lineCap = 'round';
            asesorCtx.lineJoin = 'round';
            asesorCtx.strokeStyle = '#0f172a';
            asesorCtx.lineWidth = 2;
        };

        const pos = (event) => {
            const rect = asesorCanvas.getBoundingClientRect();
            const point = event.touches && event.touches[0] ? event.touches[0] : event;
            return { x: point.clientX - rect.left, y: point.clientY - rect.top };
        };

        const start = (event) => { event.preventDefault(); isDrawing = true; const p = pos(event); lastX = p.x; lastY = p.y; };
        const move = (event) => { event.preventDefault(); if (!isDrawing) return; const p = pos(event); asesorCtx.beginPath(); asesorCtx.moveTo(lastX, lastY); asesorCtx.lineTo(p.x, p.y); asesorCtx.stroke(); lastX = p.x; lastY = p.y; };
        const stop = () => { isDrawing = false; };

        if (asesorClear) {
            asesorClear.addEventListener('click', () => {
                asesorCtx.clearRect(0, 0, asesorCanvas.width, asesorCanvas.height);
                if (asesorDateInput && !asesorDateInput.value) asesorDateInput.value = '';
                if (asesorHidden) asesorHidden.value = '';
            });
        }

        const asesorForm = document.getElementById('formTandaTanganAsesor');
        if (asesorForm) {
            asesorForm.addEventListener('submit', function() {
                if (asesorHidden) {
                    asesorHidden.value = asesorCanvas.toDataURL('image/png');
                }
            });
        }

        asesorCanvas.addEventListener('mousedown', start);
        asesorCanvas.addEventListener('mousemove', move);
        asesorCanvas.addEventListener('mouseup', stop);
        asesorCanvas.addEventListener('mouseleave', stop);
        asesorCanvas.addEventListener('touchstart', start, { passive: false });
        asesorCanvas.addEventListener('touchmove', move, { passive: false });
        asesorCanvas.addEventListener('touchend', stop);
        window.addEventListener('resize', resizeAsesor);
        resizeAsesor();
    }

    // Handle "Lainnya" checkbox toggle
    const buktiLainnyaCheckbox = document.getElementById('buktiLainnyaCheckbox');
    const buktiLainnyaKeteranganDiv = document.getElementById('buktiLainnyaKeteranganDiv');
    if (buktiLainnyaCheckbox && buktiLainnyaKeteranganDiv) {
        buktiLainnyaCheckbox.addEventListener('change', function() {
            buktiLainnyaKeteranganDiv.style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>
@endsection
