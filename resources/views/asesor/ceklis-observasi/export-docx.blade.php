<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.IA.01 - Ceklis Observasi Aktivitas Praktik</title>
    <style>
        @page { margin: 18px 16px; }
        body { font-family: DejaVu Sans, Calibri, sans-serif; font-size: 14.5px; color: #000; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #111; padding: 4px 5px; vertical-align: top; }
        .no-border { border: none !important; }
        .title { font-weight: 700; font-size: 16px; margin-bottom: 6px; }
        .small { font-size: 14.5px; }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .section-gap { margin-top: 0px; }
        .unit-header-table td {
            vertical-align: middle;
            padding: 5px 7px;
        }
        .unit-header-table {
            table-layout: fixed;
        }
        .unit-header-name {
            width: 22%;
            font-weight: 700;
            font-size: 14.5px;
            vertical-align: middle;
        }
        .unit-header-label {
            width: 18%;
            font-weight: 700;
            font-size: 14.5px;
            white-space: nowrap;
        }
        .unit-header-colon {
            width: 3%;
            text-align: center;
            padding-left: 0;
            padding-right: 0;
        }
        .unit-header-value {
            width: 57%;
            padding-right: 0;
        }
        .detail-table {
            table-layout: fixed;
        }
        .detail-table th {
            vertical-align: middle;
            text-align: center;
        }
        .detail-table tr:first-child th[rowspan="2"] {
            height: 54px;
            padding-top: 0;
            padding-bottom: 0;
            line-height: 1.2;
        }
        .check-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #111;
            box-sizing: border-box;
            line-height: 1;
            text-align: center;
            font-size: 14.5px;
            vertical-align: middle;
            padding: 0;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
        }
        .meta-table td { padding: 6px 8px; }
        .meta-table .left-header {
            width: 30%;
            vertical-align: middle;
            font-size: 14.5px;
            line-height: 1.35;
        }
        .meta-table .label-col {
            width: 12%;
            border-right: 1px solid #111;
            white-space: nowrap;
        }
        .meta-table .colon-col {
            width: 2%;
            border-left: 1px solid #111;
            border-right: 1px solid #111;
            text-align: center;
        }
        .meta-table .value-col {
            border-left: 1px solid #111;
        }
        .signature-box {
            border: 1px solid #111;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 4px;
            margin-bottom: 4px;
            overflow: hidden;
        }
        .signature-box img {
            max-height: 44px;
            max-width: 95%;
        }
    </style>
