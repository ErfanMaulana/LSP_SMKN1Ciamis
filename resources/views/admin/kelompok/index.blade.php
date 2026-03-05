@extends('admin.layout')

@section('title', 'Kelompok')
@section('page-title', 'Kelompok')

@section('content')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
    .page-header h2 { font-size:24px; font-weight:700; color:#1e293b; }
    .page-header .subtitle { font-size:14px; color:#64748b; margin-top:4px; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .2s; }
    .btn-primary { background:#0061A5; color:white; }
    .btn-primary:hover { background:#00509e; color:white; }
    .btn-success { background:#16a34a; color:white; }
    .btn-success:hover { background:#15803d; color:white; }
    .btn-sm { padding:6px 14px; font-size:13px; }
    .btn-outline { background:transparent; border:1.5px solid #0061A5; color:#0061A5; }
    .btn-outline:hover { background:#0061A5; color:white; }
    .card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.07); border:1px solid #e2e8f0; overflow:hidden; margin-bottom:24px; }
    .card-body { padding:20px; }
    .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:24px; }
    .stat-card { background:white; border-radius:12px; padding:20px; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1px solid #e5e7eb; display:flex; align-items:center; gap:16px; }
    .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; }
    .stat-icon.blue { background:#dbeafe; color:#1d4ed8; }
    .stat-icon.green { background:#d1fae5; color:#065f46; }
    .stat-icon.amber { background:#fef3c7; color:#92400e; }
    .stat-icon.red { background:#fee2e2; color:#991b1b; }
    .stat-info h3 { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; }
    .stat-info .value { font-size:28px; font-weight:800; color:#1e293b; }
    .filter-section { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .search-box { position:relative; flex:1; min-width:200px; }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; }
    .search-box input { width:100%; padding:10px 12px 10px 38px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s; }
    .search-box input:focus { border-color:#0061A5; box-shadow:0 0 0 3px rgba(0,97,165,.1); }
    .btn-search { padding:10px 20px; background:#0061A5; color:white; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
    .table-wrapper { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    thead th { padding:12px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; background:#f8fafc; border-bottom:2px solid #e2e8f0; }
    tbody td { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:14px; color:#374151; }
    tbody tr:hover { background:#f8fafc; }
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .badge-blue { background:#dbeafe; color:#1d4ed8; }
    .badge-green { background:#d1fae5; color:#065f46; }
    .badge-gray { background:#f1f5f9; color:#94a3b8; }
    .empty-row { text-align:center; padding:32px!important; color:#94a3b8; }
    .empty-row i { font-size:32px; display:block; margin-bottom:8px; }
    .pagination-wrapper { padding:16px 20px; display:flex; justify-content:center; }
    .alert { padding:14px 20px; border-radius:10px; font-size:14px; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
    .alert-success { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
    .action-menu-wrapper { position:relative; }
    .action-menu-btn { width:32px; height:32px; padding:0; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:6px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b; font-size:18px; transition:all .2s; }
    .action-menu-btn:hover { background:#e2e8f0; color:#1e293b; }
    .action-dropdown { position:fixed; background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.12); min-width:160px; z-index:9999; display:none; }
    .action-dropdown.open { display:block; }
    .action-dropdown a, .action-dropdown button { display:flex; align-items:center; gap:10px; width:100%; padding:10px 12px; border:none; background:none; text-align:left; font-size:13px; color:#374151; cursor:pointer; transition:all .2s; text-decoration:none; }
    .action-dropdown a:hover, .action-dropdown button:hover { background:#f1f5f9; color:#0061a5; }
    .action-dropdown button { color:#ef4444; }
    .action-dropdown button:hover { background:#fee2e2; color:#ef4444; }
</style>

<div class="page-header">
    <div>
        <h2>Kelompok</h2>
        <p class="subtitle">Kelola kelompok uji kompetensi, asesor, dan asesi</p>
    </div>
    <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Kelompok
    </a>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-collection"></i></div>
        <div class="stat-info">
            <h3>TOTAL KELOMPOK</h3>
            <div class="value">{{ $stats['total_kelompok'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info">
            <h3>KELOMPOK AKTIF</h3>
            <div class="value">{{ $stats['kelompok_aktif'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <h3>ASESI DITUGASKAN</h3>
            <div class="value">{{ $stats['total_asesi'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-person-dash"></i></div>
        <div class="stat-info">
            <h3>ASESI BELUM DITUGASKAN</h3>
            <div class="value">{{ $stats['belum_ditugaskan'] }}</div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.kelompok.index') }}" class="filter-section">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kelompok, skema, atau asesor...">
            </div>
            <button type="submit" class="btn-search">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.kelompok.index') }}" class="btn btn-outline btn-sm">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kelompok</th>
                    <th>Skema</th>
                    <th>Asesor</th>
                    <th>Jumlah Asesi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompoks as $index => $kelompok)
                    <tr>
                        <td>{{ $kelompoks->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight:600;color:#1e293b;">{{ $kelompok->nama_kelompok }}</div>
                        </td>
                        <td>
                            @if($kelompok->skema)
                                <span class="badge badge-green">{{ $kelompok->skema->nama_skema }}</span>
                            @else
                                <span class="badge badge-gray">Tidak ada skema</span>
                            @endif
                        </td>
                        <td>
                            @forelse($kelompok->asesors as $asesor)
                                <span class="badge badge-blue" style="margin-bottom:2px;">{{ $asesor->nama }}</span>
                            @empty
                                <span class="badge badge-gray">Belum ada asesor</span>
                            @endforelse
                        </td>
                        <td>
                            @php $jumlahAsesi = $kelompok->asesis->count(); @endphp
                            <div style="display:flex;align-items:center;gap:10px;min-width:120px;">
                                <span style="font-weight:700;color:#0061A5;font-size:16px;">{{ $jumlahAsesi }}</span>
                                <span style="font-size:12px;color:#94a3b8;">asesi</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-menu-wrapper">
                                <button class="action-menu-btn" onclick="toggleMenu(this)">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="action-dropdown">
                                    <a href="{{ route('admin.kelompok.show', $kelompok->id) }}">
                                        <i class="bi bi-people-fill"></i> Kelola Asesi
                                    </a>
                                    <a href="{{ route('admin.kelompok.edit', $kelompok->id) }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" onclick="deleteKelompok({{ $kelompok->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">
                            <i class="bi bi-collection"></i>
                            Belum ada kelompok. <a href="{{ route('admin.kelompok.create') }}">Buat kelompok baru</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kelompoks->hasPages())
        <div class="pagination-wrapper">{{ $kelompoks->links() }}</div>
    @endif
</div>

<script>
function toggleMenu(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('open');
    
    document.querySelectorAll('.action-dropdown.open').forEach(m => {
        if (m !== menu) m.classList.remove('open');
    });
    
    if (!isOpen) {
        const rect = btn.getBoundingClientRect();
        menu.style.left = (rect.left + rect.width - 160) + 'px';
        menu.style.top = (rect.bottom + 4) + 'px';
        menu.classList.add('open');
    } else {
        menu.classList.remove('open');
    }
    event.stopPropagation();
}

function deleteKelompok(id) {
    if (confirm('Yakin hapus kelompok ini beserta semua asesinya?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/kelompok/${id}`;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('click', function(e) {
    document.querySelectorAll('.action-dropdown.open').forEach(menu => {
        const wrapper = menu.parentElement;
        if (!wrapper.contains(e.target)) {
            menu.classList.remove('open');
        }
    });
});
</script>
@endsection
