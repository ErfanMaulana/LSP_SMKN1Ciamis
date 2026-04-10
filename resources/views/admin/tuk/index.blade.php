@extends('admin.layout')

@section('title', 'Manajemen TUK')
@section('page-title', 'TUK (Tempat Uji Kompetensi)')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .stat-card { background: white; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.2s; }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .stat-icon {
        width: 56px; height: 56px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0073bd); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.red    { background: linear-gradient(135deg,#ef4444,#dc2626); }
    .stat-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.5px; }
    .stat-value { font-size: 28px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

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
        padding: 9px 18px; background: #0073bd; color: #fff;
        border: none; border-radius: 8px; font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .btn-add:hover { background: #005f99; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: visible; }
    .card > .admin-table-scroll {
        overflow: visible !important;
        max-height: none !important;
    }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b;
         text-transform: uppercase; letter-spacing: .5px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.aktif    { background: #d1fae5; color: #065f46; }
    .badge.nonaktif { background: #fee2e2; color: #991b1b; }
    .badge.sewaktu  { background: #dbeafe; color: #1e40af; }
    .badge.tempat_kerja { background: #fef3c7; color: #92400e; }
    .badge.mandiri  { background: #ede9fe; color: #5b21b6; }

    .action-menu { position: relative; }
    .action-btn { width: 32px; height: 32px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .action-btn:hover { background: #f1f5f9; }
    .action-dropdown { display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; background: white; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.15); min-width: 170px; z-index: 10; overflow: hidden; }
    .action-dropdown.show { display: block; }
    .action-dropdown a, .action-dropdown button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 14px; color: #475569; cursor: pointer; transition: all .2s; text-decoration: none; }
    .action-dropdown a:hover, .action-dropdown button:hover { background: #f8fafc; color: #0F172A; }
    .action-dropdown button[type="submit"]:last-child:hover { background: #fef2f2; color: #dc2626; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p  { font-size: 14px; }

    .pagination-wrap { padding: 16px; }

    .tuk-delete-confirm-overlay {
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

    .tuk-delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .tuk-delete-confirm-modal {
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

    .tuk-delete-confirm-overlay.show .tuk-delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .tuk-delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .tuk-delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .tuk-delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .tuk-delete-btn-cancel,
    .tuk-delete-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
    }

    .tuk-delete-btn-cancel {
        background: #0073bd;
    }
    .tuk-delete-btn-cancel:hover {
        background: #005f99;
    }

    .tuk-delete-btn-submit {
        background: #0073bd;
    }
    .tuk-delete-btn-submit:hover {
        background: #005f99;
    }

    @media (prefers-reduced-motion: reduce) {
        .tuk-delete-confirm-overlay,
        .tuk-delete-confirm-modal {
            transition: none;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            align-items: stretch;
        }

        .page-header .btn-add {
            width: 100%;
            justify-content: center;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            min-width: 0;
            width: 100%;
        }

        .filter-group {
            width: 100%;
        }

        .filter-select,
        .btn-filter-search,
        .btn-filter-reset {
            width: 100%;
            justify-content: center;
        }

        .card {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .card table {
            min-width: 820px;
        }

        .card th,
        .card td {
            white-space: nowrap;
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
</style>
@endsection

@section('content')

<!-- Header -->
<div class="page-header">
    <div>
        <h2>Tempat Uji Kompetensi (TUK)</h2>
        <p>Kelola data tempat uji kompetensi untuk pelaksanaan ujian sertifikasi</p>
    </div>
    <a href="{{ route('admin.tuk.create') }}" class="btn-add">
        <i class="bi bi-plus-circle"></i> Tambah TUK
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-building"></i></div>
        <div>
            <div class="stat-label">Total TUK</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-label">TUK Aktif</div>
            <div class="stat-value">{{ $stats['aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-label">Non-Aktif</div>
            <div class="stat-value">{{ $stats['nonaktif'] }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<form method="GET" action="{{ route('admin.tuk.index') }}" class="toolbar" id="filterForm">
    <div class="filter-section">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama TUK, kode, kota..." autocomplete="off">
        </div>
        <div class="filter-group">
            <select class="filter-select" name="status" id="statusFilter">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            
        </div>
    </div>
</form>

<!-- Table -->
<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama TUK</th>
                <th>Tipe</th>
                <th>Kota</th>
                <th>Kapasitas</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tukTableBody">
            @forelse($tuks as $i => $tuk)
            <tr>
                <td style="color:#94a3b8;font-weight:600;">{{ $tuks->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;color:#0F172A;">{{ $tuk->nama_tuk }}</div>                   
                </td>
                <td>
                    @php
                        $tipeMap = ['sewaktu'=>'TUK Sewaktu','tempat_kerja'=>'Tempat Kerja','mandiri'=>'TUK Mandiri'];
                    @endphp
                    <span class="badge {{ $tuk->tipe_tuk }}">{{ $tipeMap[$tuk->tipe_tuk] ?? $tuk->tipe_tuk }}</span>
                </td>
                <td>{{ $tuk->kota ?? '-' }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span>{{ number_format($tuk->kapasitas) }} peserta</span>
                    </div>
                </td>
                <td>
                    <span style="font-weight:600;color:#0061a5;">{{ $tuk->jadwal_ujikom_count }}</span>
                    <span style="font-size:11px;color:#94a3b8;"></span>
                </td>
                <td>
                    <span class="badge {{ $tuk->status }}">
                        <i class="bi bi-{{ $tuk->status === 'aktif' ? 'check-circle' : 'x-circle' }}"></i>
                        {{ $tuk->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </td>
                <td>
                    <div class="action-menu">
                        <button type="button" class="action-btn" onclick="toggleMenu(event, this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.tuk.edit', $tuk->id) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.tuk.toggle', $tuk->id) }}" style="margin:0;">
                                @csrf @method('PATCH')
                                <button type="submit">
                                    <i class="bi bi-{{ $tuk->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                                    {{ $tuk->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.tuk.destroy', $tuk->id) }}" style="margin:0;"
                                  onsubmit="return openTukDeleteModal(event, this, @js('Hapus TUK "' . $tuk->nama_tuk . '" ?'))">
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
                        <p style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data TUK ditemukan</p>
                        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>

                        @if(!$search && $status === 'all')
                            <a href="{{ route('admin.tuk.create') }}" class="btn-add" style="display:inline-flex;margin-top:12px;">
                                <i class="bi bi-plus-lg"></i> Tambah TUK Sekarang
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap" id="tukPaginationWrap">
        @if($tuks->hasPages())
            {{ $tuks->links() }}
        @endif
    </div>
</div>

<div id="tuk-delete-confirm-overlay" class="tuk-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="tukDeleteConfirmTitle" aria-hidden="true">
    <div class="tuk-delete-confirm-modal">
        <h3 id="tukDeleteConfirmTitle" class="tuk-delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="tukDeleteConfirmText" class="tuk-delete-confirm-text">Apakah Anda yakin?</p>
        <div class="tuk-delete-confirm-actions">
            <button type="button" id="tukDeleteConfirmCancel" class="tuk-delete-btn-cancel">Batal</button>
            <button type="button" id="tukDeleteConfirmSubmit" class="tuk-delete-btn-submit">Hapus</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let pendingTukDeleteForm = null;

function openTukDeleteModal(event, form, message) {
    if (event) {
        event.preventDefault();
    }

    pendingTukDeleteForm = form;

    const overlay = document.getElementById('tuk-delete-confirm-overlay');
    const text = document.getElementById('tukDeleteConfirmText');
    if (!overlay || !text) return false;

    text.textContent = message || 'Apakah Anda yakin?';
    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden', 'false');

    return false;
}

function closeTukDeleteModal() {
    const overlay = document.getElementById('tuk-delete-confirm-overlay');
    if (!overlay) return;

    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden', 'true');
    pendingTukDeleteForm = null;
}

const tukDeleteOverlay = document.getElementById('tuk-delete-confirm-overlay');
const tukDeleteCancelBtn = document.getElementById('tukDeleteConfirmCancel');
const tukDeleteSubmitBtn = document.getElementById('tukDeleteConfirmSubmit');

tukDeleteCancelBtn?.addEventListener('click', closeTukDeleteModal);

tukDeleteOverlay?.addEventListener('click', function(event) {
    if (event.target === tukDeleteOverlay) {
        closeTukDeleteModal();
    }
});

tukDeleteSubmitBtn?.addEventListener('click', function() {
    if (!pendingTukDeleteForm) return;
    const formToSubmit = pendingTukDeleteForm;
    closeTukDeleteModal();
    formToSubmit.submit();
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeTukDeleteModal();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    if (window.__tukAjaxInitialized) return;
    window.__tukAjaxInitialized = true;

    const table = document.querySelector('.card table');
    const wrapper = table ? table.closest('.admin-table-scroll') : null;

    if (table && wrapper && wrapper.parentNode) {
        wrapper.parentNode.insertBefore(table, wrapper);
        wrapper.remove();
    }

    const filterForm = document.getElementById('filterForm');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('tukTableBody');
    const paginationWrap = document.getElementById('tukPaginationWrap');

    async function fetchTukData(page = null) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        if (page) {
            params.set('page', page);
        }

        const requestUrl = `${filterForm.action}?${params.toString()}`;

        try {
            const response = await fetch(requestUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data TUK.');
            }

            const data = await response.json();
            tableBody.innerHTML = data.rows || '';
            paginationWrap.innerHTML = data.pagination || '';

            const url = new URL(window.location.href);
            url.search = params.toString();
            window.history.replaceState({}, '', url);
        } catch (error) {
            console.error(error);
        }
    }

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        fetchTukData();
    });

    statusFilter.addEventListener('change', function () {
        fetchTukData();
    });

    paginationWrap.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link) return;

        event.preventDefault();
        const linkUrl = new URL(link.href);
        const page = linkUrl.searchParams.get('page');
        fetchTukData(page);
    });
});

function toggleMenu(event, button) {
    if (event) event.stopPropagation();
    document.querySelectorAll('.action-dropdown.show').forEach(d => {
        if (d !== button.nextElementSibling) d.classList.remove('show');
    });
    button.nextElementSibling.classList.toggle('show');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));
    }
});
</script>
@endsection
