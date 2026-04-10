@extends('admin.layout')

@section('title', 'Edit Komponen Umpan Balik')
@section('page-title', 'Edit Komponen Umpan Balik')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .page-header h2 { margin: 0; font-size: 24px; color: #0F172A; font-weight: 700; }
    .page-header p { margin: 6px 0 0; color: #64748b; font-size: 13px; }

    .btn { padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all .3s; }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-primary:hover { background: #005a94; transform: translateY(-1px); }
    .btn-secondary { background: #64748b; color: #fff; }
    .btn-secondary:hover { background: #475569; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow: hidden; }
    .meta { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; color: #334155; font-size: 14px; }
    .meta strong { color: #0F172A; }

    table { width: 100%; border-collapse: collapse; }
    th { background: #f8fafc; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding: 12px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 13px; color: #334155; }
    tr:last-child td { border-bottom: none; }

    .status-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 999px; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }

    .actions { display: flex; gap: 8px; }
    .btn-xs { padding: 6px 10px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; }
    .btn-edit { background: #eff6ff; color: #1d4ed8; }
    .btn-delete { background: #fef2f2; color: #dc2626; }

    .pagination-wrap { padding: 12px 14px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
    .pagination-info { font-size: 13px; color: #64748b; }

    .empty-state { text-align: center; padding: 48px 20px; color: #94a3b8; }
    .empty-state i { font-size: 42px; display: block; margin-bottom: 10px; }

    @media (max-width: 768px) {
        .btn { width: 100%; justify-content: center; }
        .card { overflow-x: auto; }
        table { min-width: 760px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Komponen Umpan Balik</h2>
        <p>{{ $skema->nama_skema }} ({{ $skema->nomor_skema }})</p>
    </div>
    <div class="actions">
        <a href="{{ route('admin.umpan-balik-komponen.create', ['skema_id' => $skema->id]) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Komponen
        </a>
        <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="meta">
        Daftar pernyataan komponen yang dapat Anda edit untuk skema <strong>{{ $skema->nama_skema }}</strong>.
    </div>

    @if($komponen->count())
        <table>
            <thead>
                <tr>
                    <th width="70">Urutan</th>
                    <th>Pernyataan Komponen</th>
                    <th width="130">Status</th>
                    <th width="170">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    <tr>
                        <td>{{ $item->urutan }}</td>
                        <td>{{ $item->pernyataan }}</td>
                        <td>
                            <span class="status-badge {{ $item->is_active ? 'active' : 'inactive' }}">
                                <i class="bi bi-{{ $item->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.umpan-balik-komponen.edit', $item->id) }}" class="btn-xs btn-edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.umpan-balik-komponen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus komponen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-xs btn-delete"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $komponen->firstItem() ?? 0 }} sampai {{ $komponen->lastItem() ?? 0 }} dari {{ $komponen->total() }} data
            </div>
            <div>{{ $komponen->links() }}</div>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-journal-x"></i>
            <h4>Belum ada komponen pada skema ini</h4>
        </div>
    @endif
</div>
@endsection
