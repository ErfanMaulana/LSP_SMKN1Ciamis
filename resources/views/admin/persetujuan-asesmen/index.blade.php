@extends('admin.layout')

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

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; flex-shrink: 0; }
    .stat-icon.blue { background: #0073bd; }
    .stat-icon.green { background: #0073bd; }
    .stat-icon.purple { background: #0073bd; }
    .stat-label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 22px; color: #0f172a; font-weight: 700; margin-top: 2px; }

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

    .filter-select {
        padding: 10px 36px 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s;
    }

    .filter-select:hover {
        border-color: #cbd5e1;
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

    .btn-danger:hover {
        background: #fee2e2;
        color: #991b1b;
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

    .persetujuan-delete-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 10000;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .persetujuan-delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .persetujuan-delete-confirm-modal {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.3);
        transform: translateY(10px) scale(0.96);
        opacity: 0.92;
        transition: transform 0.22s ease, opacity 0.22s ease;
    }

    .persetujuan-delete-confirm-overlay.show .persetujuan-delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .persetujuan-delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .persetujuan-delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .persetujuan-delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .persetujuan-delete-btn-cancel,
    .persetujuan-delete-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .persetujuan-delete-btn-cancel {
        background: #0073bd;
        border-color: #0073bd;
    }
    .persetujuan-delete-btn-cancel:hover {
        background: #005f99;
        border-color: #005f99;
    }

    .persetujuan-delete-btn-submit {
        background: #0073bd;
        border-color: #0073bd;
    }
    .persetujuan-delete-btn-submit:hover {
        background: #005f99;
        border-color: #005f99;
    }

    @media (prefers-reduced-motion: reduce) {
        .persetujuan-delete-confirm-overlay,
        .persetujuan-delete-confirm-modal {
            transition: none;
        }
    }
</style>
@endsection

@section('content')
@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp

<div class="page-header">
    <h2>Persetujuan Asesmen dan Kerahasiaan</h2>
    @if(Auth::guard('admin')->user()->hasPermission('persetujuan-asesmen.create'))
        <a href="{{ route('admin.persetujuan-asesmen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </a>
    @endif
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-layout-text-sidebar-reverse"></i></div>
        <div>
            <div class="stat-label">Total Skema</div>
            <div class="stat-value">{{ $stats['total_skema'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-check"></i></div>
        <div>
            <div class="stat-label">Total Asesi</div>
            <div class="stat-value">{{ $stats['total_asesi'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-person-badge"></i></div>
        <div>
            <div class="stat-label">Total Asesor</div>
            <div class="stat-value">{{ $stats['total_asesor'] }}</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.persetujuan-asesmen.index') }}" class="search-row" id="filterForm">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari skema, asesor, atau asesi..." autocomplete="off">
        </div>
        <div class="filter-group">
            <select name="skema" class="filter-select">
                <option value="">Semua Skema</option>
                @foreach($skemaList as $skemaName)
                    <option value="{{ $skemaName }}" {{ $skemaFilter === $skemaName ? 'selected' : '' }}>{{ $skemaName }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card">
    <div class="admin-table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Asesi</th>
                    <th>Asesor</th>
                    <th>Skema</th>
                    <th>Nomor Skema</th>
                    <th>Dibuat</th>
                    <th class="action-cell">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @include('admin.persetujuan-asesmen.partials.table-rows')
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap" id="paginationWrap">
        @if($isPaginator && $items->hasPages())
            {{ $items->links() }}
        @endif
    </div>
</div>

<div id="persetujuan-delete-confirm-overlay" class="persetujuan-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="persetujuanDeleteConfirmTitle" aria-hidden="true">
    <div class="persetujuan-delete-confirm-modal">
        <h3 id="persetujuanDeleteConfirmTitle" class="persetujuan-delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="persetujuanDeleteConfirmText" class="persetujuan-delete-confirm-text">Apakah Anda yakin?</p>
        <div class="persetujuan-delete-confirm-actions">
            <button type="button" id="persetujuanDeleteConfirmCancel" class="persetujuan-delete-btn-cancel">Batal</button>
            <button type="button" id="persetujuanDeleteConfirmSubmit" class="persetujuan-delete-btn-submit">Hapus</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleMenu(button) {
        const menu = button.nextElementSibling;
        if (!menu) return;

        document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
            if (dropdown !== menu) {
                dropdown.classList.remove('show');
            }
        });

        const isVisible = menu.classList.contains('show');
        if (!isVisible) {
            const rect = button.getBoundingClientRect();
            menu.style.top = `${rect.bottom + window.scrollY + 6}px`;
            menu.style.left = `${rect.left + window.scrollX - 120}px`;
            menu.classList.add('show');
        } else {
            menu.classList.remove('show');
        }
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(dropdown => dropdown.classList.remove('show'));
        }
    });

    // AJAX Search and Filters
    const filterForm = document.getElementById('filterForm');
    const tableBody = document.querySelector('table tbody');
    const paginationWrap = document.getElementById('paginationWrap');

    function performAjaxSearch(pageUrl = null) {
        if (!filterForm || !tableBody || !paginationWrap) return;

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        let url = pageUrl ? pageUrl : (filterForm.action + '?' + params.toString());

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal memuat data.');
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = data.rows || '';
            paginationWrap.innerHTML = data.pagination || '';
            window.history.replaceState({}, '', url);
        })
        .catch(error => console.error('Search error:', error));
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(event) {
            event.preventDefault();
            performAjaxSearch();
        });

        const searchInput = filterForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    performAjaxSearch();
                }
            });

            let searchTimer = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    performAjaxSearch();
                }, 300);
            });
        }

        const skemaSelect = filterForm.querySelector('select[name="skema"]');
        if (skemaSelect) {
            skemaSelect.addEventListener('change', function() {
                performAjaxSearch();
            });
        }
    }

    paginationWrap?.addEventListener('click', function(event) {
        const link = event.target.closest('a');
        if (!link) return;

        event.preventDefault();
        performAjaxSearch(link.href);
    });

    let pendingPersetujuanDeleteForm = null;

    window.openPersetujuanDeleteModal = function(event, form, message) {
        if (event) {
            event.preventDefault();
        }

        pendingPersetujuanDeleteForm = form;

        const overlay = document.getElementById('persetujuan-delete-confirm-overlay');
        const text = document.getElementById('persetujuanDeleteConfirmText');
        if (!overlay || !text) return false;

        text.textContent = message || 'Apakah Anda yakin?';
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');

        return false;
    };

    window.closePersetujuanDeleteModal = function() {
        const overlay = document.getElementById('persetujuan-delete-confirm-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        pendingPersetujuanDeleteForm = null;
    };

    const persetujuanDeleteOverlay = document.getElementById('persetujuan-delete-confirm-overlay');
    const persetujuanDeleteCancelBtn = document.getElementById('persetujuanDeleteConfirmCancel');
    const persetujuanDeleteSubmitBtn = document.getElementById('persetujuanDeleteConfirmSubmit');

    persetujuanDeleteCancelBtn?.addEventListener('click', closePersetujuanDeleteModal);

    persetujuanDeleteOverlay?.addEventListener('click', function(event) {
        if (event.target === persetujuanDeleteOverlay) {
            closePersetujuanDeleteModal();
        }
    });

    persetujuanDeleteSubmitBtn?.addEventListener('click', function() {
        if (!pendingPersetujuanDeleteForm) return;
        const formToSubmit = pendingPersetujuanDeleteForm;
        closePersetujuanDeleteModal();
        formToSubmit.submit();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePersetujuanDeleteModal();
        }
    });
</script>
@endsection
