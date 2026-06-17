@extends('admin.layout')

@section('title', 'Ceklis Observasi Aktivitas Praktik')
@section('page-title', 'Ceklis Observasi Aktivitas Praktik')

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

    .page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .toolbar {
        margin-bottom: 16px;
    }

    .search-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .filter-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-filter-search {
        padding: 9px 14px;
        background: #0073bd;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }

    .btn-filter-search:hover {
        background: #005f99;
    }

    .btn-filter-reset {
        padding: 9px 12px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-filter-reset:hover {
        background: #fecaca;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        font-family: inherit;
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

    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .badge.success {
        background: #dcfce7;
        color: #15803d;
    }

    .badge.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .action-wrap { display: flex; justify-content: center; }

    .action-menu {
        position: relative;
        display: inline-block;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .2s;
    }

    .action-btn:hover {
        background: #f1f5f9;
    }

    .action-dropdown {
        display: none;
        position: fixed;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .15);
        min-width: 160px;
        z-index: 9990;
        overflow: hidden;
    }

    .action-dropdown.show {
        display: block;
    }

    .dropdown-item,
    .action-dropdown a,
    .action-dropdown button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        border: none;
        background: none;
        text-align: left;
        font-size: 14px;
        color: #475569;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .dropdown-item:hover,
    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .dropdown-item.danger {
        color: #475569;
    }

    .action-dropdown button[type="submit"]:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .pagination-wrap { padding: 14px; }

    .empty {
        padding: 36px 16px;
        text-align: center;
        color: #64748b;
    }
</style>
@endsection

@section('content')
@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp

<div class="page-header">
    <h2>Ceklis Observasi Aktivitas Praktik</h2>
    @if(Auth::guard('admin')->user()->hasPermission('ceklis-observasi-aktivitas-praktik.create'))
        <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </a>
    @endif
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="search-row">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari skema, asesor, atau asesi..." autocomplete="off">
        </div>
        <div class="filter-group">
            <button type="submit" class="btn-filter-search"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="btn-filter-reset"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="admin-table-scroll">
        <table>
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Skema</th>
                    <th>Asesi</th>
                    <th>Asesor</th>
                    <th>Rekomendasi</th>
                    <th>Dibuat</th>
                    <th style="width: 80px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $isPaginator ? $items->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td>
                            <div>{{ $item->skema?->nama_skema ?? '-' }}</div>
                            <small style="color:#64748b;">{{ $item->skema?->nomor_skema ?? '-' }}</small>
                        </td>
                        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
                        <td>{{ $item->asesor?->nama ?? '-' }}</td>
                        <td>
                            @if($item->rekomendasi === 'kompeten')
                                <span class="badge success">Kompeten</span>
                            @else
                                <span class="badge warning">Belum Kompeten</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</td>
                        <td style="text-align:center;">
                            <div class="action-wrap">
                                <div class="action-menu">
                                    <button type="button" class="action-btn" onclick="toggleMenu(this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.show', $item->id) }}" class="dropdown-item">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>

                                        @if(Auth::guard('admin')->user()->hasPermission('ceklis-observasi-aktivitas-praktik.edit'))
                                            <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.edit', $item->id) }}" class="dropdown-item">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        @endif

                                        @if(Auth::guard('admin')->user()->hasPermission('ceklis-observasi-aktivitas-praktik.delete'))
                                            <form method="POST" action="{{ route('admin.ceklis-observasi-aktivitas-praktik.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item danger">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <i class="bi bi-inboxes" style="font-size: 28px;"></i>
                                <div>Belum ada data ceklis observasi aktivitas praktik.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isPaginator && $items->hasPages())
        <div class="pagination-wrap">{{ $items->links() }}</div>
    @endif
</div>

<script>
    function toggleMenu(button) {
        const dropdown = button.nextElementSibling;
        const isVisible = dropdown.classList.contains('show');

        document.querySelectorAll('.action-dropdown.show').forEach((menu) => {
            if (menu !== dropdown) {
                menu.classList.remove('show');
            }
        });

        if (!isVisible) {
            const rect = button.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.left = (rect.right - 160) + 'px';
        }

        dropdown.classList.toggle('show');
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach((menu) => {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endsection
