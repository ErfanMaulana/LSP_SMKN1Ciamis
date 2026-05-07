@php
    $item = $item ?? null;
    $role = $role ?? 'asesi';
    $skema = $skema ?? null;
    $tukList = $tukList ?? collect();
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Persetujuan Asesmen - {{ $skema->nama_skema ?? $item->judul_skema }}</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Skema:</strong> {{ $item->judul_skema ?? $skema->nama_skema }}</p>
            <p><strong>Nomor Skema:</strong> {{ $item->nomor_skema ?? $skema->nomor_skema }}</p>
            <p><strong>Nama Asesi:</strong> {{ $item->nama_asesi }}</p>
            <p><strong>TUK (tipe):</strong> {{ $item->tuk ?? '-' }}</p>
            <p><strong>Hari/Tanggal:</strong> {{ $item->hari_tanggal ?? '-' }}</p>
            <p><strong>Waktu:</strong> {{ $item->waktu ?? '-' }}</p>
        </div>
    </div>

    <hr>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($role === 'asesor')
        <form method="POST" action="{{ route('persetujuan.front.asesor.sign', $item->id) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Asesor</label>
                <input type="text" name="ttd_asesor_nama" class="form-control" value="{{ old('ttd_asesor_nama', $item->ttd_asesor_nama) }}">
                @error('ttd_asesor_nama')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="ttd_asesor_tanggal" class="form-control" value="{{ old('ttd_asesor_tanggal', $item->ttd_asesor_tanggal?->format('Y-m-d')) }}">
                @error('ttd_asesor_tanggal')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary">Simpan Tanda Tangan Asesor</button>
        </form>
    @else
        <form method="POST" action="{{ route('persetujuan.front.asesi.sign', $item->id) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Asesi</label>
                <input type="text" name="ttd_asesi_nama" class="form-control" value="{{ old('ttd_asesi_nama', $item->ttd_asesi_nama) }}">
                @error('ttd_asesi_nama')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="ttd_asesi_tanggal" class="form-control" value="{{ old('ttd_asesi_tanggal', $item->ttd_asesi_tanggal?->format('Y-m-d')) }}">
                @error('ttd_asesi_tanggal')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary">Simpan Tanda Tangan Asesi</button>
        </form>
    @endif
    <p class="text-muted mt-3">Catatan: tanda tangan digital sederhana; canvas tidak disimpan di implementasi ini.</p>
</div>
@endsection
