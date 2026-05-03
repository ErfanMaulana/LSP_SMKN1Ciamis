@extends('asesor.layout')

@section('title', 'Rekaman Asesmen Kompetensi')
@section('page-title', 'Rekaman Asesmen Kompetensi')

@section('styles')
<style>
    .toolbar {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 14px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 240px;
    }

    .search-box input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13px;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }
    .btn-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
    th { text-align: left; background: #f8fafc; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; }

    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge.success { background: #dcfce7; color: #15803d; }
    .badge.warning { background: #fef3c7; color: #92400e; }

    .action-wrap { display: flex; gap: 6px; flex-wrap: wrap; }

    .empty {
        padding: 26px;
        text-align: center;
        color: #64748b;
    }

    .pager {
        padding: 12px;
    }
</style>
@endsection

@section('content')
@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp

<div class="toolbar">
    <form method="GET" action="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="search-box">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari skema atau asesi...">
    </form>

    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Rekaman</a>
    </div>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:46px;">No</th>
                    <th>Skema</th>
                    <th>Asesi</th>
                    <th>Rekomendasi</th>
                    <th>Periode</th>
                    <th style="width:240px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $isPaginator ? $items->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td>
                            {{ $item->skema?->nama_skema ?? '-' }}
                            <br>
                            <small style="color:#64748b;">{{ $item->skema?->nomor_skema ?? '-' }}</small>
                        </td>
                        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
                        <td>
                            @if($item->rekomendasi === 'kompeten')
                                <span class="badge success">Kompeten</span>
                            @else
                                <span class="badge warning">Belum Kompeten</span>
                            @endif
                        </td>
                        <td>
                            {{ $item->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                            <span style="color:#94a3b8;"> - </span>
                            {{ $item->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                        <td>
                            <div class="action-wrap">
                                <a href="{{ route('asesor.rekaman-asesmen-kompetensi.show', $item->id) }}" class="btn btn-secondary"><i class="bi bi-eye"></i> Lihat</a>
                                <a href="{{ route('asesor.rekaman-asesmen-kompetensi.edit', $item->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                                <form method="POST" action="{{ route('asesor.rekaman-asesmen-kompetensi.destroy', $item->id) }}" onsubmit="return confirm('Hapus rekaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty">Belum ada rekaman asesmen kompetensi.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isPaginator && $items->hasPages())
        <div class="pager">{{ $items->links() }}</div>
    @endif
</div>
@endsection
