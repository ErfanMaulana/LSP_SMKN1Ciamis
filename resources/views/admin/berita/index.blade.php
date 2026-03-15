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
        box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; overflow: show;
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
    .dropdown-action {
        position: relative;
        display: inline-block;
        text-align: center;
    }

    .btn-dropdown {
        background: none;
        border: none;
        padding: 8px;
        color: #64748b;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-dropdown:hover {
        background: #f1f5f9;
        color: #0F172A;
    }

    .btn-dropdown i {
        font-size: 18px;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 4px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        min-width: 160px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        font-size: 14px;
        color: #475569;
        text-decoration: none;
        border: none;
        background: none;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        text-align: left;
    }

    .dropdown-item:first-child {
        border-radius: 8px 8px 0 0;
    }

    .dropdown-item:last-child {
        border-radius: 0 0 8px 8px;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .dropdown-item i {
        font-size: 15px;
    }

    .dropdown-item.danger {
        color: #dc2626;
    }

    .dropdown-item.danger:hover {
        background: #fef2f2;
        color: #dc2626;
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
                    <div class="dropdown-action">
                        <button class="btn-dropdown" onclick="toggleDropdown(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="dropdown-item">
                                <i class="bi bi-pencil"></i> Ubah
                            </a>
                            <a href="{{ route('admin.berita.show', $item->id) }}" class="dropdown-item">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">
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
@endsection

@section('scripts')
<script>
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
    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        
        // Close all other dropdowns
        allDropdowns.forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.remove('show');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-action')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endsection
