@extends('admin.layout')

@section('title', 'Tambah Bukti Persyaratan Dasar Pemohon')
@section('page-title', 'Tambah Bukti Persyaratan Dasar Pemohon')

@section('styles')
<style>
    .page-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:24px;
        flex-wrap:wrap;
    }

    .page-header h2 {
        margin:0;
        font-size:24px;
        font-weight:700;
        color:#0f172a;
    }

    .page-header p {
        margin:6px 0 0;
        color:#64748b;
        font-size:13px;
        max-width:720px;
    }

    .card {
        background:#fff;
        border-radius:12px;
        box-shadow:0 2px 8px rgba(0,0,0,.08);
        border:1px solid #e5e7eb;
        overflow:hidden;
    }

    .card-body {
        padding:28px;
    }

    .section-title {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:18px;
        flex-wrap:wrap;
    }

    .section-title h3 {
        margin:0;
        font-size:16px;
        font-weight:700;
        color:#0f172a;
    }

    .section-title p {
        margin:6px 0 0;
        color:#64748b;
        font-size:13px;
    }

    .form-grid {
        display:grid;
        grid-template-columns:1fr;
        gap:16px;
    }

    .form-group {
        display:flex;
        flex-direction:column;
        gap:8px;
    }

    .form-group.full-width { grid-column:1 / -1; }

    .form-label {
        font-weight:600;
        color:#334155;
        font-size:14px;
    }

    .form-control {
        width:100%;
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:12px 14px;
        font-size:14px;
        outline:none;
        background:#fff;
    }

    .form-control:focus {
        border-color:#0073bd;
        box-shadow:0 0 0 3px rgba(0,115,189,.12);
    }

    .btn-primary,
    .btn-secondary {
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 16px;
        border-radius:8px;
        text-decoration:none;
        font-weight:600;
        cursor:pointer;
        border:none;
    }

    .btn-primary { background:#0073bd; color:#fff; }
    .btn-primary:hover { background:#005f9c; }
    .btn-secondary { background:#fff; color:#334155; border:1px solid #d1d5db; }

    .requirements-card {
        margin-top:24px;
        border-top:1px solid #e5e7eb;
        padding-top:24px;
    }

    .requirement-row {
        display:flex;
        gap:10px;
        align-items:center;
        margin-bottom:10px;
    }

    .requirement-number {
        width:42px;
        min-width:42px;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        border:1px solid #d1d5db;
        border-radius:10px;
        background:#f8fafc;
        font-weight:600;
        color:#475569;
    }

    .requirement-input { flex:1; }

    .remove-btn {
        width:42px;
        height:42px;
        border:none;
        border-radius:10px;
        background:#fee2e2;
        color:#dc2626;
        cursor:pointer;
    }

    .text-danger { color:#dc2626; font-size:12px; }

    .form-actions {
        display:flex;
        gap:12px;
        justify-content:flex-end;
        margin-top:28px;
        padding-top:20px;
        border-top:1px solid #e5e7eb;
        flex-wrap:wrap;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Tambah Bukti Persyaratan Dasar Pemohon</h2>
        <p>Isi master persyaratan yang akan muncul sebagai checklist dinamis pada halaman permohonan sertifkasi.</p>
    </div>
    <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form method="POST" action="{{ route('admin.bukti-persyaratan-dasar-pemohon.store') }}">
    @csrf
    @include('admin.bukti-persyaratan-dasar-pemohon._form', ['submitLabel' => 'Simpan Data', 'item' => $item])
</form>
@endsection