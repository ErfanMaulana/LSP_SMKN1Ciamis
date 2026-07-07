@extends('asesor.layout')

@section('title', 'Asesmen Mandiri')
@section('page-title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        cursor: pointer;
        text-decoration: none;
        display: block;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: #0073bd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .stat-card.active {
        border-color: #0073bd;
        background-color: #f0f9ff;
        box-shadow: 0 0 0 2px rgba(0, 115, 189, 0.2);
    }

    .stat-card.active .stat-value {
        color: #0073bd;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #0073bd;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

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
        grid-template-columns: minmax(0, 1fr) minmax(200px, 240px) minmax(200px, 240px) auto;
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

    .table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        background: #f8fafc;
        padding: 11px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #6b7280; }

    .badge-rekomendasi-lanjut { background: #d1fae5; color: #059669; }
    .badge-rekomendasi-tidak { background: #fee2e2; color: #dc2626; }
    .badge-rekomendasi-pending { background: #f1f5f9; color: #94a3b8; }

    .btn-review {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #e0f2fe;
        color: #0c4a6e;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .btn-review:hover {
        background: #bae6fd;
        color: #0c4a6e;
    }

    .btn-review.disabled {
        background: #e2e8f0;
        color: #94a3b8;
        pointer-events: none;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    @media (max-width: 900px) {
        .filter-row-top {
            grid-template-columns: 1fr;
        }
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
<div class="page-header">
    <div>
        <h2>Asesmen Mandiri</h2>
        <p>Kelola rekomendasi asesmen mandiri untuk asesi.</p>
    </div>
</div>

<div class="stats-grid">
    <a href="{{ route('asesor.asesmen-mandiri.index', ['status' => 'menunggu_review', 'search' => $search]) }}" class="stat-card {{ $status === 'menunggu_review' ? 'active' : '' }}">
        <div class="stat-value">{{ $summary['pending_review'] ?? 0 }}</div>
        <div class="stat-label">Menunggu Review</div>
    </a>
    <a href="{{ route('asesor.asesmen-mandiri.index', ['status' => 'belum_dikerjakan', 'search' => $search]) }}" class="stat-card {{ $status === 'belum_dikerjakan' ? 'active' : '' }}">
        <div class="stat-value">{{ $summary['belum_dikerjakan'] ?? 0 }}</div>
        <div class="stat-label">Belum Dikerjakan</div>
    </a>
    <a href="{{ route('asesor.asesmen-mandiri.index', ['status' => 'sudah_direkomendasikan', 'search' => $search]) }}" class="stat-card {{ $status === 'sudah_direkomendasikan' ? 'active' : '' }}">
        <div class="stat-value">{{ $summary['sudah_direkomendasikan'] ?? 0 }}</div>
        <div class="stat-label">Sudah Direkomendasikan</div>
    </a>
    <a href="{{ route('asesor.asesmen-mandiri.index', ['status' => 'tidak_direkomendasikan', 'search' => $search]) }}" class="stat-card {{ $status === 'tidak_direkomendasikan' ? 'active' : '' }}">
        <div class="stat-value">{{ $summary['tidak_direkomendasikan'] ?? 0 }}</div>
        <div class="stat-label">Tidak Direkomendasikan</div>
    </a>
    <a href="{{ route('asesor.asesmen-mandiri.index', ['status' => 'all', 'search' => $search]) }}" class="stat-card {{ $status === 'all' ? 'active' : '' }}">
        <div class="stat-value">{{ $summary['total'] ?? 0 }}</div>
        <div class="stat-label">Total Asesi</div>
    </a>
</div>

<form method="GET" action="{{ route('asesor.asesmen-mandiri.index') }}" class="filter-form" id="asesmenMandiriFilterForm">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                class="search-input"
                id="asesmenMandiriSearchInput"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama asesi, NIK, atau skema..."
                autocomplete="off"
            >
        </div>
        <div class="filter-field">
            <label class="filter-label">Status</label>
            <select name="status" id="asesmenMandiriStatusFilter" class="filter-select">
                <option value="">Semua Status</option>
                <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="sedang_mengerjakan" {{ $status === 'sedang_mengerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="belum_mulai" {{ $status === 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
            </select>
        </div>
        <div class="filter-field">
            <label class="filter-label">Rekomendasi</label>
            <select name="rekomendasi" id="asesmenMandiriRekomendasiFilter" class="filter-select">
                <option value="">Semua Rekomendasi</option>
                <option value="lanjut" {{ $rekomendasi === 'lanjut' ? 'selected' : '' }}>Lanjut</option>
                <option value="tidak_lanjut" {{ $rekomendasi === 'tidak_lanjut' ? 'selected' : '' }}>Tidak Lanjut</option>
                <option value="belum" {{ $rekomendasi === 'belum' ? 'selected' : '' }}>Belum Direview</option>
            </select>
        </div>
        <div class="filter-field">
            <label class="filter-label" style="visibility: hidden;">Switcher</label>
            <div class="view-switcher" id="asesmenMandiriViewSwitcher">
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

<div class="table-card" id="asesmenMandiriTableContainer">
    @include('asesor.asesmen-mandiri.partials.table-rows')
</div>

<script>
    let asesmenMandiriAjaxController = null;
    let asesmenMandiriSearchTimer = null;
    let asesmenMandiriCurrentView = '{{ $viewMode ?? "menunggu" }}';

    // Caching HTML to prevent loading delay when switching views
    let asesmenMandiriCache = {
        'menunggu': null,
        'selesai': null
    };

    function prefetchAsesmenMandiriView() {
        const otherView = asesmenMandiriCurrentView === 'menunggu' ? 'selesai' : 'menunggu';
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
            asesmenMandiriCache[otherView] = html;
        })
        .catch(err => console.warn('Prefetch warning:', err));
    }

    function ajaxLoadAsesmenMandiri(url) {
        if (asesmenMandiriAjaxController) {
            asesmenMandiriAjaxController.abort();
        }

        asesmenMandiriAjaxController = new AbortController();

        const tableContainer = document.getElementById('asesmenMandiriTableContainer');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: asesmenMandiriAjaxController.signal
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data asesmen mandiri');
            }
            return response.text();
        })
        .then(function(html) {
            if (tableContainer) {
                tableContainer.innerHTML = html;
                tableContainer.style.opacity = '1';
            }

            asesmenMandiriCache[asesmenMandiriCurrentView] = html;
            window.history.replaceState({}, '', url);
            prefetchAsesmenMandiriView();
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
        const searchInput = document.getElementById('asesmenMandiriSearchInput');
        const statusFilter = document.getElementById('asesmenMandiriStatusFilter');
        const rekomendasiFilter = document.getElementById('asesmenMandiriRekomendasiFilter');
        const url = new URL('{{ route('asesor.asesmen-mandiri.index') }}', window.location.origin);

        url.searchParams.set('view', viewMode || asesmenMandiriCurrentView);

        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        if (statusFilter && statusFilter.value.trim() !== '') {
            url.searchParams.set('status', statusFilter.value.trim());
        }

        if (rekomendasiFilter && rekomendasiFilter.value.trim() !== '') {
            url.searchParams.set('rekomendasi', rekomendasiFilter.value.trim());
        }

        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('asesmenMandiriSearchInput');
        const statusFilter = document.getElementById('asesmenMandiriStatusFilter');
        const rekomendasiFilter = document.getElementById('asesmenMandiriRekomendasiFilter');
        const tableContainer = document.getElementById('asesmenMandiriTableContainer');

        // Store initial view into cache
        if (tableContainer) {
            asesmenMandiriCache[asesmenMandiriCurrentView] = tableContainer.innerHTML;
        }

        // Prefetch other view in background immediately
        prefetchAsesmenMandiriView();

        function handleFilterChange() {
            // Clear cache since query has changed
            asesmenMandiriCache['menunggu'] = null;
            asesmenMandiriCache['selesai'] = null;
            ajaxLoadAsesmenMandiri(serializeFilterForm(asesmenMandiriCurrentView));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(asesmenMandiriSearchTimer);
                asesmenMandiriSearchTimer = setTimeout(handleFilterChange, 400);
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', handleFilterChange);
        }

        if (rekomendasiFilter) {
            rekomendasiFilter.addEventListener('change', handleFilterChange);
        }

        // Switcher buttons
        const switcher = document.getElementById('asesmenMandiriViewSwitcher');
        if (switcher) {
            switcher.querySelectorAll('.view-switcher-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const targetView = this.dataset.view;
                    if (targetView === asesmenMandiriCurrentView) return;

                    asesmenMandiriCurrentView = targetView;
                    switcher.querySelectorAll('.view-switcher-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // If status filter dropdown is set, we clear it to switch between global Menunggu and Selesai view modes smoothly
                    if (statusFilter && statusFilter.value !== '') {
                        statusFilter.value = '';
                    }

                    if (asesmenMandiriCache[targetView]) {
                        if (tableContainer) {
                            tableContainer.innerHTML = asesmenMandiriCache[targetView];
                        }
                        const url = serializeFilterForm(targetView);
                        window.history.replaceState({}, '', url);
                    } else {
                        ajaxLoadAsesmenMandiri(serializeFilterForm(targetView));
                    }
                });
            });
        }
    });
</script>
@endsection
