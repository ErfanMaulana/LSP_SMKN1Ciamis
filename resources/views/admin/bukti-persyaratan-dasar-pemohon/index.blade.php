@extends('admin.layout')

@section('title', 'Bukti Persyaratan Dasar Pemohon')
@section('page-title', 'Bukti Persyaratan Dasar Pemohon')

@section('styles')
<style>
    .page-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:24px;
        flex-wrap:wrap;
    }

        .page-header h2 {
            margin:0;
            font-size:24px;
        }

        .card,
        .card-body,
        .table-container {
            overflow: visible;
        }

        .table-container {
            overflow-x: auto;
        }

        .actions {
            display:flex;
            gap:8px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }

        .action-menu {
            position:relative;
            display:inline-flex;
            align-items:center;
        }

        .action-btn {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:32px;
            height:32px;
            padding:0;
            border:none;
            border-radius:6px;
            background:transparent;
            color:#64748b;
            cursor:pointer;
            font-size:18px;
            line-height:1;
            transition: all .2s ease;
        }

        .action-btn:hover {
            background:#f1f5f9;
            color:#0f172a;
        }

        .action-btn:focus {
            outline:none;
        }

        .action-dropdown {
            position:fixed;
            min-width:180px;
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:8px;
            box-shadow:0 4px 24px rgba(0,0,0,.15);
            padding:0;
            display:none;
            z-index:1200;
            overflow:hidden;
            visibility:hidden;
            pointer-events:none;
        }

        .action-dropdown.show {
            display:block;
            visibility:visible;
            pointer-events:auto;
        }

        .action-dropdown.open-up {
            /* top is set dynamically in JS */
        }

        .action-dropdown a,
        .action-dropdown button {
            width:100%;
            display:flex;
            align-items:center;
            gap:10px;
            padding:10px 16px;
            border:none;
            background:transparent;
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            color:#0f172a;
            text-align:left;
            cursor:pointer;
        }

        .action-dropdown a:hover,
        .action-dropdown button:hover {
            background:#f8fafc;
        }

        .action-dropdown form {
            margin:0;
        }

        .action-dropdown button[type="submit"]:hover {
            background:#fef2f2;
            color:#dc2626;
        }

    .btn-primary:hover { background:#005f9c; }

    .card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:12px;
        box-shadow:0 1px 3px rgba(0,0,0,.06);
        overflow:visible;
    }

    .card-body { padding:20px; overflow:visible; }

    .toolbar {
        display:flex;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:18px;
    }

    .search-box {
        display:flex;
        align-items:center;
        gap:10px;
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:10px 12px;
        min-width:min(100%, 420px);
        background:#fff;
    }

    .search-box input {
        border:none;
        outline:none;
        width:100%;
        font-size:14px;
    }

    .table-container { overflow-x:auto; overflow-y:visible; }

    table {
        width:100%;
        border-collapse:collapse;
    }

    th, td {
        padding:14px 12px;
        border-bottom:1px solid #e5e7eb;
        text-align:left;
        vertical-align:top;
    }

    th {
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:.04em;
        color:#64748b;
        background:#f8fafc;
    }

    .badge {
        display:inline-flex;
        align-items:center;
        padding:4px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:600;
        background:#eef6ff;
        color:#00538d;
    }

    .actions {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        justify-content:flex-end;
    }

    td:last-child {
        overflow:visible;
        position:relative;
    }

    .btn-sm {
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:8px 12px;
        border-radius:8px;
        border:none;
        text-decoration:none;
        font-size:13px;
        font-weight:600;
        cursor:pointer;
    }

    .btn-edit { background:#0ea5e9; color:#fff; }
    .btn-delete { background:#ef4444; color:#fff; }

    .empty-state {
        text-align:center;
        padding:56px 16px;
        color:#64748b;
    }

    .empty-state i {
        font-size:36px;
        color:#cbd5e1;
        display:block;
        margin-bottom:12px;
    }

    .search-submit { display:none; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Bukti Persyaratan Dasar Pemohon</h2>
        <p>Kelola daftar persyaratan dasar per skema untuk form permohonan sertifkasi.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama skema atau nomor skema...">
            </div>
            <button type="submit" class="search-submit">Cari</button>
        </form>

        @if($items->count())
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Skema</th>
                            <th>Jenis</th>
                            <th>Jumlah Item</th>
                            <th>Terakhir Diperbarui</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <div style="font-weight:700;color:#0f172a;">{{ $item->skema->nama_skema ?? '-' }}</div>
                                    <div style="font-size:12px;color:#64748b;">{{ $item->skema->nomor_skema ?? '-' }}</div>
                                </td>
                                <td><span class="badge">{{ $item->skema->jenis_skema ?? '-' }}</span></td>
                                <td>{{ is_array($item->items) ? count($item->items) : 0 }} item</td>
                                <td>{{ $item->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                                <td style="position:relative; overflow:visible;">
                                    <div class="actions">
                                        <div class="action-menu">
                                            <button type="button" class="action-btn" aria-label="Aksi" aria-expanded="false" onclick="toggleActionMenu(this)">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <div class="action-dropdown">
                                                <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.edit', $item->id) }}"><i class="bi bi-pencil"></i> Edit</a>
                                                <form method="POST" action="{{ route('admin.bukti-persyaratan-dasar-pemohon.destroy', $item->id) }}" onsubmit="return confirm('Hapus data persyaratan dasar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="bi bi-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:16px;">
                {{ $items->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <div>Belum ada data bukti persyaratan dasar pemohon.</div>
            </div>
        @endif
    </div>
</div>

<script>
    function closeActionMenus(exceptMenu = null) {
        document.querySelectorAll('.action-dropdown.show').forEach((menu) => {
            if (menu !== exceptMenu) {
                menu.classList.remove('show');
                menu.classList.remove('open-up');
                menu.style.top = '';
                menu.style.left = '';
                const trigger = menu.parentElement?.querySelector('.action-btn');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    function toggleActionMenu(button) {
        const menu = button.parentElement?.querySelector('.action-dropdown');
        if (!menu) return;

        const isOpen = menu.classList.contains('show');
        closeActionMenus();
        if (!isOpen) {
            const wrapper = button.closest('.action-menu');
            const dropdownRect = wrapper?.getBoundingClientRect();
            const buttonRect = button.getBoundingClientRect();
            const scrollX = window.pageXOffset || document.documentElement.scrollLeft || 0;
            const scrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
            const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            const menuWidth = 180;
            const openUp = dropdownRect ? (dropdownRect.bottom + 220 > viewportHeight) : false;
            const left = Math.min(buttonRect.right - menuWidth + scrollX, viewportWidth - menuWidth - 12 + scrollX);
            const top = openUp
                ? (buttonRect.top + scrollY - 10)
                : (buttonRect.bottom + scrollY + 10);

            menu.classList.toggle('open-up', openUp);
            menu.classList.add('show');
            menu.style.left = `${Math.max(12 + scrollX, left)}px`;
            menu.style.top = openUp
                ? `${Math.max(12 + scrollY, top - menu.offsetHeight)}px`
                : `${top}px`;
            button.setAttribute('aria-expanded', 'true');
        }
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            closeActionMenus();
        }
    });
</script>
@endsection