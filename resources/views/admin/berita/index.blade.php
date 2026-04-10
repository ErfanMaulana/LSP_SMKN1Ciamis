@extends('admin.layout')

@section('title', 'Manajemen Berita')
@section('page-title', 'Manajemen Berita')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 56px; height: 56px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0073bd); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-icon.orange { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .search-box {
        flex: 1; min-width: 300px; position: relative;
    }
    .search-box i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 16px; z-index: 1;
    }
    .search-box input {
        width: 100%; padding: 10px 14px 10px 42px; border: 1px solid #e2e8f0;
        border-radius: 8px; font-size: 14px; outline: none; transition: all 0.2s;
    }
    .search-box input:focus {
        border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,.1);
    }

    .filter-controls {
        display: flex; gap: 12px; flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; background: white; color: #475569; cursor: pointer;
        transition: all 0.2s; min-width: 160px;
    }
    .filter-select:hover { border-color: #cbd5e1; }
    .filter-select:focus {
        outline: none; border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; border-radius: 8px; font-size: 14px;
        font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all .2s;
    }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-primary:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    .card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; overflow: visible;
    }

    /* Disable global table scroll wrapper for this page */
    .card > .admin-table-scroll {
        overflow: visible !important;
    }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead th {
        background: #f8fafc; padding: 11px 16px; text-align: left;
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .data-table tbody td {
        padding: 13px 16px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }

    .berita-title { font-weight: 600; color: #0F172A; max-width: 300px; }
    .berita-image {
        width: 60px; height: 60px; object-fit: cover; border-radius: 8px;
    }
    .status-badge {
        display: inline-block; padding: 4px 10px; border-radius: 20px;
        font-size: 12px; font-weight: 600;
    }
    .status-badge.published { background: #d1fae5; color: #065f46; }
    .status-badge.draft { background: #fef3c7; color: #92400e; }

    /* Dropdown Action */
    .action-menu { position: relative; display: inline-block; }
    .action-btn { width: 32px; height: 32px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .action-btn:hover { background: #f1f5f9; }
    .action-dropdown { display: none; position: fixed; background: white; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.15); min-width: 160px; z-index: 9990; overflow: hidden; }
    .action-dropdown.show { display: block; }
    .dropdown-item, .action-dropdown a, .action-dropdown button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 14px; color: #475569; cursor: pointer; transition: all .2s; text-decoration: none; }
    .dropdown-item:hover, .action-dropdown a:hover, .action-dropdown button:hover { background: #f8fafc; color: #0F172A; }
    .dropdown-item.danger { color: #475569; }
    .action-dropdown button[type="submit"]:hover { background: #fef2f2; color: #dc2626; }

    .berita-delete-confirm-overlay {
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

    .berita-delete-confirm-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .berita-delete-confirm-modal {
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

    .berita-delete-confirm-overlay.show .berita-delete-confirm-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .berita-delete-confirm-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .berita-delete-confirm-text {
        margin: 8px 0 0;
        font-size: 14px;
        color: #0f172a;
    }

    .berita-delete-confirm-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .berita-delete-btn-cancel,
    .berita-delete-btn-submit {
        border: 1px solid #0073bd;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
    }

    .berita-delete-btn-cancel {
        background: #0073bd;
    }
    .berita-delete-btn-cancel:hover {
        background: #005fa3;
    }

    .berita-delete-btn-submit {
        background: #0073bd;
    }
    .berita-delete-btn-submit:hover {
        background: #005fa3;
    }


    @media (prefers-reduced-motion: reduce) {
        .berita-delete-confirm-overlay,
        .berita-delete-confirm-modal {
            transition: none;
        }
    }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state h4 { font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px; }
    .empty-state p { font-size: 13px; color: #9ca3af; margin: 0; }

    .pagination-row {
        padding: 14px 20px; border-top: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }

    .alert {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
        font-size: 14px; display: flex; align-items: center; gap: 10px;
    }
    .alert-success {
        background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;
    }
    .alert-error {
        background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
    }

    @media (max-width: 768px) {
        .page-header {
            align-items: stretch;
        }

        .page-header .btn {
            width: 100%;
            justify-content: center;
        }

        .toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            min-width: 0;
            width: 100%;
        }

        .filter-controls {
            width: 100%;
        }

        .filter-select {
            width: 100%;
            min-width: 0;
        }

        .card {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            min-width: 760px;
        }

        .data-table tbody td {
            white-space: nowrap;
        }

        .pagination-row {
            flex-direction: column;
            align-items: flex-start;
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
<div class="page-header">
    <div>
        <h2>Manajemen Berita</h2>
        <p>Kelola berita dan artikel LSP</p>
    </div>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Berita
    </a>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-newspaper"></i>
        </div>
        <div>
            <div class="stat-label">Total Berita</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="stat-label">Published</div>
            <div class="stat-value">{{ $stats['published'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <div class="stat-label">Draft</div>
            <div class="stat-value">{{ $stats['draft'] }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari berita..." value="{{ $search ?? '' }}">
    </div>
    <div class="filter-controls">
        <select class="filter-select" id="statusFilter">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
    </div>
</div>

<!-- Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 80px;">Gambar</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tanggal Publikasi</th>
                <th>Status</th>
                <th style="width: 120px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($berita as $item)
            <tr>
                <td>
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                             alt="{{ $item->judul }}" 
                             class="berita-image"
                             onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}';">
                    @else
                        <div style="width: 60px; height: 60px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image" style="color: #9ca3af;"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div class="berita-title">{{ $item->judul }}</div>
                </td>
                <td>{{ $item->penulis }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_publikasi)->locale('id')->translatedFormat('d M Y') }}</td>
                <td>
                    <span class="status-badge {{ $item->status }}">
                        {{ $item->status == 'published' ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div class="action-menu">
                        <button type="button" class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            
                            
                            <a href="{{ route('admin.berita.show', $item->id) }}" class="dropdown-item">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="dropdown-item">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="bi bi-newspaper"></i>
                        <h4>Belum ada berita</h4>
                        <p>Mulai tambahkan berita baru</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($berita->hasPages())
    <div class="pagination-row">
        <div class="pagination-info">
            Menampilkan {{ $berita->firstItem() }} - {{ $berita->lastItem() }} dari {{ $berita->total() }} berita
        </div>
        {{ $berita->links() }}
    </div>
    @endif
</div>

<div id="berita-delete-confirm-overlay" class="berita-delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="beritaDeleteConfirmTitle" aria-hidden="true">
    <div class="berita-delete-confirm-modal">
        <h3 id="beritaDeleteConfirmTitle" class="berita-delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="beritaDeleteConfirmText" class="berita-delete-confirm-text">Apakah Anda yakin?</p>
        <div class="berita-delete-confirm-actions">
            <button type="button" id="beritaDeleteConfirmCancel" class="berita-delete-btn-cancel">Batal</button>
            <button type="button" id="beritaDeleteConfirmSubmit" class="berita-delete-btn-submit">Hapus</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let pendingBeritaDeleteForm = null;

    function openBeritaDeleteModal(event, form, message) {
        if (event) {
            event.preventDefault();
        }

        pendingBeritaDeleteForm = form;

        const overlay = document.getElementById('berita-delete-confirm-overlay');
        const text = document.getElementById('beritaDeleteConfirmText');
        if (!overlay || !text) return false;

        text.textContent = message || 'Apakah Anda yakin?';
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
        return false;
    }

    function closeBeritaDeleteModal() {
        const overlay = document.getElementById('berita-delete-confirm-overlay');
        if (!overlay) return;

        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        pendingBeritaDeleteForm = null;
    }

    const beritaDeleteOverlay = document.getElementById('berita-delete-confirm-overlay');
    const beritaDeleteCancelBtn = document.getElementById('beritaDeleteConfirmCancel');
    const beritaDeleteSubmitBtn = document.getElementById('beritaDeleteConfirmSubmit');

    beritaDeleteCancelBtn?.addEventListener('click', closeBeritaDeleteModal);

    beritaDeleteOverlay?.addEventListener('click', function(event) {
        if (event.target === beritaDeleteOverlay) {
            closeBeritaDeleteModal();
        }
    });

    beritaDeleteSubmitBtn?.addEventListener('click', function() {
        if (!pendingBeritaDeleteForm) return;
        const formToSubmit = pendingBeritaDeleteForm;
        closeBeritaDeleteModal();
        formToSubmit.submit();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBeritaDeleteModal();
        }
    });

    // Remove global table scroll wrapper on this page
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('.card .data-table');
        const wrapper = table ? table.closest('.admin-table-scroll') : null;

        if (table && wrapper && wrapper.parentNode) {
            wrapper.parentNode.insertBefore(table, wrapper);
            wrapper.remove();
        }
    });

    // Search functionality
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (e.target.value) {
                url.searchParams.set('search', e.target.value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        const url = new URL(window.location.href);
        if (e.target.value) {
            url.searchParams.set('status', e.target.value);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    });

    // Dropdown functionality
    function toggleMenu(button) {
        const dropdown = button.nextElementSibling;
        const isVisible = dropdown.classList.contains('show');

        // Close all other dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            if (d !== dropdown) d.classList.remove('show');
        });

        if (!isVisible) {
            // Match carousel behavior: fixed dropdown that escapes table clipping
            const rect = button.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.left = (rect.right - 160) + 'px';
        }

        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));
        }
    });

    document.addEventListener('submit', function(event) {
        const form = event.target.closest('form[action*="/admin/berita/"]');
        if (!form) return;

        const methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput || methodInput.value !== 'DELETE') return;

        const row = form.closest('tr');
        const titleEl = row ? row.querySelector('.berita-title') : null;
        const title = titleEl ? titleEl.textContent.trim() : 'berita ini';
        const message = 'Apakah Anda yakin menghapus berita "' + title + '" ini?';
        openBeritaDeleteModal(event, form, message);
    });
</script>
@endsection
