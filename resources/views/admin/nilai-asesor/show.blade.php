@extends('admin.layout')

@section('title', 'Detail Nilai Asesor')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        margin: 0;
        font-size: 22px;
        color: #0f172a;
    }

    .meta {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        text-decoration: none;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        background: #fff;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
    }

    .summary-label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
    }

    .summary-value {
        margin-top: 5px;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
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
        padding: 12px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 12px 14px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    tr:hover td {
        background: #f8fafc;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge.k {
        background: #d1fae5;
        color: #065f46;
    }

    .badge.bk {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty {
        text-align: center;
        padding: 50px 16px;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2><i class="bi bi-list-ul" style="color:#0061a5"></i> Detail Nilai Asesor</h2>
        <div class="meta">
            <strong>{{ $asesi->nama }}</strong> ({{ $asesi->NIK }})
            - {{ $skema->nama_skema }}
        </div>
    </div>
    <a href="{{ route('admin.nilai-asesor.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-label">Total Elemen</div>
        <div class="summary-value">{{ $summary->total_elemen }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-label">Elemen Kompeten (K)</div>
        <div class="summary-value">{{ $summary->total_k }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-label">Rata-rata Nilai</div>
        <div class="summary-value">{{ number_format((float) $summary->rata_rata, 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($rows->count())
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Unit</th>
                        <th>Elemen</th>
                        <th style="width:100px;">Nilai</th>
                        <th style="width:100px;">Status</th>
                        <th style="width:180px;">Asesor</th>
                        <th style="width:160px;">Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $row->kode_unit }}</strong>
                                <div style="font-size:12px;color:#64748b;">{{ $row->judul_unit }}</div>
                            </td>
                            <td>{{ $row->nama_elemen }}</td>
                            <td>{{ (int) $row->nilai }}</td>
                            <td>
                                @if($row->status === 'K')
                                    <span class="badge k"><i class="bi bi-check-circle"></i> K</span>
                                @else
                                    <span class="badge bk"><i class="bi bi-x-circle"></i> BK</span>
                                @endif
                            </td>
                            <td>{{ $row->nama_asesor ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">Data nilai per elemen belum tersedia.</div>
        @endif
    </div>
</div>
@endsection
