@extends('asesor.layout')

@section('title', 'Tambah Rekaman Asesmen Kompetensi')
@section('page-title', 'Rekaman Asesmen Kompetensi')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Tambah Rekaman Asesmen</h2>
    <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary" style="text-decoration:none;padding:9px 12px;border-radius:8px;background:#64748b;color:#fff;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('asesor.rekaman-asesmen-kompetensi.store') }}">
    @csrf
    @include('asesor.rekaman-asesmen-kompetensi.partials.form', [
        'item' => null,
        'defaults' => $defaults,
        'skemaList' => $skemaList,
        'submitLabel' => 'Simpan Rekaman',
    ])
</form>
@endsection
