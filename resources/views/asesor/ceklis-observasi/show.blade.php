@extends('asesor.layout')

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

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .signature-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
    }

    .signature-frame {
        border-radius: 8px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .signature-frame img {
        max-width: 100%;
        max-height: 120px;
        object-fit: contain;
    }

    @media (max-width: 768px) {
        .meta-grid {
            grid-template-columns: 1fr;
        }

        .signature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $backTo = $backTo ?? '';
    $backUrl = ($backTo && str_starts_with($backTo, 'asesi:'))
        ? route('asesor.asesi.show', substr($backTo, 6))
        : route('asesor.ceklis-observasi.index');
    $backLabel = ($backTo && str_starts_with($backTo, 'asesi:')) ? 'Kembali ke Detail Asesi' : 'Kembali';
    $editUrl = route('asesor.ceklis-observasi.edit', $item->id) . ($backTo ? '?back_to=' . urlencode($backTo) : '');
    $rekamanCreateUrl = route('asesor.rekaman-asesmen-kompetensi.create', ['asesi_nik' => $item->asesi_nik, 'skema_id' => $item->skema_id]) . ($backTo ? '&back_to=' . urlencode($backTo) : '');
@endphp
<div class="top-actions">
    <h2>Detail Ceklis Observasi Aktivitas Praktik</h2>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ $backUrl }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ $backLabel }}
        </a>
        @if(empty($item->ttd_asesi_file))
            <button class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;" onclick="alert('Form FR.IA.01 belum dapat diexport karena asesi belum menandatangani ceklis observasi.')">
                <i class="bi bi-download"></i> Export FR.IA.01 (.doc)
            </button>
        @else
            <a href="{{ route('asesor.ceklis-observasi.export', $item->id) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-download"></i> Export FR.IA.01 (.doc)
            </a>
        @endif
        <a href="{{ $editUrl }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="{{ $rekamanCreateUrl }}" class="btn btn-primary">
            <i class="bi bi-journal-plus"></i> Buat Rekaman Asesmen
        </a>
    </div>
</div>

