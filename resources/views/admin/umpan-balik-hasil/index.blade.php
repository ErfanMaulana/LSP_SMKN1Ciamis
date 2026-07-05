@extends('admin.layout')

@section('title', 'Hasil Umpan Balik Asesi')
@section('page-title', 'Hasil Umpan Balik Asesi')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }
    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .stat-card small {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: .4px;
    }
    .stat-card strong {
        display: block;
        margin-top: 5px;
        font-size: 24px;
        color: #0073bd;
    }

    .toolbar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 16px;
    }
    .toolbar form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        min-width: 280px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 14px;
        font-family: inherit;
    }
    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }
    .btn-search {
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        background: #0073bd;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; min-width: 860px; }
    th {
        background: #f8fafc;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 11px 14px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    td {
        font-size: 13px;
        color: #334155;
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #e0f2fe;
        color: #0c4a6e;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-detail:hover { background: #bae6fd; }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 42px;
        display: block;
        margin-bottom: 10px;
        color: #cbd5e1;
    }
    .empty-state h4 {
        color: #475569;
        margin: 0 0 6px;
        font-size: 16px;
    }

    .paginate { padding: 12px 14px; border-top: 1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Hasil Umpan Balik Asesi</h2>
        <p>Ringkasan umpan balik asesi terhadap kinerja asesor (FR.AK.03).</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <small>Total Asesi Mengisi</small>
        <strong>{{ $stats['total_respon'] }}</strong>
    </div>
    <div class="stat-card">
        <small>Total Jawaban Komponen</small>
        <strong>{{ $stats['total_jawaban'] }}</strong>
    </div>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.umpan-balik-hasil.index') }}">
        <input type="text" name="search" class="search-input" value="{{ $search }}" placeholder="Cari nama asesi, NIK, nama skema, asesor...">
        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
    </form>
</div>

<div class="card">
    @if($data->count())
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Asesi</th>
                    <th>Skema Sertifikasi</th>
                    <th>Asesor Terkait</th>
                    <th>Komponen Diisi</th>
                    <th>Tanggal Pengisian</th>
                    <th style="width: 110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ ($data->firstItem() ?? 1) + $loop->index }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $item->asesi_nama }}</div>
                            <div style="font-size: 12px; color: #64748b;">NIK: {{ $item->asesi_nik }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->nama_skema }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $item->nomor_skema }}</div>
                        </td>
                        <td>{{ $item->asesor_nama ?? '-' }}</td>
                        <td>
                            <span style="font-weight: 600; color: #0073bd;">{{ $item->total_terisi }} Komponen</span>
                        </td>
                        <td>{{ $item->submitted_at ? \Carbon\Carbon::parse($item->submitted_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.umpan-balik-hasil.show', [$item->asesi_nik, $item->skema_id]) }}" class="btn-detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($data->hasPages())
            <div class="paginate">{{ $data->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <i class="bi bi-clipboard-x"></i>
            <h4>Belum Ada Data Umpan Balik</h4>
            <p>Hasil pengisian umpan balik dari asesi akan muncul di sini setelah asesi menyimpannya.</p>
        </div>
    @endif
</div>
@endsection
