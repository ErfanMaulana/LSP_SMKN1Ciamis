@extends('asesor.layout')

@section('title', 'Banding Asesmen')
@section('page-title', 'Banding Asesmen')

@section('styles')
<style>
    .page-head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:18px; flex-wrap:wrap; }
    .page-head h2 { margin:0; font-size:22px; color:#0f172a; }
    .page-head p { margin:4px 0 0; font-size:13px; color:#64748b; }

    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:12px; margin-bottom:16px; }
    .stat { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:14px; }
    .stat small { display:block; font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700; letter-spacing:.4px; }
    .stat strong { display:block; margin-top:5px; font-size:24px; color:#0f172a; }

    .toolbar { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px; margin-bottom:12px; }
    .toolbar form { display:flex; gap:10px; flex-wrap:wrap; }
    .input, .select { border:1px solid #cbd5e1; border-radius:8px; font-size:14px; padding:9px 12px; }
    .input { min-width:260px; flex:1; }
    .select { min-width:180px; background:#fff; }
    .btn { border:none; border-radius:8px; padding:9px 14px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-light { background:#eef2ff; color:#1e3a8a; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:auto; }
    table { width:100%; border-collapse:collapse; min-width:900px; }
    th { background:#f8fafc; font-size:11px; color:#64748b; padding:11px 14px; text-align:left; text-transform:uppercase; letter-spacing:.4px; border-bottom:1px solid #e2e8f0; }
    td { font-size:13px; color:#334155; padding:12px 14px; border-bottom:1px solid #f1f5f9; }
    tr:last-child td { border-bottom:none; }

    .badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:4px; }
    .badge.draft { background:#e2e8f0; color:#475569; }
    .badge.diajukan { background:#dbeafe; color:#1e40af; }
    .badge.ditinjau { background:#fef3c7; color:#92400e; }
    .badge.diterima { background:#dcfce7; color:#166534; }
    .badge.ditolak { background:#fee2e2; color:#991b1b; }
    .badge.tidak_banding { background:#e5e7eb; color:#374151; }

    .empty { padding:54px 18px; text-align:center; color:#94a3b8; }
    .empty i { font-size:42px; display:block; margin-bottom:10px; }

    .paginate { padding:12px 14px; border-top:1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="page-head">
    <div>
        <h2>Daftar Banding Asesmen</h2>
        <p>Monitoring pengajuan banding FR.AK.04 dari asesi pada skema yang Anda ampuh.</p>
    </div>
</div>

<div class="stats">
    <div class="stat"><small>Total Kandidat Banding</small><strong>{{ $stats['total'] ?? 0 }}</strong></div>
    <div class="stat"><small>Diajukan</small><strong>{{ $stats['diajukan'] ?? 0 }}</strong></div>
    <div class="stat"><small>Ditinjau</small><strong>{{ $stats['ditinjau'] ?? 0 }}</strong></div>
    <div class="stat"><small>Diterima</small><strong>{{ $stats['diterima'] ?? 0 }}</strong></div>
    <div class="stat"><small>Ditolak</small><strong>{{ $stats['ditolak'] ?? 0 }}</strong></div>
    <div class="stat"><small>Tidak Banding</small><strong>{{ $stats['tidak_banding'] ?? 0 }}</strong></div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('asesor.banding.index') }}">
        <input type="text" class="input" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama asesi, NIK, nama skema, nomor skema...">
        <select class="select" name="status">
            @php $currentStatus = $status ?? 'all'; @endphp
            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="belum" {{ $currentStatus === 'belum' ? 'selected' : '' }}>Belum Mengajukan</option>
            <option value="diajukan" {{ $currentStatus === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="ditinjau" {{ $currentStatus === 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
            <option value="diterima" {{ $currentStatus === 'diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="ditolak" {{ $currentStatus === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="tidak_banding" {{ $currentStatus === 'tidak_banding' ? 'selected' : '' }}>Tidak Banding</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
        <a href="{{ route('asesor.banding.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </form>
</div>

<div class="card">
    @if(($rows instanceof \Illuminate\Support\Collection && $rows->count()) || (method_exists($rows, 'count') && $rows->count()))
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Keputusan Asesmen</th>
                    <th>Status Banding</th>
                    <th>Tgl Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @php
                        $statusBanding = $row->banding_status ?: 'draft';
                        $statusLabel = [
                            'draft' => 'Belum Diajukan Asesi',
                            'diajukan' => 'Diajukan',
                            'ditinjau' => 'Ditinjau',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'tidak_banding' => 'Tidak Banding',
                        ][$statusBanding] ?? ucfirst($statusBanding);
                    @endphp
                    <tr>
                        <td>{{ method_exists($rows, 'firstItem') ? (($rows->firstItem() ?? 0) + $loop->index) : ($loop->iteration) }}</td>
                        <td>
                            <div style="font-weight:700;color:#0f172a;">{{ $row->asesi_nama }}</div>
                            <div style="font-size:12px;color:#64748b;">NIK: {{ $row->asesi_nik }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $row->nama_skema }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $row->nomor_skema }}</div>
                        </td>
                        <td>
                            @if($row->rekomendasi === 'lanjut')
                                <span class="badge diterima">Asesmen Dapat Dilanjutkan</span>
                            @else
                                <span class="badge ditolak">Asesmen Tidak Dapat Dilanjutkan</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $statusBanding }}">{{ $statusLabel }}</span></td>
                        <td>{{ $row->tanggal_pengajuan ? \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d-m-Y') : '-' }}</td>
                        <td>
                            @if($row->banding_id)
                                <a href="{{ route('asesor.banding.form', [$row->asesi_nik, $row->skema_id]) }}" class="btn btn-light">
                                    <i class="bi bi-eye"></i>
                                    Detail
                                </a>
                            @else
                                <span class="btn btn-light" style="opacity:.75;cursor:not-allowed;">
                                    <i class="bi bi-hourglass-split"></i>
                                    Menunggu Asesi
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(method_exists($rows, 'links'))
            <div class="paginate">{{ $rows->links() }}</div>
        @endif
    @else
        <div class="empty">
            <i class="bi bi-clipboard-x"></i>
            <div>Belum ada data banding asesmen yang bisa ditampilkan.</div>
        </div>
    @endif
</div>
@endsection
