@extends('admin.layout')

@section('title', 'Edit Jurusan')
@section('page-title', 'Edit Jurusan')

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Edit Jurusan</h2>
            <p class="subtitle">Update academic program or major information</p>
        </div>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.jurusan.update', $jurusan->ID_jurusan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan <span class="required">*</span></label>
                        <input type="text" 
                               id="nama_jurusan" 
                               name="nama_jurusan" 
                               class="form-control @error('nama_jurusan') is-invalid @enderror" 
                               value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" 
                               placeholder="e.g., Rekayasa Perangkat Lunak"
                               required>
                        @error('nama_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan <span class="required">*</span></label>
                        <input type="text" 
                               id="kode_jurusan" 
                               name="kode_jurusan" 
                               class="form-control @error('kode_jurusan') is-invalid @enderror" 
                               value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" 
                               placeholder="e.g., RPL"
                               required>
                        @error('kode_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Additional Information</strong>
                        <p>This jurusan currently has {{ $jurusan->asesi()->count() }} registered students.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Jurusan
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
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
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

    .info-box {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #dbeafe;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .info-box i {
        font-size: 20px;
        color: #1e40af;
        flex-shrink: 0;
    }

    .info-box strong {
        display: block;
        color: #1e40af;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .info-box p {
        color: #1e40af;
        font-size: 13px;
        margin: 0;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }
</style>
@endsection
