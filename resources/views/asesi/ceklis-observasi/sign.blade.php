@extends('asesi.layout')

@section('title', 'Tanda Tangan Ceklis Observasi')
@section('page-title', 'Tanda Tangan Ceklis Observasi')

@section('content')
<style>
    .detail-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        padding: 18px;
        margin-bottom: 14px;
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .top-actions h2 {
        margin: 0;
        font-size: 20px;
        color: #0f172a;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0061A5; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .meta-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
    }

    .meta-item .label {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
    }

    .meta-item .value {
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
    }

    .table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 860px;
    }

    th, td {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        font-size: 13px;
        vertical-align: top;
    }

    th {
        background: #f8fafc;
        color: #334155;
        text-align: center;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .signature-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
    }

    .signature-frame {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #e6eef5;
        border-radius: 8px;
        padding: 12px;
        min-height: 120px;
        background: #ffffff;
        position: relative;
        margin-top: 8px;
    }

    .signature-canvas {
        width: 100%;
        height: 120px;
        display: block;
        border-radius: 6px;
        background: transparent;
    }

    .signature-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .signature-actions {
        margin-top: 8px;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-clear-signature {
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        color: #0f172a;
    }

    .unit-title {
        margin: 0 0 10px;
        font-size: 15px;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .meta-grid, .signature-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="top-actions">
    <h2>Form Ceklis Observasi Aktivitas Praktik</h2>
    <a href="{{ route('asesi.ceklis-observasi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-card">
    <div class="meta-grid">
        <div class="meta-item"><div class="label">Kode Form</div><div class="value">{{ $item->kode_form }}</div></div>
        <div class="meta-item"><div class="label">Judul Form</div><div class="value">{{ $item->judul_form }}</div></div>
        <div class="meta-item"><div class="label">Skema</div><div class="value">{{ $item->skema?->nama_skema }} ({{ $item->skema?->nomor_skema }})</div></div>
        <div class="meta-item"><div class="label">TUK / Tanggal</div><div class="value">{{ $item->tuk ?? '-' }} / {{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Nama Asesi</div><div class="value">{{ $asesi->nama }}</div></div>
        <div class="meta-item"><div class="label">Asesor</div><div class="value">{{ $item->ttd_asesor_nama ?: ($item->asesor?->nama ?? '-') }}</div></div>
    </div>
</div>

@forelse($detailsByUnit as $unitDetails)
    @php $unit = $unitDetails->first()?->unit; @endphp
    <div class="detail-card">
        <h3 class="unit-title">{{ $unit?->kode_unit }} - {{ $unit?->judul_unit }}</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th style="width:220px;">Elemen</th>
                        <th>Kriteria Unjuk Kerja</th>
                        <th style="width:110px;">Pencapaian</th>
                        <th style="width:220px;">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unitDetails as $idx => $detail)
                        <tr>
                            <td style="text-align:center;">{{ $idx + 1 }}</td>
                            <td>{{ $detail->elemen?->nama_elemen ?? '-' }}</td>
                            <td>{{ $detail->kriteria?->deskripsi_kriteria ?? '-' }}</td>
                            <td style="text-align:center;">{{ strtoupper($detail->pencapaian ?? '-') }}</td>
                            <td>{{ $detail->penilaian_lanjut ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="detail-card">Belum ada detail ceklis.</div>
@endforelse

<div class="detail-card">
    <h3 style="margin:0 0 12px;font-size:16px;">Tanda Tangan</h3>

    <div class="signature-grid">
        <div class="signature-box">
            <h4 style="margin:0 0 8px;font-size:14px;">Tanda Tangan Asesor</h4>
            <div class="signature-frame">
                @if($item->ttd_asesor_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor" style="max-width:100%;max-height:120px;">
                @else
                    <span style="color:#94a3b8;font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>
            <div style="font-size:13px;color:#334155;">
                <strong>{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</strong><br>
                {{ $item->ttd_asesor_tanggal?->translatedFormat('d F Y') ?: 'Tanggal Tanda Tangan' }}
            </div>
        </div>

        <div class="signature-box">
            <h4 style="margin:0 0 8px;font-size:14px;">Tanda Tangan Asesi</h4>
            <div class="signature-frame">
                @if($item->ttd_asesi_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi" style="max-width:100%;max-height:120px;">
                @else
                    <span style="color:#94a3b8;font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>

            @if(!$item->ttd_asesi_file)
                <form method="POST" action="{{ route('asesi.ceklis-observasi.sign', $item->id) }}" id="asesiCeklisSignForm">
                    @csrf
                    <div class="signature-canvas-wrapper">
                        <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                        <div class="signature-placeholder">
                            <i class="bi bi-pen"></i>
                            <span>Tanda tangan di sini</span>
                        </div>
                    </div>

                    <div class="signature-actions">
                        <button type="button" class="btn-clear-signature" id="clearSignatureAsesi">Hapus Tanda Tangan</button>
                        <div style="font-size:13px;color:#64748b;">Tanggal: <strong>{{ now()->translatedFormat('d M Y') }}</strong></div>
                    </div>

                    <div style="margin-top:10px;">
                        <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Tanggal Tanda Tangan</label>
                        <input type="date" name="ttd_asesi_tanggal" value="{{ old('ttd_asesi_tanggal', now()->format('Y-m-d')) }}" required style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 10px;">
                    </div>

                    <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput">

                    <button type="submit" class="btn btn-primary" style="margin-top:12px;">
                        <i class="bi bi-check2-circle"></i> Simpan Tanda Tangan
                    </button>
                </form>
            @else
                <div style="font-size:13px;color:#334155;">
                    <strong>{{ $item->ttd_asesi_nama ?: $asesi->nama }}</strong><br>
                    {{ $item->ttd_asesi_tanggal?->translatedFormat('d F Y') ?: '-' }}
                </div>
            @endif
        </div>
    </div>
</div>

@if(!$item->ttd_asesi_file)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signatureCanvasAsesi');
    const clearBtn = document.getElementById('clearSignatureAsesi');
    const hidden = document.getElementById('ttdAsesiFileInput');
    const form = document.getElementById('asesiCeklisSignForm');

    if (!canvas || !hidden || !form) return;

    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

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

    const start = (e) => { e.preventDefault(); isDrawing = true; const p = pos(e); lastX = p.x; lastY = p.y; };
    const move = (e) => { e.preventDefault(); if (!isDrawing) return; const p = pos(e); ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(p.x, p.y); ctx.stroke(); lastX = p.x; lastY = p.y; };
    const stop = () => { isDrawing = false; };

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hidden.value = '';
        });
    }

    form.addEventListener('submit', function () {
        hidden.value = canvas.toDataURL('image/png');
    });

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    canvas.addEventListener('mouseup', stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', stop);
    window.addEventListener('resize', resize);
    resize();
});
</script>
@endif
@endsection
