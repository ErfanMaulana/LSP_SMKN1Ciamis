@extends('asesor.layout')

@section('title', 'Detail Rekaman Asesmen Kompetensi')
@section('page-title', 'Detail Rekaman Asesmen Kompetensi')

@section('content')
<style>
    .detail-card { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.08); padding:20px; margin-bottom:16px; }
    .top-actions { display:flex; gap:10px; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:20px; }
    .top-actions h2 { margin:0; font-size:22px; font-weight:700; color:#0f172a; }

    .btn { border:none; border-radius:8px; padding:10px 14px; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-secondary { background:#64748b; color:#fff; }

    .meta-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .meta-item { border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; }
    .meta-item .label { font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#64748b; margin-bottom:6px; }
    .meta-item .value { font-size:14px; color:#0f172a; font-weight:600; }

    .table-wrap { overflow-x:auto; border:1px solid #e2e8f0; border-radius:8px; }
    table { width:100%; border-collapse:collapse; min-width:1000px; }
    th, td { border:1px solid #e2e8f0; padding:8px 10px; font-size:13px; vertical-align:middle; text-align:center; }
    th { background:#f8fafc; color:#334155; font-weight:700; }
    td:first-child, td:nth-child(2) { text-align:left; }

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
    .signature-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        background: #f8fafc;
        text-align: center;
    }
    .signature-frame {
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        width: 150px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        margin: 0 auto 12px;
        overflow: hidden;
    }
    .signature-frame img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    @media (max-width:768px) { 
        .meta-grid { grid-template-columns:1fr; } 
        .signature-grid { grid-template-columns:1fr; }
    }
</style>

<div class="top-actions">
    <h2>Detail Rekaman Asesmen Kompetensi</h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.edit', $item->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
        @if(empty($item->ttd_asesi_file) || empty($item->ttd_asesor_file))
            <button class="btn" style="background:#cbd5e1; color:#94a3b8; cursor:not-allowed;" onclick="alert('Form belum ditandatangani secara lengkap oleh asesi dan asesor.')" title="Belum ditandatangani lengkap">
                <i class="bi bi-download"></i> Export FR.AK.02 (.doc)
            </button>
        @else
            <a href="{{ route('asesor.rekaman-asesmen-kompetensi.export', $item->id) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-download"></i> Export FR.AK.02 (.doc)
            </a>
        @endif
    </div>
</div>

<div class="detail-card">
    <div class="meta-grid">
        <div class="meta-item"><div class="label">Kode Form</div><div class="value">{{ $item->kode_form }}</div></div>
        <div class="meta-item"><div class="label">Judul Form</div><div class="value">{{ $item->judul_form }}</div></div>
        <div class="meta-item"><div class="label">Skema</div><div class="value">{{ $item->skema?->nama_skema }} ({{ $item->skema?->nomor_skema }})</div></div>
        <div class="meta-item"><div class="label">TUK</div><div class="value">{{ $item->tuk ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Nama Asesi</div><div class="value">{{ $item->asesi?->nama ?? $item->asesi_nik }}</div></div>
        <div class="meta-item"><div class="label">Tanggal Asesmen</div><div class="value">Mulai: {{ $item->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }} | Selesai: {{ $item->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Rekomendasi</div><div class="value">{{ $item->rekomendasi === 'kompeten' ? 'KOMPETEN' : 'BELUM KOMPETEN' }}</div></div>
        <div class="meta-item" style="grid-column:1 / -1;"><div class="label">Tindak Lanjut</div><div class="value" style="font-weight:500;">{{ $item->tindak_lanjut ?: '-' }}</div></div>
        <div class="meta-item" style="grid-column:1 / -1;"><div class="label">Komentar atau Observasi Asesor</div><div class="value" style="font-weight:500;">{{ $item->komentar_observasi ?: '-' }}</div></div>
    </div>
</div>

<div class="detail-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th style="min-width:280px;">Unit Kompetensi</th>
                    <th>Observasi Demonstrasi</th>
                    <th>Portofolio</th>
                    <th>Pernyataan Pihak Ketiga</th>
                    <th>Pertanyaan Lisan</th>
                    <th>Pertanyaan Tertulis</th>
                    <th>Proyek Kerja</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $idx => $detail)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $detail->unit?->judul_unit ?? '-' }}</td>
                        <td>{{ $detail->observasi_demonstrasi ? 'Ya' : '' }}</td>
                        <td>{{ $detail->portofolio ? 'Ya' : '' }}</td>
                        <td>{{ $detail->pernyataan_pihak_ketiga ? 'Ya' : '' }}</td>
                        <td>{{ $detail->pertanyaan_lisan ? 'Ya' : '' }}</td>
                        <td>{{ $detail->pertanyaan_tertulis ? 'Ya' : '' }}</td>
                        <td>{{ $detail->proyek_kerja ? 'Ya' : '' }}</td>
                        <td>{{ $detail->lainnya ? 'Ya' : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9">Belum ada detail unit kompetensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="detail-card">
    <h3 style="margin:0 0 16px; font-size:16px; font-weight:700; color:#0f172a;">Tanda Tangan</h3>
    <div class="signature-grid">
        <div class="signature-box">
            <h4 style="margin:0 0 8px; font-size:14px; font-weight:600; color:#475569;">Tanda Tangan Asesor</h4>
            <div class="signature-frame">
                @if($item->ttd_asesor_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Ttd Asesor">
                @else
                    <span style="color:#94a3b8; font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>
            <div style="font-size:13px; color:#334155;">
                <strong>{{ $item->ttd_asesor_nama ?: ($item->asesor?->nama ?? '-') }}</strong><br>
                <span style="color:#64748b;">No. Reg: {{ $item->ttd_asesor_no_reg ?: ($item->asesor?->no_met ?? '-') }}</span><br>
                {{ $item->ttd_asesor_tanggal?->translatedFormat('d F Y') ?: '-' }}
            </div>
        </div>

        <div class="signature-box">
            <h4 style="margin:0 0 8px; font-size:14px; font-weight:600; color:#475569;">Tanda Tangan Asesi</h4>
            <div class="signature-frame">
                @if($item->ttd_asesi_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Ttd Asesi">
                @else
                    <span style="color:#94a3b8; font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>
            <div style="font-size:13px; color:#334155;">
                <strong>{{ $item->ttd_asesi_nama ?: ($item->asesi?->nama ?? '-') }}</strong><br>
                <span style="color:#64748b;">NIK: {{ $item->asesi_nik }}</span><br>
                {{ $item->ttd_asesi_tanggal?->translatedFormat('d F Y') ?: '-' }}
            </div>
        </div>
    </div>
</div>
@endsection
