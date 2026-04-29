@extends('admin.layout')

@section('title', 'Ceklis Banding Asesmen')
@section('page-title', 'Ceklis Banding Asesmen')

@section('styles')
<style>
    .head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
    .head h2 { margin:0; color:#0f172a; font-size:22px; }
    .head p { margin:4px 0 0; color:#64748b; font-size:13px; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }

    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:12px; margin-bottom:14px; }
    .stat { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; }
    .stat small { font-size:11px; color:#64748b; text-transform:uppercase; font-weight:700; }
    .stat strong { display:block; margin-top:5px; font-size:24px; color:#0f172a; }

    .toolbar { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px; margin-bottom:12px; }
    .toolbar form { display:flex; gap:10px; }
    .input { flex:1; border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; font-size:14px; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:auto; }
    table { width:100%; border-collapse:collapse; min-width:760px; }
    th { background:#f8fafc; border-bottom:1px solid #e2e8f0; font-size:11px; text-transform:uppercase; color:#64748b; letter-spacing:.4px; padding:11px 14px; text-align:left; }
    td { border-bottom:1px solid #f1f5f9; padding:12px 14px; font-size:13px; color:#334155; }
    tr:last-child td { border-bottom:none; }

    .badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge.active { background:#dcfce7; color:#166534; }
    .badge.inactive { background:#fee2e2; color:#991b1b; }

    .actions { display:flex; gap:8px; }
    .btn-sm { font-size:12px; padding:6px 10px; border-radius:6px; }
    .btn-edit { background:#e0f2fe; color:#0c4a6e; }
    .btn-delete { background:#fee2e2; color:#991b1b; border:none; cursor:pointer; }

    .empty { padding:52px 18px; text-align:center; color:#94a3b8; }
    .empty i { font-size:42px; display:block; margin-bottom:8px; }

    .paginate { padding:12px 14px; border-top:1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="head">
    <div>
        <h2>Ceklis Banding Asesmen</h2>
        <p>Kelola pernyataan ceklis FR.AK.04 yang akan diisi pada form banding asesor.</p>
    </div>
    <a href="{{ route('admin.banding-asesmen-komponen.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Ceklis</a>
</div>

<div class="stats">
    <div class="stat"><small>Total Komponen</small><strong>{{ $stats['total'] }}</strong></div>
    <div class="stat"><small>Aktif</small><strong>{{ $stats['active'] }}</strong></div>
    <div class="stat"><small>Nonaktif</small><strong>{{ $stats['inactive'] }}</strong></div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.banding-asesmen-komponen.index') }}">
        <input type="text" class="input" name="search" value="{{ $search }}" placeholder="Cari pernyataan ceklis...">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
    </form>
</div>

<div class="card">
    @if($komponen->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Urutan</th>
                    <th>Pernyataan Ceklis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    <tr>
                        <td>{{ ($komponen->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $item->urutan }}</td>
                        <td>{{ $item->pernyataan }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge active">Aktif</span>
                            @else
                                <span class="badge inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.banding-asesmen-komponen.edit', $item->id) }}" class="btn btn-sm btn-edit"><i class="bi bi-pencil"></i> Edit</a>
                                <form method="POST" action="{{ route('admin.banding-asesmen-komponen.destroy', $item->id) }}" onsubmit="return confirm('Hapus komponen ceklis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="paginate">{{ $komponen->links() }}</div>
    @else
        <div class="empty">
            <i class="bi bi-list-check"></i>
            <div>Belum ada komponen ceklis banding.</div>
        </div>
    @endif
</div>
@endsection
