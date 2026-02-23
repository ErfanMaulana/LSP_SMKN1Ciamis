@extends('admin.layout')

@section('title', 'Tambah Sosial Media')
@section('page-title', 'Tambah Sosial Media')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('admin.socialmedia.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h2>Tambah Sosial Media</h2>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.socialmedia.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Platform <span class="required">*</span></label>
                <select name="platform" class="form-control @error('platform') is-error @enderror" required onchange="updatePreview(this.value)">
                    <option value="">-- Pilih Platform --</option>
                    <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="youtube"   {{ old('platform') == 'youtube'   ? 'selected' : '' }}>YouTube</option>
                    <option value="facebook"  {{ old('platform') == 'facebook'  ? 'selected' : '' }}>Facebook</option>
                    <option value="tiktok"    {{ old('platform') == 'tiktok'    ? 'selected' : '' }}>TikTok</option>
                    <option value="twitter"   {{ old('platform') == 'twitter'   ? 'selected' : '' }}>Twitter / X</option>
                    <option value="whatsapp"  {{ old('platform') == 'whatsapp'  ? 'selected' : '' }}>WhatsApp</option>
                    <option value="linkedin"  {{ old('platform') == 'linkedin'  ? 'selected' : '' }}>LinkedIn</option>
                </select>
                @error('platform')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Nama Tampilan <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Instagram" class="form-control @error('name') is-error @enderror" required>
                @error('name')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>URL <span class="required">*</span></label>
                <input type="url" name="url" value="{{ old('url') }}" placeholder="https://www.instagram.com/smkn1ciamis" class="form-control @error('url') is-error @enderror" required>
                @error('url')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" class="form-control">
                    <p class="form-hint">Angka lebih kecil = tampil lebih awal</p>
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

            <div class="form-actions">
                <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-container { max-width: 640px; }
    .form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .form-header h2 { font-size: 20px; color: #1e293b; font-weight: 700; margin: 0; }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: 14px; padding: 8px 14px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; transition: all 0.2s; }
    .back-btn:hover { background: #f8fafc; color: #334155; }
    .form-card { background: white; border-radius: 12px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .form-group { margin-bottom: 20px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; }
    .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; color: #1e293b; outline: none; transition: border 0.2s; background: white; }
    .form-control:focus { border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,0.1); }
    .form-control.is-error { border-color: #ef4444; }
    .error-msg { color: #ef4444; font-size: 12px; margin-top: 4px; display: block; }
    .form-hint { font-size: 12px; color: #94a3b8; margin-top: 4px; }

    .toggle-wrap { display: flex; align-items: center; gap: 12px; padding: 10px 0; }
    .toggle { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: #e2e8f0; border-radius: 24px; transition: 0.3s; }
    .toggle-slider:before { content: ''; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; }
    .toggle input:checked + .toggle-slider { background: #0073bd; }
    .toggle input:checked + .toggle-slider:before { transform: translateX(20px); }
    .toggle-label { font-size: 14px; color: #374151; font-weight: 400; }

    .preview-section { margin-bottom: 20px; }
    .preview-box { display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; margin-top: 8px; }
    .preview-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; }

    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #f1f5f9; margin-top: 8px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #0073bd; }
    .btn-cancel { background: #f8fafc; color: #64748b; border: 1.5px solid #e2e8f0; }
    .btn-cancel:hover { background: #f1f5f9; }
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
