@extends('admin.layout')

@section('title', 'Tambah ' . ucfirst($type))
@section('page-title', 'Tambah ' . ucfirst($type))

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Tambah {{ ucfirst($type) }} LSP</h2>
            <p class="subtitle">Tambahkan {{ $type === 'visi' ? 'visi' : 'misi' }} baru untuk ditampilkan di halaman profil</p>
        </div>
        <a href="{{ route('admin.profile-content.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.profile-content.vision-mission.store') }}" method="POST">
                @csrf

                <input type="hidden" name="type" value="{{ $type }}">

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

                <div class="form-group">
                    <label for="content">Konten <span class="required">*</span></label>
                    <textarea id="content" name="content" rows="6" 
                              class="form-control @error('content') is-invalid @enderror"
                              placeholder="Tulis konten {{ $type }} di sini...">{{ old('content') }}</textarea>
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
                        <span class="toggle-label">Aktifkan {{ $type }} ini</span>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.profile-content.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan {{ ucfirst($type) }}
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
    .btn-primary:hover { background: #003961; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #475569; }
    .btn-outline:hover { border-color: #94a3b8; color: #334155; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .required { color: #ef4444; }
    .text-optional { color: #94a3b8; font-size: 12px; font-weight: normal; }
    .help-text { font-size: 12px; color: #94a3b8; margin-top: 6px; display: block; }

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
        .form-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection
