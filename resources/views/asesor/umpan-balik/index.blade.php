@extends('asesor.layout')

@section('title', 'Umpan Balik Asesi')
@section('page-title', 'Umpan Balik Asesi')

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
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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

    .filter-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .filter-row {
        display: grid;
        gap: 10px;
        align-items: end;
    }

    .filter-row-top {
        grid-template-columns: minmax(0, 1fr);
    }

    .search-input-wrapper {
        flex: 1 1 360px;
        position: relative;
        min-width: 0;
    }

    .search-input {
        width: 100%;
        padding: 12px 44px 12px 42px;
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }

    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }
    td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: top;
    }
    tr:last-child td {
        border-bottom: none;
    }

    .btn-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #e0f2fe;
        color: #0c4a6e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover {
        background: #bae6fd;
        color: #0c4a6e;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 42px;
        display: block;
        margin-bottom: 10px;
    }
    .empty-state h3 {
        color: #475569;
        font-size: 16px;
        margin: 0 0 6px;
    }
    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    .pager {
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Umpan Balik Asesi</h2>
        <p>Daftar hasil umpan balik (FR.AK.03) yang telah dikirimkan oleh asesi Anda.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <small>Total Asesi Mengisi</small>
        <strong>{{ $stats['total_respon'] }}</strong>
    </div>
    <div class="stat-card">
        <small>Tanggapan Positif (Ya)</small>
        <strong style="color: #16a34a;">{{ $stats['persen_positif'] }}</strong>
    </div>
    <div class="stat-card">
        <small>Komponen Dievaluasi</small>
        <strong>{{ $stats['total_jawaban'] }}</strong>
    </div>
    <div class="stat-card">
        <small>Catatan / Masukan</small>
        <strong style="color: #d97706;">{{ $stats['total_catatan'] }}</strong>
    </div>
</div>

<form method="GET" action="{{ route('asesor.umpan-balik.index') }}" class="filter-form">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                value="{{ $search }}" 
                placeholder="Cari nama asesi, NIK, atau nama skema..."
                autocomplete="off"
            >
        </div>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Nomor Skema</th>
                    <th>Tanggal Dikirim</th>
                    <th style="width:100px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            <div style="font-weight:700; color:#0f172a;">{{ $item->asesi_nama }}</div>
                            <div style="font-size:11px; color:#64748b; font-family:monospace; margin-top:2px;">NIK: {{ $item->asesi_nik }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nama_skema }}</div>
                        </td>
                        <td>
                            <div style="font-family:monospace;">{{ $item->nomor_skema }}</div>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->submitted_at)->translatedFormat('d M Y H:i') }}
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('asesor.umpan-balik.show', [$item->asesi_nik, $item->skema_id]) }}" class="btn-review">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-chat-left-text"></i>
                                <h3>Belum Ada Umpan Balik</h3>
                                <p>Belum ada asesi Anda yang memberikan umpan balik.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($items, 'hasPages') && $items->hasPages())
        <div class="pager">{{ $items->links() }}</div>
    @endif
</div>
@endsection
