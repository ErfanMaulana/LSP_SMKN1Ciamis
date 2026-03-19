@extends('admin.layout')

@section('title', 'Edit Kontak')
@section('page-title', 'Edit Kontak')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label.required::after {
        content: " *";
        color: #dc2626;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-input:focus,
    .form-textarea:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-hint {
        color: #6b7280;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #0073bd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #003961;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        margin-top: 32px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #eff6ff;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Kontak LSP</h2>
        <p>Perbarui informasi kontak</p>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.kontak.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <h3 class="section-title">Informasi Dasar</h3>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-textarea @error('alamat') error @enderror" 
                      placeholder="Masukkan alamat lengkap LSP">{{ old('alamat', $kontak->alamat ?? '') }}</textarea>
            @error('alamat')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Kontak Telepon -->
        <h3 class="section-title">Kontak Telepon</h3>

        <div class="form-row full">
            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="tel" name="telepon" class="form-input @error('telepon') error @enderror" 
                       placeholder="(0265) 771234" 
                       value="{{ old('telepon', $kontak->telepon ?? '') }}">
                @error('telepon')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <h3 class="section-title">Alamat Email</h3>

        <div class="form-row full">
            <div class="form-group">
                <label class="form-label">Email Utama</label>
                <input type="email" name="email_1" class="form-input @error('email_1') error @enderror" 
                       placeholder="email@example.com" 
                       value="{{ old('email_1', $kontak->email_1 ?? '') }}">
                @error('email_1')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.kontak.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection
