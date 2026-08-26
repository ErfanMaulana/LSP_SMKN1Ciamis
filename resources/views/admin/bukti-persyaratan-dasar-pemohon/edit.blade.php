@extends('admin.layout')

@section('title', 'Edit Bukti Persyaratan Dasar Pemohon')
@section('page-title', 'Edit Bukti Persyaratan Dasar Pemohon')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
        max-width: 720px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .btn-back:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
        transform: translateX(-2px);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Bukti Persyaratan Dasar Pemohon</h2>
        <p>Perbarui daftar item persyaratan untuk skema yang dipilih. Perubahan akan langsung dipakai di halaman permohonan sertifikasi.</p>
    </div>
    <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
</div>

<form method="POST" action="{{ route('admin.bukti-persyaratan-dasar-pemohon.update', $item->id) }}">
    @csrf
    @method('PUT')
    @include('admin.bukti-persyaratan-dasar-pemohon._form', ['submitLabel' => 'Perbarui Data', 'item' => $item])
</form>
@endsection