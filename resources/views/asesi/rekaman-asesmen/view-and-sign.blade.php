@extends('asesi.layout')

@section('title', 'Rekaman Asesmen Kompetensi (FR.AK.02)')
@section('page-title', 'Rekaman Asesmen Kompetensi')

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
        vertical-align: middle;
        text-align: center;
    }

    th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
    }

    td:nth-child(2) {
        text-align: left;
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
        cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='4' fill='%230073bd' stroke='white' stroke-width='2'/%3E%3Cpath d='M16 2v8M16 22v8M2 16h8M22 16h8' stroke='%230073bd' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") 16 16, crosshair;
        display: block;
        touch-action: none;
        user-select: none;
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
    <h2 style="margin: 0 0 20px; font-size: 22px; font-weight: 700; color: #0f172a;">
        {{ $item->judul_form }}
    </h2>

    <div class="meta-grid">
        <div class="meta-item">
            <div class="label">Skema Sertifikasi</div>
            <div class="value">{{ $item->skema->nama_skema }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Nomor Skema</div>
            <div class="value">{{ $item->skema->nomor_skema }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Nama Asesi</div>
            <div class="value">{{ $item->asesi?->nama ?? $account->nama }}</div>
        </div>
        <div class="meta-item">
            <div class="label">TUK</div>
            <div class="value">{{ $item->tuk ?? '-' }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Tanggal Asesmen</div>
            <div class="value">
                Mulai: {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }} | 
                Selesai: {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
            </div>
        </div>
        <div class="meta-item">
            <div class="label">Asesor Penilai</div>
            <div class="value">{{ $item->asesor->nama ?? '-' }}</div>
        </div>
    </div>

    <div class="rekomendasi-box">
        <div class="rekomendasi-label">REKOMENDASI KEPUTUSAN:</div>
        <div class="rekomendasi-value">
            @if ($item->rekomendasi === 'kompeten')
                <span style="color: #059669;">✓ KOMPETEN</span>
            @else
                <span style="color: #dc2626;">✗ BELUM KOMPETEN</span>
            @endif
        </div>
    </div>

    @if($item->tindak_lanjut)
    <div class="meta-item" style="margin-bottom: 12px;">
        <div class="label">Tindak Lanjut</div>
        <div class="value" style="font-weight: 500;">{{ $item->tindak_lanjut }}</div>
    </div>
    @endif

    @if($item->komentar_observasi)
    <div class="meta-item" style="margin-bottom: 16px;">
        <div class="label">Komentar / Observasi Asesor</div>
        <div class="value" style="font-weight: 500;">{{ $item->komentar_observasi }}</div>
    </div>
    @endif

    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 20px 0 10px;">Metode Asesmen per Unit Kompetensi</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No.</th>
                    <th style="text-align: left; min-width: 250px;">Unit Kompetensi</th>
                    <th>Observasi Demonstrasi</th>
                    <th>Portofolio</th>
                    <th>Pernyataan Pihak Ketiga</th>
                    <th>Pertanyaan Lisan</th>
                    <th>Pertanyaan Tertulis</th>
                    <th>Proyek Kerja</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $detail)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: left;">
                            <strong>{{ $detail->unit?->kode_unit }}</strong><br>
                            <span style="font-size: 12.5px; color: #475569;">{{ $detail->unit?->judul_unit }}</span>
                        </td>
                        <td>{!! $detail->observasi_demonstrasi ? '✓' : '-' !!}</td>
                        <td>{!! $detail->portofolio ? '✓' : '-' !!}</td>
                        <td>{!! $detail->pernyataan_pihak_ketiga ? '✓' : '-' !!}</td>
                        <td>{!! $detail->pertanyaan_lisan ? '✓' : '-' !!}</td>
                        <td>{!! $detail->pertanyaan_tertulis ? '✓' : '-' !!}</td>
                        <td>{!! $detail->proyek_kerja ? '✓' : '-' !!}</td>
                        <td>{!! $detail->lainnya ? '✓' : '-' !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="detail-card">
    <form method="POST" action="{{ route('asesi.rekaman-asesmen.sign', $item->id) }}" id="signForm">
        @csrf

        <div class="signature-section">
            <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
            <p class="signature-subtitle">Dengan menandatangani, asesi menyatakan telah menerima keputusan penilaian dan penjelasan serta umpan balik dari Asesor.</p>

            @if($item->ttd_asesi_file)
                {{-- Sudah ditandatangani --}}
                <div class="signature-canvas-wrapper has-signature" id="signatureWrapper" style="border-style: solid; border-color: #cbd5e1; background: #fff;">
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" class="signature-saved-img" id="savedSignatureImgAsesi" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; background:#fff; pointer-events:none;">
                </div>
                <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $item->ttd_asesi_nama ?? '' }}">
                <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ $item->ttd_asesi_tanggal ? $item->ttd_asesi_tanggal->format('Y-m-d') : '' }}">
                <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="{{ $item->ttd_asesi_file ?? '' }}">
                <div class="signature-actions">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong>{{ $item->ttd_asesi_tanggal ? $item->ttd_asesi_tanggal->locale('id')->isoFormat('D MMMM YYYY') : now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                </div>
            @elseif(isset($savedSignature) && $savedSignature)
                {{-- Belum TTD & ada TTD tersimpan: tampilkan pilihan --}}
                <div id="sigChoiceWrapAsesi" style="margin-bottom:14px; text-align:left;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;margin-bottom:8px;" id="optSavedAsesiLabel">
                        <input type="radio" name="sig_choice_asesi" value="saved" checked id="optSavedAsesi" onchange="toggleAsesiSigChoice()" style="accent-color:#10b981;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#166534;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Gunakan tanda tangan tersimpan</div>
                            <div style="font-size:12px;color:#64748b;">Menggunakan TTD yang sudah disimpan di profil Anda</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;background:#f8fafc;" id="optNewAsesiLabel">
                        <input type="radio" name="sig_choice_asesi" value="new" id="optNewAsesi" onchange="toggleAsesiSigChoice()" style="accent-color:#0073bd;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda tangan baru</div>
                            <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk rekaman ini</div>
                        </div>
                    </label>
                </div>

                {{-- Preview TTD tersimpan --}}
                <div id="savedAsesiSigPreview" style="margin-bottom:12px; text-align:center;">
                    <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;margin-bottom:8px;">
                        <img src="{{ $savedSignature }}" alt="TTD Tersimpan" style="max-width:260px;height:auto;display:block;">
                    </div>
                    <div style="font-size:11px;color:#94a3b8;">Tanda tangan tersimpan dari profil Anda</div>
                </div>

                {{-- Canvas tanda tangan baru (tersembunyi) --}}
                <div id="newAsesiSigDraw" style="display:none;">
                    <div class="signature-canvas-wrapper" id="signatureWrapper">
                        <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                        <div class="signature-placeholder">
                            <i class="bi bi-pen"></i>
                            <span>Tanda tangan di sini</span>
                        </div>
                    </div>
                    <div class="signature-actions" style="margin-top:8px;">
                        <button type="button" class="btn-clear-signature" id="clearSignature">
                            <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                        </button>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="">
                <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="">
                <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="{{ $savedSignature }}">

                <div class="signature-actions" style="margin-top:8px;">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong id="signatureDate">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                </div>
            @else
                {{-- Belum TTD & tidak ada TTD tersimpan --}}
                <div class="signature-canvas-wrapper" id="signatureWrapper">
                    <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="">
                <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="">
                <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="">

                <div class="signature-actions">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong id="signatureDate">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                    <button type="button" class="btn-clear-signature" id="clearSignature">
                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                    </button>
                </div>
            @endif
        </div>

        <div class="form-actions">
            @if(!$item->ttd_asesi_file)
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Tandatangani & Simpan</button>
            @endif
            <a href="{{ route('asesi.dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const savedSignatureUrl = @json($savedSignature ?? null);
    const asesiNama = '{{ $account->nama }}';

    // Toggle between saved and new signature modes
    window.toggleAsesiSigChoice = function() {
        const optSaved = document.getElementById('optSavedAsesi');
        const savedPreview = document.getElementById('savedAsesiSigPreview');
        const newDraw = document.getElementById('newAsesiSigDraw');
        const optSavedLabel = document.getElementById('optSavedAsesiLabel');
        const optNewLabel = document.getElementById('optNewAsesiLabel');
        const hiddenInput = document.getElementById('ttdAsesiFileInput');
        const ttdNama = document.getElementById('ttdAsesiNamaInput');
        const ttdTanggal = document.getElementById('ttdAsesiTanggalInput');

        if (!optSaved) return;

        if (optSaved.checked) {
            if (savedPreview) savedPreview.style.display = '';
            if (newDraw) newDraw.style.display = 'none';
            if (optSavedLabel) { optSavedLabel.style.borderColor = '#d1fae5'; optSavedLabel.style.background = '#f0fdf4'; }
            if (optNewLabel) { optNewLabel.style.borderColor = '#e2e8f0'; optNewLabel.style.background = '#f8fafc'; }
            if (hiddenInput && savedSignatureUrl) hiddenInput.value = savedSignatureUrl;
            if (ttdNama) ttdNama.value = asesiNama;
            if (ttdTanggal) {
                const now = new Date();
                ttdTanggal.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
            }
        } else {
            if (savedPreview) savedPreview.style.display = 'none';
            if (newDraw) newDraw.style.display = 'block';
            if (optSavedLabel) { optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc'; }
            if (optNewLabel) { optNewLabel.style.borderColor = '#bfdbfe'; optNewLabel.style.background = '#eff6ff'; }
            if (hiddenInput) hiddenInput.value = '';
            if (ttdNama) ttdNama.value = '';
            if (ttdTanggal) ttdTanggal.value = '';
            setTimeout(function() { window.dispatchEvent(new Event('resize')); }, 50);
        }
    };

    // Initialize ttd inputs if saved choice is pre-selected
    const optSaved = document.getElementById('optSavedAsesi');
    if (optSaved && optSaved.checked && savedSignatureUrl) {
        const ttdNama = document.getElementById('ttdAsesiNamaInput');
        const ttdTanggal = document.getElementById('ttdAsesiTanggalInput');
        if (ttdNama && !ttdNama.value) ttdNama.value = asesiNama;
        if (ttdTanggal && !ttdTanggal.value) {
            const now = new Date();
            ttdTanggal.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
        }
    }

    // Canvas drawing logic
    const canvas = document.getElementById('signatureCanvas');
    const wrapper = document.getElementById('signatureWrapper');
    const clearBtn = document.getElementById('clearSignature');
    const ttdAsesiNamaInput = document.getElementById('ttdAsesiNamaInput');
    const ttdAsesiTanggalInput = document.getElementById('ttdAsesiTanggalInput');
    const signForm = document.getElementById('signForm');

    if (canvas) {
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
            return { x: point.clientX - rect.left, y: point.clientY - rect.top };
        };

        const fillSignatureMeta = () => {
            if (ttdAsesiNamaInput && !ttdAsesiNamaInput.value) ttdAsesiNamaInput.value = asesiNama;
            if (ttdAsesiTanggalInput && !ttdAsesiTanggalInput.value) {
                const now = new Date();
                ttdAsesiTanggalInput.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
            }
        };

        const startDrawing = (event) => {
            event.preventDefault();
            if (canvas.width === 0 || canvas.height === 0) updateCanvasSize();
            isDrawing = true;
            const pos = getPos(event);
            lastX = pos.x;
            lastY = pos.y;
            if (wrapper) wrapper.classList.add('active');
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
                if (wrapper) wrapper.classList.add('has-signature');
            }
            fillSignatureMeta();
        };

        const stopDrawing = () => {
            isDrawing = false;
            if (wrapper) wrapper.classList.remove('active');
        };

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                if (ttdAsesiNamaInput) ttdAsesiNamaInput.value = '';
                if (ttdAsesiTanggalInput) ttdAsesiTanggalInput.value = '';
                if (wrapper) wrapper.classList.remove('has-signature');
                const fileInput = document.getElementById('ttdAsesiFileInput');
                if (fileInput) fileInput.value = '';
            });
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);
        window.addEventListener('resize', updateCanvasSize);
        updateCanvasSize();
    }

    if (signForm) {
        signForm.addEventListener('submit', function (e) {
            const optSaved = document.getElementById('optSavedAsesi');
            const fileInput = document.getElementById('ttdAsesiFileInput');
            const ttdNama = document.getElementById('ttdAsesiNamaInput');
            const ttdTanggal = document.getElementById('ttdAsesiTanggalInput');

            if (ttdNama && !ttdNama.value) ttdNama.value = asesiNama;
            if (ttdTanggal && !ttdTanggal.value) {
                const now = new Date();
                ttdTanggal.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
            }

            if (optSaved && optSaved.checked && savedSignatureUrl) {
                if (fileInput) fileInput.value = savedSignatureUrl;
            } else {
                if (!canvas) {
                    e.preventDefault();
                    alert('Silakan tanda tangani terlebih dahulu');
                    return;
                }
                if (fileInput && fileInput.value === '') {
                    fileInput.value = canvas.toDataURL('image/png');
                }
                if (!fileInput || !fileInput.value) {
                    e.preventDefault();
                    alert('Silakan tanda tangani terlebih dahulu');
                }
            }
        });
    }
});
</script>
@endsection

