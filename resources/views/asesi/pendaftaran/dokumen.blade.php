@extends('asesi.layout')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Pendukung')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    :root {
        --brand-500: #0061a5;
        --brand-600: #00538d;
        --brand-400: #0073bd;
        --brand-soft: #eef6ff;
        --brand-soft-border: #bfdbfe;
    }

    .reg-card {
        background: white; border-radius: 12px; padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08); max-width: 900px;
        width: 100%;
        margin: 0 auto;
    }
    .reg-card h3 {
        font-size: 16px; font-weight: 700; color: #0F172A;
        margin-bottom: 6px;
    }
    .reg-card .subtitle {
        font-size: 12px; color: #64748b; margin-bottom: 20px;
    }

    .reg-card,
    .reg-card * {
        max-width: 100%;
    }

    /* Step indicator */
    .step-indicator {
        display: flex; align-items: center; justify-content: center;
        gap: 16px; margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .step { display: flex; align-items: center; gap: 8px; }
    .step-number {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--brand-500); color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .step.completed .step-number { background: var(--brand-400); }
    .step-label { font-size: 12px; font-weight: 600; color: var(--brand-500); }
    .step-line { width: 50px; height: 2px; background: var(--brand-500); }

    /* Alerts */
    .alert-box {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
        display: flex; gap: 12px; font-size: 12px; line-height: 1.5;
    }
    .alert-box p {
        margin: 0;
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .reg-card h3,
    .subtitle,
    .step-label,
    .upload-card h4,
    .upload-card p {
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert-info { background: var(--brand-soft); border-left: 4px solid var(--brand-500); color: var(--brand-600); }
    .alert-box i { flex-shrink: 0; margin-top: 2px; font-size: 15px; }
    .error-list { list-style: none; margin: 4px 0 0; padding-left: 16px; }
    .error-list li { font-size: 11px; margin-top: 2px; }
    .error-list li:before { content: "• "; }

    /* Photo upload */
    .photo-upload { display: flex; flex-direction: column; align-items: center; margin-bottom: 28px; }
    .photo-box {
        width: 120px; height: 160px; border-radius: 8px;
        border: 3px dashed #cbd5e1; background: #f1f5f9;
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px; cursor: pointer; transition: border-color .2s;
    }
    .photo-box:hover { border-color: var(--brand-500); }
    .photo-box img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover; border-radius: 5px;
    }
    .photo-box i { font-size: 36px; color: #94a3b8; }
    .photo-badge {
        position: absolute; bottom: 6px; right: 6px;
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--brand-500); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }
    .photo-ratio-badge {
        position: absolute; top: 6px; left: 6px;
        background: rgba(0,0,0,0.5); color: #fff;
        font-size: 10px; font-weight: 700; padding: 2px 6px;
        border-radius: 4px; letter-spacing: .5px;
    }
    /* Edit overlay shown when photo is previewed */
    .photo-edit-overlay {
        display: none;
        position: absolute; inset: 0;
        background: rgba(0,0,0,0.45);
        align-items: center; justify-content: center;
        flex-direction: column; gap: 6px;
        border-radius: 5px;
        transition: opacity .2s;
    }
    .photo-box:hover .photo-edit-overlay.visible { display: flex; }
    .photo-edit-overlay span {
        color: #fff; font-size: 11px; font-weight: 600;
    }
    .photo-edit-overlay i { color: white; font-size: 22px; }
    /* Action buttons under photo */
    .photo-actions {
        display: none; gap: 8px; margin-top: 2px;
    }
    .photo-actions.visible { display: flex; }
    .photo-action-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; font-size: 11px; font-weight: 600;
        border-radius: 20px; cursor: pointer; transition: all .2s; border: 1px solid;
    }
    .photo-action-btn.edit { color: var(--brand-500); background: #eff6ff; border-color: var(--brand-soft-border); }
    .photo-action-btn.edit:hover { background: #dbeafe; }
    .photo-action-btn.change { color: #64748b; background: #f8fafc; border-color: #e2e8f0; }
    .photo-action-btn.change:hover { background: #f1f5f9; }
    .photo-label { font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .photo-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; font-size: 12px; font-weight: 600;
        color: var(--brand-500); background: var(--brand-soft); border: 1px solid var(--brand-soft-border);
        border-radius: 20px; cursor: pointer; transition: all .2s;
    }
    .photo-btn:hover { background: #dbeafe; }
    .photo-name { font-size: 11px; color: #94a3b8; margin-top: 6px; }

    /* Cropper Modal */
    .cropper-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,0.7); align-items: center; justify-content: center;
    }
    .cropper-modal-overlay.open { display: flex; }
    .cropper-modal-box {
        background: white; border-radius: 14px; overflow: hidden;
        width: 90%; max-width: 540px; display: flex; flex-direction: column;
        max-height: 90vh;
    }
    .cropper-modal-header {
        padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: space-between;
    }
    .cropper-modal-header h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0; }
    .cropper-modal-body { padding: 20px; overflow: auto; background: #1e293b; }
    .cropper-modal-body img { max-width: 100%; display: block; }
    .cropper-modal-footer {
        padding: 14px 20px; border-top: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    }
    .btn-crop-cancel {
        padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
        border: 1px solid #e2e8f0; background: white; color: #64748b; cursor: pointer;
    }
    .btn-crop-apply {
        padding: 8px 24px; border-radius: 8px; font-size: 13px; font-weight: 600;
        background: var(--brand-500); color: white; border: none; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-crop-apply:hover { background: var(--brand-600); }

    /* Signature modal styles (popup) */
    .signature-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.58);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .signature-modal-overlay.show {
        display: flex;
    }

    .signature-modal {
        width: 100%;
        max-width: 640px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 24px 80px rgba(15, 23, 42, 0.22);
        overflow: hidden;
    }

    .signature-modal-header { padding: 20px 24px 12px; border-bottom: 1px solid #e5e7eb; }
    .signature-modal-header h4 { margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; display:flex; align-items:center; gap:8px; }
    .signature-modal-header p { margin: 8px 0 0; color: #64748b; font-size: 13px; }

    .signature-modal-body { padding: 18px 24px 24px; }
    .signature-modal-actions { display:flex; gap:10px; justify-content:space-between; align-items:center; margin-top:12px; }
    .signature-modal-footer { padding: 16px 24px 24px; display:flex; justify-content:flex-end; gap:10px; border-top:1px solid #e5e7eb; }

    .signature-meta {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }

    .signature-box {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        padding: 8px;
        margin-bottom: 12px;
        position: relative;
        width: 220px;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .signature-canvas {
        display: block;
        width: 100%;
        height: 100%;
        background: transparent;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        cursor: crosshair;
        touch-action: none;
    }

    .signature-placeholder { position: absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#cbd5e1; font-size:14px; pointer-events:none; text-align:center; }

    .signature-error { display:none; background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:4px; color:#991b1b; font-size:13px; margin-bottom:16px; }

    .btn-signature-clear,
    .btn-signature-cancel,
    .btn-signature-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all .2s;
        height: 40px;
        padding: 0 18px;
    }

    .btn-signature-clear {
        color: #475569;
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .btn-signature-clear:hover { background: #f1f5f9; }

    .btn-signature-cancel {
        color: #475569;
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .btn-signature-cancel:hover { background: #f1f5f9; }

    .btn-signature-submit {
        color: #fff;
        background: #0073bd;
        border-color: #0073bd;
        min-width: 170px;
    }
    .btn-signature-submit:hover { background: #005a9e; border-color: #005a9e; }

    .signature-icon {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    /* Upload card */
    .upload-card {
        border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 20px; background: white; margin-bottom: 16px;
        transition: all .2s;
    }
    .upload-card:hover { border-color: var(--brand-500); box-shadow: 0 2px 8px rgba(0,97,165,.12); }
    .upload-card-header { display: flex; align-items: flex-start; gap: 16px; }
    .upload-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 20px;
        background: var(--brand-soft);
        color: var(--brand-500);
    }
    .upload-card h4 {
        font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 4px;
    }
    .upload-card p { font-size: 11px; color: #94a3b8; margin-bottom: 12px; }
    .file-list { margin-bottom: 12px; }
    .file-row {
        display: flex; align-items: center; gap: 10px; margin-bottom: 8px;
        min-width: 0;
    }
    .file-choose-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; font-size: 11px; font-weight: 600;
        color: var(--brand-500); background: var(--brand-soft); border: 1px solid var(--brand-soft-border);
        border-radius: 20px; cursor: pointer; transition: all .2s; white-space: nowrap;
    }
    .file-choose-btn:hover { background: #dbeafe; }
    .file-name { font-size: 11px; color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px; }
    .file-remove {
        padding: 4px; color: #ef4444; cursor: pointer; background: none; border: none;
        border-radius: 4px; transition: all .15s; flex-shrink: 0;
    }
    .file-remove:hover { background: #fef2f2; }
    .add-file-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; font-size: 12px; font-weight: 600;
        color: var(--brand-500); background: var(--brand-soft); border: 1px solid var(--brand-soft-border);
        border-radius: 20px; cursor: pointer; transition: all .2s;
    }
    .add-file-btn:hover { background: #dbeafe; }

    /* Submit */
    .reg-actions {
        display: flex; gap: 12px; justify-content: center;
        margin-top: 28px; padding-top: 20px; border-top: 1px solid #e2e8f0;
    }
    .btn-reg {
        padding: 12px 32px; border-radius: 8px; font-size: 14px; font-weight: 600;
        cursor: pointer; border: none; display: inline-flex;
        align-items: center; gap: 8px; transition: all .2s;
    }
    .btn-reg-success { background: var(--brand-500); color: white; flex: 1; justify-content: center; }
    .btn-reg-success:hover { background: var(--brand-600); }

    @media (max-width: 768px) {
        .reg-card {
            padding: 16px;
            border-radius: 10px;
        }

        .reg-card h3 {
            font-size: 15px;
            line-height: 1.4;
        }

        .step-indicator {
            gap: 6px;
            margin-bottom: 16px;
            justify-content: flex-start;
        }

        .step-number {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }

        .step-line {
            width: 20px;
        }

        .step-label {
            font-size: 11px;
        }

        .upload-card {
            padding: 14px;
            margin-bottom: 12px;
        }

        .upload-card-header {
            gap: 12px;
        }

        .upload-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
            border-radius: 8px;
        }

        .file-row {
            flex-wrap: wrap;
            gap: 6px;
        }

        .file-name {
            max-width: 100%;
            width: 100%;
        }

        .cropper-modal-box {
            width: calc(100% - 18px);
            max-height: 92vh;
        }

        .cropper-modal-body {
            padding: 10px;
        }

        .cropper-modal-footer {
            padding: 10px 12px;
        }

        .reg-actions {
            margin-top: 18px;
            padding-top: 14px;
        }

        .alert-box {
            padding: 10px 12px;
            gap: 8px;
            font-size: 11.5px;
        }

        .btn-reg-success {
            width: 100%;
        }
    }

    @media (max-width: 360px) {
        .reg-card {
            padding: 12px;
        }

        .upload-card {
            padding: 12px;
        }

        .photo-box {
            width: 108px;
            height: 144px;
        }

        .reg-card h3 {
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="reg-card">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step completed">
            <div class="step-number"><i class="bi bi-check-lg"></i></div>
            <span class="step-label">Formulir</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">2</div>
            <span class="step-label">Dokumen/Berkas</span>
        </div>
    </div>

    <h3><i class="bi bi-cloud-upload" style="color:#0061a5;"></i> Upload Bukti Pendukung</h3>
    <p class="subtitle">Ini adalah langkah terakhir! Upload dokumen yang diperlukan lalu kirim pendaftaran.</p>

    @if ($errors->any())
        <div class="alert-box alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="alert-box alert-info">
        <i class="bi bi-info-circle"></i>
        <p>Setelah submit, pendaftaran Anda akan dikirim ke admin untuk diverifikasi. Pastikan semua dokumen sudah benar.</p>
    </div>

    <form action="{{ route('asesi.pendaftaran.dokumen.store') }}" method="POST" enctype="multipart/form-data" id="dokumenForm">
        @csrf

        <!-- Pas Foto -->
        <div class="photo-upload">
            <div class="photo-box" id="photo-box" onclick="handlePhotoBoxClick()">
                <i class="bi bi-person-fill" id="photo-placeholder"></i>
                <img id="photo-preview" src="" alt="Preview" style="display:none;">
                <div class="photo-badge" id="photo-badge"><i class="bi bi-crop"></i></div>
                <div class="photo-ratio-badge">3×4</div>
                <div class="photo-edit-overlay" id="photo-edit-overlay">
                    <i class="bi bi-crop"></i>
                    <span>Edit Crop</span>
                </div>
            </div>
            <span class="photo-label">Pas Foto <span style="color:#ef4444;">*</span></span>
            {{-- shown when no photo --}}
            <label class="photo-btn" id="photo-btn-pick" onclick="event.stopPropagation(); document.getElementById('pas_foto_raw').click()">
                <i class="bi bi-paperclip"></i> Pilih & Crop Foto
            </label>
            {{-- shown after crop --}}
            <div class="photo-actions" id="photo-actions">
                <button type="button" class="photo-action-btn edit" onclick="editCrop()">
                    <i class="bi bi-crop"></i> Edit Crop
                </button>
                <button type="button" class="photo-action-btn change" onclick="document.getElementById('pas_foto_raw').click()">
                    <i class="bi bi-arrow-repeat"></i> Ganti Foto
                </button>
            </div>
            {{-- raw input triggers cropper, hidden cropped input is submitted --}}
            <input type="file" id="pas_foto_raw" accept="image/*" style="display:none;" onchange="openCropper(this)">
            <input type="file" name="pas_foto" id="pas_foto" style="display:none;" required>
            <span class="photo-name" id="pas_foto_name">Belum ada foto dipilih</span>
        </div>

        <!-- Cropper Modal -->
        <div class="cropper-modal-overlay" id="cropperModal">
            <div class="cropper-modal-box">
                <div class="cropper-modal-header">
                    <h4><i class="bi bi-crop" style="margin-right:8px;"></i>Sesuaikan Pas Foto (3×4)</h4>
                    <button type="button" class="btn-crop-cancel" onclick="closeCropper()" style="border:none;background:none;font-size:20px;line-height:1;padding:0;color:#94a3b8;cursor:pointer;">×</button>
                </div>
                <div class="cropper-modal-body">
                    <img id="cropper-image" src="">
                </div>
                <div class="cropper-modal-footer">
                    <button type="button" class="btn-crop-cancel" onclick="closeCropper()">Batal</button>
                    <button type="button" class="btn-crop-apply" onclick="applyCrop()">
                        <i class="bi bi-check-lg"></i> Pangkas & Gunakan
                    </button>
                </div>
            </div>
        </div>

        <!-- Transkrip Nilai -->
        <div class="upload-card">
            <div class="upload-card-header">
                <div class="upload-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div style="flex:1;">
                    <h4>Transkrip Nilai <span style="color:#ef4444;">*</span></h4>
                    <p>Scan/foto transkrip nilai akademik. Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                    <div class="file-list" id="transkrip_nilai_list"></div>
                    <button type="button" class="add-file-btn" onclick="addFileInput('transkrip_nilai')">
                        <i class="bi bi-plus-lg"></i> Tambah File
                    </button>
                </div>
            </div>
        </div>

        <!-- Identitas Pribadi -->
        <div class="upload-card">
            <div class="upload-card-header">
                <div class="upload-icon"><i class="bi bi-person-vcard"></i></div>
                <div style="flex:1;">
                    <h4>Identitas Pribadi (KTP / Kartu Pelajar / KK) <span style="color:#ef4444;">*</span></h4>
                    <p>Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                    <div class="file-list" id="identitas_pribadi_list"></div>
                    <button type="button" class="add-file-btn" onclick="addFileInput('identitas_pribadi')">
                        <i class="bi bi-plus-lg"></i> Tambah File
                    </button>
                </div>
            </div>
        </div>

        <!-- Bukti Kompetensi -->
        <div class="upload-card">
            <div class="upload-card-header">
                <div class="upload-icon"><i class="bi bi-award"></i></div>
                <div style="flex:1;">
                    <h4>Bukti Kompetensi (Sertifikat, Basic Skill Report) <span style="color:#ef4444;">*</span></h4>
                    <p>Format: JPG, PNG, WebP, PDF. Maks: 2MB per file</p>
                    <div class="file-list" id="bukti_kompetensi_list"></div>
                    <button type="button" class="add-file-btn" onclick="addFileInput('bukti_kompetensi')">
                        <i class="bi bi-plus-lg"></i> Tambah File
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="reg-actions">
            <button type="button" class="btn-reg btn-reg-success" id="openSignatureDokumenBtn">
                <i class="bi bi-check-circle"></i>
                <span>Selesaikan Pendaftaran & Kirim ke Admin</span>
            </button>
        </div>

        <input type="hidden" name="tanda_tangan_pendaftar" id="signatureInputDokumen" value="">
    </form>

    <div class="signature-modal-overlay" id="signatureModalDokumen">
        <div class="signature-modal">
            <div class="signature-modal-header">
                <h4><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda Tangan Pendaftar</h4>
                <p>Silakan tanda tangan sebelum pendaftaran dikirim ke admin.</p>
            </div>
            <div class="signature-modal-body">
                <div id="signatureErrorDokumen" class="signature-error" style="display:none;">Tanda tangan harus diisi sebelum submit</div>

                @php
                    $regSavedTTD = $asesi->tanda_tangan ?? null;
                    if ($regSavedTTD) {
                        $regSavedTTD = trim($regSavedTTD);
                        if (str_contains($regSavedTTD, '/storage/')) {
                            $regSavedTTD = asset('storage/' . ltrim(explode('/storage/', $regSavedTTD)[1], '/'));
                        } elseif (str_starts_with($regSavedTTD, 'persetujuan-asesmen/') || str_starts_with($regSavedTTD, 'signatures/') || str_starts_with($regSavedTTD, 'pendaftar/') || str_starts_with($regSavedTTD, 'dokumen_asesi/')) {
                            $regSavedTTD = asset('storage/' . ltrim($regSavedTTD, '/'));
                        } elseif (!str_starts_with($regSavedTTD, 'http://') && !str_starts_with($regSavedTTD, 'https://') && !str_starts_with($regSavedTTD, 'data:image')) {
                            $regSavedTTD = 'data:image/png;base64,' . preg_replace('/\s+/', '', $regSavedTTD);
                        }
                    }
                @endphp

                @if($regSavedTTD)
                {{-- Ada TTD tersimpan di profil --}}
                <div id="regSigChoiceWrap" style="margin-bottom:14px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;margin-bottom:8px;" id="regOptSavedLabel">
                        <input type="radio" name="reg_sig_choice" value="saved" checked id="regOptSaved" onchange="toggleRegSigChoice()" style="accent-color:#10b981;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#166534;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Gunakan tanda tangan tersimpan</div>
                            <div style="font-size:12px;color:#64748b;">Menggunakan TTD yang sudah disimpan di profil Anda</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;background:#f8fafc;" id="regOptNewLabel">
                        <input type="radio" name="reg_sig_choice" value="new" id="regOptNew" onchange="toggleRegSigChoice()" style="accent-color:#0073bd;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda tangan baru</div>
                            <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk pendaftaran ini</div>
                        </div>
                    </label>
                </div>
                <div id="regSavedSigPreview">
                    <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;margin-bottom:8px;">
                        <img src="{{ $regSavedTTD }}" alt="TTD Tersimpan" style="max-width:260px;height:auto;display:block;">
                    </div>
                </div>
                <div id="regNewSigDraw" style="display:none;">
                    <div class="signature-box" id="signatureBoxDokumen">
                        <canvas id="signatureCanvasDokumen" class="signature-canvas"></canvas>
                        <div class="signature-placeholder" style="pointer-events: none;">Tanda tangan Anda akan muncul di sini</div>
                    </div>
                    <div class="signature-modal-actions">
                        <p class="signature-meta">Tanggal &amp; waktu akan dicatat secara otomatis</p>
                        <button type="button" onclick="clearSignatureDokumen()" class="btn-signature-clear">
                            <svg class="signature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
                @else
                {{-- Tidak ada TTD tersimpan --}}
                <div class="signature-box" id="signatureBoxDokumen">
                    <canvas id="signatureCanvasDokumen" class="signature-canvas"></canvas>
                    <div class="signature-placeholder" style="pointer-events: none;">Tanda tangan Anda akan muncul di sini</div>
                </div>
                <div class="signature-modal-actions">
                    <p class="signature-meta">Tanggal &amp; waktu akan dicatat secara otomatis</p>
                    <button type="button" onclick="clearSignatureDokumen()" class="btn-signature-clear">
                        <svg class="signature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
                @endif
            </div>
            <div class="signature-modal-footer">
                <button type="button" onclick="closeSignatureModal()" class="btn-signature-cancel">Batal</button>
                <button type="submit" form="dokumenForm" class="btn-signature-submit">
                    <svg class="signature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Ya, Kirim ke Admin
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let cropperInstance = null;
let originalImageSrc = null; // stores the raw (pre-crop) image for re-editing
const DOKUMEN_DRAFT_KEY = 'asesi_dokumen_draft_v1';

function getDokumenDraft() {
    try {
        return JSON.parse(sessionStorage.getItem(DOKUMEN_DRAFT_KEY) || '{}');
    } catch (e) {
        return {};
    }
}

function saveDokumenDraft(next = {}) {
    const current = getDokumenDraft();
    const merged = { ...current, ...next };
    try {
        sessionStorage.setItem(DOKUMEN_DRAFT_KEY, JSON.stringify(merged));
    } catch (e) {
        // Ignore storage quota issues gracefully.
    }
}

function clearDokumenDraft() {
    try {
        sessionStorage.removeItem(DOKUMEN_DRAFT_KEY);
    } catch (e) {
        // no-op
    }
}

function saveFileRowsDraft() {
    const countRows = (type) => {
        const list = document.getElementById(type + '_list');
        return list ? list.children.length : 0;
    };

    saveDokumenDraft({
        transkripRows: countRows('transkrip_nilai'),
        identitasRows: countRows('identitas_pribadi'),
        kompetensiRows: countRows('bukti_kompetensi'),
    });
}

function restoreFileRowsDraft() {
    const draft = getDokumenDraft();
    const applyRows = (type, wantedRows) => {
        const total = Math.max(1, Number(wantedRows) || 1);
        const list = document.getElementById(type + '_list');
        if (!list) return;
        while (list.children.length < total) {
            addFileInput(type);
        }
    };

    applyRows('transkrip_nilai', draft.transkripRows);
    applyRows('identitas_pribadi', draft.identitasRows);
    applyRows('bukti_kompetensi', draft.kompetensiRows);
}

function restorePhotoDraft() {
    const draft = getDokumenDraft();
    if (!draft.photoDataUrl) return;

    originalImageSrc = draft.photoDataUrl;
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const badge = document.getElementById('photo-badge');
    const photoName = document.getElementById('pas_foto_name');

    if (preview) {
        preview.src = draft.photoDataUrl;
        preview.style.display = 'block';
    }
    if (placeholder) placeholder.style.display = 'none';
    if (badge) badge.style.display = 'none';
    if (photoName) {
        photoName.textContent = draft.photoName || 'pas_foto.jpg';
        photoName.style.color = '#1e293b';
    }

    const photoBtn = document.getElementById('photo-btn-pick');
    const photoActions = document.getElementById('photo-actions');
    const photoOverlay = document.getElementById('photo-edit-overlay');
    if (photoBtn) photoBtn.style.display = 'none';
    if (photoActions) photoActions.classList.add('visible');
    if (photoOverlay) photoOverlay.classList.add('visible');
}

function restoreSignatureDraft() {
    const draft = getDokumenDraft();
    if (!draft.signatureDataUrl) return;

    const signatureInput = document.getElementById('signatureInputDokumen');
    const placeholder = document.getElementById('signatureBoxDokumen')?.querySelector('.signature-placeholder');
    const canvas = document.getElementById('signatureCanvasDokumen');

    // Restore the input value
    if (signatureInput) signatureInput.value = draft.signatureDataUrl;
    if (placeholder) placeholder.style.display = 'none';

    // Redraw the signature on the canvas
    if (canvas && draft.signatureDataUrl) {
        // Ensure canvas is properly sized before drawing
        if (window.dokumenSignatureResize) {
            window.dokumenSignatureResize();
        }
        
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = function() {
            // Draw the restored signature on the properly sized canvas
            ctx.drawImage(img, 0, 0);
        };
        img.onerror = function() {
            console.error('Failed to load signature image from draft');
        };
        img.src = draft.signatureDataUrl;
    }
}

function handlePhotoBoxClick() {
    if (originalImageSrc) {
        editCrop();
    } else {
        document.getElementById('pas_foto_raw').click();
    }
}

function openCropperWithSrc(src) {
    const img = document.getElementById('cropper-image');
    img.src = src;
    document.getElementById('cropperModal').classList.add('open');
    document.body.style.overflow = 'hidden';
    if (cropperInstance) { cropperInstance.destroy(); }
    cropperInstance = new Cropper(img, {
        aspectRatio: 3 / 4,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 0.9,
        responsive: true,
        restore: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
    });
}

function openCropper(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        originalImageSrc = e.target.result; // save original for re-crop
        openCropperWithSrc(originalImageSrc);
    };
    reader.readAsDataURL(input.files[0]);
    input.value = ''; // reset so same file can be re-selected
}

function editCrop() {
    if (!originalImageSrc) return;
    openCropperWithSrc(originalImageSrc);
}

function closeCropper() {
    document.getElementById('cropperModal').classList.remove('open');
    document.body.style.overflow = '';
    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
}

function applyCrop() {
    if (!cropperInstance) return;
    const canvas = cropperInstance.getCroppedCanvas({ width: 300, height: 400 });
    canvas.toBlob(function(blob) {
        // Assign cropped blob to the actual file input
        const file = new File([blob], 'pas_foto.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('pas_foto').files = dt.files;

        // Update preview
        document.getElementById('photo-placeholder').style.display = 'none';
        document.getElementById('photo-badge').style.display = 'none';
        const preview = document.getElementById('photo-preview');
        const photoDataUrl = canvas.toDataURL('image/jpeg');
        preview.src = photoDataUrl;
        preview.style.display = 'block';
        document.getElementById('pas_foto_name').textContent = 'pas_foto.jpg';
        document.getElementById('pas_foto_name').style.color = '#1e293b';

        saveDokumenDraft({
            photoDataUrl: photoDataUrl,
            photoName: 'pas_foto.jpg',
        });

        // Show edit overlay and action buttons
        document.getElementById('photo-edit-overlay').classList.add('visible');
        document.getElementById('photo-btn-pick').style.display = 'none';
        document.getElementById('photo-actions').classList.add('visible');

        closeCropper();
    }, 'image/jpeg', 0.92);
}

function addFileInput(type) {
    const list = document.getElementById(type + '_list');
    const index = list.children.length;
    const id = type + '_' + index;

    const row = document.createElement('div');
    row.className = 'file-row';
    row.id = 'row_' + id;

    row.innerHTML = `
        <label class="file-choose-btn" for="${id}">
            <i class="bi bi-paperclip"></i> Pilih File
        </label>
        <input type="file" name="${type}[]" id="${id}" accept="image/*,.pdf" style="display:none;"
            onchange="onFileSelected(this, '${id}')">
        <span class="file-name" id="name_${id}">Belum ada file dipilih</span>
        ${index > 0 ? `<button type="button" class="file-remove" onclick="removeFileInput('${id}')" title="Hapus">
            <i class="bi bi-x-lg"></i>
        </button>` : ''}
    `;

    list.appendChild(row);
    saveFileRowsDraft();
}

function removeFileInput(id) {
    const row = document.getElementById('row_' + id);
    if (row) row.remove();
    saveFileRowsDraft();
}

function onFileSelected(input, id) {
    const span = document.getElementById('name_' + id);
    if (input.files && input.files[0]) {
        span.textContent = input.files[0].name;
        span.style.color = '#1e293b';
    } else {
        span.textContent = 'Belum ada file dipilih';
        span.style.color = '#94a3b8';
    }
    saveFileRowsDraft();
}

function openSignatureModal() {
    const modal = document.getElementById('signatureModalDokumen');
    const errorBox = document.getElementById('signatureErrorDokumen');
    errorBox.style.display = 'none';
    modal.classList.add('show');

    window.requestAnimationFrame(() => {
        window.requestAnimationFrame(() => {
            if (typeof window.dokumenSignatureResize === 'function') {
                window.dokumenSignatureResize();
            }
        });
    });
}

function closeSignatureModal() {
    document.getElementById('signatureModalDokumen').classList.remove('show');
}

const initSignaturePadDokumen = () => {
    const canvas = document.getElementById('signatureCanvasDokumen');
    const signatureInput = document.getElementById('signatureInputDokumen');
    const signatureBox = document.getElementById('signatureBoxDokumen');
    const errorBox = document.getElementById('signatureErrorDokumen');
    const placeholder = signatureBox.querySelector('.signature-placeholder');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    const resizeCanvas = () => {
        const rect = canvas.getBoundingClientRect();
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#1f2937';
        ctx.clearRect(0, 0, rect.width, rect.height);
    };

    window.dokumenSignatureResize = resizeCanvas;
    window.addEventListener('resize', resizeCanvas);

    const getPoint = (e) => {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top,
        };
    };

    const startDrawing = (e) => {
        if (!canvas.width || !canvas.height) {
            resizeCanvas();
        }
        const { x, y } = getPoint(e);
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(x, y);
        placeholder.style.display = 'none';
        errorBox.style.display = 'none';
    };

    const draw = (e) => {
        if (!drawing) return;
        const { x, y } = getPoint(e);
        ctx.lineTo(x, y);
        ctx.stroke();
    };

    const stopDrawing = () => {
        if (drawing) {
            const data = canvas.toDataURL('image/png');
            signatureInput.value = data;
            saveDokumenDraft({ signatureDataUrl: data });
        }
        drawing = false;
    };

    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointerleave', stopDrawing);
};

function clearSignatureDokumen() {
    const canvas = document.getElementById('signatureCanvasDokumen');
    const signatureInput = document.getElementById('signatureInputDokumen');
    const placeholder = document.getElementById('signatureBoxDokumen').querySelector('.signature-placeholder');
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    signatureInput.value = '';
    placeholder.style.display = 'block';
    saveDokumenDraft({ signatureDataUrl: '' });
}

document.addEventListener('DOMContentLoaded', function() {
    const openButton = document.getElementById('openSignatureDokumenBtn');
    const form = document.getElementById('dokumenForm');
    const modal = document.getElementById('signatureModalDokumen');
    const canvas = document.getElementById('signatureCanvasDokumen');

    restoreFileRowsDraft();
    restorePhotoDraft();
    initSignaturePadDokumen();
    restoreSignatureDraft();

    if (!document.getElementById('transkrip_nilai_list')?.children.length) {
        addFileInput('transkrip_nilai');
    }
    if (!document.getElementById('identitas_pribadi_list')?.children.length) {
        addFileInput('identitas_pribadi');
    }
    if (!document.getElementById('bukti_kompetensi_list')?.children.length) {
        addFileInput('bukti_kompetensi');
    }

    if (openButton) {
        openButton.addEventListener('click', function(event) {
            event.preventDefault();
            openSignatureModal();
        });
    }

    if (form) {
        form.addEventListener('submit', function(event) {
            const signatureInput = document.getElementById('signatureInputDokumen');
            const signatureError = document.getElementById('signatureErrorDokumen');
            if (!signatureInput.value) {
                event.preventDefault();
                signatureError.style.display = 'block';
                openSignatureModal();
                canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // On successful submit attempt, clear saved draft.
            clearDokumenDraft();
        });
    }

    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeSignatureModal();
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSignatureModal();
        }
    });

    // Pre-fill signatureInput with saved TTD when modal opens (if "saved" is selected by default)
    const _savedSrc = document.querySelector('#regSavedSigPreview img')?.src || '';
    if (_savedSrc) {
        const signatureInput = document.getElementById('signatureInputDokumen');
        if (signatureInput) signatureInput.value = _savedSrc;
    }
});

function toggleRegSigChoice() {
    const optSaved = document.getElementById('regOptSaved');
    const savedPreview = document.getElementById('regSavedSigPreview');
    const newDraw = document.getElementById('regNewSigDraw');
    const optSavedLabel = document.getElementById('regOptSavedLabel');
    const optNewLabel = document.getElementById('regOptNewLabel');
    const signatureInput = document.getElementById('signatureInputDokumen');
    const savedSrc = document.querySelector('#regSavedSigPreview img')?.src || '';

    if (!optSaved) return;

    if (optSaved.checked) {
        if (savedPreview) savedPreview.style.display = '';
        if (newDraw) newDraw.style.display = 'none';
        optSavedLabel.style.borderColor = '#d1fae5'; optSavedLabel.style.background = '#f0fdf4';
        optNewLabel.style.borderColor = '#e2e8f0'; optNewLabel.style.background = '#f8fafc';
        if (signatureInput && savedSrc) signatureInput.value = savedSrc;
    } else {
        if (savedPreview) savedPreview.style.display = 'none';
        if (newDraw) {
            newDraw.style.display = 'block';
            // Initialize canvas when revealed
            window.requestAnimationFrame(() => {
                window.requestAnimationFrame(() => {
                    if (typeof window.dokumenSignatureResize === 'function') {
                        window.dokumenSignatureResize();
                    }
                });
            });
        }
        optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc';
        optNewLabel.style.borderColor = '#bfdbfe'; optNewLabel.style.background = '#eff6ff';
        if (signatureInput) signatureInput.value = '';
    }
}
</script>
@endsection
