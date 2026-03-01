@extends('asesi.layout')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Pendukung')

@section('styles')
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
    .photo-circle {
        width: 120px; height: 120px; border-radius: 50%;
        border: 4px dashed #cbd5e1; background: #f1f5f9;
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px; cursor: pointer;
    }
    .photo-circle:hover { border-color: #14532d; }
    .photo-circle img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    }
    .photo-circle i { font-size: 36px; color: #94a3b8; }
    .photo-badge {
        position: absolute; bottom: 0; right: 0;
        width: 32px; height: 32px; border-radius: 50%;
        background: #14532d; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; cursor: pointer;
    }
    .photo-label { font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .photo-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; font-size: 12px; font-weight: 600;
        color: #14532d; background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 20px; cursor: pointer; transition: all .2s;
    }
    .photo-btn:hover { background: #dcfce7; }
    .photo-name { font-size: 11px; color: #94a3b8; margin-top: 6px; }

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
            <div class="photo-circle" onclick="document.getElementById('pas_foto').click()">
                <i class="bi bi-person-fill" id="photo-placeholder"></i>
                <img id="photo-preview" src="" alt="Preview" style="display:none;">
                <div class="photo-badge"><i class="bi bi-camera-fill"></i></div>
            </div>
            <span class="photo-label">Pas Foto <span style="color:#ef4444;">*</span></span>
            <label class="photo-btn" for="pas_foto">
                <i class="bi bi-paperclip"></i> Pilih File
            </label>
            <input type="file" name="pas_foto" id="pas_foto" accept="image/*" class="hidden" style="display:none;" onchange="previewPhoto(this)">
            <span class="photo-name" id="pas_foto_name"></span>
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
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-placeholder').style.display = 'none';
            const img = document.getElementById('photo-preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('pas_foto_name').textContent = input.files[0].name;
    }
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
