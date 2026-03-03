@extends('admin.layout')

@section('title', 'Tambah Mitra')
@section('page-title', 'Tambah Mitra')

@section('content')
<div class="page-header">
    <h2>Tambah Mitra Baru</h2>
    <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.mitra.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Informasi Mitra</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="no_mou">Nomor MOU <span class="required">*</span></label>
                        <input type="text" 
                               id="no_mou" 
                               name="no_mou" 
                               class="form-control @error('no_mou') is-invalid @enderror" 
                               value="{{ old('no_mou') }}" 
                               placeholder="Contoh: MOU/2024/001"
                               required
                               autofocus>
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

                <div class="form-row">
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
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

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
@endsection
