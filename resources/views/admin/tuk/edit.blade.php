@extends('admin.layout')

@section('title', 'Edit TUK')
@section('page-title', 'Edit TUK')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-header h2 { font-size: 24px; color: #0F172A; font-weight: 700; margin: 0; }
    .btn { padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s; text-decoration: none; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #005a94; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 115, 189, 0.3); }
    .btn-secondary { background: #64748b; color: white; }
    .btn-secondary:hover { background: #475569; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); border: 1px solid #e2e8f0; margin-bottom: 24px; }
    .card-body { padding: 24px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-full { grid-column: 1 / -1; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-group .hint  { font-size: 11px; color: #94a3b8; }
    .form-control { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; transition: border-color .2s; outline: none; }
    .form-control:focus { border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    textarea.form-control { resize: vertical; min-height: 90px; }
    .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 2px; }
    .required { color: #ef4444; margin-left: 2px; }
    .form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 25px; border-top: 2px solid #f1f5f9; }
    @media(max-width:640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Edit Tempat Uji Kompetensi</h2>
    <a href="{{ route('admin.tuk.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.tuk.update', $tuk->id) }}">
    @csrf @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="form-grid">
            <div class="form-group form-full">
                <label>Nama TUK <span class="required">*</span></label>
                <input type="text" name="nama_tuk" value="{{ old('nama_tuk', $tuk->nama_tuk) }}"
                       class="form-control {{ $errors->has('nama_tuk') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: TUK SMKN 1 Ciamis">
                @error('nama_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Kode TUK</label>
                <input type="text" name="kode_tuk" value="{{ old('kode_tuk', $tuk->kode_tuk) }}"
                       class="form-control {{ $errors->has('kode_tuk') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: TUK-001">
                @error('kode_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Tipe TUK <span class="required">*</span></label>
                <select name="tipe_tuk" class="form-control {{ $errors->has('tipe_tuk') ? 'is-invalid' : '' }}">
                    <option value="sewaktu"      {{ old('tipe_tuk', $tuk->tipe_tuk) === 'sewaktu'      ? 'selected' : '' }}>TUK Sewaktu</option>
                    <option value="tempat_kerja" {{ old('tipe_tuk', $tuk->tipe_tuk) === 'tempat_kerja' ? 'selected' : '' }}>TUK Tempat Kerja</option>
                    <option value="mandiri"      {{ old('tipe_tuk', $tuk->tipe_tuk) === 'mandiri'      ? 'selected' : '' }}>TUK Mandiri</option>
                </select>
                @error('tipe_tuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Kapasitas Peserta <span class="required">*</span></label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', $tuk->kapasitas) }}" min="1"
                       class="form-control {{ $errors->has('kapasitas') ? 'is-invalid' : '' }}">
                @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group form-full">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control">{{ old('alamat', $tuk->alamat) }}</textarea>
            </div>

            <div class="form-group">
                <label>Kota/Kabupaten</label>
                <input type="text" name="kota" value="{{ old('kota', $tuk->kota) }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $tuk->provinsi) }}" class="form-control">
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $tuk->no_telepon) }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $tuk->email) }}"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-control">
                    <option value="aktif"    {{ old('status', $tuk->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $tuk->status) === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <div class="form-group form-full">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control">{{ old('keterangan', $tuk->keterangan) }}</textarea>
            </div>
        </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-actions" style="border-top: none; padding-top: 0;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.tuk.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
