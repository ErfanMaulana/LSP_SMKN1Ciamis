@extends('admin.layout')

@section('title', 'Tambah Mitra')
@section('page-title', 'Tambah Mitra')

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Tambah Mitra Baru</h2>
            <p class="subtitle">Tambahkan mitra industri dan informasi MOU</p>
        </div>
        <a href="{{ route('admin.mitra.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.mitra.store') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="no_mou">Nomor MOU <span class="required">*</span></label>
                        <input type="text" 
                               id="no_mou" 
                               name="no_mou" 
                               class="form-control @error('no_mou') is-invalid @enderror" 
                               value="{{ old('no_mou') }}" 
                               placeholder="Contoh: MOU/2024/001"
                               required>
                        @error('no_mou')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_mitra">Nama Mitra <span class="required">*</span></label>
                        <input type="text" 
                               id="nama_mitra" 
                               name="nama_mitra" 
                               class="form-control @error('nama_mitra') is-invalid @enderror" 
                               value="{{ old('nama_mitra') }}" 
                               placeholder="Contoh: PT Maju Bersama"
                               required>
                        @error('nama_mitra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="jenis_usaha">Jenis Usaha</label>
                    <input type="text" 
                           id="jenis_usaha" 
                           name="jenis_usaha" 
                           class="form-control @error('jenis_usaha') is-invalid @enderror" 
                           value="{{ old('jenis_usaha') }}" 
                           placeholder="Contoh: Software Development, Manufaktur, dll">
                    @error('jenis_usaha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="tanggal_mou">Tanggal MOU</label>
                        <input type="date" 
                               id="tanggal_mou" 
                               name="tanggal_mou" 
                               class="form-control @error('tanggal_mou') is-invalid @enderror" 
                               value="{{ old('tanggal_mou') }}">
                        @error('tanggal_mou')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_berakhir">Tanggal Berakhir</label>
                        <input type="date" 
                               id="tanggal_berakhir" 
                               name="tanggal_berakhir" 
                               class="form-control @error('tanggal_berakhir') is-invalid @enderror" 
                               value="{{ old('tanggal_berakhir') }}">
                        @error('tanggal_berakhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Tanggal berakhir harus sama atau setelah tanggal MOU</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan Mitra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-container {
        padding: 0;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .form-header h2 {
        font-size: 28px;
        color: #0F172A;
        font-weight: 700;
        margin: 0 0 4px 0;
    }

    .subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: #0F172A;
        color: white;
    }

    .btn-primary:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .btn-outline {
        background: white;
        color: #0F172A;
        border: 1px solid #e2e8f0;
    }

    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 32px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 13px;
        color: #ef4444;
        margin-top: 4px;
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
</style>
@endsection
