@extends('admin.layout')

@section('title', 'Tambah TUK')
@section('page-title', 'Tambah TUK')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px; }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .card-body { padding: 32px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-full { grid-column: 1 / -1; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-group .hint  { font-size: 11px; color: #94a3b8; }
    .form-control {
        padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; font-family: inherit; transition: border-color .2s; outline: none;
    }
    .form-control:focus { border-color: #0061a5; box-shadow: 0 0 0 3px rgba(0,97,165,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    textarea.form-control { resize: vertical; min-height: 90px; }
    .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 2px; }
    .required { color: #ef4444; margin-left: 2px; }

    .btn { padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #0073bd; transform: translateY(-1px); }
    .btn-secondary { background: #64748b; color: white; }
    .btn-secondary:hover { background: #475569; }
    
    .form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 25px; border-top: 2px solid #f1f5f9; }
    
    @media(max-width:640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Tambah Tempat Uji Kompetensi (TUK)</h2>
    <a href="{{ route('admin.tuk.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

    <form method="POST" action="{{ route('admin.tuk.store') }}">
        @csrf

        <div class="form-grid">
            <!-- Nama TUK -->
            <div class="form-group form-full">
                <label>Nama TUK <span class="required">*</span></label>
                <input type="text" name="nama_tuk" value="{{ old('nama_tuk') }}"
                       class="form-control {{ $errors->has('nama_tuk') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: TUK SMKN 1 Ciamis">
                @error('nama_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Kode TUK -->
            <div class="form-group">
                <label>Kode TUK</label>
                <input type="text" name="kode_tuk" value="{{ old('kode_tuk') }}"
                       class="form-control {{ $errors->has('kode_tuk') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: TUK-001">
                <span class="hint">Kode unik untuk identifikasi TUK</span>
                @error('kode_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Tipe TUK -->
            <div class="form-group">
                <label>Tipe TUK <span class="required">*</span></label>
                <select name="tipe_tuk" class="form-control {{ $errors->has('tipe_tuk') ? 'is-invalid' : '' }}">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="sewaktu"      {{ old('tipe_tuk') === 'sewaktu'      ? 'selected' : '' }}>TUK Sewaktu</option>
                    <option value="tempat_kerja" {{ old('tipe_tuk') === 'tempat_kerja' ? 'selected' : '' }}>TUK Tempat Kerja</option>
                    <option value="mandiri"      {{ old('tipe_tuk') === 'mandiri'      ? 'selected' : '' }}>TUK Mandiri</option>
                </select>
                @error('tipe_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Kapasitas -->
            <div class="form-group">
                <label>Kapasitas Peserta <span class="required">*</span></label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', 30) }}" min="1"
                       class="form-control {{ $errors->has('kapasitas') ? 'is-invalid' : '' }}"
                       placeholder="Jumlah peserta maksimal">
                @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Alamat -->
            <div class="form-group form-full">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                          placeholder="Jalan, Nomor, RT/RW, Kelurahan...">{{ old('alamat') }}</textarea>
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Kota -->
            <div class="form-group">
                <label>Kota/Kabupaten</label>
                <input type="text" name="kota" value="{{ old('kota') }}"
                       class="form-control" placeholder="Contoh: Ciamis">
            </div>

            <!-- Provinsi -->
            <div class="form-group">
                <label>Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', 'Jawa Barat') }}"
                       class="form-control" placeholder="Contoh: Jawa Barat">
            </div>

            <!-- No. Telepon -->
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                       class="form-control" placeholder="Contoh: 0265-771234">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="email@example.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-control">
                    <option value="aktif"    {{ old('status', 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="form-group form-full">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"
                          placeholder="Informasi tambahan tentang TUK ini...">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
            <a href="{{ route('admin.tuk.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </div>
</div>
@endsection
