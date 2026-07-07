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

    .filter-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .filter-row {
        display: grid;
        gap: 10px;
        align-items: end;
    }

    .filter-row-top {
        grid-template-columns: minmax(0, 1fr) minmax(240px, 280px) auto;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .search-input-wrapper {
        flex: 1 1 360px;
        position: relative;
        min-width: 0;
    }

    .search-input {
        width: 100%;
        padding: 12px 44px 12px 42px;
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }

    .filter-select {
        width: 100%;
        min-width: 0;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #dbe4ef;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        color: #0f172a;
        font-size: 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        outline: none;
    }

    .filter-select:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    @media (max-width: 900px) {
        .filter-row-top {
            grid-template-columns: 1fr;
        }
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

    .btn-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #0073bd;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover { background: #005f9a; color: white; }
    .btn-review.disabled {
        background: #e2e8f0;
        color: #64748b;
        pointer-events: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
    }
    .status-badge--warning { background: #fef3c7; color: #92400e; }
    .status-badge--info { background: #dbeafe; color: #1d4ed8; }
    .status-badge--success { background: #dcfce7; color: #166534; }

    .pagination-wrap { padding: 14px; }

    .empty {
        padding: 36px 16px;
        text-align: center;
        color: #64748b;
    }

    .view-switcher {
        display: inline-flex;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .view-switcher-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all .2s ease;
        white-space: nowrap;
    }
    .view-switcher-btn:hover {
        color: #334155;
        background: #e2e8f0;
    }
    .view-switcher-btn.active {
        background: #0073bd;
        color: #fff;
        box-shadow: 0 2px 4px rgba(0,115,189,.25);
    }
    .view-switcher-btn .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
    }
    .view-switcher-btn.active .count-badge {
        background: rgba(255,255,255,.25);
        color: #fff;
    }
    .view-switcher-btn:not(.active) .count-badge {
        background: #e2e8f0;
        color: #64748b;
    }
</style>
@endsection

@section('content')
@php $items = collect($items ?? []); @endphp

<div class="page-header">
    <h2>Persetujuan Asesmen dan Kerahasiaan</h2>
</div>

<form method="GET" action="{{ route('asesor.persetujuan-asesmen.index') }}" class="filter-form" id="persetujuanAsesmenFilterForm">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                class="search-input"
                id="persetujuanAsesmenSearchInput"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari skema atau asesi..."
                autocomplete="off"
            >
        </div>
        <div class="filter-field">
            <label class="filter-label">Status</label>
            <select name="status" id="persetujuanAsesmenStatusFilter" class="filter-select">
                <option value="">Semua Status</option>
                <option value="belum_asesor" {{ ($status ?? '') === 'belum_asesor' ? 'selected' : '' }}>Belum Ditandatangani Asesor</option>
                <option value="belum_asesi" {{ ($status ?? '') === 'belum_asesi' ? 'selected' : '' }}>Belum Ditandatangani Asesi</option>
                <option value="sudah" {{ ($status ?? '') === 'sudah' ? 'selected' : '' }}>Sudah Ditandatangani</option>
            </select>
        </div>
        <div class="filter-field">
            <label class="filter-label" style="visibility: hidden;">Switcher</label>
            <div class="view-switcher" id="persetujuanViewSwitcher">
                <button type="button" class="view-switcher-btn {{ ($viewMode ?? 'menunggu') === 'menunggu' ? 'active' : '' }}" data-view="menunggu">
                    <i class="bi bi-hourglass-split"></i> Menunggu
                    <span class="count-badge">{{ $pendingCount ?? 0 }}</span>
                </button>
                <button type="button" class="view-switcher-btn {{ ($viewMode ?? 'menunggu') === 'selesai' ? 'active' : '' }}" data-view="selesai">
                    <i class="bi bi-check-circle-fill"></i> Selesai
                    <span class="count-badge">{{ $completedCount ?? 0 }}</span>
                </button>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="admin-table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Nomor Skema</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="persetujuanAsesmenTableContainer">
                @include('persetujuan-asesmen.partials.asesor-table-rows')
            </tbody>
        </table>
    </div>
</div>

<script>
    let persetujuanAsesmenAjaxController = null;
    let persetujuanAsesmenSearchTimer = null;
    let persetujuanCurrentView = '{{ $viewMode ?? "menunggu" }}';

    // Caching HTML to prevent loading delay when switching views
    let persetujuanCache = {
        'menunggu': null,
        'selesai': null
    };

    function prefetchPersetujuanView() {
        const otherView = persetujuanCurrentView === 'menunggu' ? 'selesai' : 'menunggu';
        const url = serializeFilterForm(otherView);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) return response.text();
            throw new Error('Prefetch failed');
        })
        .then(html => {
            persetujuanCache[otherView] = html;
        })
        .catch(err => console.warn('Prefetch warning:', err));
    }

    function ajaxLoadPersetujuanAsesmen(url) {
        if (persetujuanAsesmenAjaxController) {
            persetujuanAsesmenAjaxController.abort();
        }

        persetujuanAsesmenAjaxController = new AbortController();

        const tableContainer = document.getElementById('persetujuanAsesmenTableContainer');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: persetujuanAsesmenAjaxController.signal
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data persetujuan asesmen');
            }
            return response.text();
        })
        .then(function(html) {
            if (tableContainer) {
                tableContainer.innerHTML = html;
                tableContainer.style.opacity = '1';
            }

            persetujuanCache[persetujuanCurrentView] = html;
            window.history.replaceState({}, '', url);
            prefetchPersetujuanView();
        })
        .catch(function(error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                if (tableContainer) {
                    tableContainer.style.opacity = '1';
                }
            }
        });
    }

    function serializeFilterForm(viewMode) {
        const searchInput = document.getElementById('persetujuanAsesmenSearchInput');
        const statusFilter = document.getElementById('persetujuanAsesmenStatusFilter');
        const url = new URL('{{ route('asesor.persetujuan-asesmen.index') }}', window.location.origin);

        url.searchParams.set('view', viewMode || persetujuanCurrentView);

        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        if (statusFilter && statusFilter.value.trim() !== '') {
            url.searchParams.set('status', statusFilter.value.trim());
        }

        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('persetujuanAsesmenSearchInput');
        const statusFilter = document.getElementById('persetujuanAsesmenStatusFilter');
        const tableContainer = document.getElementById('persetujuanAsesmenTableContainer');

        // Store initial view into cache
        if (tableContainer) {
            persetujuanCache[persetujuanCurrentView] = tableContainer.innerHTML;
        }

        // Prefetch other view in background immediately
        prefetchPersetujuanView();

        function handleFilterChange() {
            // Clear cache since query has changed
            persetujuanCache['menunggu'] = null;
            persetujuanCache['selesai'] = null;
            ajaxLoadPersetujuanAsesmen(serializeFilterForm(persetujuanCurrentView));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(persetujuanAsesmenSearchTimer);
                persetujuanAsesmenSearchTimer = setTimeout(handleFilterChange, 400);
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', handleFilterChange);
        }

        // Switcher buttons
        const switcher = document.getElementById('persetujuanViewSwitcher');
        if (switcher) {
            switcher.querySelectorAll('.view-switcher-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetView = this.dataset.view;
                    if (targetView === persetujuanCurrentView) return;

                    persetujuanCurrentView = targetView;
                    switcher.querySelectorAll('.view-switcher-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    if (persetujuanCache[targetView]) {
                        if (tableContainer) {
                            tableContainer.innerHTML = persetujuanCache[targetView];
                        }
                        const url = serializeFilterForm(targetView);
                        window.history.replaceState({}, '', url);
                    } else {
                        ajaxLoadPersetujuanAsesmen(serializeFilterForm(targetView));
                    }
                });
            });
        }
    });
</script>
@endsection
