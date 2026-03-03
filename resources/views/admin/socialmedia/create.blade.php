@extends('admin.layout')

@section('title', 'Tambah Sosial Media')
@section('page-title', 'Tambah Sosial Media')

@section('content')
<div class="page-header">
    <h2>Tambah Sosial Media</h2>
    <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.socialmedia.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h3>Informasi Media Sosial</h3>

                <div class="form-group">
                    <label>Platform <span class="required">*</span></label>
                    <select name="platform" class="form-control @error('platform') is-invalid @enderror" required onchange="updatePreview(this.value)">
                        <option value="">-- Pilih Platform --</option>
                        <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="youtube"   {{ old('platform') == 'youtube'   ? 'selected' : '' }}>YouTube</option>
                        <option value="facebook"  {{ old('platform') == 'facebook'  ? 'selected' : '' }}>Facebook</option>
                        <option value="tiktok"    {{ old('platform') == 'tiktok'    ? 'selected' : '' }}>TikTok</option>
                        <option value="twitter"   {{ old('platform') == 'twitter'   ? 'selected' : '' }}>Twitter / X</option>
                        <option value="whatsapp"  {{ old('platform') == 'whatsapp'  ? 'selected' : '' }}>WhatsApp</option>
                        <option value="linkedin"  {{ old('platform') == 'linkedin'  ? 'selected' : '' }}>LinkedIn</option>
                    </select>
                    @error('platform')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nama Tampilan <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Instagram" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>URL <span class="required">*</span></label>
                    <input type="url" name="url" value="{{ old('url') }}" placeholder="https://www.instagram.com/smkn1ciamis" class="form-control @error('url') is-invalid @enderror" required>
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Urutan Tampil</label>
                        <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" class="form-control">
                        <small class="form-text">Angka lebih kecil = tampil lebih awal</small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="toggle-wrap">
                            <label class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Aktif (tampil di footer)</span>
                        </div>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="preview-section" id="preview-section" style="display:none">
                    <label>Preview Tampilan</label>
                    <div class="preview-box">
                        <div id="preview-icon" class="preview-icon"><i id="preview-bi" class="bi bi-globe"></i></div>
                        <span id="preview-name">Nama Platform</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3:before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        background: #f8fafc;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #0073bd;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
    }

    .toggle {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #e2e8f0;
        border-radius: 24px;
        transition: 0.3s;
    }

    .toggle-slider:before {
        content: '';
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: 0.3s;
    }

    .toggle input:checked + .toggle-slider {
        background: #0073bd;
    }

    .toggle input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    .toggle-label {
        font-size: 14px;
        color: #374151;
        font-weight: 400;
    }

    .preview-section {
        margin-bottom: 20px;
    }

    .preview-box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        margin-top: 8px;
        background: white;
    }

    .preview-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f1f5f9;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #005a94;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>
<script>
const platformData = {
    instagram: { color: '#e1306c', icon: 'bi-instagram', name: 'Instagram' },
    youtube:   { color: '#ff0000', icon: 'bi-youtube',   name: 'YouTube'   },
    facebook:  { color: '#1877f2', icon: 'bi-facebook',  name: 'Facebook'  },
    tiktok:    { color: '#010101', icon: 'bi-tiktok',    name: 'TikTok'    },
    twitter:   { color: '#1da1f2', icon: 'bi-twitter-x', name: 'Twitter/X' },
    whatsapp:  { color: '#25d366', icon: 'bi-whatsapp',  name: 'WhatsApp'  },
    linkedin:  { color: '#0a66c2', icon: 'bi-linkedin',  name: 'LinkedIn'  },
};
function updatePreview(val) {
    const sec = document.getElementById('preview-section');
    const ico = document.getElementById('preview-icon');
    const bi  = document.getElementById('preview-bi');
    const nm  = document.getElementById('preview-name');
    const nameInput = document.querySelector('input[name="name"]');
    if (!val || !platformData[val]) { sec.style.display = 'none'; return; }
    const p = platformData[val];
    sec.style.display = 'block';
    ico.style.background = p.color + '1a';
    ico.style.color = p.color;
    bi.className = 'bi ' + p.icon;
    nm.textContent = nameInput.value || p.name;
    if (!nameInput.value) nameInput.value = p.name;
}
</script>
@endsection
