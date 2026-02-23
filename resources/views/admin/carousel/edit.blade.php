@extends('admin.layout')

@section('title', 'Edit Banner Carousel')
@section('page-title', 'Edit Banner')

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Edit Banner</h2>
            <p class="subtitle">Perbarui gambar atau konten banner carousel</p>
        </div>
        <a href="{{ route('admin.carousel.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.carousel.update', $carousel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Preview Gambar --}}
                <div class="form-group">
                    <label>Gambar Banner</label>
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-placeholder" id="uploadPlaceholder" style="{{ $carousel->image ? 'display:none' : '' }}">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Klik untuk upload gambar baru</h4>
                            <p>Format: JPG, PNG, WebP | Maks: 5MB | Rasio: 16:9 (1920x1080 px)</p>
                        </div>
                        <img id="imagePreview" class="preview-image" 
                             src="{{ $carousel->image ? asset('storage/' . $carousel->image) : '' }}" 
                             style="{{ $carousel->image ? '' : 'display:none' }}"
                             onerror="this.style.display='none'; document.getElementById('uploadPlaceholder').style.display='block';">
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp" 
                           class="@error('image') is-invalid @enderror" style="display:none" onchange="previewImage(this)">
                    <small class="help-text">Kosongkan jika tidak ingin mengganti gambar</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Judul Banner <span class="required">*</span></label>
                        <input type="text" id="title" name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $carousel->title) }}" 
                               placeholder="Contoh: Sertifikasi Kompetensi">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" 
                               class="form-control @error('subtitle') is-invalid @enderror" 
                               value="{{ old('subtitle', $carousel->subtitle) }}" 
                               placeholder="Contoh: Lembaga Sertifikasi Profesi">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" 
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Deskripsi singkat">{{ old('description', $carousel->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="button_text">Teks Tombol</label>
                        <input type="text" id="button_text" name="button_text" 
                               class="form-control" 
                               value="{{ old('button_text', $carousel->button_text) }}" 
                               placeholder="Lihat Skema">
                    </div>

                    <div class="form-group">
                        <label for="button_link">Link Tombol</label>
                        <input type="text" id="button_link" name="button_link" 
                               class="form-control" 
                               value="{{ old('button_link', $carousel->button_link) }}" 
                               placeholder="#skema atau /register/asesi">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input type="number" id="urutan" name="urutan" 
                               class="form-control" 
                               value="{{ old('urutan', $carousel->urutan) }}" min="0"
                               placeholder="0">
                        <small class="help-text">Angka kecil tampil lebih dulu</small>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div class="toggle-wrapper">
                            <label class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Aktifkan banner ini</span>
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="info-box">
                    <i class="bi bi-clock"></i>
                    <span>Dibuat: {{ $carousel->created_at->format('d M Y, H:i') }} | 
                          Terakhir diubah: {{ $carousel->updated_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.carousel.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-container { padding: 0; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .form-header h2 { font-size: 22px; color: #1e293b; font-weight: 700; }
    .form-header .subtitle { font-size: 13px; color: #64748b; margin-top: 4px; }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .card-body { padding: 30px; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #0073bd; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #475569; }
    .btn-outline:hover { border-color: #94a3b8; color: #334155; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .required { color: #ef4444; }
    .help-text { font-size: 12px; color: #94a3b8; margin-top: 4px; display: block; }

    .form-control {
        width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; color: #334155; transition: all 0.2s; font-family: inherit;
    }
    .form-control:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,0.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 6px; }

    .upload-area {
        border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px 20px;
        text-align: center; cursor: pointer; transition: all 0.3s;
        background: #f8fafc; position: relative; overflow: hidden;
        aspect-ratio: 16/9; max-height: 320px; display: flex; align-items: center; justify-content: center;
    }
    .upload-area:hover { border-color: #0073bd; background: #eff6ff; }
    .upload-placeholder i { font-size: 48px; color: #94a3b8; }
    .upload-placeholder h4 { font-size: 16px; color: #475569; margin-top: 12px; }
    .upload-placeholder p { font-size: 12px; color: #94a3b8; margin-top: 6px; }
    .preview-image { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; border-radius: 10px; }

    .toggle-wrapper { display: flex; align-items: center; gap: 12px; padding-top: 8px; }
    .toggle { position: relative; display: inline-block; width: 48px; height: 26px; }
    .toggle input { display: none; }
    .toggle-slider {
        position: absolute; inset: 0; background: #cbd5e1; border-radius: 26px;
        cursor: pointer; transition: 0.3s;
    }
    .toggle-slider::before {
        content: ''; position: absolute; width: 20px; height: 20px; border-radius: 50%;
        background: white; top: 3px; left: 3px; transition: 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .toggle input:checked + .toggle-slider { background: #0073bd; }
    .toggle input:checked + .toggle-slider::before { transform: translateX(22px); }
    .toggle-label { font-size: 14px; color: #475569; }

    .info-box {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 12px 16px; font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 8px; margin-top: 10px;
    }

    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
