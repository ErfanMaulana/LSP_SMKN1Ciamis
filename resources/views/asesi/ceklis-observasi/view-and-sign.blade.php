@extends('asesi.layout')

@section('title', 'Ceklis Observasi Aktivitas Praktik')
@section('page-title', 'Ceklis Observasi Aktivitas Praktik')

@section('content')
<style>
    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin-bottom: 16px;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .meta-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
    }

    .meta-item .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        margin-bottom: 4px;
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
        margin-bottom: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
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
        font-weight: 700;
        text-align: center;
    }

    .unit-title {
        font-weight: 700;
        padding: 10px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .rekomendasi-box {
        background: #f0f4f8;
        border-left: 4px solid #0073bd;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .rekomendasi-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .rekomendasi-value {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .signature-section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        margin-top: 14px;
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
        margin-bottom: 14px;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
        max-width: 260px;
        margin: 0 auto;
        aspect-ratio: 1 / 1;
    }

    .signature-canvas-wrapper.active {
        border-color: #0073bd;
        background: #fff;
    }

    .signature-canvas-wrapper.has-signature {
        border-style: solid;
        border-color: #0073bd;
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
        gap: 8px;
        flex-wrap: wrap;
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

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
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

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .alert-success {
        background: #d1f2eb;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    @media (max-width: 768px) {
        .meta-grid {
            grid-template-columns: 1fr;
        }

        .signature-canvas-wrapper {
            max-width: 180px;
        }
    }
</style>

<div class="detail-card">
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 style="margin: 0 0 20px; font-size: 22px; font-weight: 700; color: #0f172a;">
        {{ $ceklis->judul_form }}
    </h2>

    <div class="meta-grid">
        <div class="meta-item">
            <div class="label">Skema Sertifikasi</div>
            <div class="value">{{ $ceklis->skema->nama_skema }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Nomor Skema</div>
            <div class="value">{{ $ceklis->skema->nomor_skema }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Nama Asesi</div>
            <div class="value">{{ $account->nama }}</div>
        </div>
        <div class="meta-item">
            <div class="label">TUK</div>
            <div class="value">{{ $ceklis->tuk ?? '-' }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Tanggal Penilaian</div>
            <div class="value">{{ $ceklis->tanggal ? $ceklis->tanggal->format('d/m/Y') : '-' }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Asesor</div>
            <div class="value">{{ $ceklis->asesor->nama ?? '-' }}</div>
        </div>
    </div>

    <div class="rekomendasi-box">
        <div class="rekomendasi-label">REKOMENDASI:</div>
        <div class="rekomendasi-value">
            @if ($ceklis->rekomendasi === 'kompeten')
                <span style="color: #059669;">✓ KOMPETEN</span>
            @else
                <span style="color: #dc2626;">✗ BELUM KOMPETEN</span>
            @endif
        </div>
    </div>

    @if ($detailsByUnit)
        <div class="table-wrap">
            <table>
                @foreach ($detailsByUnit as $unitGroup)
                    <thead>
                        <tr>
                            <td colspan="5" class="unit-title">
                                {{ $unitGroup['unit']->kode_unit }} - {{ $unitGroup['unit']->judul_unit }}
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th style="width: 200px;">Elemen</th>
                            <th>Kriteria Unjuk Kerja</th>
                            <th style="width: 100px;">Pencapaian</th>
                            <th style="width: 150px;">Penilaian Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unitGroup['items'] as $index => $item)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td>{{ $item['elemen']->nama_elemen }}</td>
                                <td>{{ $item['kriteria']->deskripsi_kriteria }}</td>
                                <td style="text-align: center;">
                                    @if ($item['pencapaian'] === 'ya')
                                        <span style="color: #059669; font-weight: 600;">Ya</span>
                                    @elseif ($item['pencapaian'] === 'tidak')
                                        <span style="color: #dc2626; font-weight: 600;">Tidak</span>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>{{ $item['penilaian_lanjut'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endforeach
            </table>
        </div>
    @endif
</div>

<div class="detail-card">
    <form method="POST" action="{{ route('asesi.ceklis-observasi.sign', $ceklis->id) }}" id="signForm">
        @csrf

        <div class="signature-section">
            <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
            <p class="signature-subtitle">Dengan menandatangani, asesi menyatakan telah menerima dan menyetujui hasil penilaian di atas.</p>

            <div class="signature-canvas-wrapper" id="signatureWrapper">
                <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                <div class="signature-placeholder">
                    <i class="bi bi-pen"></i>
                    <span>Tanda tangan di sini</span>
                </div>
            </div>

            <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $ceklis->ttd_asesi_nama ?? '' }}">
            <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ $ceklis->ttd_asesi_tanggal ? $ceklis->ttd_asesi_tanggal->format('Y-m-d') : '' }}">

            <div class="signature-actions">
                <div class="signature-date">
                    <i class="bi bi-calendar3"></i>
                    Tanggal: <strong id="signatureDate">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                </div>
                <button type="button" class="btn-clear-signature" id="clearSignature">
                    <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                </button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Tandatangani & Simpan</button>
            <a href="{{ route('asesi.dashboard') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Kembali</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signatureCanvas');
    const wrapper = document.getElementById('signatureWrapper');
    const clearBtn = document.getElementById('clearSignature');
    const ttdAsesiNamaInput = document.getElementById('ttdAsesiNamaInput');
    const ttdAsesiTanggalInput = document.getElementById('ttdAsesiTanggalInput');
    const signForm = document.getElementById('signForm');

    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let hasSignature = false;
    let lastX = 0;
    let lastY = 0;

    const updateCanvasSize = () => {
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

    const getPos = (event) => {
        const rect = canvas.getBoundingClientRect();
        const point = event.touches && event.touches[0] ? event.touches[0] : event;
        return {
            x: point.clientX - rect.left,
            y: point.clientY - rect.top,
        };
    };

    const fillSignatureMeta = () => {
        if (!ttdAsesiNamaInput.value) {
            ttdAsesiNamaInput.value = '{{ $account->nama }}';
        }

        if (!ttdAsesiTanggalInput.value) {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            ttdAsesiTanggalInput.value = `${yyyy}-${mm}-${dd}`;
        }
    };

    const startDrawing = (event) => {
        event.preventDefault();
        isDrawing = true;
        const pos = getPos(event);
        lastX = pos.x;
        lastY = pos.y;
        wrapper.classList.add('active');
    };

    const draw = (event) => {
        event.preventDefault();
        if (!isDrawing) return;

        const pos = getPos(event);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        lastX = pos.x;
        lastY = pos.y;

        if (!hasSignature) {
            hasSignature = true;
            wrapper.classList.add('has-signature');
        }

        fillSignatureMeta();
    };

    const stopDrawing = () => {
        isDrawing = false;
        wrapper.classList.remove('active');
    };

    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
        ttdAsesiNamaInput.value = '';
        ttdAsesiTanggalInput.value = '';
        wrapper.classList.remove('has-signature');
    });

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing);

    signForm.addEventListener('submit', function (e) {
        if (!hasSignature) {
            e.preventDefault();
            alert('Silakan tanda tangani terlebih dahulu');
            return;
        }
    });

    window.addEventListener('resize', updateCanvasSize);
    updateCanvasSize();

    if (ttdAsesiNamaInput.value || ttdAsesiTanggalInput.value) {
        wrapper.classList.add('has-signature');
    }
});
</script>
@endsection
