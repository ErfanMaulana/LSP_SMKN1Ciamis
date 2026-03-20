@extends('admin.layout')

@section('title', 'Kelola Kontak')
@section('page-title', 'Kelola Kontak')

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

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0073bd;
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-content h3 {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin: 0 0 4px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-content p {
        font-size: 15px;
        color: #1f2937;
        margin: 0;
        word-break: break-all;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
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

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Kelola Kontak LSP</h2>
        <p>Informasi kontak terpusat</p>
    </div>
    <a href="{{ route('admin.kontak.edit') }}" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Edit Kontak
    </a>
</div>

<div class="info-card">
    <!-- Alamat -->
    <div class="info-item">
        <div class="info-icon">
            <i class="bi bi-geo-alt"></i>
        </div>
        <div class="info-content">
            <h3>Alamat</h3>
            <p>{{ $kontak->alamat ?? 'Belum diatur' }}</p>
        </div>
    </div>

    <!-- Telepon -->
    <div class="info-item">
        <div class="info-icon">
            <i class="bi bi-telephone"></i>
        </div>
        <div class="info-content">
            <h3>Nomor Telepon</h3>
            <p>{{ $kontak->telepon ?? 'Belum diatur' }}</p>
        </div>
    </div>

    <!-- Email 1 -->
    <div class="info-item">
        <div class="info-icon">
            <i class="bi bi-envelope"></i>
        </div>
        <div class="info-content">
            <h3>Email Utama</h3>
            <p>{{ $kontak->email_1 ?? 'Belum diatur' }}</p>
        </div>
    </div>
</div>
@endsection
