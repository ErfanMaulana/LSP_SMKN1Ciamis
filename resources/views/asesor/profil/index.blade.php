@extends('asesor.layout')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="profile-page">
    <div class="page-header">
        <h2>Profil Asesor</h2>
        <p>Informasi profil ini terhubung langsung dengan data asesor di admin.</p>
    </div>

    <div class="profile-grid">
        <div class="card photo-card">
            <h3>Foto Profil</h3>
            <div class="photo-preview-wrap" id="photoPreviewWrap">
                @if($asesor->foto_profil)
                    <img src="{{ asset('storage/' . $asesor->foto_profil) }}" alt="Foto {{ $asesor->nama }}" class="photo-preview" id="photoPreviewImage">
                    <div class="photo-fallback" id="photoFallback" style="display:none;">{{ strtoupper(substr($asesor->nama, 0, 1)) }}</div>
                @else
                    <img src="" alt="Preview Foto {{ $asesor->nama }}" class="photo-preview" id="photoPreviewImage" style="display:none;">
                    <div class="photo-fallback" id="photoFallback">{{ strtoupper(substr($asesor->nama, 0, 1)) }}</div>
                @endif
            </div>

            <form action="{{ route('asesor.profil.update') }}" method="POST" enctype="multipart/form-data" class="photo-form">
                @csrf
                @method('PUT')

                <label class="input-label" for="foto_profil">Upload Foto Baru</label>
                <input id="foto_profil" type="file" name="foto_profil" accept="image/*" class="file-input @error('foto_profil') input-error @enderror">
                <input type="hidden" name="foto_profil_cropped" id="foto_profil_cropped" value="">
                <p class="hint">Format: JPG, PNG, WEBP. Maksimal 5MB.</p>
                <p class="hint">Setelah pilih file, kamu bisa crop, zoom, dan putar sebelum disimpan.</p>
                @error('foto_profil')
                    <p class="error-text">{{ $message }}</p>
                @enderror

                @if($asesor->foto_profil)
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="hapus_foto" value="1">
                        <span>Hapus foto profil saat ini</span>
                    </label>
                @endif

                <button type="submit" class="btn-primary">
                    <i class="bi bi-save"></i> Simpan Foto Profil
                </button>
            </form>
        </div>

        <div class="crop-modal" id="cropModal" aria-hidden="true">
            <div class="crop-modal-backdrop" id="cropModalBackdrop"></div>
            <div class="crop-modal-content" role="dialog" aria-modal="true" aria-label="Crop Foto Profil">
                <div class="crop-modal-header">
                    <h4>Atur Foto Profil</h4>
                    <button type="button" class="crop-close" id="cropCloseButton">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="crop-canvas-wrap">
                    <img id="cropTargetImage" alt="Target Crop Foto" src="">
                </div>

                <div class="crop-tools">
                    <button type="button" class="crop-tool-btn" id="cropZoomIn"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="crop-tool-btn" id="cropZoomOut"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="crop-tool-btn" id="cropRotateLeft"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="crop-tool-btn" id="cropRotateRight"><i class="bi bi-arrow-clockwise"></i></button>
                    <button type="button" class="crop-tool-btn" id="cropReset"><i class="bi bi-arrow-repeat"></i></button>
                </div>

                <div class="crop-actions">
                    <button type="button" class="btn-secondary" id="cropCancel">Batal</button>
                    <button type="button" class="btn-primary" id="cropApply">Gunakan Hasil Crop</button>
                </div>
            </div>
        </div>

        <div class="profile-main-column">
            <div class="card detail-card">
                <h3>Informasi Asesor</h3>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nama Lengkap</label>
                        <div class="detail-value">{{ $asesor->nama }}</div>
                    </div>

                    <div class="detail-item">
                        <label>No. Registrasi</label>
                        <div class="detail-value">
                            @if($asesor->no_met)
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle-fill"></i> {{ $asesor->no_met }}
                                </span>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item detail-item-full">
                        <label>Skema Sertifikasi</label>
                        <div class="detail-value">
                            @if($asesor->skemas->count())
                                <div class="skema-list">
                                    @foreach($asesor->skemas as $skema)
                                        <div class="skema-item">
                                            <div class="skema-name">{{ $skema->nama_skema }}</div>
                                            @if($skema->nomor_skema)
                                                <div class="skema-number">{{ $skema->nomor_skema }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Status Login</label>
                        <div class="detail-value">
                            @if($asesor->no_met)
                                <span class="badge badge-active">
                                    <i class="bi bi-person-check-fill"></i> Akun Aktif
                                </span>
                            @else
                                <span class="badge badge-inactive">
                                    <i class="bi bi-person-x-fill"></i> Tidak Ada Akun
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Status Asesor</label>
                        <div class="detail-value">
                            @if($asesor->skemas->count())
                                <span class="badge badge-active">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="badge badge-inactive">
                                    <i class="bi bi-x-circle-fill"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Dibuat pada</label>
                        <div class="detail-value">
                            @if($asesor->created_at)
                                {{ \Carbon\Carbon::parse($asesor->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Terakhir diupdate</label>
                        <div class="detail-value">
                            @if($asesor->updated_at)
                                {{ \Carbon\Carbon::parse($asesor->updated_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card password-card" id="password-form">
                <h3>Ubah Password</h3>
                <p class="password-intro">Gunakan password yang kuat untuk menjaga keamanan akun asesor Anda.</p>

                <form action="{{ route('asesor.password.update') }}" method="POST" class="password-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="password_lama">Password Lama</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock"></i>
                            <input id="password_lama" type="password" name="password_lama" class="form-input @error('password_lama') input-error @enderror" required>
                            <button type="button" class="toggle-password" data-target="password_lama" aria-label="Tampilkan password lama">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password_lama')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-lock"></i>
                            <input id="password_baru" type="password" name="password_baru" class="form-input @error('password_baru') input-error @enderror" required>
                            <button type="button" class="toggle-password" data-target="password_baru" aria-label="Tampilkan password baru">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <p class="password-hint">Minimal 8 karakter dan harus berbeda dari password lama.</p>
                        @error('password_baru')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-check"></i>
                            <input id="password_baru_confirmation" type="password" name="password_baru_confirmation" class="form-input" required>
                            <button type="button" class="toggle-password" data-target="password_baru_confirmation" aria-label="Tampilkan konfirmasi password baru">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check2-circle"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('foto_profil');
        const croppedInput = document.getElementById('foto_profil_cropped');
        const imagePreview = document.getElementById('photoPreviewImage');
        const fallbackPreview = document.getElementById('photoFallback');
        const cropModal = document.getElementById('cropModal');
        const cropModalBackdrop = document.getElementById('cropModalBackdrop');
        const cropTargetImage = document.getElementById('cropTargetImage');
        const cropCloseButton = document.getElementById('cropCloseButton');
        const cropCancelButton = document.getElementById('cropCancel');
        const cropApplyButton = document.getElementById('cropApply');
        const cropZoomIn = document.getElementById('cropZoomIn');
        const cropZoomOut = document.getElementById('cropZoomOut');
        const cropRotateLeft = document.getElementById('cropRotateLeft');
        const cropRotateRight = document.getElementById('cropRotateRight');
        const cropReset = document.getElementById('cropReset');

        let cropper = null;
        let objectUrl = null;

        if (!fileInput || !croppedInput || !imagePreview || !fallbackPreview || !cropModal || !cropTargetImage) {
            return;
        }

        const closeCropModal = function () {
            cropModal.classList.remove('show');
            cropModal.setAttribute('aria-hidden', 'true');

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
        };

        const cancelCropSelection = function () {
            closeCropModal();
            fileInput.value = '';
            croppedInput.value = '';
        };

        const openCropModal = function (file) {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
            }

            objectUrl = URL.createObjectURL(file);
            cropTargetImage.src = objectUrl;
            cropModal.classList.add('show');
            cropModal.setAttribute('aria-hidden', 'false');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropTargetImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                responsive: true,
                restore: false,
                guides: false,
                center: true,
                highlight: false,
                background: false,
                movable: true,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
            });
        };

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {
                return;
            }

            croppedInput.value = '';
            openCropModal(file);
        });

        cropApplyButton.addEventListener('click', function () {
            if (!cropper) {
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 600,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
            croppedInput.value = dataUrl;
            imagePreview.src = dataUrl;
            imagePreview.style.display = 'block';
            fallbackPreview.style.display = 'none';

            closeCropModal();
        });

        cropCancelButton.addEventListener('click', cancelCropSelection);
        cropCloseButton.addEventListener('click', cancelCropSelection);
        cropModalBackdrop.addEventListener('click', cancelCropSelection);

        cropZoomIn.addEventListener('click', function () {
            if (cropper) cropper.zoom(0.1);
        });

        cropZoomOut.addEventListener('click', function () {
            if (cropper) cropper.zoom(-0.1);
        });

        cropRotateLeft.addEventListener('click', function () {
            if (cropper) cropper.rotate(-45);
        });

        cropRotateRight.addEventListener('click', function () {
            if (cropper) cropper.rotate(45);
        });

        cropReset.addEventListener('click', function () {
            if (cropper) cropper.reset();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && cropModal.classList.contains('show')) {
                cancelCropSelection();
            }
        });
    });
