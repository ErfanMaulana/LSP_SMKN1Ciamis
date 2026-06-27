@extends('admin.layout')

@section('title', 'Ceklis Banding Asesmen')
@section('page-title', 'Ceklis Banding Asesmen')

@section('styles')
<style>
    .head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
    .head h2 { margin:0; color:#0f172a; font-size:22px; }
    .head p { margin:4px 0 0; color:#64748b; font-size:13px; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-bottom:20px; }
    .stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; display:flex; align-items:center; gap:12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;}
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .stat-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; flex-shrink:0; }
    .stat-icon.blue { background:#0073bd; }
    .stat-icon.green { background:#10b981; }
    .stat-icon.gray { background:#64748b; }
    .stat-label { font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
    .stat-value { font-size:22px; color:#0f172a; font-weight:700; margin-top:2px; }

    .toolbar { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px; margin-bottom:12px; }
    .toolbar form { display:flex; gap:10px; flex-wrap:wrap; }
    .input { flex:1; min-width:260px; border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; font-size:14px; }
    .filter-select { min-width:170px; padding:9px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; background:#fff; }
    .input:focus, .filter-select:focus { outline:none; border-color:#0073bd; box-shadow:0 0 0 3px rgba(0, 115, 189, .1); }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:auto; }
    table { width:100%; border-collapse:collapse; min-width:760px; }
    th { background:#f8fafc; border-bottom:1px solid #e2e8f0; font-size:11px; text-transform:uppercase; color:#64748b; letter-spacing:.4px; padding:11px 14px; text-align:left; }
    td { border-bottom:1px solid #f1f5f9; padding:12px 14px; font-size:13px; color:#334155; }
    tr:last-child td { border-bottom:none; }

    .badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge.active { background:#dcfce7; color:#166534; }
    .badge.inactive { background:#fee2e2; color:#991b1b; }

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

    .empty { padding:52px 18px; text-align:center; color:#94a3b8; }
    .empty i { font-size:42px; display:block; margin-bottom:8px; }

    .paginate { padding:12px 14px; border-top:1px solid #e2e8f0; }

    @media (max-width: 768px) {
        .toolbar form { flex-direction: column; }
        .input, .filter-select, .btn-primary { width: 100%; }
        .card { overflow-x: auto; }
        table { min-width: 760px; }
    }
</style>
@endsection

@section('content')
<div class="head">
    <div>
        <h2>Ceklis Banding Asesmen</h2>
        <p>Kelola pernyataan ceklis FR.AK.04 yang akan diisi pada form banding asesor.</p>
    </div>
    <a href="{{ route('admin.banding-asesmen-komponen.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Ceklis</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-list-check"></i></div>
        <div>
            <div class="stat-label">Total Komponen</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">Aktif</div>
            <div class="stat-value">{{ $stats['active'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-slash-circle"></i></div>
        <div>
            <div class="stat-label">Nonaktif</div>
            <div class="stat-value">{{ $stats['inactive'] }}</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <form id="ajaxFilterForm" method="GET" action="{{ route('admin.banding-asesmen-komponen.index') }}">
        <input type="text" id="searchInput" class="input" name="search" value="{{ $search }}" placeholder="Cari pernyataan ceklis..." autocomplete="off">
        <select id="statusSelect" name="status" class="filter-select">
            <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <!-- <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button> -->
    </form>
</div>

<div class="card">
    @if($komponen->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Urutan</th>
                    <th>Pernyataan Ceklis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    <tr>
                        <td>{{ ($komponen->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $item->urutan }}</td>
                        <td>{{ $item->pernyataan }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge active">Aktif</span>
                            @else
                                <span class="badge inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-menu">
                                <button class="action-btn" onclick="toggleMenu(event, this)">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="action-dropdown">
                                    <a href="{{ route('admin.banding-asesmen-komponen.show', $item->id) }}">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                    @if(Auth::guard('admin')->user()->hasPermission('banding-asesmen-komponen.edit'))
                                        <a href="{{ route('admin.banding-asesmen-komponen.edit', $item->id) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @endif
                                    @if(Auth::guard('admin')->user()->hasPermission('banding-asesmen-komponen.delete'))
                                        <form action="{{ route('admin.banding-asesmen-komponen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus komponen ceklis ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="paginate">{{ $komponen->links() }}</div>
    @else
        <div class="empty">
            <i class="bi bi-list-check"></i>
            <div>Belum ada komponen ceklis banding.</div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
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

    (function () {
        const form = document.getElementById('ajaxFilterForm');
        if (!form) return;

        const searchInput = document.getElementById('searchInput');
        const statusSelect = document.getElementById('statusSelect');

        let debounceTimer = null;
        let activeController = null;

        const buildUrl = (pageUrl = null) => {
            const url = new URL(pageUrl || form.action, window.location.origin);
            const params = new URLSearchParams();

            params.set('search', (searchInput.value || '').trim());
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

                const newStats = doc.querySelector('.stats');
                const currentStats = document.querySelector('.stats');
                if (newStats && currentStats) {
                    currentStats.replaceWith(newStats);
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
        statusSelect.addEventListener('change', function () { fetchAndReplaceCard(); });

        document.addEventListener('click', function (event) {
            const pageLink = event.target.closest('.card a[href*="page="]');
            if (!pageLink) return;

            event.preventDefault();
            fetchAndReplaceCard(pageLink.href);
        });
    })();
</script>
@endsection
