@extends('admin.layout')

@section('title', 'Manajemen Admin')
@section('page-title', 'Manajemen Admin')

@section('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px; margin-bottom: 24px;
    }
    .stat-card {
        background: white; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1); transition: all .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); transform: translateY(-2px); }
    .stat-icon {
        width: 56px; height: 56px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: #fff; flex-shrink: 0;
    }
    .stat-icon.blue   { background: linear-gradient(135deg,#0073bd,#0073bd); }
    .stat-icon.green  { background: linear-gradient(135deg,#10b981,#059669); }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2; margin-top: 2px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .search-box { flex: 1; min-width: 300px; position: relative; }
    .search-box i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 16px;
    }
    .search-box input {
        width: 100%; padding: 10px 14px 10px 42px;
        border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;
        background: white; transition: all .2s;
    }
    .search-box input:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0,115,189,.1); }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); overflow: hidden; }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        background: #f8fafc; padding: 14px 16px; text-align: left;
        font-size: 11px; font-weight: 600; color: #64748b;
        text-transform: uppercase; letter-spacing: .5px; border-bottom: 2px solid #e2e8f0;
    }
    .data-table td {
        padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
        font-size: 14px; color: #334155; vertical-align: middle;
    }
    .data-table tr:hover td { background: #f8fafc; }

    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: #0073bd;
        color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .user-info .user-name { font-weight: 600; color: #0F172A; }
    .user-info .user-email { font-size: 12px; color: #94a3b8; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 600; padding: 3px 10px;
        border-radius: 20px; margin: 2px;
    }
    .badge-primary { background: #dbeafe; color: #1d4ed8; }
    .badge-warning { background: #fef3c7; color: #b45309; }
    .badge-self { background: #dcfce7; color: #15803d; }

    .btn {
        padding: 8px 16px; border: none; border-radius: 6px;
        font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; transition: all .2s;
    }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #005a94; transform: translateY(-1px); }
    .btn-sm { padding: 6px 12px; font-size: 12px; }
    .btn-outline-primary { background: transparent; border: 1px solid #0073bd; color: #0073bd; }
    .btn-outline-primary:hover { background: #0073bd; color: white; }
    .btn-outline-danger { background: transparent; border: 1px solid #ef4444; color: #ef4444; }
    .btn-outline-danger:hover { background: #ef4444; color: white; }

    /* Action Menu */
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

    .action-dropdown button:last-child:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    .alert {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;
        font-size: 14px; display: flex; align-items: center; gap: 8px;
    }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

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

    @media (max-width: 768px) {
        .search-box { min-width: 100%; }
        .data-table th:nth-child(3), .data-table td:nth-child(3) { display: none; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Manajemen Admin</h2>
        <p>Kelola akun administrator sistem</p>
    </div>
    <a href="{{ route('admin.admin-management.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Admin
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-label">Total Admin</div>
            <div class="stat-value">{{ \App\Models\Admin::count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-shield-check"></i></div>
        <div>
            <div class="stat-label">Total Role</div>
            <div class="stat-value">{{ \App\Models\Role::count() }}</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari admin..." value="{{ request('search') }}">
    </div>
</div>

<div class="card">
    @if($admins->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Admin</th>
                <th>Username</th>
                <th>Role</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $adminItem)
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar">{{ strtoupper(substr($adminItem->name, 0, 1)) }}</div>
                        <div class="user-info">
                            <div class="user-name">
                                {{ $adminItem->name }}
                                @if($adminItem->id === Auth::guard('admin')->id())
                                    <span class="badge badge-self">Anda</span>
                                @endif
                            </div>
                            <div class="user-email">{{ $adminItem->email }}</div>
                        </div>
                    </div>
                </td>
                <td><code style="font-size:12px;background:#f1f5f9;padding:2px 8px;border-radius:4px;">{{ $adminItem->username }}</code></td>
                <td>
                    @forelse($adminItem->roles as $role)
                        <span class="badge {{ $role->is_super_admin ? 'badge-warning' : 'badge-primary' }}">
                            {{ $role->display_name }}
                        </span>
                    @empty
                        <span style="color:#94a3b8;font-size:12px;">Belum ada role</span>
                    @endforelse
                </td>
                <td>
                    <div class="action-menu">
                        <button class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.admin-management.edit', $adminItem) }}" title="Edit">
                                <i class="bi bi-pencil" style="font-size: 16px;"></i> Edit
                            </a>
                            @if($adminItem->id !== Auth::guard('admin')->id())
                            <button type="button" onclick="confirmDelete({{ $adminItem->id }}, '{{ $adminItem->name }}')" title="Hapus">
                                <i class="bi bi-trash" style="font-size: 16px;"></i> Hapus
                            </button>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($admins->hasPages())
    <div style="padding: 16px; display: flex; justify-content: center;">
        {{ $admins->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <i class="bi bi-person-x"></i>
        <p>Belum ada admin.</p>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteConfirmOverlay" class="delete-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
    <div class="delete-confirm-modal">
        <h3 id="deleteConfirmTitle" class="delete-confirm-title">Konfirmasi Hapus</h3>
        <p id="deleteConfirmText" class="delete-confirm-text">Hapus admin ini?</p>
        <div class="delete-confirm-actions">
            <button type="button" id="deleteConfirmCancel" class="btn-confirm-cancel">Batal</button>
            <form id="deleteForm" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-confirm-submit">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);
            if (this.value) params.set('search', this.value); else params.delete('search');
            window.location.search = params.toString();
        }, 500);
    });

    // Action Menu Toggle with Fixed Positioning
    function toggleMenu(button) {
        const dropdown = button.nextElementSibling;
        const isOpen = dropdown.classList.contains('show');

        // Close all open dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.style.top = '';
            d.style.left = '';
        });

        if (!isOpen) {
            const rect = button.getBoundingClientRect();
            dropdown.classList.add('show');
            // Position below the button, aligned to its right edge
            const dropW = 160;
            let left = rect.right - dropW;
            if (left < 8) left = 8;
            dropdown.style.top  = (rect.bottom + 4) + 'px';
            dropdown.style.left = left + 'px';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(d => {
                d.classList.remove('show');
                d.style.top = '';
                d.style.left = '';
            });
        }
    });

    function confirmDelete(id, name) {
        closeMenus();
        const text = document.getElementById('deleteConfirmText');
        if (text) {
            text.textContent = 'Hapus admin ' + name + '?';
        }
        document.getElementById('deleteForm').action = '/admin/admin-management/' + id;
        const modal = document.getElementById('deleteConfirmOverlay');
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeMenus() {
        document.querySelectorAll('.action-dropdown').forEach(m => m.classList.remove('show'));
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmOverlay');
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    const deleteOverlay = document.getElementById('deleteConfirmOverlay');
    const deleteCancelBtn = document.getElementById('deleteConfirmCancel');

    deleteCancelBtn?.addEventListener('click', closeDeleteModal);

    deleteOverlay?.addEventListener('click', function(e) {
        if (e.target === deleteOverlay) closeDeleteModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeMenus();
        }
    });
</script>
@endsection
