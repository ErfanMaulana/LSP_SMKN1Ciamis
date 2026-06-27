@extends('admin.layout')

@section('title', 'Detail Ceklis Banding')
@section('page-title', 'Detail Ceklis Banding')

@section('styles')
<style>
    .head { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:12px; flex-wrap:wrap; }
    .head h2 { margin:0; font-size:22px; color:#0f172a; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-primary:hover { background: #005a94; }
    .btn-secondary { background:#64748b; color:#fff; }
    .btn-secondary:hover { background: #475569; }
    .btn-danger { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
    .btn-danger:hover { background: #fecaca; }
    .btn-light { background:#e2e8f0; color:#334155; }
    .btn-light:hover { background: #cbd5e1; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .body { padding:24px; }

    .detail-section { margin-bottom:24px; }
    .detail-section:last-of-type { margin-bottom:0; }
    .detail-section h3 { font-size:16px; color:#0f172a; font-weight:600; margin:0 0 16px; display:flex; align-items:center; gap:8px; }
    .detail-section h3:before { content:''; width:4px; height:16px; background:#0073bd; border-radius:2px; }

    .detail-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px; }
    .detail-item { display:flex; flex-direction:column; gap:6px; }
    .detail-item.full-width { grid-column:1 / -1; }
    .detail-item label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; }
    .detail-value { font-size:14px; color:#1e293b; font-weight:500; line-height:1.5; }

    .badge { display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge.active { background:#dcfce7; color:#166534; }
    .badge.inactive { background:#fee2e2; color:#991b1b; }

    .form-actions { margin-top:24px; padding-top:20px; border-top:1px solid #e2e8f0; display:flex; gap:12px; flex-wrap:wrap; }

    @media (max-width: 768px) {
        .head, .form-actions { width:100%; }
        .head .btn, .form-actions .btn { width:100%; justify-content:center; }
    }
</style>
@endsection

@section('content')
<div class="head">
    <h2>Detail Komponen Ceklis Banding</h2>
    <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="body">
        <div class="detail-section">
            <h3>Informasi Komponen Ceklis</h3>
            
            <div class="detail-grid">
                <div class="detail-item full-width">
                    <label>Pernyataan Ceklis</label>
                    <div class="detail-value">{{ $komponen->pernyataan }}</div>
                </div>

                <div class="detail-item">
                    <label>Urutan Tampil</label>
                    <div class="detail-value">{{ $komponen->urutan }}</div>
                </div>

                <div class="detail-item">
                    <label>Status</label>
                    <div class="detail-value">
                        @if($komponen->is_active)
                            <span class="badge active">Aktif</span>
                        @else
                            <span class="badge inactive">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Informasi Sistem</h3>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dibuat Pada</label>
                    <div class="detail-value">
                        {{ $komponen->created_at ? $komponen->created_at->locale('id')->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir Diperbarui</label>
                    <div class="detail-value">
                        {{ $komponen->updated_at ? $komponen->updated_at->locale('id')->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            @if(Auth::guard('admin')->user()->hasPermission('banding-asesmen-komponen.edit'))
                <a href="{{ route('admin.banding-asesmen-komponen.edit', $komponen->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Komponen
                </a>
            @endif

            @if(Auth::guard('admin')->user()->hasPermission('banding-asesmen-komponen.delete'))
                <form method="POST" action="{{ route('admin.banding-asesmen-komponen.destroy', $komponen->id) }}" onsubmit="return confirm('Hapus komponen ceklis ini?')" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus Komponen
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.banding-asesmen-komponen.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