</script>
<script>
    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            const icon = button.querySelector('i');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            if (icon) {
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            }
        });
    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<style>
    .profile-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .page-header p {
        color: #64748b;
        font-size: 14px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: minmax(280px, 320px) 1fr;
        gap: 20px;
        align-items: start;
    }

    .photo-card {
        height: fit-content;
    }

    .profile-main-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    #password-form {
        scroll-margin-top: 88px;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        font-size: 18px;
        margin-bottom: 18px;
        color: #0f172a;
    }

    .photo-preview-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .photo-preview,
    .photo-fallback {
        width: 144px;
        height: 144px;
        border-radius: 50%;
    }

    .photo-preview {
        object-fit: cover;
        border: 4px solid #e2e8f0;
    }

    .photo-fallback {
        background: linear-gradient(135deg, #0073bd, #00558e);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 48px;
        font-weight: 700;
        border: 4px solid #e2e8f0;
    }

    .photo-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .input-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .file-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px;
        font-size: 13px;
    }

    .input-error {
        border-color: #ef4444;
    }

    .hint {
        color: #64748b;
        font-size: 12px;
        margin-top: -4px;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
    }

    .checkbox-wrap {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        font-size: 13px;
        color: #334155;
    }

    .btn-primary {
        margin-top: 4px;
        border: none;
        border-radius: 8px;
        background: #0073bd;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        padding: 11px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #00558e;
    }

    .btn-secondary {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        background: #f8fafc;
    }

    .crop-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 1100;
    }

    .crop-modal.show {
        display: block;
    }

    .crop-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
    }

    .crop-modal-content {
        position: relative;
        width: min(92vw, 760px);
        background: #fff;
        margin: 4vh auto;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
    }

    .crop-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .crop-modal-header h4 {
        font-size: 17px;
        color: #0f172a;
    }

    .crop-close {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        color: #334155;
        cursor: pointer;
    }

    .crop-close:hover {
        background: #e2e8f0;
    }

    .crop-canvas-wrap {
        width: 100%;
        min-height: 320px;
        max-height: 58vh;
        overflow: hidden;
        border-radius: 10px;
        background: #f8fafc;
    }

    .crop-canvas-wrap img {
        max-width: 100%;
        display: block;
    }

    .crop-tools {
        margin-top: 12px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .crop-tool-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #334155;
        cursor: pointer;
        font-size: 16px;
    }

    .crop-tool-btn:hover {
        background: #f8fafc;
    }

    .crop-actions {
        margin-top: 14px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item-full {
        grid-column: 1 / -1;
    }

    .detail-item label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .detail-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 500;
    }

    .text-muted {
        color: #94a3b8;
        font-style: italic;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-active {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .skema-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .skema-item {
        padding: 10px 12px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .skema-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
    }

    .skema-number {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .password-card {
        border: 1px solid #e2e8f0;
    }

    .password-intro {
        color: #64748b;
        font-size: 13px;
        margin-top: -8px;
        margin-bottom: 16px;
    }

    .password-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap > i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
    }

    .form-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 42px 10px 38px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.12);
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #94a3b8;
        width: 24px;
        height: 24px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .toggle-password:hover {
        color: #475569;
    }

    .password-hint {
        color: #64748b;
        font-size: 12px;
        margin-top: -3px;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .profile-main-column {
            gap: 14px;
        }

        .crop-modal-content {
            width: calc(100vw - 16px);
            margin: 8px auto;
            padding: 14px;
        }

        .crop-canvas-wrap {
            min-height: 260px;
            max-height: 52vh;
        }
    }

    @media (max-width: 640px) {
        .profile-page {
            gap: 14px;
        }

        .page-header h2 {
            font-size: 20px;
        }

        .page-header p {
            font-size: 13px;
        }

        .card {
            padding: 16px;
            border-radius: 10px;
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 14px;
        }

        .password-intro {
            margin-top: -4px;
            margin-bottom: 12px;
        }

        .photo-preview,
        .photo-fallback {
            width: 118px;
            height: 118px;
        }

        .photo-fallback {
            font-size: 36px;
        }

        .btn-primary {
            width: 100%;
        }

        .crop-tools {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
        }

        .crop-tool-btn {
            width: 100%;
        }

        .crop-actions {
            flex-direction: column-reverse;
            gap: 8px;
        }

        .crop-actions .btn-primary,
        .crop-actions .btn-secondary {
            width: 100%;
        }
    }

    @media (max-width: 420px) {
        .crop-canvas-wrap {
            min-height: 220px;
            max-height: 46vh;
        }
    }
</style>
@endsection
