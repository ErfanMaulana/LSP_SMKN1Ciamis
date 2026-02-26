@extends('asesor.layout')

@section('title', 'Daftar Asesi')
@section('page-title', 'Daftar Asesi')

@section('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
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
    .filter-btn:hover, .filter-btn.active { background: #2563eb; color: white; border-color: #2563eb; }

    .table-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; overflow: hidden;
    }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead th {
        background: #f8fafc; padding: 13px 16px;
        text-align: left; font-size: 11.5px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-card tbody td {
        padding: 14px 16px; font-size: 14px; color: #374151;
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
        background: #2563eb; color: white;
        padding: 6px 14px; border-radius: 6px;
        font-size: 12px; font-weight: 500; text-decoration: none;
        transition: background 0.2s;
    }
    .btn-review:hover { background: #1d4ed8; color: white; }
    .btn-review.disabled { background: #94a3b8; pointer-events: none; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 44px; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 14px; }
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
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Asesi</th>
                <th>NIK</th>
                <th>No. Reg</th>
                <th>Jurusan</th>
                <th>Status Asesmen</th>
                <th>Rekomendasi</th>
                <th>Mulai</th>
                <th>Selesai</th>
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
                    'selesai'            => '✓ Selesai',
                    'sedang_mengerjakan' => '⏳ Sedang Dikerjakan',
                    default              => '○ Belum Mulai',
                };
            @endphp
            <tr>
                <td style="color:#94a3b8;font-size:13px;">{{ $i + 1 }}</td>
                <td>
                    <div style="font-weight:600;color:#1e3a5f;">{{ $asesi?->nama ?? '—' }}</div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $asesi?->email ?? '' }}</div>
                </td>
                <td><code style="font-size:12px;color:#475569;">{{ $row->asesi_nik }}</code></td>
                <td>{{ $asesi?->no_reg ?? '—' }}</td>
                <td>{{ $asesi?->jurusan?->nama_jurusan ?? '—' }}</td>
                <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                <td>
                    @if($row->rekomendasi === 'lanjut')
                        <span class="badge" style="background:#d1fae5;color:#059669;">✓ Dapat Lanjut</span>
                    @elseif($row->rekomendasi === 'tidak_lanjut')
                        <span class="badge" style="background:#fee2e2;color:#dc2626;">✗ Tidak Lanjut</span>
                    @else
                        <span style="font-size:12px;color:#94a3b8;">— Belum direview</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#64748b;">
                    {{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') : '—' }}
                </td>
                <td style="font-size:12px;color:#64748b;">
                    {{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') : '—' }}
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
    @else
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>Belum ada asesi yang terdaftar pada skema ini.</p>
    </div>
    @endif
</div>

@endsection
