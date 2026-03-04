@extends('admin.layout')

@section('title', 'Edit Konten Profil')
@section('page-title', 'Edit Konten')

@section('content')
<div class="page-header">
    <h2>Edit Konten Profil</h2>
    <a href="{{ route('admin.profile-content.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.profile-content.update', $content->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3>Informasi Konten</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="type">Tipe Konten <span class="required">*</span></label>
                        <select id="type" name="type" 
                                class="form-control @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="sejarah" {{ $content->type === 'sejarah' || old('type') === 'sejarah' ? 'selected' : '' }}>
                                Sejarah Singkat
                            </option>
                            <option value="visi" {{ $content->type === 'visi' || old('type') === 'visi' ? 'selected' : '' }}>
                                Visi
                            </option>
                            <option value="misi" {{ $content->type === 'misi' || old('type') === 'misi' ? 'selected' : '' }}>
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
                               value="{{ old('title', $content->title) }}" 
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
                                   value="{{ old('icon', $content->icon) }}" 
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
                                   value="{{ old('order', $content->order) }}" 
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
                               value="{{ old('order', $content->order) }}" 
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
                              placeholder="Tulis konten di sini...">{{ old('content', $content->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-wrapper">
                        <label class="toggle">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $content->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">Aktifkan konten ini</span>
                    </div>
                </div>            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> <span id="submitText">Simpan</span>
                </button>
                <a href="{{ route('admin.profile-content.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
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
            'sejarah': 'Simpan',
            'milestone': 'Simpan',
            'visi': 'Simpan',
            'misi': 'Simpan'
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
        } else {
            milestoneFields.style.display = 'none';
            visionMissionFields.style.display = 'none';
            titleField.style.display = 'block';
            titleInput.setAttribute('required', 'required');
        }
        
        submitText.textContent = typeLabels[this.value] || 'Simpan';
    });

    // Show fields on page load if type is selected
    document.getElementById('type').dispatchEvent(new Event('change'));
</script>
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

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }
    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .text-optional {
        color: #64748b;
        font-size: 12px;
        font-weight: normal;
    }

    .help-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }

    .help-text a {
        color: #0073bd;
        text-decoration: none;
    }

    .help-text a:hover {
        text-decoration: underline;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        color: #0F172A;
        transition: all 0.3s;
        font-family: inherit;
        background: #f8fafc;
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

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

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

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f1f5f9;
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        
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
@endsection
