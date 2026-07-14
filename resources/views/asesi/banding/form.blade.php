@extends('asesi.layout')

@section('title', 'Form Banding Asesmen')
@section('page-title', 'Form Banding Asesmen FR.AK.04')

@section('styles')
<style>
    .top-actions { margin-bottom: 20px; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; background: #fff; border: 1px solid #e2e8f0; color: #475569; text-decoration: none; font-weight: 600; font-size: 13.5px; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .btn-back:hover { background: #f8fafc; color: #1e293b; border-color: #cbd5e1; transform: translateX(-2px); }

    /* Container Card */
    .form-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04), 0 1px 3px rgba(0,0,0,0.02);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .form-header {
        background: linear-gradient(135deg, #0073bd, #005a96);
        padding: 24px;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .form-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .form-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
    }

    /* Metadata Panel */
    .metadata-panel {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }
    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .meta-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .meta-value {
        font-size: 14.5px;
        color: #1e293b;
        font-weight: 600;
    }
    .meta-value .badge-status {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
    .badge-status.diterima { background: #dcfce7; color: #166534; }
    .badge-status.ditolak { background: #fee2e2; color: #991b1b; }

    /* Section Styles */
    .form-section {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    .form-section:last-of-type {
        border-bottom: none;
    }
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Questionnaire Card Layout (Modern Cards instead of tables) */
    .question-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .question-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        transition: all 0.2s ease;
    }
    .question-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .question-text {
        font-size: 14px;
        color: #334155;
        line-height: 1.5;
        flex: 1;
    }

    /* Native radio + colored label style — matches K(Kompeten)/BK(Belum Kompeten) */
    .answer-options {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }
    .option-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 600;
        user-select: none;
        white-space: nowrap;
    }
    .option-label input[type="radio"] {
        width: 15px;
        height: 15px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .option-label.option-ya {
        color: #16a34a;
    }
    .option-label.option-ya input[type="radio"] {
        accent-color: #16a34a;
    }
    .option-label.option-tidak {
        color: #dc2626;
    }
    .option-label.option-tidak input[type="radio"] {
        accent-color: #dc2626;
    }
    .option-label input:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .option-label input:disabled ~ span {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Textarea reason */
    .reason-box {
        margin-top: 8px;
    }
    textarea {
        width: 100%;
        min-height: 120px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 12px;
        font-family: inherit;
        font-size: 13.5px;
        line-height: 1.5;
        resize: vertical;
        transition: all 0.2s ease;
        color: #1e293b;
    }
    textarea:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.15);
    }
    textarea[readonly] {
        background: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
    }

    /* Signature panel */
    .signature-area {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        align-items: center;
    }
    .signature-instructions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .signature-instructions h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }
    .signature-instructions p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
    }
    .disclaimer-box {
        background: #eff6ff;
        border-left: 3px solid #3b82f6;
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        color: #1e40af;
        line-height: 1.45;
    }

    /* Signature Canvas Styling */
    .signature-canvas-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .signature-canvas-wrapper {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 280px;
        aspect-ratio: 1.2 / 1;
        transition: all 0.2s ease;
    }
    .signature-canvas-wrapper.active {
        border-color: #0073bd;
        background: #fff;
    }
    .signature-canvas-wrapper.readonly {
        border-style: solid;
        border-color: #e2e8f0;
        background: #fff;
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
        color: #94a3b8;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        transition: opacity 0.2s ease;
    }
    .signature-canvas-wrapper.has-signature .signature-placeholder {
        opacity: 0;
    }
    .btn-clear-signature {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-clear-signature:hover {
        background: #f8fafc;
        color: #ef4444;
        border-color: #fca5a5;
    }

    /* Status Banner at Top */
    .status-banner {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .status-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .status-icon.draft { background: #f1f5f9; color: #475569; }
    .status-icon.diajukan { background: #eff6ff; color: #1d4ed8; }
    .status-icon.ditinjau { background: #fef3c7; color: #b45309; }
    .status-icon.diterima { background: #dcfce7; color: #15803d; }
    .status-icon.ditolak { background: #fee2e2; color: #b91c1c; }
    .status-icon.asesmen_ulang { background: #fef3c7; color: #b45309; }
    .status-icon.tidak_banding { background: #f3f4f6; color: #374151; }

    .status-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .status-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
    }
    .status-desc {
        font-size: 12.5px;
        color: #64748b;
    }

    /* Actions Footer */
    .form-actions-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    /* Button styles */
    .btn {
        font-family: inherit;
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-primary:hover { background: #005e9b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,115,189,0.15); }
    .btn-primary:active { transform: translateY(0); }
    
    .btn-warning { background: #fff; color: #92400e; border: 1px solid #fcd34d; }
    .btn-warning:hover { background: #fffbeb; color: #78350f; border-color: #fbbf24; transform: translateY(-1px); }
    
    .btn-secondary { background: #fff; color: #475569; border: 1px solid #e2e8f0; }
    .btn-secondary:hover { background: #f8fafc; color: #1e293b; border-color: #cbd5e1; }

    .error-text {
        margin-top: 6px;
        color: #ef4444;
        font-size: 12.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    @media (max-width: 768px) {
        .question-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }
        .answer-options {
            width: 100%;
        }
        .option-label {
            flex: 1;
        }
        .option-label span {
            min-width: 0;
            width: 100%;
        }
        .signature-area {
            grid-template-columns: 1fr;
        }
        .signature-canvas-wrapper {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    <a href="{{ route('asesi.dashboard') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
</div>

@php
    $bandingStatus = $banding->status ?? 'draft';
    $statusLabel = [
        'draft' => 'Belum Diajukan',
        'diajukan' => 'Diajukan',
        'ditinjau' => 'Ditinjau',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'asesmen_ulang' => 'Perlu Asesmen Ulang',
        'tidak_banding' => 'Tidak Banding',
    ][$bandingStatus] ?? ucfirst($bandingStatus);

    $isLocked = $isKompeten || in_array($bandingStatus, ['diajukan', 'ditinjau', 'diterima', 'ditolak', 'asesmen_ulang'], true);
    
    $statusIconClass = [
        'draft' => 'bi-pencil-square',
        'diajukan' => 'bi-send-fill',
        'ditinjau' => 'bi-hourglass-split',
        'diterima' => 'bi-check-circle-fill',
        'ditolak' => 'bi-x-circle-fill',
        'asesmen_ulang' => 'bi-arrow-repeat',
        'tidak_banding' => 'bi-x-octagon-fill',
    ][$bandingStatus] ?? 'bi-info-circle-fill';
@endphp

@if($isKompeten)
<div style="margin-bottom:20px;padding:16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;display:flex;align-items:center;gap:12px;font-size:14px;color:#166534;">
    <i class="bi bi-check-circle-fill" style="font-size:20px;color:#16a34a;"></i>
    <div>
        <strong>Informasi:</strong> Anda telah dinyatakan <strong>KOMPETEN</strong> pada Uji Kompetensi ini. 
        Formulir banding dinonaktifkan (hanya dapat dilihat) karena banding hanya diperuntukkan bagi asesi yang belum kompeten.
    </div>
</div>
@endif

<div class="status-banner">
    <div class="status-info">
        <div class="status-icon {{ $bandingStatus }}">
            <i class="bi {{ $statusIconClass }}"></i>
        </div>
        <div class="status-text">
            <div class="status-title">Status Banding: {{ $statusLabel }}</div>
            <div class="status-desc">
                @if($bandingStatus === 'tidak_banding')
                    Anda memilih Tidak Banding. Keputusan dapat diubah menjadi Ajukan Banding sebelum diproses final.
                @elseif($bandingStatus === 'draft')
                    Formulir banding bersifat opsional. Isi jika Anda ingin melakukan sanggahan atas keputusan asesmen.
                @elseif($bandingStatus === 'diajukan')
                    Menunggu verifikasi dan keputusan dari tim asesor atau admin.
                @elseif($bandingStatus === 'ditinjau')
                    Pengajuan banding sedang ditinjau oleh pihak penyelenggara LSP.
                @elseif($bandingStatus === 'diterima')
                    Banding diterima.
                    @if($banding && $banding->catatan_admin)
                        Catatan: {{ $banding->catatan_admin }}
                    @endif
                @elseif($bandingStatus === 'ditolak')
                    Banding ditolak.
                    @if($banding && $banding->catatan_admin)
                        Catatan: {{ $banding->catatan_admin }}
                    @endif
                @elseif($bandingStatus === 'asesmen_ulang')
                    Keputusan banding: Perlu Asesmen Ulang. Silakan hubungi pihak LSP untuk informasi jadwal asesmen ulang.
                    @if($banding && $banding->catatan_admin)
                        Catatan: {{ $banding->catatan_admin }}
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-container">
    <div class="form-header">
        <div style="background: rgba(255,255,255,0.15); width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="bi bi-file-earmark-ruled-fill"></i>
        </div>
        <div>
            <h3>FR.AK.04. BANDING ASESMEN</h3>
            <p>Pengajuan banding atas ketidakpuasan hasil keputusan uji kompetensi</p>
        </div>
    </div>

    <!-- Metadata Panel -->
    <div class="metadata-panel">
        <div class="metadata-grid">
            <div class="meta-item">
                <span class="meta-label">Nama Asesi</span>
                <span class="meta-value">{{ $asesi->nama }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Skema Sertifikasi</span>
                <span class="meta-value">{{ $skema->nama_skema }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">No. Skema</span>
                <span class="meta-value">{{ $skema->nomor_skema }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Keputusan Asesmen</span>
                <span class="meta-value">
                    <span class="badge-status {{ $pivot->rekomendasi === 'lanjut' ? 'diterima' : 'ditolak' }}">
                        {{ $pivot->rekomendasi === 'lanjut' ? 'Asesmen dapat dilanjutkan' : 'Asesmen tidak dapat dilanjutkan' }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('asesi.banding.store', $skema->id) }}" enctype="multipart/form-data">
        @csrf

        <!-- Checklist Questions -->
        <div class="form-section">
            <h4 class="section-title"><i class="bi bi-patch-question"></i> Pernyataan Sanggahan / Banding</h4>
            <p style="font-size: 13px; color: #64748b; margin: -10px 0 16px 0;">Jawablah pertanyaan-pertanyaan berikut ini berdasarkan pelaksanaan asesmen Anda.</p>
            
            <div class="question-list">
                @foreach($komponen as $item)
                    @php
                        $jawabanItem = collect($existingJawaban)->get($item->id);
                        $selected = old('jawaban.' . $item->id, optional($jawabanItem)->jawaban);
                    @endphp
                    <div class="question-card">
                        <div class="question-text">{{ $item->pernyataan }}</div>
                        <div class="answer-options">
                            <label class="option-label option-ya">
                                <input type="radio" name="jawaban[{{ $item->id }}]" value="ya" {{ $selected === 'ya' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                <span>Ya</span>
                            </label>
                            <label class="option-label option-tidak">
                                <input type="radio" name="jawaban[{{ $item->id }}]" value="tidak" {{ $selected === 'tidak' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                <span>Tidak</span>
                            </label>
                        </div>
                    </div>
                    @error('jawaban.' . $item->id)
                        <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Reasons Section -->
        <div class="form-section">
            <h4 class="section-title"><i class="bi bi-chat-left-text"></i> Alasan Pengajuan Banding</h4>
            <div class="reason-box">
                <textarea name="alasan_banding" placeholder="Tuliskan alasan sanggahan / banding secara rinci di sini..." {{ $isLocked ? 'readonly' : '' }}>{{ old('alasan_banding', $banding->alasan_banding ?? '') }}</textarea>
                @error('alasan_banding')
                    <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Signature Section -->
        <div class="form-section">
            <h4 class="section-title"><i class="bi bi-vector-pen"></i> Otorisasi & Tanda Tangan</h4>
            
            <div class="signature-area">
                <div class="signature-instructions">
                    <h4>Persetujuan Hak</h4>
                    <p>Anda berhak mengajukan banding apabila merasa proses asesmen tidak dilaksanakan sesuai dengan SOP atau tidak memenuhi prinsip-prinsip kejujuran dan objektivitas asesmen.</p>
                    
                    <div class="disclaimer-box">
                        <i class="bi bi-info-circle"></i> Tanda tangan digital di sebelah kanan menyatakan persetujuan data yang Anda ajukan di atas adalah benar.
                    </div>
                </div>

                <div class="signature-canvas-container" style="width: 100%; max-width: 320px; margin: 0 auto;">
                    @if($banding && $banding->ttd_asesi_file)
                        {{-- Sudah bertanda tangan --}}
                        <div class="signature-canvas-wrapper has-signature readonly" id="signatureWrapper">
                            <img src="{{ asset('storage/' . ltrim($banding->ttd_asesi_file, '/')) }}" class="signature-saved-img" id="savedSignatureImgAsesi" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; background:#fff; pointer-events:none;">
                        </div>
                    @elseif($isKompeten)
                        <div class="signature-canvas-wrapper readonly" id="signatureWrapper">
                            <div class="signature-placeholder" style="opacity: 1;">
                                <i class="bi bi-shield-fill-check" style="font-size:32px;color:#16a34a;"></i>
                                <span style="font-size:11px; font-weight: 600; color: #16a34a;">Tidak Memerlukan Otorisasi</span>
                            </div>
                        </div>
                    @elseif(isset($savedSignature) && $savedSignature)
                        {{-- Belum TTD & ada TTD tersimpan: tampilkan pilihan --}}
                        <div id="sigChoiceWrapAsesi" style="margin-bottom:14px; text-align:left; width: 100%;">
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
                                    <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pencil" style="color:#0073bd;"></i> Tanda tangan baru</div>
                                    <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk banding ini</div>
                                </div>
                            </label>
                        </div>

                        {{-- Preview TTD tersimpan --}}
                        <div id="savedAsesiSigPreview" style="margin-bottom:12px; text-align:center; width: 100%;">
                            <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;margin-bottom:8px; width: 100%; max-width: 280px; aspect-ratio: 1.2/1; position: relative;">
                                <img src="{{ $savedSignature }}" alt="TTD Tersimpan" style="position: absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; background:#fff; padding: 10px;">
                            </div>
                            <div style="font-size:11px;color:#94a3b8;">Tanda tangan tersimpan dari profil Anda</div>
                        </div>

                        {{-- Canvas tanda tangan baru (tersembunyi) --}}
                        <div id="newAsesiSigDraw" style="display:none; width: 100%;">
                            <div class="signature-canvas-wrapper" id="signatureWrapper" style="margin: 0 auto;">
                                <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                                <div class="signature-placeholder">
                                    <i class="bi bi-pencil" style="font-size:20px;"></i>
                                    <span style="font-size:11px; font-weight: 600;">Gores Tanda Tangan</span>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Canvas tanda tangan baru secara langsung --}}
                        <div class="signature-canvas-wrapper" id="signatureWrapper">
                            <canvas class="signature-canvas" id="signatureCanvas"></canvas>
                            <div class="signature-placeholder">
                                <i class="bi bi-pencil" style="font-size:20px;"></i>
                                <span style="font-size:11px; font-weight: 600;">Gores Tanda Tangan</span>
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $banding->ttd_asesi_nama ?? '' }}">
                    <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ $banding && $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->format('Y-m-d') : '' }}">
                    <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="{{ $banding && $banding->ttd_asesi_file ? asset('storage/' . ltrim($banding->ttd_asesi_file, '/')) : '' }}">

                    <div style="text-align: center; width: 100%;">
                        <div class="signature-date" style="font-size:12px; color:#64748b; margin-bottom: 6px;">
                            <i class="bi bi-calendar3"></i> Tanggal: <strong id="signatureDate">{{ $banding && $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->locale('id')->isoFormat('D MMMM YYYY') : now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                        </div>
                        @if(!$isLocked && (!$banding || empty($banding->ttd_asesi_file)))
                            <button type="button" class="btn-clear-signature" id="clearSignature" style="display: none;">
                                <i class="bi bi-eraser"></i> Bersihkan Canvas
                            </button>
                        @endif
                    </div>
                    @error('ttd_asesi_file')
                        <div class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="form-actions-footer">
            <a href="{{ route('asesi.dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            @if(!$isLocked)
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button
                        type="submit"
                        formaction="{{ route('asesi.banding.decline', $skema->id) }}"
                        class="btn btn-warning"
                        onclick="return confirm('Simpan keputusan Tidak Banding untuk skema ini?');">
                        <i class="bi bi-x-circle"></i> Pilih Tidak Banding
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> {{ $bandingStatus === 'tidak_banding' ? 'Ubah Menjadi Ajukan Banding' : 'Kirim Pengajuan Banding' }}
                    </button>
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

    const savedSignatureUrl = @json($savedSignature ?? null);
    const asesiNama = '{{ $asesi->nama }}';

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
        const clearBtn = document.getElementById('clearSignature');

        if (!optSaved) return;

        if (optSaved.checked) {
            if (savedPreview) savedPreview.style.display = '';
            if (newDraw) newDraw.style.display = 'none';
            if (clearBtn) clearBtn.style.display = 'none';
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
            if (clearBtn) clearBtn.style.display = 'inline-flex';
            if (optSavedLabel) { optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc'; }
            if (optNewLabel) { optNewLabel.style.borderColor = '#bfdbfe'; optNewLabel.style.background = '#eff6ff'; }
            if (hiddenInput) hiddenInput.value = '';
            if (ttdNama) ttdNama.value = '';
            if (ttdTanggal) ttdTanggal.value = '';
            setTimeout(function() { window.dispatchEvent(new Event('resize')); }, 50);
        }
    };

    // Initialize saved choice if active
    const optSaved = document.getElementById('optSavedAsesi');
    if (optSaved && optSaved.checked && savedSignatureUrl) {
        const fileInput = document.getElementById('ttdAsesiFileInput');
        if (fileInput && !fileInput.value) fileInput.value = savedSignatureUrl;
        if (ttdAsesiNamaInput && !ttdAsesiNamaInput.value) ttdAsesiNamaInput.value = asesiNama;
        if (ttdAsesiTanggalInput && !ttdAsesiTanggalInput.value) {
            const now = new Date();
            ttdAsesiTanggalInput.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
        }
        if (clearBtn) clearBtn.style.display = 'none';
    } else {
        if (clearBtn && !document.getElementById('signatureWrapper')?.classList.contains('readonly')) {
            clearBtn.style.display = 'inline-flex';
        }
    }

    if (canvas) {
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let hasSignature = false;
        let lastX = 0;
        let lastY = 0;

        const updateCanvasSize = () => {
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
                ttdAsesiNamaInput.value = asesiNama;
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
            const wrap = document.getElementById('signatureWrapper');
            if (wrap) wrap.classList.add('active');
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

            const wrap = document.getElementById('signatureWrapper');
            if (wrap && !hasSignature) {
                hasSignature = true;
                wrap.classList.add('has-signature');
            }

            fillSignatureMeta();
        };

        const stopDrawing = () => {
            isDrawing = false;
            const wrap = document.getElementById('signatureWrapper');
            if (wrap) wrap.classList.remove('active');

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
                const wrap = document.getElementById('signatureWrapper');
                if (wrap) wrap.classList.remove('has-signature');
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

        window.addEventListener('resize', updateCanvasSize);
        updateCanvasSize();

        if (ttdAsesiNamaInput.value || ttdAsesiTanggalInput.value) {
            const wrap = document.getElementById('signatureWrapper');
            if (wrap) wrap.classList.add('has-signature');
            hasSignature = true;
        }
    }

    if (signForm) {
        signForm.addEventListener('submit', function (e) {
            if (e.submitter && e.submitter.hasAttribute('formaction')) {
                return;
            }

            const optSaved = document.getElementById('optSavedAsesi');
            const fileInput = document.getElementById('ttdAsesiFileInput');
            const hasValue = fileInput && fileInput.value && fileInput.value.length > 0;

            if (optSaved && optSaved.checked && savedSignatureUrl) {
                if (fileInput) fileInput.value = savedSignatureUrl;
            } else {
                if (!hasSignature || !hasValue) {
                    e.preventDefault();
                    alert('Silakan tanda tangani terlebih dahulu sebelum mengirim pengajuan banding.');
                    return;
                }
            }
        });
    }
});
</script>
@endsection
