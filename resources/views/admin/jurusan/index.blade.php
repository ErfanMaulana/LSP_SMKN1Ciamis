@extends('admin.layout')

@section('title', 'Manajemen Jurusan')
@section('page-title', 'Manajemen Jurusan')

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
    .stat-icon.purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
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

    .btn-filter-reset {
        padding: 9px 12px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-filter-reset:hover { background: #fecaca; }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; border-radius: 8px; font-size: 14px;
        font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all .2s;
    }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-primary:hover { background: #003961; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }
    .btn-xs { padding: 5px 12px; font-size: 12px; border-radius: 6px; }
    .btn-edit { background: #eff6ff; color: #0073bd; }
    .btn-edit:hover { background: #dbeafe; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #fee2e2; }

    .card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e5e7eb; overflow: hidden;
    }
    .card-body {
        padding: 20px;
    }
    .card-header {
        padding: 14px 20px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h3 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0; }

    .filter-section {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead th {
        background: #f8fafc; padding: 11px 16px; text-align: left;
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .data-table thead th a { color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .data-table thead th a:hover { color: #1e293b; }
    .data-table tbody td {
        padding: 13px 16px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }

    .jurusan-name { font-weight: 600; color: #0F172A; }
    .jurusan-code {
        display: inline-block; padding: 3px 10px; border-radius: 6px;
        font-size: 12px; font-weight: 600; background: #f1f5f9; color: #475569; font-family: monospace;
    }
    .visi-text { font-size: 12px; color: #6b7280; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .asesi-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        background: #dbeafe; color: #1d4ed8;
    }
    .asesi-badge.empty { background: #f1f5f9; color: #94a3b8; }
    
    /* Action Menu */
    .action-menu {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
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

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state h4 { font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px; }
    .empty-state p { font-size: 13px; color: #9ca3af; margin: 0; }

    .pagination-row {
        padding: 14px 20px; border-top: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }
    .pagination-info { font-size: 14px; color: #6b7280; }

    /* Delete Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.5); z-index: 9999; align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px; padding: 28px;
        width: 100%; max-width: 420px; margin: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-box h3 { font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 8px; }
    .modal-box p  { font-size: 13px; color: #6b7280; margin: 0 0 20px; line-height: 1.6; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .btn-cancel {
        padding: 9px 18px; background: #f3f4f6; color: #374151;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer;
    }
    .btn-cancel:hover { background: #e5e7eb; }
    .btn-confirm-delete {
        padding: 9px 18px; background: #ef4444; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-confirm-delete:hover { background: #dc2626; }

    /* Spinner/Loading */
    .spinner-border {
        display: inline-block;
        width: 3rem;
        height: 3rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    .spinner-border.text-primary {
        color: #0073bd;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    @media (max-width: 800px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
        .data-table { display: block; overflow-x: auto; }
        .toolbar { flex-direction: column; }
        .search-box { min-width: 100%; }
        .filter-controls { width: 100%; }
        .filter-select { flex: 1; min-width: 0; }
    }
    @media (max-width: 640px) {
        .page-header {
            align-items: stretch;
            margin-bottom: 18px;
            gap: 10px;
        }

        .page-header h2 {
            font-size: 20px;
            line-height: 1.2;
        }

        .page-header p {
            font-size: 12px;
        }

        .page-header .btn {
            width: 100%;
            justify-content: center;
        }

        .card-header,
        .card-body {
            padding: 12px;
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

        .data-table thead th,
        .data-table tbody td {
            padding: 10px 12px;
        }

        .pagination-row {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px;
            gap: 10px;
        }

        .pagination-info {
            font-size: 12px;
        }

        .modal-box {
            margin: 12px;
            padding: 18px;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-confirm-delete {
            width: 100%;
            justify-content: center;
        }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2></i>Kelola Jurusan</h2>
        <p>Kelola semua program keahlian yang tersedia di LSP.</p>
    </div>
    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Jurusan
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-mortarboard"></i></div>
        <div><div class="stat-label">Total Jurusan</div><div class="stat-value">{{ $stats['total'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people"></i></div>
        <div><div class="stat-label">Total Asesi</div><div class="stat-value">{{ $stats['total_asesi'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-person-check"></i></div>
        <div><div class="stat-label">Jurusan Aktif</div><div class="stat-value">{{ $stats['with_asesi'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-graph-up"></i></div>
        <div><div class="stat-label">Rata-rata Asesi</div><div class="stat-value">{{ $stats['avg_asesi'] }}</div></div>
    </div>
</div>

<!-- Toolbar -->


<!-- Table -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Jurusan</h3>
        <span style="font-size:12px;color:#6b7280;">{{ $jurusan->total() }} jurusan</span>
    </div>

    @if($jurusan->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'nama_jurusan','order'=>$sort==='nama_jurusan'&&$order==='asc'?'desc':'asc']) }}">
                        Nama Jurusan
                        @if($sort==='nama_jurusan') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'kode_jurusan','order'=>$sort==='kode_jurusan'&&$order==='asc'?'desc':'asc']) }}">
                        Kode
                        @if($sort==='kode_jurusan') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'asesi_count','order'=>$sort==='asesi_count'&&$order==='asc'?'desc':'asc']) }}">
                        Asesi
                        @if($sort==='asesi_count') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.jurusan.index', ['search'=>$search,'sort'=>'created_at','order'=>$sort==='created_at'&&$order==='asc'?'desc':'asc']) }}">
                        Dibuat
                        @if($sort==='created_at') <i class="bi bi-arrow-{{ $order==='asc'?'up':'down' }}-short"></i> @endif
                    </a>
                </th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="jurusanTableBody">
            @include('admin.jurusan.partials.table-rows')
        </tbody>
    </table>

    <div class="pagination-row">
        <div class="pagination-info">
            Menampilkan {{ $jurusan->firstItem() }} sampai {{ $jurusan->lastItem() }} dari {{ $jurusan->total() }} jurusan
        </div>
        {{ $jurusan->links() }}
    </div>

    @else
    <div class="empty-state">
        <i class="bi bi-mortarboard"></i>
        <h4>{{ $search ? 'Tidak ada hasil untuk "' . $search . '"' : 'Belum ada data jurusan' }}</h4>
        <p>{{ $search ? 'Coba kata kunci lain.' : 'Klik "Tambah Jurusan" untuk mulai menambahkan data.' }}</p>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3><i class="bi bi-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Hapus Jurusan</h3>
        <p id="deleteModalText"></p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-confirm-delete"><i class="bi bi-trash"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Perform AJAX search and filter
    function performAjaxSearch() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        
        fetch('{{ route("admin.jurusan.index") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Replace table body with new rows
            const tableBody = document.getElementById('jurusanTableBody');
            if (tableBody) {
                tableBody.innerHTML = html;
            }
            // Re-attach event listeners to action menus
            attachActionMenuListeners();
        })
        .catch(error => console.error('Search error:', error));
    }

    // Real-time search on input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performAjaxSearch();
            }
        });
        searchInput.addEventListener('input', function(e) {
            performAjaxSearch();
        });
    }

    // Attach click handlers to action menus
    function attachActionMenuListeners() {
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.removeEventListener('click', toggleMenu);
            btn.addEventListener('click', function() { toggleMenu(this); });
        });
    }

    // Initial attachment
    attachActionMenuListeners();

    // Action Menu Toggle Function
    function toggleMenu(event, button) {
        event.stopPropagation();
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
            document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
                dropdown.style.top = '';
                dropdown.style.left = '';
            });
        }
    });

function confirmDelete(id, nama, asesiCount) {
    closeMenus();
    if (asesiCount > 0) {
        alert('Jurusan "' + nama + '" tidak dapat dihapus karena masih memiliki ' + asesiCount + ' data asesi terdaftar.');
        return;
    }
    document.getElementById('deleteModalText').innerHTML =
        'Anda yakin ingin menghapus jurusan <strong>' + nama + '</strong>?<br>Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteForm').action = '{{ url("admin/jurusan") }}/' + id;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

function closeMenus() {
    document.querySelectorAll('.action-dropdown').forEach(m => m.classList.remove('show'));
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-action')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeMenus();
    }
});
</script>
@endsection
