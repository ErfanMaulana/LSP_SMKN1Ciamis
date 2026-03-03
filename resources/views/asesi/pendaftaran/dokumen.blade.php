@extends('asesi.layout')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Pendukung')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    .reg-card {
        background: white; border-radius: 12px; padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08); max-width: 900px;
    }
    .reg-card h3 {
        font-size: 16px; font-weight: 700; color: #0F172A;
        margin-bottom: 6px;
    }
    .reg-card .subtitle {
        font-size: 12px; color: #64748b; margin-bottom: 20px;
    }

    /* Step indicator */
    .step-indicator {
        display: flex; align-items: center; justify-content: center;
        gap: 16px; margin-bottom: 24px;
    }
    .step { display: flex; align-items: center; gap: 8px; }
    .step-number {
        width: 36px; height: 36px; border-radius: 50%;
        background: #14532d; color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .step.completed .step-number { background: #16a34a; }
    .step-label { font-size: 12px; font-weight: 600; color: #14532d; }
    .step-line { width: 50px; height: 2px; background: #14532d; }

    /* Alerts */
    .alert-box {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
        display: flex; gap: 12px; font-size: 12px; line-height: 1.5;
    }
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert-info { background: #f0fdf4; border-left: 4px solid #14532d; color: #14532d; }
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
    .photo-box:hover { border-color: #14532d; }
    .photo-box img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover; border-radius: 5px;
    }
    .photo-box i { font-size: 36px; color: #94a3b8; }
    .photo-badge {
        position: absolute; bottom: 6px; right: 6px;
        width: 28px; height: 28px; border-radius: 50%;
        background: #14532d; color: white;
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
    .photo-action-btn.edit { color: #0061a5; background: #eff6ff; border-color: #bfdbfe; }
    .photo-action-btn.edit:hover { background: #dbeafe; }
    .photo-action-btn.change { color: #64748b; background: #f8fafc; border-color: #e2e8f0; }
    .photo-action-btn.change:hover { background: #f1f5f9; }
    .photo-label { font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .photo-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; font-size: 12px; font-weight: 600;
        color: #14532d; background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 20px; cursor: pointer; transition: all .2s;
    }
    .photo-btn:hover { background: #dcfce7; }
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
        background: #14532d; color: white; border: none; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-crop-apply:hover { background: #166534; }

    /* Upload card */
    .upload-card {
        border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 20px; background: white; margin-bottom: 16px;
        transition: all .2s;
    }
    .upload-card:hover { border-color: #14532d; box-shadow: 0 2px 8px rgba(20,83,45,.08); }
    .upload-card-header { display: flex; align-items: flex-start; gap: 16px; }
    .upload-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 20px;
    }
    .upload-icon.violet { background: #f3e8ff; color: #7c3aed; }
    .upload-icon.amber { background: #fef3c7; color: #d97706; }
    .upload-icon.emerald { background: #d1fae5; color: #059669; }
    .upload-card h4 {
        font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 4px;
    }
    .upload-card p { font-size: 11px; color: #94a3b8; margin-bottom: 12px; }
    .file-list { margin-bottom: 12px; }
    .file-row {
        display: flex; align-items: center; gap: 10px; margin-bottom: 8px;
    }
    .file-choose-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; font-size: 11px; font-weight: 600;
        color: #14532d; background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 20px; cursor: pointer; transition: all .2s; white-space: nowrap;
    }
    .file-choose-btn:hover { background: #dcfce7; }
    .file-name { font-size: 11px; color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px; }
    .file-remove {
        padding: 4px; color: #ef4444; cursor: pointer; background: none; border: none;
        border-radius: 4px; transition: all .15s; flex-shrink: 0;
    }
    .file-remove:hover { background: #fef2f2; }
    .add-file-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; font-size: 12px; font-weight: 600;
        color: #14532d; background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 20px; cursor: pointer; transition: all .2s;
    }
    .add-file-btn:hover { background: #dcfce7; }

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
    .btn-reg-success { background: #14532d; color: white; flex: 1; justify-content: center; }
    .btn-reg-success:hover { background: #166534; }
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

    <h3><i class="bi bi-cloud-upload" style="color:#14532d;"></i> Upload Bukti Pendukung</h3>
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

    <form action="{{ route('asesi.pendaftaran.dokumen.store') }}" method="POST" enctype="multipart/form-data">
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
                <div class="upload-icon violet"><i class="bi bi-file-earmark-text"></i></div>
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
                <div class="upload-icon amber"><i class="bi bi-person-vcard"></i></div>
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
                <div class="upload-icon emerald"><i class="bi bi-award"></i></div>
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
            <button type="submit" class="btn-reg btn-reg-success">
                <i class="bi bi-check-circle"></i>
                <span>Selesaikan Pendaftaran & Kirim ke Admin</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let cropperInstance = null;
let originalImageSrc = null; // stores the raw (pre-crop) image for re-editing

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
        preview.src = canvas.toDataURL('image/jpeg');
        preview.style.display = 'block';
        document.getElementById('pas_foto_name').textContent = 'pas_foto.jpg';
        document.getElementById('pas_foto_name').style.color = '#1e293b';

        // Show edit overlay and action buttons
        document.getElementById('photo-edit-overlay').classList.add('visible');
        document.getElementById('photo-btn-pick').style.display = 'none';
        document.getElementById('photo-actions').classList.add('visible');

        closeCropper();
    }, 'image/jpeg', 0.92);
}

document.addEventListener('DOMContentLoaded', function() {
    addFileInput('transkrip_nilai');
    addFileInput('identitas_pribadi');
    addFileInput('bukti_kompetensi');
});

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
}

function removeFileInput(id) {
    const row = document.getElementById('row_' + id);
    if (row) row.remove();
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
}
</script>
@endsection
