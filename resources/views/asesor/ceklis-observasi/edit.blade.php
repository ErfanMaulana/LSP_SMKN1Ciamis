@extends('asesor.layout')

@section('title', 'Edit Ceklis Observasi')
@section('page-title', 'Edit Ceklis Observasi Aktivitas Praktik')

@section('content')
@php
    $backTo = $backTo ?? request()->get('back_to', '');
    $backUrl = ($backTo && str_starts_with($backTo, 'asesi:'))
        ? route('asesor.asesi.show', substr($backTo, 6))
        : route('asesor.ceklis-observasi.index');
    $backLabel = ($backTo && str_starts_with($backTo, 'asesi:')) ? 'Kembali ke Detail Asesi' : 'Kembali';
@endphp
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Edit Ceklis Observasi</h2>
    <a href="{{ $backUrl }}" class="btn btn-secondary" style="text-decoration:none;padding:9px 12px;border-radius:8px;background:#64748b;color:#fff;">
        <i class="bi bi-arrow-left"></i> {{ $backLabel }}
    </a>
</div>

<form method="POST" action="{{ route('asesor.ceklis-observasi.update', $item->id) }}">
    @csrf
    @method('PUT')
    @if($backTo)
        <input type="hidden" name="back_to" value="{{ $backTo }}">
    @endif
    @include('asesor.ceklis-observasi.partials.form', [
        'item' => $item,
        'defaults' => [],
        'activeSkema' => $activeSkema,
        'submitLabel' => 'Perbarui Ceklis',
    ])
</form>
@endsection