</head>
<body>
    <table style="margin-bottom:6px; table-layout:fixed;">
        <tr>
            <td class="no-border" style="width:56px; padding:0; vertical-align:middle;">
                @if(!empty($logoDataUri) || (!empty($logoPath) && file_exists($logoPath)))
                    <img src="{{ $logoDataUri ?? $logoPath }}" alt="Logo" width="44" height="44" style="width:44px; height:44px; object-fit:contain; display:block; margin:0 auto;">
                @endif
            </td>
            <td class="no-border" style="padding:0 0 0 6px; vertical-align:middle;">
                <div class="title" style="margin:0;">{{ $item->kode_form }} &nbsp;&nbsp; {{ $item->judul_form }}</div>
            </td>
        </tr>
    </table>

    @php
        $skemaKategori = trim((string) ($item->skema?->jenis_skema ?? ''));
        $skemaHeader = $skemaKategori !== ''
            ? 'Skema Sertifikasi (' . $skemaKategori . ')'
            : 'Skema Sertifikasi';
        $tanggalDisplay = $item->tanggal?->format('d-m-Y') ?? '-';
    @endphp

    <table class="meta-table">
        <tr>
            <td class="left-header" rowspan="2">{{ $skemaHeader }}</td>
            <td class="label-col">Judul</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->skema?->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Nomor</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->skema?->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">TUK</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->tuk ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesor</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->ttd_asesor_nama ?? $item->asesor?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesi</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->asesi?->nama ?? $item->asesi_nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Tanggal</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $tanggalDisplay }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td class="bold">PANDUAN BAGI ASESOR</td>
        </tr>
        <tr>
            <td>
                <div style="margin-bottom:4px;">- Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</div>
                <div style="margin-bottom:4px;">- Isi hasil observasi pada setiap kriteria unjuk kerja sesuai bukti yang ditemukan.</div>
                <div style="margin-bottom:4px;">- Beri tanda centang (v) pada kolom "YA" jika asesi mampu, atau pada kolom "Tidak" bila sebaliknya.</div>
                <div style="margin-bottom:4px;">- Penilaian lanjut diisi jika hasil belum dapat disimpulkan dan membutuhkan metode asesmen tambahan.</div>
                <div>- Isi kolom KUK sesuai unit kompetensi yang dinilai pada skema ini.</div>
            </td>
        </tr>
    </table>

    @forelse($detailsByUnit as $unitDetails)
        @php $unit = $unitDetails->first()?->unit; @endphp
        <table class="section-gap unit-header-table">
            <tr>
                <td class="unit-header-name" rowspan="2">Unit Kompetensi {{ $loop->iteration }}</td>
                <td class="unit-header-label">Kode Unit</td>
                <td class="unit-header-colon">:</td>
                <td class="unit-header-value">{{ $unit?->kode_unit ?? '-' }}</td>
            </tr>
            <tr>
                <td class="unit-header-label">Judul Unit</td>
                <td class="unit-header-colon">:</td>
                <td class="unit-header-value">{{ $unit?->judul_unit ?? '-' }}</td>
            </tr>
        </table>

        <table class="section-gap detail-table">
            <tr>
                <th rowspan="2" style="width:35px;">No.</th>
                <th rowspan="2" style="width:170px;">Elemen</th>
                <th rowspan="2" style="width:315px;">Kriteria Unjuk Kerja</th>
                <th colspan="2" style="width:120px;">Pencapaian</th>
                <th rowspan="2" style="width:120px;">Penilaian Lanjut</th>
            </tr>
            <tr>
                <th style="width:60px;">Ya</th>
                <th style="width:60px;">Tidak</th>
            </tr>
            @foreach($unitDetails as $idx => $detail)
                @php
                    $pencapaian = strtolower(trim((string) ($detail->pencapaian ?? '')));
                    $isYa = in_array($pencapaian, ['ya', 'y', '1', 'true', 'benar', 'kompeten'], true);
                    $isTidak = in_array($pencapaian, ['tidak', 't', '0', 'false', 'salah', 'belum kompeten'], true);
                @endphp
                <tr>
                    <td class="center">{{ $idx + 1 }}</td>
                    <td>{{ $detail->elemen?->nama_elemen ?? '-' }}</td>
                    <td>{{ $detail->kriteria?->deskripsi_kriteria ?? '-' }}</td>
                    <td class="center">{!! $isYa ? '☑' : '☐' !!}</td>
                    <td class="center">{!! $isTidak ? '☑' : '☐' !!}</span></td>
                    <td>{{ $detail->penilaian_lanjut ?: '-' }}</td>
                </tr>
            @endforeach
        </table>
    @empty
        <table class="section-gap">
            <tr>
                <td>Belum ada detail checklist.</td>
            </tr>
        </table>
    @endforelse

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
        $rekomendasi = strtolower(trim((string) ($item->rekomendasi ?? '')));
        $isKompeten = $rekomendasi === 'kompeten';
        $isBelumKompeten = $rekomendasi === 'belum_kompeten';
    @endphp

    <table class="section-gap" style="table-layout:fixed; width:100%;">
        <tr>
            <td rowspan="7" style="width:37%; vertical-align:top; padding:0;">
                <div style="padding:6px 8px 4px; font-weight:700; margin-left:8px;">Rekomendasi:</div>
                <table style="width:100%; table-layout:fixed; border-collapse:collapse; border:none; margin:0;">
                    <tr>
                        <td style="width:18px; padding:4px 0 0 8px; vertical-align:top; border:none; text-align:center;">
                            <span class="center">{!! $isKompeten ? '☑' : '☐' !!}</span>
                        </td>
                        <td style="padding:2px 8px 8px 2px; vertical-align:top; border:none;">
                            Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>KOMPETEN</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:18px; padding:6px 0 0 8px; vertical-align:top; border:none; text-align:center;">
                            <span class="center">{!! $isBelumKompeten ? '☑' : '☐' !!}</span>
                        </td>
                        <td style="padding:4px 8px 8px 2px; vertical-align:top; border:none;">
                            Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>BELUM KOMPETEN</strong>
                            <div style="margin-top:4px;">pada:</div>
                            <div style="margin-top:2px;">Kelompok Pekerjaan: {{ $belumKompetenKelompok->isNotEmpty() ? $belumKompetenKelompok->implode(', ') : '-' }}</div>
                            <div>Unit: {{ $belumKompetenUnit->isNotEmpty() ? $belumKompetenUnit->implode(', ') : '-' }}</div>
                            <div>Elemen: {{ $belumKompetenElemen->isNotEmpty() ? $belumKompetenElemen->implode(', ') : '-' }}</div>
                            <div>KUK: {{ $belumKompetenKuk->isNotEmpty() ? $belumKompetenKuk->implode(', ') : '-' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="3" style="padding:6px 8px; font-weight:700;">Asesi</td>
        </tr>
        <tr>
            <td style="width:42%; padding:8px 10px;">Nama</td>
            <td style="width:4%; padding:8px 0; text-align:center;">:</td>
            <td style="padding:8px 10px;">{{ $item->ttd_asesi_nama ?: ($item->asesi?->nama ?? '-') }}</td>
        </tr>
        <tr>
            <td style="padding:8px 10px;">Tanda tangan/ Tanggal</td>
            <td style="padding:8px 0; text-align:center;">:</td>
            <td style="padding:8px 10px;">
                <div style="width:96px; height:54px; overflow:hidden; display:flex; align-items:center; justify-content:center; margin:0 0 4px;">
                    @if($item->ttd_asesi_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
                <div>{{ $item->ttd_asesi_tanggal?->format('d-m-Y') ?: 'Tanggal Tanda Tangan' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:6px 8px; font-weight:700; border-top:1px solid #111;">Asesor</td>
        </tr>
        <tr>
            <td style="padding:8px 10px;">Nama</td>
            <td style="padding:8px 0; text-align:center;">:</td>
            <td style="padding:8px 10px;">{{ $item->ttd_asesor_nama ?: ($item->asesor?->nama ?? '-') }}</td>
        </tr>
        <tr>
            <td style="padding:8px 10px;">No. Reg</td>
            <td style="padding:8px 0; text-align:center;">:</td>
            <td style="padding:8px 10px;">{{ $item->ttd_asesor_no_reg ?: ($item->asesor?->no_met ?? '-') }}</td>
        </tr>
        <tr>
            <td style="padding:8px 10px;">Tanda tangan/ Tanggal</td>
            <td style="padding:8px 0; text-align:center;">:</td>
            <td style="padding:8px 10px;">
                <div style="width:96px; height:54px; overflow:hidden; display:flex; align-items:center; justify-content:center; margin:0 0 4px;">
                    @if($item->ttd_asesor_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
                <div>{{ $item->ttd_asesor_tanggal?->format('d-m-Y') ?: 'Tanggal Tanda Tangan' }}</div>
            </td>
        </tr>
    </table>

    @if($item->catatan_footer)
        <div style="margin-top: 8px; font-size: 14.5px; color: #333;">
            <em>{{ $item->catatan_footer }}</em>
        </div>
    @endif
</body>
</html>