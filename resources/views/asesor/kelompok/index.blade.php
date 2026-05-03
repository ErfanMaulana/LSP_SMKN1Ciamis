@extends('asesor.layout')

@section('title', 'Kelompok Saya')
@section('page-title', 'Kelompok Saya')

@section('styles')
<style>
    .page-header {
        background: #0073bd;
        border-radius: 12px;
        padding: 22px 26px;
        color: white;
        margin-bottom: 22px;
    }

    .page-header h2 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .page-header p {
        font-size: 13px;
        opacity: 0.9;
        margin: 0;
    }

    .group-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }

    .group-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .group-head {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-amber { background: #fff7ed; color: #c2410c; }
    .badge-gray { background: #f1f5f9; color: #94a3b8; }

    .group-name {
        font-size: 16px;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0 0 6px;
    }

    .group-meta {
        font-size: 12px;
        color: #64748b;
    }

    .group-status-detail {
        margin-top: 6px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.45;
    }

    .group-body {
        padding: 16px 18px;
    }

    .group-actions {
        margin-top: 12px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #0369a1;
        text-decoration: none;
        border: 1px solid #bae6fd;
        background: #f0f9ff;
        border-radius: 8px;
        padding: 7px 10px;
    }

    .btn-detail:hover {
        background: #e0f2fe;
    }

    .meta-row {
        font-size: 13px;
        color: #334155;
        margin-bottom: 8px;
    }

    .meta-row strong {
        color: #0f172a;
    }

    .asesi-list {
        margin-top: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .asesi-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }

    .asesi-item:last-child {
        border-bottom: none;
    }

    .asesi-name {
        font-weight: 600;
        color: #1e293b;
    }

    .asesi-info {
        color: #64748b;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        display: block;
    }

    .filters {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
    }

    .status-filter {
        min-width: 220px;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        color: #0f172a;
        font-size: 13px;
        outline: none;
    }

    .status-filter:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 16px;
            margin-bottom: 16px;
        }

        .page-header h2 {
            font-size: 16px;
            line-height: 1.35;
        }

        .group-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .group-head,
        .group-body {
            padding: 14px;
        }

        .asesi-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }

        .asesi-item .asesi-info[style*='text-align:right'] {
            text-align: left !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Kelompok Yang Saya Ampu</h2>
    <p>Informasi kelompok dan daftar asesi yang ditugaskan ke asesor Anda.</p>
</div>

<form method="GET" action="{{ route('asesor.kelompok.index') }}" class="filters">
    <select name="status" class="status-filter" onchange="this.form.submit()">
        <option value="all" @selected(request('status', 'all') === 'all')>Semua Status</option>
        <option value="belum terjadwal" @selected(request('status') === 'belum terjadwal')>Belum Terjadwal</option>
        <option value="terjadwal" @selected(request('status') === 'terjadwal')>Terjadwal</option>
        <option value="sedang asesmen" @selected(request('status') === 'sedang asesmen')>Sedang Asesmen</option>
        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
    </select>
</form>

@if($kelompoks->count())
    <div class="group-grid">
        @foreach($kelompoks as $kelompok)
            <div class="group-card">
                <div class="group-head">
                    <h3 class="group-name">{{ $kelompok->nama_kelompok }} <span class="badge {{ $kelompok->status_badge_class ?? 'badge-gray' }}" title="{{ $kelompok->status_tooltip }}">{{ $kelompok->status_label }}</span></h3>
                    <div class="group-meta">Skema: {{ $kelompok->skema?->nama_skema ?? '-' }}</div>
                    <div class="group-status-detail" title="{{ $kelompok->status_detail }}">{{ $kelompok->status_detail }}</div>
                </div>
                <div class="group-body">
                    <div class="meta-row"><strong>Total Asesi:</strong> {{ $kelompok->asesis->count() }} peserta</div>

                    <div class="asesi-list">
                        @forelse($kelompok->asesis as $asesi)
                            <div class="asesi-item">
                                <div>
                                    <div class="asesi-name">{{ $asesi->nama }}</div>
                                    <div class="asesi-info">NIK: {{ $asesi->NIK }}</div>
                                </div>
                                <div class="asesi-info" style="text-align:right;">
                                    {{ $asesi->jurusan?->nama_jurusan ?? '-' }}
                                </div>
                            </div>
                        @empty
                            <div class="asesi-item">
                                <div class="asesi-info">Belum ada asesi di kelompok ini.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="group-actions">
                        <a href="{{ route('asesor.kelompok.show', $kelompok->id) }}" class="btn-detail">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>Belum ada kelompok yang ditugaskan untuk asesor ini.</p>
    </div>
@endif
@endsection
