@extends('asesor.layout')

@section('title', 'Edit Rekaman Asesmen Kompetensi')
@section('page-title', 'Rekaman Asesmen Kompetensi')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Edit Rekaman Asesmen</h2>
    <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary" style="text-decoration:none;padding:9px 12px;border-radius:8px;background:#64748b;color:#fff;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('asesor.rekaman-asesmen-kompetensi.update', $item->id) }}">
    @csrf
    @method('PUT')
    @include('asesor.rekaman-asesmen-kompetensi.partials.form', [
        'item' => $item,
        'defaults' => [],
        'skemaList' => $skemaList,
        'submitLabel' => 'Perbarui Rekaman',
    ])
</form>
@endsection
