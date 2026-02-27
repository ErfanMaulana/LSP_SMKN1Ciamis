@extends('admin.layout')

@section('title', 'Kelola Banner Carousel')
@section('page-title', 'Banner Carousel')

@section('content')
<div class="carousel-container">
    <div class="page-header">
        <div>
            <h2>Kelola Banner Carousel</h2>
            <p class="subtitle">Atur banner slider yang tampil di halaman utama (Rasio 16:9)</p>
        </div>
        <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Banner
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon bg-blue"><i class="bi bi-images"></i></div>
            <div class="stat-info">
                <h3>{{ $carousels->count() }}</h3>
                <p>Total Banner</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-blue"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $carousels->where('is_active', true)->count() }}</h3>
                <p>Aktif</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-blue"><i class="bi bi-x-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $carousels->where('is_active', false)->count() }}</h3>
                <p>Nonaktif</p>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="info-banner">
        <i class="bi bi-info-circle"></i>
        <span>Gunakan gambar dengan rasio <strong>16:9</strong> (1920x1080 px) untuk hasil terbaik. Format: JPG, PNG, WebP. Maks 5MB.</span>
    </div>

    {{-- Table --}}
    @if($carousels->count() > 0)
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th width="60">Urutan</th>
                    <th width="180">Preview</th>
                    <th>Judul</th>
                    <th>Subtitle</th>
                    <th width="90">Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carousels as $carousel)
                <tr>
                    <td class="text-center">
                        <span class="order-badge">{{ $carousel->urutan }}</span>
                    </td>
                    <td>
                        <div class="preview-img">
                            <img src="{{ asset('storage/' . $carousel->image) }}" 
                                 alt="{{ $carousel->title }}"
                                 onerror="this.src='https://via.placeholder.com/320x180/334155/94a3b8?text=No+Image'">
                        </div>
                    </td>
                    <td>
                        <strong>{{ $carousel->title }}</strong>
                        @if($carousel->description)
                            <p class="desc-preview">{{ Str::limit($carousel->description, 60) }}</p>
                        @endif
                    </td>
                    <td>{{ $carousel->subtitle ?? '-' }}</td>
                    <td class="text-center">
                        <form action="{{ route('admin.carousel.toggle', $carousel->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="status-badge {{ $carousel->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                {{ $carousel->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.carousel.edit', $carousel->id) }}" class="btn-action edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.carousel.destroy', $carousel->id) }}" method="POST" 
                                  onsubmit="return confirm('Hapus banner ini?')">
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
        <i class="bi bi-images"></i>
        <h3>Belum ada banner</h3>
        <p>Tambahkan banner carousel untuk ditampilkan di halaman utama</p>
        <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Banner Pertama
        </a>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .carousel-container { padding: 0; }

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
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: white; }
    .stat-icon.bg-blue { background: #0073bd; }
    .stat-icon.bg-green { background: #22c55e; }
    .stat-icon.bg-red { background: #ef4444; }
    .stat-info h3 { font-size: 24px; font-weight: 700; color: #1e293b; }
    .stat-info p { font-size: 12px; color: #64748b; margin-top: 2px; }

    /* Info banner */
    .info-banner {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px 18px;
        font-size: 13px; color: #004a7a; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
    }

    /* Table */
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; }
    .table { width: 100%; border-collapse: collapse; }
    .table thead { background: #f8fafc; }
    .table th { padding: 14px 16px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 2px solid #e2e8f0; }
    .table td { padding: 14px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    .text-center { text-align: center; }

    .order-badge { background: #e2e8f0; color: #475569; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; }

    .preview-img { width: 160px; height: 90px; border-radius: 8px; overflow: hidden; border: 2px solid #e2e8f0; }
    .preview-img img { width: 100%; height: 100%; object-fit: cover; }

    .desc-preview { font-size: 12px; color: #94a3b8; margin-top: 4px; }

    .status-badge {
        padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s;
    }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.active:hover { background: #bbf7d0; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    .status-badge.inactive:hover { background: #fecaca; }

    .action-btns { display: flex; gap: 8px; }
    .btn-action {
        width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
        border: none; cursor: pointer; font-size: 16px; transition: all 0.2s; text-decoration: none;
    }
    .btn-action.edit { background: #eff6ff; color: #0073bd; }
    .btn-action.edit:hover { background: #dbeafe; }
    .btn-action.delete { background: #fef2f2; color: #ef4444; }
    .btn-action.delete:hover { background: #fee2e2; }

    /* Empty state */
    .empty-state {
        background: white; border-radius: 12px; padding: 60px 20px; text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .empty-state i { font-size: 48px; color: #cbd5e1; }
    .empty-state h3 { font-size: 18px; color: #334155; margin-top: 16px; }
    .empty-state p { font-size: 14px; color: #94a3b8; margin: 8px 0 20px; }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection
