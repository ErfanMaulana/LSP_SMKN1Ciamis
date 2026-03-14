@extends('admin.layout')

@section('title', 'Nilai Asesor')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-value {
        margin-top: 6px;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }

    .toolbar {
        margin-bottom: 14px;
    }

    .filter-form {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-form input,
    .filter-form select {
        padding: 9px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
    }

    .filter-form input[type=text] {
        min-width: 240px;
        flex: 1;
    }

    .filter-form input:focus,
    .filter-form select:focus {
        border-color: #0061a5;
    }

    .btn-search {
        padding: 9px 16px;
        background: #0061a5;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    td {
        padding: 12px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    tr:hover td {
        background: #f8fafc;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge.kompeten {
        background: #d1fae5;
        color: #065f46;
    }

    .badge.belum {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-detail {
        padding: 6px 12px;
        background: #f0f7ff;
        color: #0061a5;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-detail:hover {
        background: #0061a5;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 8px;
        display: block;
    }

    .pagination-wrapper {
        padding: 16px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper svg {
        height: 20px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2><i class="bi bi-clipboard-data" style="color:#0061a5"></i> Tabel Nilai Asesor</h2>
        <p>Monitoring nilai hasil input asesor untuk setiap asesi dan skema</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Form Dinilai</div>
        <div class="stat-value">{{ $stats->total_form ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Elemen Dinilai</div>
        <div class="stat-value">{{ $stats->total_elemen_dinilai ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Rata-rata Global Nilai</div>
        <div class="stat-value">{{ number_format((float) ($stats->rata_global ?? 0), 2) }}</div>
    </div>
</div>

<div class="toolbar">
    <form class="filter-form" method="GET" action="{{ route('admin.nilai-asesor.index') }}">
        <input type="text" name="search" placeholder="Cari nama / NIK / skema / asesor..." value="{{ request('search') }}">
        <select name="skema_id">
            <option value="">Semua Skema</option>
            @foreach($skemas as $skema)
                <option value="{{ $skema->id }}" {{ request('skema_id') == $skema->id ? 'selected' : '' }}>{{ $skema->nama_skema }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        @if($data->count())
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Asesi</th>
                        <th>NIK</th>
                        <th>Skema</th>
                        <th>Asesor</th>
                        <th style="width:90px;">Elemen</th>
                        <th style="width:120px;">Rata-rata</th>
                        <th style="width:130px;">Hasil</th>
                        <th style="width:160px;">Terakhir Dinilai</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                        <tr>
                            <td>{{ $data->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $row->nama_asesi }}</strong>
                                <div style="font-size:12px;color:#64748b;">{{ $row->email_asesi }}</div>
                            </td>
                            <td style="font-family:monospace;">{{ $row->asesi_nik }}</td>
                            <td>
                                <strong>{{ $row->nama_skema }}</strong>
                                <div style="font-size:12px;color:#64748b;">{{ $row->nomor_skema }}</div>
                            </td>
                            <td>{{ $row->nama_asesor ?? '-' }}</td>
                            <td>{{ $row->total_elemen }}</td>
                            <td>{{ number_format((float) $row->rata_rata, 2) }}</td>
                            <td>
                                @if((int) $row->total_k === (int) $row->total_elemen)
                                    <span class="badge kompeten"><i class="bi bi-check-circle"></i> Kompeten</span>
                                @else
                                    <span class="badge belum"><i class="bi bi-x-circle"></i> Belum Kompeten</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.nilai-asesor.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" class="btn-detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">{{ $data->links() }}</div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada data nilai asesor.</p>
            </div>
        @endif
    </div>
</div>
@endsection
