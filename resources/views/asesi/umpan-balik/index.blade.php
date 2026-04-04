@extends('asesi.layout')

@section('title', 'Umpan Balik Asesor')
@section('page-title', 'Umpan Balik Asesor')

@section('styles')
<style>
    .page-header {
        background: #0061A5;
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
        opacity: 0.92;
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
        transition: all 0.25s ease;
        position: relative;
    }

    .skema-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.10);
        border-color: #0061A5;
    }

    .skema-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .skema-badge.selesai { background: #dcfce7; color: #166534; }
    .skema-badge.belum { background: #fef3c7; color: #92400e; }
    .skema-badge.kosong { background: #e5e7eb; color: #374151; }

    .skema-icon {
        width: 50px;
        height: 50px;
        background: #0061A5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .skema-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .skema-code {
        font-size: 12px;
        color: #64748b;
        font-family: monospace;
        margin-bottom: 14px;
    }

    .meta-row {
        font-size: 13px;
        color: #475569;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-row i {
        color: #0073bd;
    }

    .btn-action {
        width: 100%;
        margin-top: 14px;
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-fill {
        background: #0061A5;
        color: white;
    }

    .btn-fill:hover {
        box-shadow: 0 8px 20px rgba(0, 97, 165, 0.35);
        transform: translateY(-1px);
    }

    .btn-view {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-disabled {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 56px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .empty-state i {
        font-size: 58px;
        color: #cbd5e1;
        margin-bottom: 14px;
        display: block;
    }

    @media (max-width: 768px) {
        .page-header { padding: 16px; margin-bottom: 16px; }
        .page-header h2 { font-size: 17px; line-height: 1.35; }
        .skema-grid { grid-template-columns: 1fr; gap: 12px; }
        .skema-card { padding: 16px; }
        .skema-badge { position: static; display: inline-flex; margin-bottom: 10px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="bi bi-chat-square-text"></i> Umpan Balik Kinerja Asesor</h2>
    <p>Pilih skema untuk memberikan penilaian Ya/Tidak pada setiap pernyataan komponen. Catatan komentar pada setiap komponen wajib diisi.</p>
</div>

@if($skemas->count() > 0)
<div class="skema-grid">
    @foreach($skemas as $skema)
        @php
            $statusClass = $skema->total_komponen === 0
                ? 'kosong'
                : ($skema->umpan_balik_selesai ? 'selesai' : 'belum');
        @endphp

        <div class="skema-card">
            <span class="skema-badge {{ $statusClass }}">
                @if($statusClass === 'selesai')
                    <i class="bi bi-check-circle"></i> Sudah Diisi
                @elseif($statusClass === 'belum')
                    <i class="bi bi-hourglass-split"></i> Belum Lengkap
                @else
                    <i class="bi bi-dash-circle"></i> Belum Tersedia
                @endif
            </span>

            <div class="skema-icon">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <div class="skema-title">{{ $skema->nama_skema }}</div>
            <div class="skema-code">{{ $skema->nomor_skema }}</div>

            <div class="meta-row">
                <i class="bi bi-ui-checks-grid"></i>
                <span>{{ $skema->total_komponen }} Komponen Aktif</span>
            </div>
            <div class="meta-row">
                <i class="bi bi-pencil-square"></i>
                <span>{{ $skema->total_terisi }} Komponen Terisi</span>
            </div>

            @if($skema->total_komponen === 0)
                <button class="btn-action btn-disabled" type="button" disabled>
                    <i class="bi bi-lock"></i> Menunggu Komponen
                </button>
            @elseif($skema->umpan_balik_selesai)
                <a href="{{ route('asesi.umpan-balik.show', $skema->id) }}" class="btn-action btn-view">
                    <i class="bi bi-eye"></i> Lihat / Ubah Umpan Balik
                </a>
            @else
                <a href="{{ route('asesi.umpan-balik.show', $skema->id) }}" class="btn-action btn-fill">
                    <i class="bi bi-play-fill"></i> Isi Umpan Balik
                </a>
            @endif
        </div>
    @endforeach
</div>
@else
<div class="empty-state">
    <i class="bi bi-inbox"></i>
    <h3>Belum Ada Skema</h3>
    <p>Anda belum memiliki skema terdaftar untuk penilaian umpan balik asesor.</p>
</div>
@endif
@endsection
