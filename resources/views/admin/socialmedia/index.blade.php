@extends('admin.layout')

@section('title', 'Kelola Sosial Media')
@section('page-title', 'Sosial Media')

@section('content')
<div class="sm-container">
    <div class="page-header">
        <div>
            <h2>Kelola Sosial Media</h2>
            <p class="subtitle">Atur link sosial media yang tampil di footer website</p>
        </div>
        <a href="{{ route('admin.socialmedia.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Sosial Media
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-share"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">TOTAL PLATFORM</div>
                <div class="stat-value">{{ $socialMedias->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">AKTIF</div>
                <div class="stat-value">{{ $socialMedias->where('is_active', true)->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">NONAKTIF</div>
                <div class="stat-value">{{ $socialMedias->where('is_active', false)->count() }}</div>
            </div>
        </div>
    </div>

    @if($socialMedias->count() > 0)
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th width="60">Urutan</th>
                    <th width="60">Ikon</th>
                    <th>Platform</th>
                    <th>URL</th>
                    <th width="90">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($socialMedias as $sm)
                <tr>
                    <td class="text-center">
                        <span class="order-badge">{{ $sm->urutan }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $colors = [
                                'instagram' => '#e1306c',
                                'youtube'   => '#ff0000',
                                'facebook'  => '#1877f2',
                                'tiktok'    => '#010101',
                                'twitter'   => '#1da1f2',
                                'whatsapp'  => '#25d366',
                                'linkedin'  => '#0a66c2',
                            ];
                            $icons = [
                                'instagram' => 'bi-instagram',
                                'youtube'   => 'bi-youtube',
                                'facebook'  => 'bi-facebook',
                                'tiktok'    => 'bi-tiktok',
                                'twitter'   => 'bi-twitter-x',
                                'whatsapp'  => 'bi-whatsapp',
                                'linkedin'  => 'bi-linkedin',
                            ];
                            $color = $colors[$sm->platform] ?? '#64748b';
                            $icon  = $icons[$sm->platform] ?? 'bi-globe';
                        @endphp
                        <div class="platform-icon" style="background: {{ $color }}1a; color: {{ $color }}">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $sm->name }}</strong>
                        <p class="platform-slug">{{ $sm->platform }}</p>
                    </td>
                    <td>
                        <a href="{{ $sm->url }}" target="_blank" class="url-link">
                            {{ Str::limit($sm->url, 50) }}
                            <i class="bi bi-box-arrow-up-right" style="font-size:11px"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.socialmedia.toggle', $sm->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="status-badge {{ $sm->is_active ? 'active' : 'inactive' }}" title="Klik untuk toggle">
                                {{ $sm->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.socialmedia.edit', $sm->id) }}" class="btn-action edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.socialmedia.destroy', $sm->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus {{ $sm->name }}?')">
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
        <i class="bi bi-share"></i>
        <h3>Belum ada sosial media</h3>
        <p>Tambahkan link sosial media untuk ditampilkan di footer website</p>
        <a href="{{ route('admin.socialmedia.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pertama
        </a>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .sm-container { padding: 0; }

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h2 { font-size: 22px; color: #1e293b; font-weight: 700; }
    .page-header .subtitle { font-size: 13px; color: #64748b; margin-top: 4px; }

    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; font-size: 14px; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #003961; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }

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

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; }
    .table { width: 100%; border-collapse: collapse; }
    .table thead { background: #f8fafc; }
    .table th { padding: 14px 16px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 2px solid #e2e8f0; }
    .table td { padding: 14px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    .text-center { text-align: center; }

    .order-badge { background: #e2e8f0; color: #475569; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; }

    .platform-icon { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; }
    .platform-slug { font-size: 11px; color: #94a3b8; margin-top: 2px; text-transform: capitalize; }
    .url-link { color: #0073bd; text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 4px; }
    .url-link:hover { text-decoration: underline; }

    .status-badge { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.active:hover { background: #bbf7d0; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    .status-badge.inactive:hover { background: #fecaca; }

    .action-btns { display: flex; gap: 8px; }
    .btn-action { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; font-size: 16px; transition: all 0.2s; text-decoration: none; }
    .btn-action.edit { background: #eff6ff; color: #0073bd; }
    .btn-action.edit:hover { background: #dbeafe; }
    .btn-action.delete { background: #fef2f2; color: #ef4444; }
    .btn-action.delete:hover { background: #fee2e2; }

    .empty-state { background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .empty-state i { font-size: 48px; color: #cbd5e1; }
    .empty-state h3 { font-size: 18px; color: #334155; margin-top: 16px; }
    .empty-state p { font-size: 14px; color: #94a3b8; margin: 8px 0 20px; }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection
