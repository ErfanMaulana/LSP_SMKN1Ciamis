@extends('asesor.layout')

@section('title', 'Detail Kelompok')
@section('page-title', 'Detail Kelompok')

@section('styles')
<style>
    .detail-wrap {
        display: grid;
        gap: 16px;
    }

    .top-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .top-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #1e3a5f;
    }

    .top-sub {
        margin-top: 6px;
        font-size: 13px;
        color: #64748b;
    }

    .meta-grid {
        margin-top: 14px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
    }

    .meta-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        background: #f8fafc;
    }

    .meta-label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .meta-value {
        margin-top: 3px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .table-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .table-head h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .small-note {
        font-size: 12px;
        color: #64748b;
    }

    .asesi-table {
        width: 100%;
        border-collapse: collapse;
    }

    .asesi-table th,
    .asesi-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        text-align: left;
        vertical-align: top;
    }

    .asesi-table th {
        background: #ffffff;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: .5px;
    }

    .asesi-table tr:last-child td {
        border-bottom: none;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #334155;
        text-decoration: none;
        background: #ffffff;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="detail-wrap">
    <div class="actions">
        <a href="{{ route('asesor.kelompok.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Kelompok
        </a>
    </div>

    <div class="top-card">
        <h2 class="top-title">{{ $kelompok->nama_kelompok }}</h2>
        <div class="top-sub">Detail kelompok yang ditugaskan ke asesor Anda.</div>

        <div class="meta-grid">
            <div class="meta-box">
                <div class="meta-label">Skema</div>
                <div class="meta-value">{{ $kelompok->skema?->nama_skema ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Total Asesi</div>
                <div class="meta-value">{{ $kelompok->asesis->count() }} Peserta</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">ID Kelompok</div>
                <div class="meta-value">#{{ $kelompok->id }}</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-head">
            <h3>Daftar Asesi</h3>
            <div class="small-note">Kelompok: {{ $kelompok->nama_kelompok }}</div>
        </div>

        <table class="asesi-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompok->asesis as $asesi)
                    <tr>
                        <td>{{ $asesi->nama }}</td>
                        <td>{{ $asesi->NIK }}</td>
                        <td>{{ $asesi->jurusan?->nama_jurusan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#64748b;">Belum ada asesi di kelompok ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
