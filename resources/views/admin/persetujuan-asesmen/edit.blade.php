@extends('admin.layout')

@section('title', 'Edit Persetujuan Asesmen')
@section('page-title', 'Edit Persetujuan Asesmen')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Edit Persetujuan Asesmen dan Kerahasiaan</h2>
    <a href="{{ route('admin.persetujuan-asesmen.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.persetujuan-asesmen.update', $item->id) }}">
    @csrf
    @method('PUT')
    @include('admin.persetujuan-asesmen.partials.form', [
        'item' => $item,
        'defaults' => [],
        'skemaList' => $skemaList,
        'tukList' => $tukList,
        'submitLabel' => 'Simpan Perubahan',
    ])
</form>
@endsection
