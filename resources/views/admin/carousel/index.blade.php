@extends('admin.layout')

@section('title', 'Kelola Banner Carousel')
@section('page-title', 'Banner Carousel')

@section('content')
@php
    $adminUser = Auth::guard('admin')->user();
    $canCreateCarousel = $adminUser->hasPermission('carousel.create');
    $canEditCarousel = $adminUser->hasPermission('carousel.edit');
    $canDeleteCarousel = $adminUser->hasPermission('carousel.delete');
@endphp

<div class="carousel-container">
    <div class="page-header">
        <div>
            <h2>Kelola Banner Carousel</h2>
            <p class="subtitle">Atur banner slider yang tampil di halaman utama (Rasio 16:9)</p>
        </div>
        @if($canCreateCarousel)
            <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Banner
            </a>
        @endif
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-images"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL BANNER</div>
                <div class="stat-value">{{ $carousels->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">AKTIF</div>
                <div class="stat-value">{{ $carousels->where('is_active', true)->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">NONAKTIF</div>
                <div class="stat-value">{{ $carousels->where('is_active', false)->count() }}</div>
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
                        @if($canEditCarousel)
                            <form action="{{ route('admin.carousel.toggle', $carousel->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="status-badge {{ $carousel->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                    {{ $carousel->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        @else
                            <span class="status-badge {{ $carousel->is_active ? 'active' : 'inactive' }}">
                                {{ $carousel->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($canEditCarousel || $canDeleteCarousel)
                            <div class="action-menu">
                                <button class="action-btn" onclick="toggleMenu(this)">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="action-dropdown">
                                    @if($canEditCarousel)
                                        <a href="{{ route('admin.carousel.edit', $carousel->id) }}" class="dropdown-item">
                                            <i class="bi bi-pencil"></i> Ubah</a>
                                    @endif

                                    @if($canDeleteCarousel)
                                        <form action="{{ route('admin.carousel.destroy', $carousel->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus banner ini?')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span style="color:#94a3b8;">-</span>
                        @endif
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
        @if($canCreateCarousel)
            <a href="{{ route('admin.carousel.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Banner Pertama
            </a>
        @endif
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

@section('scripts')
<script>
function toggleMenu(button) {
    const dropdown = button.nextElementSibling;
    const isVisible = dropdown.classList.contains('show');
    
    // Close all other dropdowns
    document.querySelectorAll('.action-dropdown.show').forEach(d => {
        if (d !== dropdown) d.classList.remove('show');
    });
    
    if (!isVisible) {
        // Calculate position
        const rect = button.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + 4) + 'px';
        dropdown.style.left = (rect.right - 160) + 'px';
    }
    
    dropdown.classList.toggle('show');
}

document.addEventListener('click', e => {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => d.classList.remove('show'));
    }
});
</script>
@endsection
