@extends('admin.layout')

@section('title', 'Edit Asesor')
@section('page-title', 'Edit Asesor')

@section('content')
<div class="page-header">
    <h2>Edit Data Asesor</h2>
    <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.asesor.update', $asesor->ID_asesor) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h3>Informasi Asesor</h3>
                
                <div class="form-group">
                    <label for="nama">Nama Asesor <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $asesor->nama) }}" required autofocus>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ID_skema">ID Skema</label>
                        <input type="number" id="ID_skema" name="ID_skema" class="form-control @error('ID_skema') is-invalid @enderror" value="{{ old('ID_skema', $asesor->ID_skema) }}">
                        @error('ID_skema')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Masukkan ID skema sertifikasi (opsional)</small>
                    </div>

                    <div class="form-group">
                        <label for="no_mou">Mitra (No MoU)</label>
                        <select id="no_mou" name="no_mou" class="form-control @error('no_mou') is-invalid @enderror">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitra as $item)
                                <option value="{{ $item->no_mou }}" {{ old('no_mou', $asesor->no_mou) == $item->no_mou ? 'selected' : '' }}>
                                    {{ $item->nama_mitra }} ({{ $item->no_mou }})
                                </option>
                            @endforeach
                        </select>
                        @error('no_mou')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Pilih mitra yang bekerja sama (opsional)</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
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
        background: #3b82f6;
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
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    select.form-control {
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .card-body {
            padding: 20px;
        }
    }
</style>
@endsection
