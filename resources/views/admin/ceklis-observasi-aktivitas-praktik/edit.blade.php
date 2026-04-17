@extends('admin.layout')

@section('title', 'Edit Ceklis Observasi Aktivitas Praktik')
@section('page-title', 'Edit Ceklis Observasi Aktivitas Praktik')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Edit Ceklis Observasi Aktivitas Praktik</h2>
    <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.ceklis-observasi-aktivitas-praktik.update', $item->id) }}">
    @csrf
    @method('PUT')
    @include('admin.ceklis-observasi-aktivitas-praktik.partials.form', [
        'item' => $item,
        'defaults' => [],
        'skemaList' => $skemaList,
        'submitLabel' => 'Perbarui Data',
    ])
</form>
@endsection
