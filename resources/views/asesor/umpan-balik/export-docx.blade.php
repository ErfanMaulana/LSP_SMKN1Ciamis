<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>FR.AK.03 - Umpan Balik dan Catatan Asesmen</title>
    <style>
        @page {
            margin: 18px 16px;
        }

        body {
            font-family: DejaVu Sans, Calibri, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.25;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            font-size: 11pt;
        }

        .no-border {
            border: none !important;
        }

        .title {
            font-weight: 700;
            font-size: 12pt;
            margin-bottom: 4px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: 700;
        }

        .meta-table td {
            padding: 5px 8px;
        }

        .meta-table .left-header {
            width: 32%;
            vertical-align: middle;
            font-weight: bold;
        }

        .meta-table .label-col {
            width: 12%;
            border-right: 1px solid #000;
            white-space: nowrap;
            font-weight: bold;
        }

        .meta-table .colon-col {
            width: 2%;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            text-align: center;
        }

        .meta-table .value-col {
            border-left: 1px solid #000;
        }

        .komponen-table th {
            font-weight: bold;
            background-color: #ffffff;
        }

        .komponen-table td {
            padding: 8px 10px;
        }
    </style>
</head>

<body>
    <table style="margin-bottom:8px; table-layout:fixed;">
        <tr>
            <!-- <td class="no-border" style="width:56px; padding:0; vertical-align:middle;">
                @if(!empty($logoDataUri) || (!empty($logoPath) && file_exists($logoPath)))
                    <img src="{{ $logoDataUri ?? $logoPath }}" alt="Logo" width="44" height="44"
                        style="width:44px; height:44px; object-fit:contain; display:block; margin:0 auto;">
                @endif
            </td>
            <td class="no-border" style="padding:0 0 0 8px; vertical-align:middle;"> -->
            <td class="no-border" style="padding:0; vertical-align:middle;">
                <div class="title" style="margin:0; text-transform: uppercase;">FR.AK.03. UMPAN BALIK DAN CATATAN
                    ASESMEN</div>
            </td>
        </tr>
    </table>

    @php
        $tipeTuk = strtolower((string) ($jadwal?->tuk?->tipe_tuk ?? $ceklis?->tuk_tipe ?? $rekaman?->tipe_tuk ?? ''));
        $tukName = $jadwal?->tuk?->nama_tuk ?? $ceklis?->tuk ?? $rekaman?->tuk ?? '';

        $sewaktuLabel = $tipeTuk === 'sewaktu' ? '<b><u>Sewaktu</u></b>' : 'Sewaktu';
        $tempatKerjaLabel = $tipeTuk === 'tempat_kerja' ? '<b><u>Tempat Kerja</u></b>' : 'Tempat Kerja';
        $mandiriLabel = $tipeTuk === 'mandiri' ? '<b><u>Mandiri</u></b>' : 'Mandiri';

        $tukDisplay = $sewaktuLabel . '/' . $tempatKerjaLabel . '/' . $mandiriLabel . '*';
        if ($tukName) {
            $tukDisplay .= ' (' . $tukName . ')';
        }

        $tanggalMulai = $rekaman?->tanggal_mulai?->locale('id')->translatedFormat('d M Y') ?? $jadwal?->tanggal_mulai?->locale('id')->translatedFormat('d M Y') ?? $ceklis?->tanggal?->locale('id')->translatedFormat('d M Y') ?? '-';
        $tanggalSelesai = $rekaman?->tanggal_selesai?->locale('id')->translatedFormat('d M Y') ?? $jadwal?->tanggal_selesai?->locale('id')->translatedFormat('d M Y') ?? $ceklis?->tanggal?->locale('id')->translatedFormat('d M Y') ?? '-';
    @endphp

    <table class="meta-table">
        <tr>
            <td class="left-header" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td class="label-col">Judul</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $skema->nama_skema }}</td>
        </tr>
        <tr>
            <td class="label-col">Nomor</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $skema->nomor_skema }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">TUK</td>
            <td class="colon-col">:</td>
            <td class="value-col">{!! $tukDisplay !!}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesor</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $asesor->nama }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesi</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $asesi->nama }}</td>
        </tr>
        <tr>
            <td class="left-header" rowspan="2">Tanggal Asesmen</td>
            <td class="label-col">Mulai</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $tanggalMulai }}</td>
        </tr>
        <tr>
            <td class="label-col">Selesai</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $tanggalSelesai }}</td>
        </tr>
    </table>

    <div style="margin-top: 14px; margin-bottom: 8px; font-weight: bold; font-size: 11pt;">
        Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan):
    </div>

    <table class="komponen-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 58%; text-align: center; vertical-align: middle;">KOMPONEN</th>
                <th colspan="2" style="width: 16%; text-align: center; vertical-align: middle;">Hasil</th>
                <th rowspan="2" style="width: 26%; text-align: center; vertical-align: middle;">Catatan/Komentar Asesi
                </th>
            </tr>
            <tr>
                <th style="text-align: center; width: 8%; font-weight: bold; vertical-align: middle;">Ya</th>
                <th style="text-align: center; width: 8%; font-weight: bold; vertical-align: middle;">Tidak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komponenList as $index => $komponen)
                @php
                    $result = $results->get($komponen->id);
                    $jawaban = $result ? strtolower($result->jawaban) : null;
                    $catatan = $result ? $result->catatan : '';
                @endphp
                <tr>
                    <td style="text-align: left; vertical-align: top;">{{ $komponen->pernyataan }}</td>
                    <td style="text-align: center; vertical-align: middle; font-size: 14pt; padding: 2px;">
                        @if($jawaban === 'ya')
                            ☑
                        @else
                            ☐
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle; font-size: 14pt; padding: 2px;">
                        @if($jawaban === 'tidak')
                            ☑
                        @else
                            ☐
                        @endif
                    </td>
                    <td style="text-align: left; vertical-align: top;">{{ $catatan ?: '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: left; padding: 8px 10px; height: 70px; vertical-align: top;">
                    Catatan/komentar lainnya (apabila ada) :
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>