@extends('admin.layout')

@section('title', 'Kelola Konten Profil')
@section('page-title', 'Konten Profil')

@section('content')
<div class="profile-content-container">
    <div class="page-header">
        <div>
            <h2>Kelola Konten Profil</h2>
            <p class="subtitle">Kelola Sejarah Singkat, Visi & Misi LSP</p>
        </div>
        <a href="{{ route('admin.profile-content.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Konten
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">SEJARAH SINGKAT</div>
                <div class="stat-value">{{ $sejarah->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">KONTEN AKTIF</div>
                <div class="stat-value">{{ $sejarah->where('is_active', true)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Sejarah Singkat Section --}}
    <div style="margin-top: 2rem;">
        <div class="section-header">
            <h3>Sejarah Singkat</h3>
            <span class="content-count">{{ $sejarah->count() }} item</span>
        </div>

        @if($sejarah->count() > 0)
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="60">Urutan</th>
                            <th>Judul</th>
                            <th width="90">Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sejarah as $item)
                        <tr>
                            <td class="text-center">
                                <span class="count-badge">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                <p class="desc-preview">{{ Str::limit($item->content, 100) }}</p>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.profile-content.toggle', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-badge {{ $item->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.profile-content.edit', $item->id) }}" class="btn-action edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.profile-content.destroy', $item->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus konten ini?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-book"></i>
                <h3>Belum ada Sejarah Singkat</h3>
                <p>Tambahkan konten Sejarah Singkat untuk ditampilkan di halaman profil</p>
                <a href="{{ route('admin.profile-content.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Sejarah
                </a>
            </div>
        @endif
    </div>

    {{-- Visi Section --}}
    <div style="margin-top: 2rem;">
        <div class="section-header">
            <h3>Visi LSP</h3>
            <span class="content-count">{{ $visions->count() }} item</span>
        </div>

        @if($visions->count() > 0)
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="60">Urutan</th>
                            <th>Konten</th>
                            <th width="90">Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visions as $vision)
                        <tr>
                            <td class="text-center">
                                <span class="order-badge">{{ $vision->order }}</span>
                            </td>
                            <td>
                                <p class="desc-preview">{{ Str::limit($vision->content, 150) }}</p>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.profile-content.vision-mission.toggle', $vision->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-badge {{ $vision->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                        {{ $vision->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.profile-content.vision-mission.edit', $vision->id) }}" class="btn-action edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.profile-content.vision-mission.destroy', $vision->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus visi ini?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-lightbulb"></i>
                <h3>Belum ada Visi</h3>
                <p>Tambahkan Visi LSP untuk ditampilkan di halaman profil</p>
                <a href="{{ route('admin.profile-content.vision-mission.create', 'visi') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Visi
                </a>
            </div>
        @endif
    </div>

    {{-- Misi Section --}}
    <div style="margin-top: 2rem;">
        <div class="section-header">
            <h3>Misi LSP</h3>
            <span class="content-count">{{ $missions->count() }} item</span>
        </div>

        @if($missions->count() > 0)
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="60">Urutan</th>
                            <th>Konten</th>
                            <th width="90">Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($missions as $mission)
                        <tr>
                            <td class="text-center">
                                <span class="order-badge">{{ $mission->order }}</span>
                            </td>
                            <td>
                                <p class="desc-preview">{{ Str::limit($mission->content, 150) }}</p>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.profile-content.vision-mission.toggle', $mission->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-badge {{ $mission->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                        {{ $mission->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.profile-content.vision-mission.edit', $mission->id) }}" class="btn-action edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.profile-content.vision-mission.destroy', $mission->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus misi ini?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-target"></i>
                <h3>Belum ada Misi</h3>
                <p>Tambahkan Misi LSP untuk ditampilkan di halaman profil</p>
                <a href="{{ route('admin.profile-content.vision-mission.create', 'misi') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Misi
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@section('styles')
<style>
    .profile-content-container { padding: 0; }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .page-header h2 { font-size: 22px; color: #1e293b; font-weight: 700; }
    .page-header .subtitle { font-size: 13px; color: #64748b; margin-top: 4px; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #003961; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

    /* Stats */
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
        gap: 20px; 
        margin-bottom: 24px; 
    }
    .stat-card { 
        background: white; 
        padding: 20px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        gap: 16px; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
        transition: all 0.2s; 
    }
    .stat-card:hover { 
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        transform: translateY(-2px); 
    }
    .stat-icon { 
        width: 56px; 
        height: 56px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 24px; 
        color: white; 
        flex-shrink: 0;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-content { flex: 1; }
    .stat-label { 
        font-size: 11px; 
        font-weight: 600; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 4px; 
    }
    .stat-value { 
        font-size: 28px; 
        font-weight: 700; 
        color: #0F172A; 
        display: flex; 
        align-items: baseline; 
        gap: 8px; 
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-header h3 {
        margin: 0;
        color: #1e293b;
        font-size: 1.15rem;
        font-weight: 600;
    }

    .section-header i {
        margin-right: 0.5rem;
        color: #0073bd;
    }

    .content-count {
        background: #f1f5f9;
        color: #475569;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Table */
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; }
    .table { width: 100%; border-collapse: collapse; }
    .table thead { background: #f8fafc; }
    .table th { padding: 14px 16px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 2px solid #e2e8f0; }
    .table td { padding: 14px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    .text-center { text-align: center; }
    .text-muted { color: #94a3b8; }

    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        background: #f1f5f9;
        border-radius: 0.375rem;
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
    }

    .order-badge { 
        background: #e2e8f0; 
        color: #475569; 
        padding: 4px 12px; 
        border-radius: 6px; 
        font-weight: 600; 
        font-size: 13px; 
    }

    .desc-preview { 
        font-size: 12px; 
        color: #94a3b8; 
        margin-top: 4px; 
    }

    .status-badge {
        padding: 5px 14px; 
        border-radius: 20px; 
        font-size: 12px; 
        font-weight: 600; 
        border: none; 
        cursor: pointer; 
        transition: all 0.2s;
    }
    .status-badge.active { 
        background: #dcfce7; 
        color: #166534; 
    }
    .status-badge.active:hover { 
        background: #bbf7d0; 
    }
    .status-badge.inactive { 
        background: #fee2e2; 
        color: #991b1b; 
    }
    .status-badge.inactive:hover { 
        background: #fecaca; 
    }

    .action-btns { 
        display: flex; 
        gap: 8px; 
    }
    .btn-action {
        width: 36px; 
        height: 36px; 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        border: none; 
        cursor: pointer; 
        font-size: 16px; 
        transition: all 0.2s; 
        text-decoration: none;
    }
    .btn-action.edit { 
        background: #eff6ff; 
        color: #0073bd; 
    }
    .btn-action.edit:hover { 
        background: #dbeafe; 
    }
    .btn-action.delete { 
        background: #fef2f2; 
        color: #ef4444; 
    }
    .btn-action.delete:hover { 
        background: #fee2e2; 
    }

    /* Empty state */
    .empty-state {
        background: white; 
        border-radius: 12px; 
        padding: 60px 20px; 
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .empty-state i { 
        font-size: 48px; 
        color: #cbd5e1; 
    }
    .empty-state h3 { 
        font-size: 18px; 
        color: #334155; 
        margin-top: 16px; 
    }
    .empty-state p { 
        font-size: 14px; 
        color: #94a3b8; 
        margin: 8px 0 20px; 
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection
