@extends('admin.layout')

@section('title', 'Jadwal Ujikom')
@section('page-title', 'Jadwal Uji Kompetensi')

@section('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
        gap: 16px; margin-bottom: 24px;
    }
    .stat-card {
        background: white; border-radius: 12px; padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        transition: all 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0061a5); }
    .stat-icon.yellow { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.orange { background: linear-gradient(135deg,#f97316,#ea580c); }
    .stat-icon.purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.5px; }
    .stat-value { font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 2px; }

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
    .btn-add {
        padding: 9px 18px; background: #0061a5; color: #fff; border: none;
        border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
    }
    .btn-add:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    table { width: 100%; border-collapse: collapse; overflow: visible; }
    .card > table { overflow: visible; }
    .card > .admin-table-scroll { overflow: visible; }
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
    .badge.dijadwalkan { background: #dbeafe; color: #1e40af; }
    .badge.berlangsung { background: #fef3c7; color: #92400e; }
    .badge.selesai     { background: #d1fae5; color: #065f46; }
    .badge.dibatalkan  { background: #fee2e2; color: #991b1b; }

    .kuota-bar {
        width: 100%; background: #f1f5f9; border-radius: 20px; height: 6px; margin-top: 4px; overflow: hidden;
    }
    .kuota-fill { height: 100%; border-radius: 20px; background: #0061a5; transition: width .3s; }

    .action-menu { position: relative; }
    .action-btn { width: 32px; height: 32px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .action-btn:hover { background: #f1f5f9; }
    .action-dropdown { display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; background: white; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.15); min-width: 160px; z-index: 1200; overflow: hidden; }
    .action-dropdown.open-up { top: auto; bottom: 100%; margin-top: 0; margin-bottom: 4px; }
    .action-dropdown.show { display: block; }
    .action-dropdown a, .action-dropdown button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 14px; color: #475569; cursor: pointer; transition: all .2s; text-decoration: none; }
    .action-dropdown a:hover, .action-dropdown button:hover { background: #f8fafc; color: #0F172A; }
    .action-dropdown button[type="submit"]:hover { background: #fef2f2; color: #dc2626; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p  { font-size: 14px; }

    .pagination-wrap { padding: 16px; }

    .delete-confirm-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .delete-confirm-modal {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.3);
        border: 1px solid #e2e8f0;
        padding: 20px;
        transform: translateY(10px) scale(0.96);
        opacity: 0.92;
        transition: transform 0.22s ease, opacity 0.22s ease;
    }

    .delete-confirm-overlay.show .delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-confirm-cancel,
    .btn-confirm-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
    }

    .btn-confirm-cancel {
        background: #0073bd;
    }
    .btn-confirm-cancel:hover {
        background: #005f99;
    }

    .btn-confirm-submit {
        background: #0073bd;
    }
    .btn-confirm-submit:hover {
        background: #005f99;
    }

    @media (prefers-reduced-motion: reduce) {
        .delete-confirm-overlay,
        .delete-confirm-modal {
            transition: none;
        }
    }

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

    @media (max-width: 768px) {
        .card > .admin-table-scroll {
            overflow-x: auto;
            overflow-y: visible;
        }
    }
</style>
@endsection

@section('content')

<!-- Header -->
<div class="page-header">
    <div>
        <h2>Jadwal Uji Kompetensi</h2>
        <p>Kelola jadwal pelaksanaan uji kompetensi dan penempatan TUK</p>
    </div>
    <a href="{{ route('admin.jadwal-ujikom.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar3"></i></div>
        <div>
            <div class="stat-label">Total Jadwal</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-label">Dijadwalkan</div>
            <div class="stat-value">{{ $stats['dijadwalkan'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-play-circle"></i></div>
        <div>
            <div class="stat-label">Berlangsung</div>
            <div class="stat-value">{{ $stats['berlangsung'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats['selesai'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
        <div>
            <div class="stat-label">Bulan Ini</div>
            <div class="stat-value">{{ $stats['bulan_ini'] }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" class="toolbar" id="filterForm" action="{{ route('admin.jadwal-ujikom.index') }}">
    <div class="filter-section">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul, TUK, skema..." autocomplete="off">
        </div>
        <div class="filter-group">
            <input type="month" name="bulan" value="{{ $bulan }}" title="Filter bulan" class="filter-select" style="padding-right:14px;background-image:none;">
            <select name="status" class="filter-select">
                <option value="all"         {{ $status === 'all'         ? 'selected' : '' }}>Semua Status</option>
                <option value="dijadwalkan" {{ $status === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                <option value="berlangsung" {{ $status === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                <option value="selesai"     {{ $status === 'selesai'     ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan"  {{ $status === 'dibatalkan'  ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            
        </div>
    </div>
</form>

<!-- Table -->
<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jadwal</th>
                <th>Skema</th>
                <th>TUK</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="jadwalTableBody">
            @forelse($jadwals as $i => $jadwal)
            <tr>
                <td style="color:#94a3b8;font-weight:600;">{{ $jadwals->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;color:#0F172A;">{{ $jadwal->judul_jadwal }}</div>
                    <div style="font-size:12px;color:#0061a5;margin-top:2px;font-weight:600;">
                        <i class="bi bi-calendar3"></i>
                        @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                            @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                                {{ $jadwal->tanggal_mulai->translatedFormat('d F Y') }}
                            @else
                                {{ $jadwal->tanggal_mulai->translatedFormat('d M') }} - {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                        @else
                            -
                        @endif
                    </div>
                </td>
                <td>
                    @if($jadwal->skema)
                    <div style="font-size:13px;font-weight:500;">{{ Str::limit($jadwal->skema->nama_skema, 40) }}</div>
                    <div style="font-size:11px;color:#94a3b8;font-family:monospace;">{{ $jadwal->skema->nomor_skema }}</div>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    @if($jadwal->tuk)
                    <div style="font-size:13px;font-weight:500;">{{ $jadwal->tuk->nama_tuk }}</div>
                    <div style="font-size:11px;color:#64748b;">{{ $jadwal->tuk->kota ?? '' }}</div>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                        @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                            <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
                        @else
                            <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
                            <div style="font-size:12px;color:#64748b;">s/d {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}</div>
                        @endif
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $jadwal->status }}">
                        {{ $jadwal->status_label }}
                    </span>
                </td>
                <td>
                    <div class="action-menu">
                        <button class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.jadwal-ujikom.edit', $jadwal->id) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal-ujikom.destroy', $jadwal->id) }}" style="margin:0;"
                                  data-confirm-message="Hapus jadwal &quot;{{ $jadwal->judul_jadwal }}&quot; ?"
                                  onsubmit="return openDeleteJadwalModal(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data jadwal ujikom ditemukan</h4>
                        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap" id="jadwalPaginationWrap">
        @if($jadwals->hasPages())
            {{ $jadwals->links() }}
        @endif
    </div>
</div>

<div id="deleteConfirmOverlay" class="delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
    <div class="delete-confirm-modal">
        <h3 id="deleteConfirmTitle" class="delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="deleteConfirmText" class="delete-confirm-text">Hapus jadwal ini?</p>
        <div class="delete-confirm-actions">
            <button type="button" id="deleteConfirmCancel" class="btn-confirm-cancel">Batal</button>
            <button type="button" id="deleteConfirmSubmit" class="btn-confirm-submit">Hapus</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let pendingDeleteForm = null;

function openDeleteJadwalModal(event, form, message) {
    if (event) {
        event.preventDefault();
    }

    pendingDeleteForm = form;

    const overlay = document.getElementById('deleteConfirmOverlay');
    const text = document.getElementById('deleteConfirmText');
    if (!overlay || !text) return false;

    text.textContent = message || form?.dataset?.confirmMessage || 'Hapus jadwal ini?';
    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden', 'false');

    return false;
}

function closeDeleteJadwalModal() {
    const overlay = document.getElementById('deleteConfirmOverlay');
    if (!overlay) return;

    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden', 'true');
    pendingDeleteForm = null;
}

const deleteOverlay = document.getElementById('deleteConfirmOverlay');
const deleteCancelBtn = document.getElementById('deleteConfirmCancel');
const deleteSubmitBtn = document.getElementById('deleteConfirmSubmit');

deleteCancelBtn?.addEventListener('click', closeDeleteJadwalModal);

deleteOverlay?.addEventListener('click', function(event) {
    if (event.target === deleteOverlay) {
        closeDeleteJadwalModal();
    }
});

deleteSubmitBtn?.addEventListener('click', function() {
    if (!pendingDeleteForm) return;
    const formToSubmit = pendingDeleteForm;
    closeDeleteJadwalModal();
    formToSubmit.submit();
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteJadwalModal();
    }
});

function toggleMenu(button) {
    const dropdown = button.nextElementSibling;
    if (!dropdown) return;

    document.querySelectorAll('.action-dropdown.show').forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('show', 'open-up');
        }
    });

    const willOpen = !dropdown.classList.contains('show');
    dropdown.classList.toggle('show');
    dropdown.classList.remove('open-up');

    if (willOpen) {
        const rect = dropdown.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.top;
        if (spaceBelow < rect.height + 12) {
            dropdown.classList.add('open-up');
        }
    }
}
document.addEventListener('click', e => {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show', 'open-up'));
    }
});

const filterForm = document.getElementById('filterForm');
const jadwalTableBody = document.getElementById('jadwalTableBody');
const jadwalPaginationWrap = document.getElementById('jadwalPaginationWrap');

function performAjaxSearch(page = null) {
    if (!filterForm || !jadwalTableBody || !jadwalPaginationWrap) return;

    const formData = new FormData(filterForm);
    const params = new URLSearchParams(formData);
    if (page) {
        params.set('page', page);
    }

    fetch(filterForm.action + '?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal memuat data jadwal.');
        return response.json();
    })
    .then(data => {
        jadwalTableBody.innerHTML = data.rows || '';
        jadwalPaginationWrap.innerHTML = data.pagination || '';
        window.history.replaceState({}, '', filterForm.action + '?' + params.toString());
    })
    .catch(error => console.error('Search error:', error));
}

if (filterForm) {
    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        performAjaxSearch();
    });
}

const searchInput = filterForm ? filterForm.querySelector('input[name="search"]') : null;
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

document.querySelectorAll('#filterForm select, #filterForm input[name="bulan"]').forEach(el => {
    el.addEventListener('change', function() {
        performAjaxSearch();
    });
});

jadwalPaginationWrap?.addEventListener('click', function(event) {
    const link = event.target.closest('a');
    if (!link) return;

    event.preventDefault();
    const linkUrl = new URL(link.href);
    const page = linkUrl.searchParams.get('page');
    performAjaxSearch(page);
});

function resetFilters() {
    if (!filterForm) return;

    const searchField = filterForm.querySelector('input[name="search"]');
    const monthField = filterForm.querySelector('input[name="bulan"]');
    const statusField = filterForm.querySelector('select[name="status"]');

    if (searchField) searchField.value = '';
    if (monthField) monthField.value = '{{ now()->format('Y-m') }}';
    if (statusField) statusField.value = 'all';

    performAjaxSearch();
}
</script>
@endsection
