@extends('asesor.layout')

@section('title', 'Isi Ceklis Observasi')
@section('page-title', 'Isi Ceklis Observasi Aktivitas Praktik')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
    <h2 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Isi Ceklis Observasi</h2>
    <a href="{{ route('asesor.ceklis-observasi.index') }}" class="btn btn-secondary" style="text-decoration:none;padding:9px 12px;border-radius:8px;background:#64748b;color:#fff;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('asesor.ceklis-observasi.store') }}">
    @csrf
    @include('asesor.ceklis-observasi.partials.form', [
        'item' => null,
        'defaults' => $defaults,
        'activeSkema' => $activeSkema,
        'submitLabel' => 'Simpan Ceklis',
    ])
</form>
@endsection
