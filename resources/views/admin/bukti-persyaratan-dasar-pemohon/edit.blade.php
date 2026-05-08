@extends('admin.layout')

@section('title', 'Edit Bukti Persyaratan Dasar Pemohon')
@section('page-title', 'Edit Bukti Persyaratan Dasar Pemohon')

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

    .card-body { padding:28px; }

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
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Bukti Persyaratan Dasar Pemohon</h2>
        <p>Perbarui daftar item persyaratan untuk skema yang dipilih. Perubahan akan langsung dipakai di halaman verifikasi asesi.</p>
    </div>
    <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form method="POST" action="{{ route('admin.bukti-persyaratan-dasar-pemohon.update', $item->id) }}">
    @csrf
    @method('PUT')
    @include('admin.bukti-persyaratan-dasar-pemohon._form', ['submitLabel' => 'Perbarui Data', 'item' => $item])
</form>
@endsection