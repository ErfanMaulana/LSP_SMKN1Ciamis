@php
    $item = $item ?? null;
    $role = $role ?? 'asesi';
    $skema = $skema ?? null;
    $tukList = $tukList ?? collect();
    $layout = $role === 'asesor' ? 'asesor.layout' : 'asesi.layout';
@endphp

@extends($layout)

@section('title', 'Persetujuan Asesmen - ' . ($skema->nama_skema ?? $item->judul_skema ?? 'Asesmen'))
@section('page-title', 'Persetujuan Asesmen')

@section('styles')
<style>
    .persetujuan-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .info-card h5 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row label {
        color: #64748b;
        font-weight: 500;
    }

    .info-row span {
        color: #1e293b;
        font-weight: 600;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .form-card h5 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        font-weight: 500;
        color: #1e293b;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .btn-submit {
        background: #0073bd;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 8px;
    }

    .btn-submit:hover {
        background: #005fa3;
    }

    .alert {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .error-text {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div class="persetujuan-container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Info Card -->
    <div class="info-card">
        <h5>📋 Informasi Asesmen</h5>
        <div class="info-row">
            <label>Skema</label>
            <span>{{ $item->judul_skema ?? $skema->nama_skema ?? '-' }}</span>
        </div>
        <div class="info-row">
            <label>Nomor Skema</label>
            <span>{{ $item->nomor_skema ?? $skema->nomor_skema ?? '-' }}</span>
        </div>
        <div class="info-row">
            <label>Nama Asesi</label>
            <span>{{ $item->nama_asesi ?? '-' }}</span>
        </div>
        <div class="info-row">
            <label>TUK</label>
            <span>{{ $item->tuk ?? '-' }}</span>
        </div>
        <div class="info-row">
            <label>Hari/Tanggal</label>
            <span>{{ $item->hari_tanggal ?? '-' }}</span>
        </div>
        <div class="info-row">
            <label>Waktu</label>
            <span>{{ $item->waktu ?? '-' }}</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        @if($role === 'asesor')
            <h5>✍️ Tanda Tangan Asesor</h5>
            <form method="POST" action="{{ route('asesor.persetujuan.front.asesor.sign', $item->id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Asesor</label>
                    <input type="text" name="ttd_asesor_nama" class="form-control" value="{{ old('ttd_asesor_nama', $item->ttd_asesor_nama) }}" required>
                    @error('ttd_asesor_nama')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Penandatanganan</label>
                    <input type="date" name="ttd_asesor_tanggal" class="form-control" value="{{ old('ttd_asesor_tanggal', $item->ttd_asesor_tanggal?->format('Y-m-d')) }}" required>
                    @error('ttd_asesor_tanggal')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-submit">Simpan Tanda Tangan Asesor</button>
            </form>
        @else
            <h5>✍️ Tanda Tangan Asesi</h5>
            <form method="POST" action="{{ route('asesi.persetujuan.front.asesi.sign', $item->id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Asesi</label>
                    <input type="text" name="ttd_asesi_nama" class="form-control" value="{{ old('ttd_asesi_nama', $item->ttd_asesi_nama) }}" required>
                    @error('ttd_asesi_nama')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Penandatanganan</label>
                    <input type="date" name="ttd_asesi_tanggal" class="form-control" value="{{ old('ttd_asesi_tanggal', $item->ttd_asesi_tanggal?->format('Y-m-d')) }}" required>
                    @error('ttd_asesi_tanggal')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-submit">Simpan Tanda Tangan Asesi</button>
            </form>
        @endif
    </div>

    <p style="color: #64748b; font-size: 13px; margin-top: 20px; text-align: center;">
        💡 Catatan: Tanda tangan digital sederhana; canvas tidak disimpan dalam implementasi ini.
    </p>
</div>
@endsection
