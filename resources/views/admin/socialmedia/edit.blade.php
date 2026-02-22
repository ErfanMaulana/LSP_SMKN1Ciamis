@extends('admin.layout')

@section('title', 'Edit Sosial Media')
@section('page-title', 'Edit Sosial Media')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('admin.socialmedia.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h2>Edit Sosial Media</h2>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.socialmedia.update', $socialMedia->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Platform <span class="required">*</span></label>
                <select name="platform" class="form-control @error('platform') is-error @enderror" required onchange="updatePreview(this.value)">
                    <option value="">-- Pilih Platform --</option>
                    <option value="instagram" {{ old('platform', $socialMedia->platform) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="youtube"   {{ old('platform', $socialMedia->platform) == 'youtube'   ? 'selected' : '' }}>YouTube</option>
                    <option value="facebook"  {{ old('platform', $socialMedia->platform) == 'facebook'  ? 'selected' : '' }}>Facebook</option>
                    <option value="tiktok"    {{ old('platform', $socialMedia->platform) == 'tiktok'    ? 'selected' : '' }}>TikTok</option>
                    <option value="twitter"   {{ old('platform', $socialMedia->platform) == 'twitter'   ? 'selected' : '' }}>Twitter / X</option>
                    <option value="whatsapp"  {{ old('platform', $socialMedia->platform) == 'whatsapp'  ? 'selected' : '' }}>WhatsApp</option>
                    <option value="linkedin"  {{ old('platform', $socialMedia->platform) == 'linkedin'  ? 'selected' : '' }}>LinkedIn</option>
                </select>
                @error('platform')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Nama Tampilan <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $socialMedia->name) }}" placeholder="Contoh: Instagram" class="form-control @error('name') is-error @enderror" required>
                @error('name')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>URL <span class="required">*</span></label>
                <input type="url" name="url" value="{{ old('url', $socialMedia->url) }}" placeholder="https://www.instagram.com/smkn1ciamis" class="form-control @error('url') is-error @enderror" required>
                @error('url')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $socialMedia->urutan) }}" min="0" class="form-control">
                    <p class="form-hint">Angka lebih kecil = tampil lebih awal</p>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $socialMedia->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">Aktif (tampil di footer)</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.socialmedia.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
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
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-control.is-error { border-color: #ef4444; }
    .error-msg { color: #ef4444; font-size: 12px; margin-top: 4px; display: block; }
    .form-hint { font-size: 12px; color: #94a3b8; margin-top: 4px; }

    .toggle-wrap { display: flex; align-items: center; gap: 12px; padding: 10px 0; }
    .toggle { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: #e2e8f0; border-radius: 24px; transition: 0.3s; }
    .toggle-slider:before { content: ''; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; }
    .toggle input:checked + .toggle-slider { background: #3b82f6; }
    .toggle input:checked + .toggle-slider:before { transform: translateX(20px); }
    .toggle-label { font-size: 14px; color: #374151; font-weight: 400; }

    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #f1f5f9; margin-top: 8px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #3b82f6; color: white; }
    .btn-primary:hover { background: #2563eb; }
    .btn-cancel { background: #f8fafc; color: #64748b; border: 1.5px solid #e2e8f0; }
    .btn-cancel:hover { background: #f1f5f9; }
</style>
@endsection
