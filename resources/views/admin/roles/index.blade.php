@extends('admin.layout')

@section('title', 'Manajemen Role')
@section('page-title', 'Manajemen Role')

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

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .search-box {
        flex: 1; min-width: 300px; position: relative;
    }
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

    .card {
        background: white; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1); overflow: hidden;
    }

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

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 600; padding: 3px 10px;
        border-radius: 20px;
    }
    .badge-primary { background: #dbeafe; color: #1d4ed8; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fef3c7; color: #b45309; }

    .btn {
        padding: 8px 16px; border: none; border-radius: 6px;
        font-size: 13px; font-weight: 500; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center;
        gap: 6px; transition: all .2s;
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
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 100%;
        margin-top: 4px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        width: fit-content;
        z-index: 1000;
        overflow: hidden;
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

    .empty-state {
        text-align: center; padding: 60px 20px; color: #94a3b8;
    }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    .alert {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;
        font-size: 14px; display: flex; align-items: center; gap: 8px;
    }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    .super-badge { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }

    /* Delete Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.45);
        z-index: 9999; align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: white; border-radius: 16px; padding: 32px;
        max-width: 420px; width: 90%; text-align: center;
        box-shadow: 0 25px 50px rgba(0,0,0,.25);
    }
    .modal-box h3 { font-size: 18px; color: #0F172A; margin: 16px 0 8px; }
    .modal-box p { font-size: 14px; color: #64748b; margin-bottom: 24px; }
    .modal-actions { display: flex; gap: 12px; justify-content: center; }
    .modal-actions .btn { min-width: 100px; justify-content: center; }

    @media (max-width: 768px) {
        .search-box { min-width: 100%; }
        .data-table th:nth-child(3), .data-table td:nth-child(3),
        .data-table th:nth-child(4), .data-table td:nth-child(4) { display: none; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Manajemen Role</h2>
        <p>Kelola role dan hak akses admin</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Role
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="toolbar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari role..." value="{{ request('search') }}">
    </div>
</div>

<div class="card">
    @if($roles->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Role</th>
                <th>Slug</th>
                <th>Permissions</th>
                <th>Admin</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>
                    <div style="font-weight:600;">{{ $role->display_name }}</div>
                    @if($role->description)
                        <div style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $role->description }}</div>
                    @endif
                    @if($role->is_super_admin)
                        <span class="badge super-badge" style="margin-top:4px;"><i class="bi bi-shield-check"></i> Super Admin</span>
                    @endif
                </td>
                <td><code style="font-size:12px;background:#f1f5f9;padding:2px 8px;border-radius:4px;">{{ $role->name }}</code></td>
                <td><span class="badge badge-primary">{{ $role->permissions_count }} permissions</span></td>
                <td><span class="badge badge-success">{{ $role->admins_count }} admin</span></td>
                <td>
                    <div class="action-menu">
                        <button class="action-btn" onclick="toggleMenu(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('admin.roles.edit', $role) }}" title="Edit">
                                <i class="bi bi-pencil" style="font-size: 16px;"></i> Edit
                            </a>
                            @if(!$role->is_super_admin)
                            <button type="button" onclick="confirmDelete({{ $role->id }}, '{{ $role->display_name }}')" title="Hapus">
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

    @if($roles->hasPages())
    <div style="padding: 16px; display: flex; justify-content: center;">
        {{ $roles->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <i class="bi bi-shield-lock"></i>
        <p>Belum ada role.</p>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
            <i class="bi bi-exclamation-triangle" style="font-size:24px;color:#ef4444;"></i>
        </div>
        <h3>Hapus Role?</h3>
        <p>Role <strong id="deleteRoleName"></strong> akan dihapus permanen.</p>
        <div class="modal-actions">
            <button class="btn btn-sm" style="background:#e2e8f0;color:#475569;" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:#ef4444;color:white;">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);
            if (this.value) params.set('search', this.value); else params.delete('search');
            window.location.search = params.toString();
        }, 500);
    });

    // Action Menu Toggle
    function toggleMenu(button) {
        document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
            if (dropdown !== button.nextElementSibling) {
                dropdown.classList.remove('show');
            }
        });
        button.nextElementSibling.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Delete modal
    function confirmDelete(id, name) {
        closeMenus();
        document.getElementById('deleteRoleName').textContent = name;
        document.getElementById('deleteForm').action = '/admin/roles/' + id;
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeMenus() {
        document.querySelectorAll('.action-dropdown').forEach(m => m.classList.remove('show'));
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeMenus();
        }
    });
</script>
@endsection
