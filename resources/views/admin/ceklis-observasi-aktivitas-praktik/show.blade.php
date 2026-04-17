@extends('admin.layout')

@section('title', 'Detail Ceklis Observasi Aktivitas Praktik')
@section('page-title', 'Detail Ceklis Observasi Aktivitas Praktik')

@section('content')
<style>
    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin-bottom: 16px;
    }

    .top-actions {
        display: flex;
        gap: 10px;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .top-actions h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .meta-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
    }

    .meta-item .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .meta-item .value {
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
    }

    .table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 860px;
    }

    th, td {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        font-size: 13px;
        vertical-align: top;
    }

    th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        text-align: center;
    }

    .unit-title {
        margin: 0 0 10px;
        font-size: 15px;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .meta-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="top-actions">
    <h2>Detail Ceklis Observasi Aktivitas Praktik</h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        @if(Auth::guard('admin')->user()->hasPermission('ceklis-observasi-aktivitas-praktik.edit'))
            <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.edit', $item->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        @endif
    </div>
</div>

<div class="detail-card">
    <div class="meta-grid">
        <div class="meta-item"><div class="label">Kode Form</div><div class="value">{{ $item->kode_form }}</div></div>
        <div class="meta-item"><div class="label">Judul Form</div><div class="value">{{ $item->judul_form }}</div></div>
        <div class="meta-item"><div class="label">Skema</div><div class="value">{{ $item->skema?->nama_skema }} ({{ $item->skema?->nomor_skema }})</div></div>
        <div class="meta-item"><div class="label">TUK / Tanggal</div><div class="value">{{ $item->tuk ?? '-' }} / {{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Asesi</div><div class="value">{{ $item->asesi?->nama ?? $item->asesi_nik }}</div></div>
        <div class="meta-item"><div class="label">Asesor</div><div class="value">{{ $item->asesor?->nama ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Rekomendasi</div><div class="value">{{ $item->rekomendasi === 'kompeten' ? 'KOMPETEN' : 'BELUM KOMPETEN' }}</div></div>
        <div class="meta-item"><div class="label">Belum Kompeten Pada</div><div class="value">Kelompok: {{ $item->belum_kompeten_kelompok_pekerjaan ?? '-' }}, Unit: {{ $item->belum_kompeten_unit ?? '-' }}, Elemen: {{ $item->belum_kompeten_elemen ?? '-' }}, KUK: {{ $item->belum_kompeten_kuk ?? '-' }}</div></div>
    </div>
</div>

@forelse($detailsByUnit as $unitDetails)
    @php $unit = $unitDetails->first()?->unit; @endphp
    <div class="detail-card">
        <h3 class="unit-title">{{ $unit?->kode_unit }} - {{ $unit?->judul_unit }}</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th style="width:220px;">Elemen</th>
                        <th>Kriteria Unjuk Kerja</th>
                        <th style="width:110px;">Pencapaian</th>
                        <th style="width:220px;">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unitDetails as $idx => $detail)
                        <tr>
                            <td style="text-align:center;">{{ $idx + 1 }}</td>
                            <td>{{ $detail->elemen?->nama_elemen ?? '-' }}</td>
                            <td>{{ $detail->kriteria?->deskripsi_kriteria ?? '-' }}</td>
                            <td style="text-align:center;">{{ strtoupper($detail->pencapaian ?? '-') }}</td>
                            <td>{{ $detail->penilaian_lanjut ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="detail-card">Belum ada detail checklist.</div>
@endforelse
@endsection
