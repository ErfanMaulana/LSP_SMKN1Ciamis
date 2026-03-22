@extends('asesor.layout')

@section('title', 'Jadwal Saya')
@section('page-title', 'Jadwal Saya')

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

    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        color: #64748b;
        background: white;
        transition: all 0.2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #0073bd;
        color: white;
        border-color: #0073bd;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
    }

    .table-card thead th {
        background: #f8fafc;
        padding: 13px 16px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-card tbody td {
        padding: 14px 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .table-card tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-dijadwalkan {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-berlangsung {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-selesai {
        background: #d1fae5;
        color: #047857;
    }

    .badge-dibatalkan {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        display: block;
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
    <h2>Jadwal Ujikom Saya</h2>
    <p>Daftar jadwal ujikom yang ditugaskan ke akun asesor Anda.</p>
</div>

<div class="filter-bar">
    <a href="{{ route('asesor.jadwal.index') }}"
       class="filter-btn {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('asesor.jadwal.index', ['status' => 'dijadwalkan']) }}"
       class="filter-btn {{ request('status') === 'dijadwalkan' ? 'active' : '' }}">Dijadwalkan</a>
    <a href="{{ route('asesor.jadwal.index', ['status' => 'berlangsung']) }}"
       class="filter-btn {{ request('status') === 'berlangsung' ? 'active' : '' }}">Berlangsung</a>
    <a href="{{ route('asesor.jadwal.index', ['status' => 'selesai']) }}"
       class="filter-btn {{ request('status') === 'selesai' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('asesor.jadwal.index', ['status' => 'dibatalkan']) }}"
       class="filter-btn {{ request('status') === 'dibatalkan' ? 'active' : '' }}">Dibatalkan</a>
</div>

<div class="table-card">
    @if($jadwals->count())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jadwal</th>
                        <th>Periode</th>
                        <th>Waktu</th>
                        <th>TUK</th>
                        <th>Skema</th>
                        <th>Kelompok</th>
                        <th>Peserta</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $i => $jadwal)
                        @php
                            $statusClass = match($jadwal->status) {
                                'dijadwalkan' => 'badge-dijadwalkan',
                                'berlangsung' => 'badge-berlangsung',
                                'selesai' => 'badge-selesai',
                                'dibatalkan' => 'badge-dibatalkan',
                                default => 'badge-dijadwalkan',
                            };
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600;color:#1e3a5f;">{{ $jadwal->judul_jadwal }}</div>
                                @if($jadwal->keterangan)
                                    <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ $jadwal->keterangan }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') : '-' }}
                                <div style="font-size:12px;color:#64748b;">
                                    s/d {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d/m/Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                {{ $jadwal->waktu_mulai ?? '-' }}
                                <div style="font-size:12px;color:#64748b;">s/d {{ $jadwal->waktu_selesai ?? '-' }}</div>
                            </td>
                            <td>{{ $jadwal->tuk?->nama_tuk ?? '-' }}</td>
                            <td>{{ $jadwal->skema?->nama_skema ?? '-' }}</td>
                            <td>{{ $jadwal->kelompok?->nama_kelompok ?? '-' }}</td>
                            <td>{{ $jadwal->peserta_terdaftar }} / {{ $jadwal->kuota }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ $jadwal->status_label }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <p>Belum ada jadwal ujikom untuk asesor ini.</p>
        </div>
    @endif
</div>
@endsection
