@extends('asesi.layout')

@section('title', 'Hasil Asesmen - ' . $skema->nama_skema)
@section('page-title', 'Hasil Asesmen Mandiri')

@section('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #16a34a;
    }

    .result-header {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
        color: white;
    }

    .result-header-top {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .result-info h2 {
        font-size: 20px;
        margin-bottom: 4px;
    }

    .result-info .skema-number {
        font-size: 14px;
        opacity: 0.8;
        font-family: monospace;
    }

    .result-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.8;
        text-transform: uppercase;
    }

    .stat-item.kompeten .stat-value { color: #86efac; }
    .stat-item.belum .stat-value { color: #fecaca; }

    .completion-badge {
        background: #d1fae5;
        color: #065f46;
        padding: 16px 24px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .completion-badge i {
        font-size: 24px;
    }

    .completion-badge .text h3 {
        font-size: 15px;
        margin-bottom: 2px;
    }

    .completion-badge .text p {
        font-size: 13px;
        opacity: 0.8;
        margin: 0;
    }

    .unit-result {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .unit-result-header {
        background: #f8fafc;
        padding: 18px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .unit-result-header h3 {
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .unit-result-header .unit-code {
        font-size: 13px;
        color: #64748b;
        font-family: monospace;
    }

    .elemen-result {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        gap: 16px;
    }

    .elemen-result:last-child {
        border-bottom: none;
    }

    .elemen-status {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.kompeten {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.belum {
        background: #fee2e2;
        color: #991b1b;
    }

    .elemen-content {
        flex: 1;
    }

    .elemen-content h4 {
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .bukti-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
    }

    .bukti-text.empty {
        color: #9ca3af;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-print {
        background: #e0e7ff;
        color: #4338ca;
    }

    .btn-print:hover {
        background: #c7d2fe;
    }

    .btn-back {
        background: #14532d;
        color: white;
    }

    .btn-back:hover {
        background: #166534;
    }

    @media print {
        .back-link, .action-buttons, .sidebar, .topbar {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }

        .content-wrapper {
            padding: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<a href="{{ route('asesi.asesmen-mandiri.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Skema
</a>

@php
    $totalKompeten = $answers->where('status', 'K')->count();
    $totalBelum = $answers->where('status', 'BK')->count();
    $totalAnswered = $answers->count();
@endphp

<!-- Result Header -->
<div class="result-header">
    <div class="result-header-top">
        <div class="result-icon">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="result-info">
            <h2>{{ $skema->nama_skema }}</h2>
            <div class="skema-number">{{ $skema->nomor_skema }}</div>
        </div>
    </div>
    <div class="result-stats">
        <div class="stat-item kompeten">
            <div class="stat-value">{{ $totalKompeten }}</div>
            <div class="stat-label">Kompeten (K)</div>
        </div>
        <div class="stat-item belum">
            <div class="stat-value">{{ $totalBelum }}</div>
            <div class="stat-label">Belum Kompeten (BK)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $totalAnswered }}</div>
            <div class="stat-label">Total Elemen</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $skema->units->count() }}</div>
            <div class="stat-label">Unit Kompetensi</div>
        </div>
    </div>
</div>

@if($pivot && $pivot->status === 'selesai')
<div class="completion-badge">
    <i class="bi bi-check-circle-fill"></i>
    <div class="text">
        <h3>Asesmen Mandiri Selesai</h3>
        <p>Diselesaikan pada {{ \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d M Y, H:i') }} WIB</p>
    </div>
</div>
@endif

<!-- Results by Unit -->
@foreach($skema->units as $unitIndex => $unit)
<div class="unit-result">
    <div class="unit-result-header">
        <h3>Unit {{ $unitIndex + 1 }}: {{ $unit->judul_unit }}</h3>
        <div class="unit-code">{{ $unit->kode_unit }}</div>
    </div>

    @foreach($unit->elemens as $elemenIndex => $elemen)
    @php
        $answer = $answers->get($elemen->id);
    @endphp
    <div class="elemen-result">
        <div class="elemen-status">
            @if($answer)
                <span class="status-badge {{ $answer->status === 'K' ? 'kompeten' : 'belum' }}">
                    {{ $answer->status === 'K' ? 'Kompeten' : 'Belum Kompeten' }}
                </span>
            @else
                <span class="status-badge belum">Belum Dijawab</span>
            @endif
        </div>
        <div class="elemen-content">
            <h4>Elemen {{ $elemenIndex + 1 }}: {{ $elemen->nama_elemen }}</h4>
            @if($answer && $answer->bukti)
                <div class="bukti-text">
                    <strong>Bukti:</strong> {{ $answer->bukti }}
                </div>
            @else
                <div class="bukti-text empty">Tidak ada bukti yang dilampirkan</div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endforeach

<div class="action-buttons">
    <button onclick="window.print()" class="btn btn-print">
        <i class="bi bi-printer"></i> Cetak Hasil
    </button>
    <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="btn btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection
