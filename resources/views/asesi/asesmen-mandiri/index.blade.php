@extends('asesi.layout')

@section('title', 'Asesmen Mandiri')
@section('page-title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
        color: white;
    }

    .page-header h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .skema-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
    }

    .skema-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .skema-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #22c55e;
    }

    .skema-card .skema-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .skema-badge.belum { background: #f3f4f6; color: #6b7280; }
    .skema-badge.sedang { background: #fef3c7; color: #d97706; }
    .skema-badge.selesai { background: #d1fae5; color: #059669; }

    .skema-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 16px;
    }

    .skema-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .skema-card .skema-code {
        font-size: 12px;
        color: #64748b;
        font-family: monospace;
        margin-bottom: 12px;
    }

    .skema-card .skema-type {
        display: inline-block;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 4px;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .skema-meta {
        display: flex;
        gap: 16px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }

    .skema-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
    }

    .skema-meta-item i {
        font-size: 14px;
        color: #94a3b8;
    }

    .btn-action {
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-start {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .btn-start:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
    }

    .btn-continue {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-continue:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-view {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .btn-view:hover {
        background: #dbeafe;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        color: #374151;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .skema-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="bi bi-clipboard-check"></i> Asesmen Mandiri (FR.APL.02)</h2>
    <p>Pilih skema sertifikasi yang akan Anda ikuti, lalu isi form asesmen mandiri untuk menilai kompetensi diri Anda.</p>
</div>

@if($skemas->count() > 0)
    <div class="skema-grid">
        @foreach($skemas as $skema)
            @php
                $asesiSkema = $asesiSkemas->get($skema->id);
                $status = $asesiSkema ? $asesiSkema->pivot->status : 'belum_mulai';
            @endphp
            <div class="skema-card">
                <span class="skema-badge {{ $status === 'selesai' ? 'selesai' : ($status === 'sedang_mengerjakan' ? 'sedang' : 'belum') }}">
                    @if($status === 'selesai')
                        <i class="bi bi-check-circle"></i> Selesai
                    @elseif($status === 'sedang_mengerjakan')
                        <i class="bi bi-hourglass-split"></i> Sedang Dikerjakan
                    @else
                        <i class="bi bi-circle"></i> Belum Mulai
                    @endif
                </span>

                <div class="skema-icon">
                    <i class="bi bi-patch-check"></i>
                </div>

                <h3>{{ $skema->nama_skema }}</h3>
                <div class="skema-code">{{ $skema->nomor_skema }}</div>
                <div class="skema-type">{{ $skema->jenis_skema ?? 'KKNI/Okupasi/Klaster' }}</div>

                <div class="skema-meta">
                    <div class="skema-meta-item">
                        <i class="bi bi-layers"></i>
                        <span>{{ $skema->units_count }} Unit Kompetensi</span>
                    </div>
                </div>

                @if($status === 'selesai')
                    <a href="{{ route('asesi.asesmen-mandiri.result', $skema->id) }}" class="btn-action btn-view">
                        <i class="bi bi-eye"></i> Lihat Hasil
                    </a>
                @elseif($status === 'sedang_mengerjakan')
                    <a href="{{ route('asesi.asesmen-mandiri.show', $skema->id) }}" class="btn-action btn-continue">
                        <i class="bi bi-pencil-square"></i> Lanjutkan
                    </a>
                @else
                    <a href="{{ route('asesi.asesmen-mandiri.show', $skema->id) }}" class="btn-action btn-start">
                        <i class="bi bi-play-fill"></i> Mulai Asesmen
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h3>Belum Ada Skema Tersedia</h3>
        <p>Skema sertifikasi belum tersedia. Silakan hubungi admin LSP untuk informasi lebih lanjut.</p>
    </div>
@endif
@endsection
