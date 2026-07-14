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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #ffffff;
        padding: 16px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffffff;
        flex-shrink: 0;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .stat-icon.teal { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #047857); }
    .stat-icon.red { background: linear-gradient(135deg, #f43f5e, #be123c); }
    
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
        margin-top: 2px;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
    }
    @media (max-width: 640px) {
        .stats-grid {
            gap: 12px;
        }
        .stat-card {
            padding: 12px;
            gap: 10px;
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
            border-radius: 8px;
        }
        .stat-label {
            font-size: 10px;
        }
        .stat-value {
            font-size: 18px;
        }
    }
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .ceklis-delete-confirm-overlay {
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

    .ceklis-delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .ceklis-delete-confirm-modal {
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

    .ceklis-delete-confirm-overlay.show .ceklis-delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .ceklis-delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .ceklis-delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .ceklis-delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .ceklis-delete-btn-cancel,
    .ceklis-delete-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ceklis-delete-btn-cancel {
        background: #0073bd;
        border-color: #0073bd;
    }
    .ceklis-delete-btn-cancel:hover {
        background: #005f99;
        border-color: #005f99;
    }

    .ceklis-delete-btn-submit {
        background: #0073bd;
        border-color: #0073bd;
    }
    .ceklis-delete-btn-submit:hover {
        background: #005f99;
        border-color: #005f99;
    }

    @media (prefers-reduced-motion: reduce) {
        .ceklis-delete-confirm-overlay,
        .ceklis-delete-confirm-modal {
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
    <h2>Ceklis Observasi Aktivitas Praktik</h2>
    @if(Auth::guard('admin')->user()->hasPermission('ceklis-observasi-aktivitas-praktik.create'))
        <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </a>
    @endif
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-text"></i></div>
        <div>
            <div class="stat-label">Skema</div>
            <div class="stat-value" id="stat-skema">{{ $stats['skema'] ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-label">Asesi</div>
            <div class="stat-value" id="stat-asesi">{{ $stats['asesi'] ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-person-badge"></i></div>
        <div>
            <div class="stat-label">Asesor</div>
            <div class="stat-value" id="stat-asesor">{{ $stats['asesor'] ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">Kompeten</div>
            <div class="stat-value" id="stat-kompeten">{{ $stats['kompeten'] ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-label">Tidak Kompeten</div>
            <div class="stat-value" id="stat-tidak-kompeten">{{ $stats['tidak_kompeten'] ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="search-row" id="filterForm">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari skema, asesor, atau asesi..." autocomplete="off">
        </div>
        <div class="filter-group">
            <select name="skema" class="filter-select">
                <option value="">Semua Skema</option>
                @foreach($skemaList ?? [] as $skemaName)
                    <option value="{{ $skemaName }}" {{ ($skemaFilter ?? '') === $skemaName ? 'selected' : '' }}>{{ $skemaName }}</option>
                @endforeach
            </select>
            <select name="rekomendasi" class="filter-select">
                <option value="">Semua Rekomendasi</option>
                <option value="kompeten" {{ ($rekomendasiFilter ?? '') === 'kompeten' ? 'selected' : '' }}>Kompeten</option>
                <option value="belum_kompeten" {{ ($rekomendasiFilter ?? '') === 'belum_kompeten' ? 'selected' : '' }}>Belum Kompeten</option>
            </select>
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
                @include('admin.ceklis-observasi-aktivitas-praktik.partials.table-rows')
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap" id="paginationWrap">
        @if($isPaginator && $items->hasPages())
            {{ $items->links() }}
        @endif
    </div>
</div>

<div id="ceklis-delete-confirm-overlay" class="ceklis-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="ceklisDeleteConfirmTitle" aria-hidden="true">
    <div class="ceklis-delete-confirm-modal">
        <h3 id="ceklisDeleteConfirmTitle" class="ceklis-delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="ceklisDeleteConfirmText" class="ceklis-delete-confirm-text">Apakah Anda yakin?</p>
        <div class="ceklis-delete-confirm-actions">
            <button type="button" id="ceklisDeleteConfirmCancel" class="ceklis-delete-btn-cancel">Batal</button>
            <button type="button" id="ceklisDeleteConfirmSubmit" class="ceklis-delete-btn-submit">Hapus</button>
        </div>
    </div>
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
            
            if (data.stats) {
                const statSkema = document.getElementById('stat-skema');
                const statAsesi = document.getElementById('stat-asesi');
                const statAsesor = document.getElementById('stat-asesor');
                const statKompeten = document.getElementById('stat-kompeten');
                const statTidakKompeten = document.getElementById('stat-tidak-kompeten');

                if (statSkema) statSkema.textContent = data.stats.skema ?? 0;
                if (statAsesi) statAsesi.textContent = data.stats.asesi ?? 0;
                if (statAsesor) statAsesor.textContent = data.stats.asesor ?? 0;
                if (statKompeten) statKompeten.textContent = data.stats.kompeten ?? 0;
                if (statTidakKompeten) statTidakKompeten.textContent = data.stats.tidak_kompeten ?? 0;
            }
            
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

        const filterSelects = filterForm.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                performAjaxSearch();
            });
        });
    }

    paginationWrap?.addEventListener('click', function(event) {
        const link = event.target.closest('a');
        if (!link) return;

        event.preventDefault();
        performAjaxSearch(link.href);
    });

    let pendingCeklisDeleteForm = null;

    window.openCeklisDeleteModal = function(event, form, message) {
        if (event) {
            event.preventDefault();
        }

        pendingCeklisDeleteForm = form;

        const overlay = document.getElementById('ceklis-delete-confirm-overlay');
        const text = document.getElementById('ceklisDeleteConfirmText');
        if (!overlay || !text) return false;

        text.textContent = message || 'Apakah Anda yakin?';
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');

        return false;
    };

    window.closeCeklisDeleteModal = function() {
        const overlay = document.getElementById('ceklis-delete-confirm-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        pendingCeklisDeleteForm = null;
    };

    const ceklisDeleteOverlay = document.getElementById('ceklis-delete-confirm-overlay');
    const ceklisDeleteCancelBtn = document.getElementById('ceklisDeleteConfirmCancel');
    const ceklisDeleteSubmitBtn = document.getElementById('ceklisDeleteConfirmSubmit');

    ceklisDeleteCancelBtn?.addEventListener('click', closeCeklisDeleteModal);

    ceklisDeleteOverlay?.addEventListener('click', function(event) {
        if (event.target === ceklisDeleteOverlay) {
            closeCeklisDeleteModal();
        }
    });

    ceklisDeleteSubmitBtn?.addEventListener('click', function() {
        if (!pendingCeklisDeleteForm) return;
        const formToSubmit = pendingCeklisDeleteForm;
        closeCeklisDeleteModal();
        formToSubmit.submit();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCeklisDeleteModal();
        }
    });
</script>
@endsection
