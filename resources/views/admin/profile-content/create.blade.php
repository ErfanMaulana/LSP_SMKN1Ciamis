@extends('admin.layout')

@section('title', 'Tambah Konten Profil')
@section('page-title', 'Tambah Konten')

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Tambah Konten Profil</h2>
            <p class="subtitle">Tambahkan Sejarah, Visi, atau Misi untuk halaman profil</p>
        </div>
        <a href="{{ route('admin.profile-content.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.profile-content.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="type">Tipe Konten <span class="required">*</span></label>
                        <select id="type" name="type" 
                                class="form-control @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="sejarah" {{ old('type') === 'sejarah' ? 'selected' : '' }}>
                                Sejarah Singkat
                            </option>
                            <option value="visi" {{ old('type') === 'visi' ? 'selected' : '' }}>
                                Visi
                            </option>
                            <option value="misi" {{ old('type') === 'misi' ? 'selected' : '' }}>
                                Misi
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="titleField">
                        <label for="title">Judul <span class="required">*</span></label>
                        <input type="text" id="title" name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="Contoh: Inisiasi & Persiapan">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="milestoneFields" style="display: none;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="icon">Icon Bootstrap <span class="text-optional">(Opsional)</span></label>
                            <input type="text" id="icon" name="icon" 
                                   class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon') }}" 
                                   placeholder="Contoh: bi bi-star-fill">
                            <small class="help-text">
                                Lihat <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> untuk pilihan icon
                            </small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Urutan <span class="text-optional">(Opsional)</span></label>
                            <input type="number" id="order" name="order" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   value="{{ old('order', 0) }}" 
                                   min="0"
                                   placeholder="Contoh: 1">
                            <small class="help-text">Angka kecil tampil lebih dulu</small>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="visionMissionFields" style="display: none;">
                    <div class="form-group">
                        <label for="order-vm">Urutan <span class="text-optional">(Opsional)</span></label>
                        <input type="number" id="order-vm" name="order" 
                               class="form-control @error('order') is-invalid @enderror" 
                               value="{{ old('order', 0) }}" 
                               min="0"
                               placeholder="Contoh: 1">
                        <small class="help-text">Angka kecil tampil lebih dulu</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">Konten <span class="required">*</span></label>
                    <textarea id="content" name="content" rows="5" 
                              class="form-control @error('content') is-invalid @enderror"
                              placeholder="Tulis konten di sini...">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-wrapper">
                        <label class="toggle">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">Aktifkan konten ini</span>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.profile-content.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> <span id="submitText">Simpan Konten</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('type').addEventListener('change', function() {
        const milestoneFields = document.getElementById('milestoneFields');
        const visionMissionFields = document.getElementById('visionMissionFields');
        const titleField = document.getElementById('titleField');
        const titleInput = document.getElementById('title');
        const submitText = document.getElementById('submitText');
        
        const typeLabels = {
            'sejarah': 'Simpan Sejarah',
            'milestone': 'Simpan Milestone',
            'visi': 'Simpan Visi',
            'misi': 'Simpan Misi'
        };
        
        if (this.value === 'sejarah' || this.value === 'milestone') {
            milestoneFields.style.display = 'block';
            visionMissionFields.style.display = 'none';
            titleField.style.display = 'block';
            titleInput.setAttribute('required', 'required');
        } else if (this.value === 'visi' || this.value === 'misi') {
            milestoneFields.style.display = 'none';
            visionMissionFields.style.display = 'block';
            titleField.style.display = 'none';
            titleInput.removeAttribute('required');
            titleInput.value = '';
        } else {
            milestoneFields.style.display = 'none';
            visionMissionFields.style.display = 'none';
            titleField.style.display = 'block';
            titleInput.setAttribute('required', 'required');
        }
        
        submitText.textContent = typeLabels[this.value] || 'Simpan Konten';
    });

    // Show fields on page load if type is selected
    document.getElementById('type').dispatchEvent(new Event('change'));
</script>
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
    .btn-primary:hover { background: #003961; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #475569; }
    .btn-outline:hover { border-color: #94a3b8; color: #334155; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .required { color: #ef4444; }
    .text-optional { color: #94a3b8; font-size: 12px; font-weight: normal; }
    .help-text { font-size: 12px; color: #94a3b8; margin-top: 6px; display: block; }
    .help-text a { color: #0073bd; text-decoration: none; }
    .help-text a:hover { text-decoration: underline; }

    .form-control {
        width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; color: #334155; transition: all 0.2s; font-family: inherit;
    }
    .form-control:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,0.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 6px; }

    /* Toggle */
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

    .form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    .form-actions .btn { flex: 1; justify-content: center; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection
