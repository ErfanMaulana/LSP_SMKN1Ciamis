@extends('admin.layout')

@section('title', 'Edit Jurusan')
@section('page-title', 'Edit Jurusan')

@section('styles')
<style>
    * { box-sizing: border-box; }
    .form-page { 
        height: 100vh;
        display: flex;
        flex-direction: column;
        background: #f9fafb;
    }
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: #6b7280; text-decoration: none; font-size: 13px; font-weight: 500;
        padding: 20px 40px 0;
        transition: color .2s;
        width: auto;
    }
    .back-link:hover { color: #0073bd; }
    .card {
        background: #fff;
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin: 0;
        border-radius: 0;
        box-shadow: none;
        border: none;
    }
    .card-header {
        padding: 24px 40px; border-bottom: 1px solid #e5e7eb;
        background: #f8fafc; display: flex; align-items: center; gap: 10px;
        flex-shrink: 0;
    }
    .card-header h3 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
    .card-body { 
        padding: 40px; 
        overflow-y: auto;
        flex: 1;
    }
    .info-banner {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 16px; background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: 8px; margin-bottom: 24px;
    }
    .info-banner i { font-size: 18px; color: #0073bd; flex-shrink: 0; }
    .info-banner p { font-size: 13px; color: #004a7a; margin: 0; }
    .form-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;
    }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full-width { grid-column: span 2; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .required { color: #ef4444; }
    .form-control {
        padding: 9px 13px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; width: 100%; transition: border-color .2s; outline: none;
        font-family: inherit;
    }
    .form-control:focus { border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
    .invalid-feedback { font-size: 12px; color: #ef4444; }
    .hint { font-size: 12px; color: #9ca3af; }
    .form-actions {
        display: flex; gap: 10px; justify-content: flex-end;
        padding-top: 20px; border-top: 1px solid #e5e7eb; margin-top: 4px;
    }
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 8px; font-size: 13px;
        font-weight: 600; border: none; cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .btn-primary { background: #0F172A; color: #fff; }
    .btn-primary:hover { background: #1e293b; color: #fff; }
    .btn-secondary { background: #f1f5f9; color: #475569; }
    .btn-secondary:hover { background: #e2e8f0; }
    @media (max-width: 768px) {
        .form-page { height: auto; }
        .card { flex: none; }
        .card-header { padding: 20px 24px; }
        .card-body { padding: 24px; }
        .back-link { padding: 16px 24px 0; }
        .form-row { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
    }
</style>
@endsection

@section('content')
<div class="form-page">
    <a href="{{ route('admin.jurusan.index') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Jurusan
    </a>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-pencil-square" style="color:#f59e0b;font-size:18px;"></i>
            <h3>Edit Jurusan &mdash; {{ $jurusan->nama_jurusan }}</h3>
        </div>
        <div class="card-body">
            <div class="info-banner">
                <i class="bi bi-info-circle"></i>
                <p>Jurusan ini memiliki <strong>{{ $jurusan->asesi()->count() }} asesi</strong> terdaftar.
                   Kode jurusan tidak dapat diubah ke kode yang sudah digunakan jurusan lain.</p>
            </div>

            <form action="{{ route('admin.jurusan.update', $jurusan->ID_jurusan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan <span class="required">*</span></label>
                        <input type="text" id="nama_jurusan" name="nama_jurusan"
                            class="form-control @error('nama_jurusan') is-invalid @enderror"
                            value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}"
                            placeholder="cth: Rekayasa Perangkat Lunak" required>
                        @error('nama_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan <span class="required">*</span></label>
                        <input type="text" id="kode_jurusan" name="kode_jurusan"
                            class="form-control @error('kode_jurusan') is-invalid @enderror"
                            value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}"
                            placeholder="cth: RPL" maxlength="10" required>
                        @error('kode_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="hint">Maksimal 10 karakter, harus unik.</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="visi">Visi</label>
                        <textarea id="visi" name="visi" class="form-control @error('visi') is-invalid @enderror"
                            rows="3" placeholder="Tuliskan visi jurusan ini...">{{ old('visi', $jurusan->visi) }}</textarea>
                        @error('visi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="misi">Misi</label>
                        <textarea id="misi" name="misi" class="form-control @error('misi') is-invalid @enderror"
                            rows="4" placeholder="Tuliskan misi jurusan ini...">{{ old('misi', $jurusan->misi) }}</textarea>
                        @error('misi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
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
