@extends('asesi.layout')

@section('title', 'Form Banding Asesmen')
@section('page-title', 'Form Banding Asesmen FR.AK.04')

@section('styles')
<style>
    .top-actions { margin-bottom:14px; }
    .btn-back { display:inline-flex; align-items:center; gap:6px; padding:9px 14px; border-radius:8px; background:#e2e8f0; color:#1e293b; text-decoration:none; font-weight:600; font-size:13px; }
    .panel { background:#fff; border:1px solid #111827; border-radius:2px; }
    .panel-head { padding:8px 10px; border-bottom:1px solid #111827; font-size:14px; font-weight:700; color:#0f172a; }
    .meta-table { width:100%; border-collapse:collapse; }
    .meta-table td { border-bottom:1px solid #111827; padding:6px 8px; font-size:13px; }
    .meta-table td:first-child { width:170px; font-weight:600; background:#f8fafc; }

    .check-table { width:100%; border-collapse:collapse; }
    .check-table th, .check-table td { border:1px solid #111827; padding:8px; font-size:13px; vertical-align:top; }
    .check-table th { background:#f8fafc; font-weight:700; }
    .check-table th:nth-child(2), .check-table th:nth-child(3), .check-table td:nth-child(2), .check-table td:nth-child(3) { width:80px; text-align:center; }

    .section { border-top:1px solid #111827; padding:10px; }
    .section h4 { margin:0 0 8px; font-size:13px; color:#0f172a; }
    .section p { margin:0; font-size:13px; color:#1f2937; line-height:1.45; }

    textarea { width:100%; min-height:120px; border:1px solid #94a3b8; border-radius:4px; padding:10px; font-family:inherit; font-size:13px; resize:vertical; }
    textarea:focus { outline:none; border-color:#0073bd; box-shadow:0 0 0 3px rgba(0,115,189,.1); }

    .status-box { margin-bottom:12px; padding:10px 12px; border-radius:10px; border:1px solid #cbd5e1; background:#f8fafc; font-size:13px; }
    .status-box strong { color:#0f172a; }

    .actions { padding:12px 10px; border-top:1px solid #111827; display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .btn { font-family:inherit; border:none; border-radius:8px; padding:10px 14px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; text-decoration:none; transition: all 0.2s ease; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-primary:hover { background:#005e9b; }
    .btn-warning { background:#fef3c7; color:#92400e; border:1px solid #fcd34d; }
    .btn-warning:hover { background:#fde68a; }
    .btn-secondary { background:#64748b; color:#fff; text-decoration:none; }
    .btn-secondary:hover { background:#4b5563; }

    .error-text { margin-top:6px; color:#dc2626; font-size:12px; }

    @media (max-width: 768px) {
        .meta-table td:first-child { width:130px; }
        .check-table th:nth-child(2), .check-table th:nth-child(3), .check-table td:nth-child(2), .check-table td:nth-child(3) { width:58px; }
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    <a href="{{ route('asesi.banding.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Banding</a>
</div>

@php
    $bandingStatus = $banding->status ?? 'draft';
    $statusLabel = [
        'draft' => 'Belum Diajukan',
        'diajukan' => 'Diajukan',
        'ditinjau' => 'Ditinjau',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'tidak_banding' => 'Tidak Banding',
    ][$bandingStatus] ?? ucfirst($bandingStatus);

    $isLocked = in_array($bandingStatus, ['diterima', 'ditolak'], true);
@endphp

<div class="status-box">
    <div><strong>Status Banding:</strong> {{ $statusLabel }}</div>
    @if($bandingStatus === 'tidak_banding')
        <div style="margin-top:4px;color:#475569;">Anda sudah memilih Tidak Banding. Anda masih bisa mengubah keputusan menjadi Ajukan Banding selama belum ada keputusan final admin.</div>
    @endif
    @if($banding && $banding->checked_at)
        <div style="margin-top:4px;color:#475569;">Diproses pada {{ $banding->checked_at->format('d-m-Y H:i') }} WIB</div>
    @endif
    @if($banding && $banding->catatan_admin)
        <div style="margin-top:8px;"><strong>Catatan Admin:</strong> {{ $banding->catatan_admin }}</div>
    @endif
</div>

<div class="panel">
    <div class="panel-head">FR.AK.04. BANDING ASESMEN</div>

    <table class="meta-table">
        <tr>
            <td>Nama Asesi</td>
            <td>{{ $asesi->nama }}</td>
        </tr>
        <tr>
            <td>Skema Sertifikasi</td>
            <td>{{ $skema->nama_skema }}</td>
        </tr>
        <tr>
            <td>Tanggal Asesmen</td>
            <td>{{ $pivot->tanggal_selesai ? \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <form method="POST" action="{{ route('asesi.banding.store', $skema->id) }}">
        @csrf

        <table class="check-table">
            <thead>
                <tr>
                    <th>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini</th>
                    <th>YA</th>
                    <th>TIDAK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    @php
                        $jawabanItem = collect($existingJawaban)->get($item->id);
                        $selected = old('jawaban.' . $item->id, optional($jawabanItem)->jawaban);
                    @endphp
                    <tr>
                        <td>{{ $item->pernyataan }}</td>
                        <td>
                            <input type="radio" name="jawaban[{{ $item->id }}]" value="ya" {{ $selected === 'ya' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="radio" name="jawaban[{{ $item->id }}]" value="tidak" {{ $selected === 'tidak' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                    </tr>
                    @error('jawaban.' . $item->id)
                        <tr>
                            <td colspan="3" class="error-text">{{ $message }}</td>
                        </tr>
                    @enderror
                @endforeach
            </tbody>
        </table>

        <div class="section">
            <h4>Banding ini diajukan atas keputusan asesmen yang dibuat terhadap skema sertifikasi berikut:</h4>
            <p>Skema Sertifikasi: {{ $skema->nama_skema }}</p>
            <p>No. Skema Sertifikasi: {{ $skema->nomor_skema }}</p>
            <p style="margin-top:6px;">Keputusan Asesmen: <strong>{{ $pivot->rekomendasi === 'lanjut' ? 'Asesmen dapat dilanjutkan' : 'Asesmen tidak dapat dilanjutkan' }}</strong></p>
        </div>

        <div class="section">
            <h4>Banding ini diajukan atas alasan sebagai berikut:</h4>
            <textarea name="alasan_banding" {{ $isLocked ? 'readonly' : '' }}>{{ old('alasan_banding', $banding->alasan_banding ?? '') }}</textarea>
            @error('alasan_banding')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="section">
            <h4 style="font-weight:700;"><i class="bi bi-pen"></i> Tanda Tangan Asesi</h4>
            <p style="font-size:12px;color:#64748b;margin-bottom:12px;">Silakan tanda tangani di bawah ini untuk mengajukan banding.</p>
            
            <div class="signature-canvas-wrapper {{ $banding && $banding->ttd_asesi_file ? 'has-signature readonly' : '' }}" id="signatureWrapper" style="border: 2px dashed #d1d5db; border-radius: 10px; background: #fafafa; position: relative; overflow: hidden; max-width: 260px; aspect-ratio: 1 / 1; margin-bottom: 12px; margin-left: auto; margin-right: auto;">
                @if($banding && $banding->ttd_asesi_file)
                    <img src="{{ asset('storage/' . ltrim($banding->ttd_asesi_file, '/')) }}" class="signature-saved-img" id="savedSignatureImgAsesi" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; background:#fff; pointer-events:none;">
                @else
                    <canvas class="signature-canvas" id="signatureCanvas" style="width:100%; height:100%; cursor:crosshair; display:block;"></canvas>
                    <div class="signature-placeholder" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; pointer-events:none; color:#9ca3af; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                        <i class="bi bi-pen" style="font-size:24px;"></i>
                        <span style="font-size:12px;">Tanda tangan di sini</span>
                    </div>
                @endif
            </div>

            <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $banding->ttd_asesi_nama ?? '' }}">
            <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ $banding && $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->format('Y-m-d') : '' }}">
            <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="{{ $banding && $banding->ttd_asesi_file ? asset('storage/' . ltrim($banding->ttd_asesi_file, '/')) : '' }}">

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div class="signature-date" style="font-size:12px; color:#475569;">
                    <i class="bi bi-calendar3"></i>
                    Tanggal: <strong id="signatureDate">{{ $banding && $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->locale('id')->isoFormat('D MMMM YYYY') : now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                </div>
                @if(!$isLocked && (!$banding || empty($banding->ttd_asesi_file)))
                    <button type="button" class="btn-clear-signature" id="clearSignature" style="padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #e5e7eb; background: #f8fafc; color: #64748b; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                    </button>
                @endif
            </div>
            @error('ttd_asesi_file')
                <div class="error-text" style="color:#dc2626; font-size:12px; margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="section">
            <p>Anda mempunyai hak untuk mengajukan banding jika menilai proses asesmen tidak sesuai SOP dan tidak memenuhi prinsip asesmen.</p>
        </div>

        <div class="actions">
            <a href="{{ route('asesi.banding.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            @if(!$isLocked)
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button
                        type="submit"
                        formaction="{{ route('asesi.banding.decline', $skema->id) }}"
                        class="btn btn-warning"
                        onclick="return confirm('Simpan keputusan Tidak Banding untuk skema ini?');">
                        <i class="bi bi-x-octagon"></i> Pilih Tidak Banding
                    </button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> {{ $bandingStatus === 'tidak_banding' ? 'Ubah Menjadi Ajukan Banding' : 'Kirim Pengajuan Banding' }}</button>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signatureCanvas');
    const wrapper = document.getElementById('signatureWrapper');
    const clearBtn = document.getElementById('clearSignature');
    const ttdAsesiNamaInput = document.getElementById('ttdAsesiNamaInput');
    const ttdAsesiTanggalInput = document.getElementById('ttdAsesiTanggalInput');
    const signForm = document.querySelector('form');

    if (canvas) {
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let hasSignature = false;
        let lastX = 0;
        let lastY = 0;

        const updateCanvasSize = () => {
            // Simpan gambar sebelum resize agar tidak terhapus
            const prevDataUrl = hasSignature ? canvas.toDataURL('image/png') : null;

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

            // Kembalikan gambar yang sudah digambar setelah resize
            if (prevDataUrl) {
                const img = new Image();
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, rect.width, rect.height);
                };
                img.src = prevDataUrl;
            }
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
                ttdAsesiNamaInput.value = '{{ $asesi->nama }}';
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

            // Langsung simpan data canvas ke hidden input setiap kali user selesai menggambar
            // Ini jauh lebih andal daripada menunggu hingga form di-submit
            if (hasSignature) {
                const fileInput = document.getElementById('ttdAsesiFileInput');
                if (fileInput) {
                    fileInput.value = canvas.toDataURL('image/png');
                }
                fillSignatureMeta();
            }
        };

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                ttdAsesiNamaInput.value = '';
                ttdAsesiTanggalInput.value = '';
                wrapper.classList.remove('has-signature');
                const fileInput = document.getElementById('ttdAsesiFileInput');
                if (fileInput) {
                    fileInput.value = '';
                }
                const savedImg = document.getElementById('savedSignatureImgAsesi');
                if (savedImg) {
                    savedImg.remove();
                }
            });
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        if (signForm) {
            signForm.addEventListener('submit', function (e) {
                if (e.submitter && e.submitter.hasAttribute('formaction')) {
                    return; // skip validation for Pilih Tidak Banding
                }

                // Cek apakah hidden input sudah terisi (diisi oleh stopDrawing)
                const fileInput = document.getElementById('ttdAsesiFileInput');
                const hasValue = fileInput && fileInput.value && fileInput.value.length > 0;

                if (!hasSignature || !hasValue) {
                    e.preventDefault();
                    alert('Silakan tanda tangani terlebih dahulu sebelum mengirim pengajuan banding.');
                    return;
                }
            });
        }

        window.addEventListener('resize', updateCanvasSize);
        updateCanvasSize();

        if (ttdAsesiNamaInput.value || ttdAsesiTanggalInput.value) {
            wrapper.classList.add('has-signature');
            hasSignature = true;
        }
    }
});
</script>
@endsection