<div class="detail-card">
    @php
        $parseMultiValue = function ($raw) {
            $raw = trim((string) $raw);

            if ($raw === '') {
                return collect();
            }

            return collect(preg_split('/\s*(?:\||,|\r\n|\r|\n)\s*/', $raw))
                ->filter(fn ($item) => trim((string) $item) !== '')
                ->map(fn ($item) => trim((string) $item))
                ->values();
        };

        $belumKompetenKelompok = $parseMultiValue($item->belum_kompeten_kelompok_pekerjaan ?? '');
        $belumKompetenUnit = $parseMultiValue($item->belum_kompeten_unit ?? '');
        $belumKompetenElemen = $parseMultiValue($item->belum_kompeten_elemen ?? '');
        $belumKompetenKuk = $parseMultiValue($item->belum_kompeten_kuk ?? '');
    @endphp
    <div class="meta-grid">
        <div class="meta-item"><div class="label">Kode Form</div><div class="value">{{ $item->kode_form }}</div></div>
        <div class="meta-item"><div class="label">Judul Form</div><div class="value">{{ $item->judul_form }}</div></div>
        <div class="meta-item"><div class="label">Skema</div><div class="value">{{ $item->skema?->nama_skema }} ({{ $item->skema?->nomor_skema }})</div></div>
        <div class="meta-item"><div class="label">TUK / Tanggal</div><div class="value">{{ $item->tuk ?? '-' }} / {{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Asesi</div><div class="value">{{ $item->asesi?->nama ?? $item->asesi_nik }}</div></div>
        <div class="meta-item"><div class="label">Asesor</div><div class="value">{{ $item->asesor?->nama ?? $item->ttd_asesor_nama ?? '-' }}</div></div>
        <div class="meta-item"><div class="label">Rekomendasi</div><div class="value">{{ $item->rekomendasi === 'kompeten' ? 'KOMPETEN' : 'BELUM KOMPETEN' }}</div></div>
        <div class="meta-item"><div class="label">Belum Kompeten Pada</div><div class="value">Kelompok: {{ $belumKompetenKelompok->isNotEmpty() ? $belumKompetenKelompok->implode(', ') : '-' }}, Unit: {{ $belumKompetenUnit->isNotEmpty() ? $belumKompetenUnit->implode(', ') : '-' }}, Elemen: {{ $belumKompetenElemen->isNotEmpty() ? $belumKompetenElemen->implode(', ') : '-' }}, KUK: {{ $belumKompetenKuk->isNotEmpty() ? $belumKompetenKuk->implode(', ') : '-' }}</div></div>
    </div>
</div>

@forelse($detailsByUnit as $unitDetails)
    @php
        $unit = $unitDetails->first()?->unit;
        $elemenGroups = $unitDetails->groupBy('elemen_id');
        $totalKukInUnit = $unitDetails->count();
        $unitStandards = $unit?->standarIndustri ?? collect();
        $isFirstRowOfUnit = true;
    @endphp
    <div class="detail-card">
        <h3 class="unit-title">{{ $unit?->kode_unit }} - {{ $unit?->judul_unit }}</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width:45px; text-align:center;">No.</th>
                        <th rowspan="2" style="width:200px;">Elemen</th>
                        <th rowspan="2">Kriteria Unjuk Kerja</th>
                        <th rowspan="2" style="width:180px;">Standar Industri / Tempat Kerja</th>
                        <th colspan="2" style="width:100px; text-align:center;">Pencapaian</th>
                        <th rowspan="2" style="width:180px;">Penilaian Lanjut</th>
                    </tr>
                    <tr>
                        <th style="width:50px; text-align:center;">Ya</th>
                        <th style="width:50px; text-align:center;">Tidak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elemenGroups as $elId => $elemenDetails)
                        @php
                            $elCount = $elemenDetails->count();
                            $firstDetail = $elemenDetails->first();
                            $elemenNum = $loop->iteration;
                        @endphp
                        @foreach($elemenDetails as $kIdx => $detail)
                            @php
                                $pencapaian = strtolower(trim((string) ($detail->pencapaian ?? '')));
                                $isYa = in_array($pencapaian, ['ya', 'y', '1', 'true', 'benar', 'kompeten'], true);
                                $isTidak = in_array($pencapaian, ['tidak', 't', '0', 'false', 'salah', 'belum kompeten'], true);
                                $kukNum = $elemenNum . '.' . ($kIdx + 1);
                            @endphp
                            <tr>
                                @if($kIdx === 0)
                                    <td style="text-align:center; vertical-align:middle; font-weight:600;" rowspan="{{ $elCount }}">{{ $elemenNum }}</td>
                                    <td style="vertical-align:middle; font-weight:500;" rowspan="{{ $elCount }}">{{ $firstDetail->elemen?->nama_elemen ?? '-' }}</td>
                                @endif

                                <td><strong>{{ $kukNum }}</strong> {{ $detail->kriteria?->deskripsi_kriteria ?? '-' }}</td>

                                @if($isFirstRowOfUnit)
                                    <td rowspan="{{ $totalKukInUnit }}" style="vertical-align:middle; background:#fafafa;">
                                        @if($unitStandards->isNotEmpty())
                                            <ul style="margin:0; padding-left:16px; font-size:13px;">
                                                @foreach($unitStandards as $st)
                                                    <li style="margin-bottom:4px;">
                                                        <strong>{{ $st->nama_standar }}</strong>
                                                        @if($st->deskripsi_standar)
                                                            <div style="font-size:11px; color:#64748b;">{{ $st->deskripsi_standar }}</div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span style="color:#94a3b8; font-size:13px;">-</span>
                                        @endif
                                    </td>
                                    @php $isFirstRowOfUnit = false; @endphp
                                @endif

                                <td style="text-align:center; vertical-align:middle;">
                                    @if($isYa)
                                        <span style="display:inline-block; width:22px; height:22px; line-height:22px; border-radius:4px; background:#dcfce7; color:#15803d; font-weight:700;">✓</span>
                                    @else
                                        <span style="color:#cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td style="text-align:center; vertical-align:middle;">
                                    @if($isTidak)
                                        <span style="display:inline-block; width:22px; height:22px; line-height:22px; border-radius:4px; background:#fee2e2; color:#b91c1c; font-weight:700;">✗</span>
                                    @else
                                        <span style="color:#cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td style="font-size:13px;">{{ $detail->penilaian_lanjut ?: '-' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="detail-card">Belum ada detail checklist.</div>
@endforelse

<div class="detail-card">
    <h3 style="margin:0 0 12px;font-size:16px;">Tanda Tangan</h3>

    <div class="signature-grid">
        <div class="signature-box">
            <h4 style="margin:0 0 8px;font-size:14px;">Tanda Tangan Asesor</h4>
            <div class="signature-frame">
                @if($item->ttd_asesor_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor">
                @else
                    <span style="color:#94a3b8;font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>
            <div style="font-size:13px;color:#334155;">
                <strong>{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</strong><br>
                {{ $item->ttd_asesor_tanggal?->translatedFormat('d F Y') ?: 'Tanggal Tanda Tangan' }}
            </div>
        </div>

        <div class="signature-box">
            <h4 style="margin:0 0 8px;font-size:14px;">Tanda Tangan Asesi</h4>
            <div class="signature-frame">
                @if($item->ttd_asesi_file)
                    <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi">
                @else
                    <span style="color:#94a3b8;font-size:13px;">Belum ditandatangani</span>
                @endif
            </div>
            <div style="font-size:13px;color:#334155;">
                <strong>{{ $item->ttd_asesi_nama ?: ($item->asesi?->nama ?? 'Nama Asesi') }}</strong><br>
                {{ $item->ttd_asesi_tanggal?->translatedFormat('d F Y') ?: 'Tanggal Tanda Tangan' }}
            </div>
        </div>
    </div>
</div>
@endsection
