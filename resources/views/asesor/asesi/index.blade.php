@extends('asesor.layout')

@section('title', 'Daftar Asesi')
@section('page-title', 'Daftar Asesi')

@section('styles')
<style>
    .page-header {
        background: #0073bd;
        border-radius: 12px;
        padding: 22px 26px;
        color: white;
        margin-bottom: 22px;
    }
    .page-header h2 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
    .page-header p  { font-size: 13px; opacity: 0.85; margin: 0; }

    .filter-bar {
        display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;
    }
    .filter-btn {
        padding: 6px 16px; border-radius: 20px; border: 1.5px solid #e2e8f0;
        font-size: 13px; font-weight: 500; text-decoration: none;
        color: #64748b; background: white; cursor: pointer; transition: all 0.2s;
    }
    .filter-btn:hover, .filter-btn.active { background: #0073bd; color: white; border-color: #0073bd; }

    .table-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; overflow: hidden;
    }
    .table-wrap { overflow-x: auto; }
    .table-card table { width: 100%; border-collapse: collapse; table-layout: fixed; min-width: 760px; }
    .table-card thead th {
        background: #f8fafc; padding: 11px 10px;
        text-align: left; font-size: 11.5px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-card tbody td {
        padding: 10px 10px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 11px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #6b7280; }

    .btn-review {
        display: inline-flex; align-items: center; gap: 5px;
        background: #0073bd; color: white;
        padding: 5px 10px; border-radius: 6px;
        font-size: 11px; font-weight: 600; text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover { background: #003961; color: white; }
    .btn-review.disabled { background: #94a3b8; pointer-events: none; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 44px; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 14px; }

    .period-col {
        min-width: 150px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
    }

    .period-sep {
        color: #94a3b8;
        margin: 0 4px;
    }

    .table-card th:nth-child(2), .table-card td:nth-child(2) { width: 122px; }
    .table-card th:nth-child(4), .table-card td:nth-child(4) { width: 98px; }
    .table-card th:nth-child(5), .table-card td:nth-child(5) { width: 118px; }
    .table-card th:nth-child(6), .table-card td:nth-child(6) { width: 136px; }
    .table-card th:nth-child(7), .table-card td:nth-child(7) { width: 74px; text-align: center; }

    @media (max-width: 768px) {
        .page-header {
            padding: 16px;
            margin-bottom: 16px;
        }

        .page-header h2 {
            font-size: 16px;
            line-height: 1.35;
        }

        .filter-bar {
            gap: 8px;
            margin-bottom: 14px;
        }

        .filter-btn {
            font-size: 12px;
            padding: 6px 12px;
        }

        .table-wrap {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2><i class="bi bi-people"></i> Daftar Asesi — {{ $skema?->nama_skema ?? 'Skema tidak ditetapkan' }}</h2>
    <p>{{ $skema?->nomor_skema }} &bull; {{ $data->count() }} asesi terdaftar</p>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <a href="{{ route('asesor.asesi.index') }}"
       class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'selesai']) }}"
       class="filter-btn {{ request('status') === 'selesai' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'sedang_mengerjakan']) }}"
       class="filter-btn {{ request('status') === 'sedang_mengerjakan' ? 'active' : '' }}">Sedang Dikerjakan</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'belum_mulai']) }}"
       class="filter-btn {{ request('status') === 'belum_mulai' ? 'active' : '' }}">Belum Mulai</a>
</div>

<div class="table-card">
    @if($data->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Asesi</th>
                    <th>NIK</th>
                    <th>Jurusan</th>
                    <th>Status Asesmen</th>
                    <th>Rekomendasi</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                @php
                    $asesi = $row->asesi;
                    $statusClass = match($row->status) {
                        'selesai'            => 'badge-selesai',
                        'sedang_mengerjakan' => 'badge-sedang',
                        default              => 'badge-belum',
                    };
                    $statusLabel = match($row->status) {
                        'selesai'            => 'Selesai',
                        'sedang_mengerjakan' => 'Sedang Dikerjakan',
                        default              => 'Belum Mulai',
                    };
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;color:#1e3a5f;">{{ $asesi?->nama ?? '—' }}</div>
                        <div style="font-size:10px;color:#94a3b8;line-height:1.3;">{{ $asesi?->email ?? '' }}</div>
                    </td>
                    <td><code style="font-size:11px;color:#475569;">{{ $row->asesi_nik }}</code></td>
                    <td><code style="font-size:11px;color:#475569;">{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</code></td>
                    <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        @if($row->rekomendasi === 'lanjut')
                            <span class="badge" style="background:#d1fae5;color:#059669;justify-content:center;white-space:nowrap;">✓ Dapat Lanjut</span>
                        @elseif($row->rekomendasi === 'tidak_lanjut')
                            <span class="badge" style="background:#fee2e2;color:#dc2626;justify-content:center;white-space:nowrap;">✗ Tidak Lanjut</span>
                        @else
                            <span style="font-size:12px;color:#94a3b8;">— Belum direview</span>
                        @endif
                    </td>
                    <td class="period-col">
                        <span>{{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') : '—' }}</span>
                        <span class="period-sep">s/d</span>
                        <span>{{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') : '—' }}</span>
                    </td>
                    <td>
                        @if($row->status === 'selesai')
                            <a href="{{ route('asesor.asesi.review', $row->asesi_nik) }}" class="btn-review">
                                <i class="bi bi-eye"></i> Review
                            </a>
                        @else
                            <span class="btn-review disabled">
                                <i class="bi bi-eye-slash"></i> Belum Selesai
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>Belum ada asesi yang terdaftar pada skema ini.</p>
    </div>
    @endif
</div>

@endsection
