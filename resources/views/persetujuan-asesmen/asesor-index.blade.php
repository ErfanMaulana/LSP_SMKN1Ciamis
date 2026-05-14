@extends('asesor.layout')

@section('title', 'Persetujuan Asesmen')
@section('page-title', 'Persetujuan Asesmen dan Kerahasiaan')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .page-header h2 { margin: 0; font-size: 22px; font-weight: 700; color: #0f172a; }

    .toolbar {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 16px;
        margin-bottom: 16px;
    }

    .search-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 280px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-box input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px 10px 36px;
        font-size: 13px;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }
    .btn-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }
    td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: top;
    }
    tr:last-child td { border-bottom: none; }

    .action-wrap { display: flex; gap: 6px; flex-wrap: wrap; }

    .pagination-wrap { padding: 14px; }

    .empty {
        padding: 36px 16px;
        text-align: center;
        color: #64748b;
    }
</style>
@endsection

@section('content')
@php $items = collect($items ?? []); @endphp

<div class="page-header">
    <h2>Persetujuan Asesmen dan Kerahasiaan</h2>
    <a href="{{ route('asesor.persetujuan-asesmen.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Data
    </a>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('asesor.persetujuan-asesmen.index') }}" class="search-row">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari skema atau asesi...">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
        <a href="{{ route('asesor.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
    </form>
</div>

<div class="card">
    <div class="admin-table-scroll">
        <table>
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Skema</th>
                    <th>Nomor Skema</th>
                    <th>Asesi</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['skema_nama'] }}</td>
                        <td>{{ $item['skema_nomor'] }}</td>
                        <td>{{ $item['asesi_nama'] }}</td>
                        <td>{{ $item['status'] }}</td>
                        <td>
                            <div class="action-wrap">
                                @if(!empty($item['asesi_nik']) && !empty($item['skema_id']))
                                    <a href="{{ route('asesor.persetujuan.front.asesor.show', [$item['asesi_nik'], $item['skema_id']]) }}" class="btn btn-secondary">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('asesor.persetujuan.front.asesor.export', [$item['asesi_nik'], $item['skema_id']]) }}" class="btn btn-secondary">
                                        <i class="bi bi-download"></i> Export
                                    </a>
                                @else
                                    <span style="font-size:12px;color:#94a3b8;">Data belum lengkap</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <i class="bi bi-inboxes" style="font-size: 28px;"></i>
                                <div>Belum ada asesi/skema yang terhubung ke akun asesor ini.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
