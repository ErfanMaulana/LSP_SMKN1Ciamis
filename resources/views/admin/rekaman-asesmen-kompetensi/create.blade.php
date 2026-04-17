@extends('admin.layout')

@section('title', 'Tambah Rekaman Asesmen Kompetensi')
@section('page-title', 'Tambah Rekaman Asesmen Kompetensi')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Tambah Rekaman Asesmen Kompetensi</h2>
    <a href="{{ route('admin.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.rekaman-asesmen-kompetensi.store') }}">
    @csrf
    @include('admin.rekaman-asesmen-kompetensi.partials.form', [
        'item' => null,
        'defaults' => $defaults,
        'skemaList' => $skemaList,
        'submitLabel' => 'Simpan Data',
    ])
</form>
@endsection
