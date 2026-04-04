@extends('admin.layout')

@section('title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.2);
        transform: translateY(-2px);
        border-color: #bfdbfe;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.gray { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .stat-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 10px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        align-items: baseline;
        gap: 8px;
        flex-wrap: wrap;
    }

    .toolbar {
        margin-bottom: 16px;
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
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
        cursor: pointer;
        display: flex;
        align-items: center;
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
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-filter-reset:hover {
        background: #fecaca;
    }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing:.5px;
        background: #f8fafc; border-bottom: 1px solid #e5e7eb;
    }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.belum_mulai        { background: #f1f5f9; color: #64748b; }
    .badge.sedang_mengerjakan { background: #fef3c7; color: #92400e; }
    .badge.selesai            { background: #d1fae5; color: #065f46; }

    .badge-rekom { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; }
    .badge-rekom.lanjut       { background: #d1fae5; color: #065f46; }
    .badge-rekom.tidak_lanjut { background: #fee2e2; color: #991b1b; }
    .badge-rekom.draft        { background: #f1f5f9; color: #94a3b8; }

    .btn-detail {
        padding: 6px 14px; background: #f0f7ff; color: #0061a5; border: 1px solid #bfdbfe;
        border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all .2s;
    }
    .btn-detail:hover { background: #0061a5; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    .pagination-wrapper { padding: 16px; display: flex; justify-content: center; }
    .pagination-wrapper nav { display: flex; }
    .pagination-wrapper svg { height: 20px; }

    @media (max-width: 640px) {
        .stats-grid {
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
            gap: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
            border-radius: 10px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Asesmen Mandiri</h2>
        <p>Monitoring status dan hasil asesmen mandiri seluruh asesi</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-text"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats->total ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-hourglass"></i></div>
        <div class="stat-content">
            <div class="stat-label">Belum Mulai</div>
            <div class="stat-value">{{ $stats->belum_mulai ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-pencil-square"></i></div>
        <div class="stat-content">
            <div class="stat-label">Sedang Mengerjakan</div>
            <div class="stat-value">{{ $stats->sedang_mengerjakan ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div class="stat-content">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats->selesai ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" action="{{ route('admin.asesmen-mandiri.index') }}" id="filterForm">
        <div class="filter-section">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Cari nama / NIK / skema..." value="{{ request('search') }}" autocomplete="off">
            </div>
            <div class="filter-group">
                <select class="filter-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="belum_mulai" {{ request('status') == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                    <option value="sedang_mengerjakan" {{ request('status') == 'sedang_mengerjakan' ? 'selected' : '' }}>Sedang Mengerjakan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <select class="filter-select" name="skema_id">
                    <option value="">Semua Skema</option>
                    @foreach($skemas as $skema)
                        <option value="{{ $skema->id }}" {{ request('skema_id') == $skema->id ? 'selected' : '' }}>{{ $skema->nama_skema }}</option>
                    @endforeach
                </select>
                
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card" id="tableContainer">
    @include('admin.asesmen-mandiri._table')
</div>
@endsection

@section('scripts')
<script>
    const filterForm = document.getElementById('filterForm');
    const tableContainer = document.getElementById('tableContainer');
    const searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;

    function performAjaxSearch() {
        if (!filterForm || !tableContainer) return;

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        fetch('{{ route("admin.asesmen-mandiri.index") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            window.history.replaceState({}, '', '{{ route("admin.asesmen-mandiri.index") }}?' + params.toString());
        })
        .catch(error => console.error('Search error:', error));
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(event) {
            event.preventDefault();
            performAjaxSearch();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                performAjaxSearch();
            }
        });

        searchInput.addEventListener('input', function() {
            performAjaxSearch();
        });
    }

    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            performAjaxSearch();
        });
    });

    tableContainer?.addEventListener('click', function(event) {
        const link = event.target.closest('.pagination a');
        if (!link) return;

        event.preventDefault();

        const linkUrl = new URL(link.href);
        const page = linkUrl.searchParams.get('page');

        const params = new URLSearchParams(new FormData(filterForm));
        if (page) {
            params.set('page', page);
        }

        fetch('{{ route("admin.asesmen-mandiri.index") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            window.history.replaceState({}, '', '{{ route("admin.asesmen-mandiri.index") }}?' + params.toString());
        })
        .catch(error => console.error('Pagination error:', error));
    });

    function resetFilters() {
        if (!filterForm) return;

        const search = filterForm.querySelector('input[name="search"]');
        const status = filterForm.querySelector('select[name="status"]');
        const skema = filterForm.querySelector('select[name="skema_id"]');

        if (search) search.value = '';
        if (status) status.value = '';
        if (skema) skema.value = '';

        performAjaxSearch();
    }
</script>
@endsection
