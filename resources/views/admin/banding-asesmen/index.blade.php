@extends('admin.layout')

@section('title', 'Banding Asesmen')
@section('page-title', 'Banding Asesmen')

@section('styles')
<style>
    .head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
    .head h2 { margin:0; font-size:22px; color:#0f172a; }
    .head p { margin:4px 0 0; color:#64748b; font-size:13px; }

    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:12px; margin-bottom:14px; }
    .stat { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; }
    .stat small { font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700; }
    .stat strong { display:block; margin-top:5px; font-size:24px; color:#0f172a; }

    .toolbar { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px; margin-bottom:12px; }
    .toolbar form { display:flex; gap:10px; flex-wrap:wrap; }
    .input, .select { border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; font-size:14px; }
    .input { min-width:280px; flex:1; }
    .select { min-width:180px; background:#fff; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-light { background:#e2e8f0; color:#334155; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:auto; }
    table { width:100%; border-collapse:collapse; min-width:980px; }
    th { background:#f8fafc; font-size:11px; color:#64748b; letter-spacing:.4px; text-transform:uppercase; padding:11px 14px; text-align:left; border-bottom:1px solid #e2e8f0; }
    td { font-size:13px; color:#334155; padding:12px 14px; border-bottom:1px solid #f1f5f9; }
    tr:last-child td { border-bottom:none; }

    .badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge.diajukan { background:#dbeafe; color:#1e40af; }
    .badge.ditinjau { background:#fef3c7; color:#92400e; }
    .badge.diterima { background:#dcfce7; color:#166534; }
    .badge.ditolak { background:#fee2e2; color:#991b1b; }
    .badge.tidak_banding { background:#e5e7eb; color:#374151; }

    .btn-detail { background:#e0f2fe; color:#0c4a6e; border-radius:6px; padding:7px 10px; font-size:12px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px; }

    .empty { padding:52px 18px; text-align:center; color:#94a3b8; }
    .empty i { font-size:42px; display:block; margin-bottom:8px; }

    .paginate { padding:12px 14px; border-top:1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="head">
    <div>
        <h2>Pengecekan Banding Asesmen</h2>
        <p>Periksa pengajuan banding FR.AK.04 dari asesi, lalu tetapkan status hasil pengecekan.</p>
    </div>
</div>

<div class="stats">
    <div class="stat"><small>Total</small><strong>{{ $stats['total'] }}</strong></div>
    <div class="stat"><small>Diajukan</small><strong>{{ $stats['diajukan'] }}</strong></div>
    <div class="stat"><small>Ditinjau</small><strong>{{ $stats['ditinjau'] }}</strong></div>
    <div class="stat"><small>Diterima</small><strong>{{ $stats['diterima'] }}</strong></div>
    <div class="stat"><small>Ditolak</small><strong>{{ $stats['ditolak'] }}</strong></div>
    <div class="stat"><small>Tidak Banding</small><strong>{{ $stats['tidak_banding'] }}</strong></div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.banding-asesmen.index') }}">
        <input type="text" class="input" name="search" value="{{ $search }}" placeholder="Cari nama asesi, NIK, nama skema, nomor skema...">
        <select class="select" name="status">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="diajukan" {{ $status === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="ditinjau" {{ $status === 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
            <option value="diterima" {{ $status === 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="tidak_banding" {{ $status === 'tidak_banding' ? 'selected' : '' }}>Tidak Banding</option>
        </select>
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Filter</button>
        <a class="btn btn-light" href="{{ route('admin.banding-asesmen.index') }}"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </form>
</div>

<div class="card">
    @if($data->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Asesor Terkait</th>
                    <th>Tgl Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    @php
                        $statusLabel = [
                            'diajukan' => 'Diajukan',
                            'ditinjau' => 'Ditinjau',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'tidak_banding' => 'Tidak Banding',
                        ][$item->status] ?? ucfirst($item->status);
                    @endphp
                    <tr>
                        <td>{{ ($data->firstItem() ?? 0) + $loop->index }}</td>
                        <td>
                            <div style="font-weight:700;color:#0f172a;">{{ $item->asesi->nama ?? '-' }}</div>
                            <div style="font-size:12px;color:#64748b;">NIK: {{ $item->asesi_nik }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->skema->nama_skema ?? '-' }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $item->skema->nomor_skema ?? '-' }}</div>
                        </td>
                        <td>{{ $item->asesor->nama ?? '-' }}</td>
                        <td>{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d-m-Y') : '-' }}</td>
                        <td><span class="badge {{ $item->status }}">{{ $statusLabel }}</span></td>
                        <td>
                            <a href="{{ route('admin.banding-asesmen.show', $item->id) }}" class="btn-detail"><i class="bi bi-eye"></i> Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="paginate">{{ $data->links() }}</div>
    @else
        <div class="empty">
            <i class="bi bi-clipboard-x"></i>
            <div>Belum ada pengajuan banding asesmen.</div>
        </div>
    @endif
</div>
@endsection
