@extends('admin.layout')

@section('title', 'Komponen Umpan Balik')
@section('page-title', 'Komponen Umpan Balik')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
    .stat-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px; display: flex; align-items: center; gap: 12px; }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; }
    .stat-icon.blue { background: #0073bd; }
    .stat-icon.green { background: #10b981; }
    .stat-icon.gray { background: #64748b; }
    .stat-label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 22px; color: #0F172A; font-weight: 700; margin-top: 2px; }

    .btn { display: inline-flex; align-items: center; gap: 6px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: 600; padding: 9px 16px; }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-primary:hover { background: #005f9a; }

    .toolbar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 14px; }
    .toolbar form { display: flex; gap: 10px; flex-wrap: wrap; }
    .search-input { flex: 1; min-width: 260px; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; }
    .search-input:focus, .filter-select:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0, 115, 189, .1); }
    .filter-select { min-width: 170px; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f8fafc; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding: 12px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 13px; color: #334155; }
    tr:last-child td { border-bottom: none; }
    .skema-badge { display: inline-block; padding: 3px 8px; border-radius: 6px; background: #eff6ff; color: #0073bd; font-size: 12px; font-weight: 600; }
    .status-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 999px; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    .actions { display: flex; gap: 8px; align-items: center; }

    .action-menu {
        position: relative;
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
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #f1f5f9;
    }

    .action-dropdown {
        display: none;
        position: fixed;
        margin-top: 4px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        min-width: 160px;
        z-index: 9990;
        overflow: visible;
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
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .action-dropdown form:last-of-type button:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .pagination-wrap { padding: 12px 14px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
    .pagination-info { font-size: 13px; color: #64748b; }

    .empty-state { text-align: center; padding: 72px 20px; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state h4 { font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px; }
    .empty-state p { font-size: 13px; color: #9ca3af; margin: 0; }

    @media (max-width: 768px) {
        .toolbar form { flex-direction: column; }
        .search-input, .filter-select, .btn-primary { width: 100%; }
        .card { overflow-x: auto; }
        table { min-width: 760px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Komponen Umpan Balik Asesi</h2>
        <p>Daftar skema yang sudah memiliki komponen umpan balik FR.AK.03.</p>
    </div>
    <a href="{{ route('admin.umpan-balik-komponen.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Komponen
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-list-check"></i></div>
        <div>
            <div class="stat-label">Total Skema</div>
            <div class="stat-value">{{ $stats['total_skema'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">Komponen Aktif</div>
            <div class="stat-value">{{ $stats['active'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-slash-circle"></i></div>
        <div>
            <div class="stat-label">Komponen Nonaktif</div>
            <div class="stat-value">{{ $stats['inactive'] }}</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <form id="ajaxFilterForm" method="GET" action="{{ route('admin.umpan-balik-komponen.index') }}">
        <input type="text" id="searchInput" name="search" class="search-input" value="{{ $search }}" placeholder="Cari nama/nomor skema...">
        <select id="skemaSelect" name="skema_id" class="filter-select">
            <option value="all" {{ $skemaId === 'all' ? 'selected' : '' }}>Semua Skema</option>
            @foreach($skemaList as $skema)
                <option value="{{ $skema->id }}" {{ (string)$skemaId === (string)$skema->id ? 'selected' : '' }}>
                    {{ $skema->nama_skema }}
                </option>
            @endforeach
        </select>
        <select id="statusSelect" name="status" class="filter-select">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </form>
</div>

<div class="card">
    @if($komponen->count())
        <table>
            <thead>
                <tr>
                    <th width="70">No</th>
                    <th>Skema</th>
                    <th width="180">Total Komponen</th>
                    <th width="180">Status Komponen</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    <tr>
                        <td>{{ ($komponen->firstItem() ?? 0) + $loop->index }}</td>
                        <td>
                            @if($item->skema)
                                <div style="font-weight:600;color:#0f172a;">{{ $item->skema->nama_skema }}</div>
                                <span class="skema-badge">{{ $item->skema->nomor_skema }}</span>
                            @else
                                <span style="color:#94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight:600;color:#0f172a;">{{ (int) $item->total_komponen }}</span>
                        </td>
                        <td>
                            <div class="actions">
                                <span class="status-badge active">
                                    <i class="bi bi-check-circle"></i>
                                    {{ (int) $item->total_active }} Aktif
                                </span>
                                @if((int) $item->total_inactive > 0)
                                    <span class="status-badge inactive">
                                        <i class="bi bi-x-circle"></i>
                                        {{ (int) $item->total_inactive }} Nonaktif
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="action-menu">
                                <button class="action-btn" onclick="toggleMenu(event, this)">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="action-dropdown">
                                    <a href="{{ route('admin.umpan-balik-komponen.show', $item->skema_id) }}">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                    <a href="{{ route('admin.umpan-balik-komponen.edit-skema', $item->skema_id) }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.umpan-balik-komponen.destroy-skema', $item->skema_id) }}" method="POST" onsubmit="return confirm('Hapus semua komponen pada skema ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $komponen->firstItem() ?? 0 }} sampai {{ $komponen->lastItem() ?? 0 }} dari {{ $komponen->total() }} data
            </div>
            <div>{{ $komponen->links() }}</div>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Tidak ada data skema sertifikasi ditemukan</h4>
            <p>Coba kata kunci lain.</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const form = document.getElementById('ajaxFilterForm');
        if (!form) return;

        const searchInput = document.getElementById('searchInput');
        const skemaSelect = document.getElementById('skemaSelect');
        const statusSelect = document.getElementById('statusSelect');

        let debounceTimer = null;
        let activeController = null;

        const buildUrl = (pageUrl = null) => {
            const url = new URL(pageUrl || form.action, window.location.origin);
            const params = new URLSearchParams();

            params.set('search', (searchInput.value || '').trim());
            params.set('skema_id', skemaSelect.value || 'all');
            params.set('status', statusSelect.value || 'all');

            const page = url.searchParams.get('page');
            if (page) params.set('page', page);

            return `${form.action}?${params.toString()}`;
        };

        const fetchAndReplaceCard = async (pageUrl = null) => {
            const requestUrl = buildUrl(pageUrl);

            if (activeController) activeController.abort();
            activeController = new AbortController();

            try {
                const response = await fetch(requestUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: activeController.signal
                });

                if (!response.ok) return;

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newCard = doc.querySelector('.card');
                const currentCard = document.querySelector('.card');
                if (newCard && currentCard) {
                    currentCard.replaceWith(newCard);
                }

                window.history.replaceState({}, '', requestUrl);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Gagal memuat data ajax:', error);
                }
            }
        };

        const debounceFetch = () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchAndReplaceCard(), 350);
        };

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            fetchAndReplaceCard();
        });

        searchInput.addEventListener('input', debounceFetch);
        skemaSelect.addEventListener('change', function () { fetchAndReplaceCard(); });
        statusSelect.addEventListener('change', function () { fetchAndReplaceCard(); });

        document.addEventListener('click', function (event) {
            const pageLink = event.target.closest('.card a[href*="page="]');
            if (!pageLink) return;

            event.preventDefault();
            fetchAndReplaceCard(pageLink.href);
        });
    })();

    function toggleMenu(event, button) {
        event.stopPropagation();
        const dropdown = button.nextElementSibling;
        const isOpen = dropdown.classList.contains('show');

        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.style.top = '';
            d.style.left = '';
        });

        if (!isOpen) {
            const rect = button.getBoundingClientRect();
            dropdown.classList.add('show');
            const dropW = 160;
            let left = rect.right - dropW;
            if (left < 8) left = 8;
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.left = left + 'px';
        }
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(d => {
                d.classList.remove('show');
                d.style.top = '';
                d.style.left = '';
            });
        }
    });
</script>
@endsection
