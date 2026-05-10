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

    .action-wrap { display: flex; align-items: center; justify-content: center; }

    .action-cell {
        width: 96px;
        text-align: center;
        padding: 12px 14px;
    }

    .action-menu { position: relative; display: inline-block; }

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
        color: #475569;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .action-dropdown {
        display: none;
        position: fixed;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0,0,0,.15);
        min-width: 160px;
        z-index: 9990;
        overflow: hidden;
    }

    .action-dropdown.show {
        display: block;
    }

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

    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .action-dropdown button[type="submit"]:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .action-dropdown .danger {
        color: #475569;
    }

    .pagination-wrap { padding: 14px; }

    .empty {
        padding: 36px 16px;
        text-align: center;
        color: #64748b;
    }

    /* Kebab Menu Styles */
    .action-menu-wrapper {
        position: relative;
        display: inline-block;
        z-index: 999;
    }

    .btn-kebab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 32px;
        height: 32px;
    }

    .btn-kebab:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .btn-kebab.active {
        background: #0073bd;
        border-color: #0073bd;
        color: white;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        min-width: 200px;
        z-index: 10000;
        display: none;
        margin-top: 4px;
        overflow: visible;
        pointer-events: auto;
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .dropdown-menu.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .dropdown-menu.show-up {
        transform: translateY(0);
    }

    .dropdown-menu.dropdown-floating {
        position: fixed;
        top: 0;
        left: 0;
        right: auto;
        margin: 0;
        width: 220px;
        min-width: 220px;
        max-width: 240px;
        max-height: calc(100vh - 24px);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 20000;
        opacity: 0;
        transform: translateY(6px);
    }

    .dropdown-menu.dropdown-floating.show {
        opacity: 1;
        transform: translateY(0);
    }

    body.dropdown-open {
        overflow: hidden;
    }

    .dropdown-menu a,
    .dropdown-menu button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 14px;
        border: none;
        background: none;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        border-bottom: 1px solid #f3f4f6;
    }

    .dropdown-menu a:last-child,
    .dropdown-menu button:last-child {
        border-bottom: none;
    }

    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
        background: #f8fafc;
        color: #0073bd;
    }

    .dropdown-menu a i,
    .dropdown-menu button i {
        font-size: 14px;
        width: 18px;
        text-align: center;
    }

    .dropdown-menu .menu-disabled {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        color: #94a3b8;
        background: #f8fafc;
        border-bottom: 1px solid #f3f4f6;
        cursor: not-allowed;
    }

    .dropdown-menu .menu-disabled:last-child {
        border-bottom: none;
    }

    .dropdown-menu .menu-disabled i {
        font-size: 14px;
        width: 18px;
        text-align: center;
        color: #94a3b8;
    }

    .dropdown-menu .menu-danger:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .dropdown-menu .menu-danger i {
        color: #dc2626;
    }

    .dropdown-menu .menu-danger:hover i {
        color: #dc2626;
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
                    <th class="action-cell">Aksi</th>
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
                        <td class="action-cell">
                            <div class="action-wrap">
                                <div class="action-menu">
                                    @if(!empty($item['asesi_nik']) && !empty($item['skema_id']))
                                        <button type="button" class="action-btn" onclick="toggleMenu(this)" aria-label="Aksi data">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div class="action-dropdown">
                                            <a href="{{ route('asesor.persetujuan.front.asesor.show', [$item['asesi_nik'], $item['skema_id']]) }}">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                        </div>
                                    @else
                                        <span style="font-size:12px;color:#94a3b8;">Data belum lengkap</span>
                                    @endif
                                </div>
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

<script>
function toggleMenu(button) {
    const menu = button.nextElementSibling;
    if (!menu) return;

    document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
        if (dropdown !== menu) {
            dropdown.classList.remove('show');
        }
    });

    menu.classList.toggle('show');

    if (menu.classList.contains('show')) {
        const buttonRect = button.getBoundingClientRect();
        const menuHeight = menu.offsetHeight;
        const viewportHeight = window.innerHeight;

        let top = buttonRect.bottom + 8;
        if (top + menuHeight > viewportHeight) {
            top = buttonRect.top - menuHeight - 8;
        }

        menu.style.top = top + 'px';
        menu.style.left = (buttonRect.left - menu.offsetWidth + button.offsetWidth) + 'px';
    }

    event.stopPropagation();
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});
</script>
@endsection
