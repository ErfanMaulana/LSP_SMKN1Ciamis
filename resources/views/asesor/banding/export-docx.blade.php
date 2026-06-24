<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.AK.04 - Banding Asesmen</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.958in 0.181in 0.194in 0.694in;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
        }

        td, th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .no-border {
            border: none !important;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .meta-table {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 8px;
        }

        .meta-table td {
            padding: 4px 8px;
        }

        .meta-table .label-col {
            width: 25%;
            font-weight: normal;
        }

        .meta-table .colon-col {
            width: 3%;
            text-align: center;
        }

        .meta-table .value-col {
            width: 72%;
        }

        .check-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .check-table th {
            font-weight: bold;
            background: #f3f4f6;
            text-align: left;
        }

        .check-table th.center-col,
        .check-table td.center-col {
            width: 60px;
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        .section-content {
            padding: 6px 8px;
            border: 1px solid #000;
            background: #fff;
            min-height: 50px;
            margin-bottom: 8px;
        }

        .sig-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .sig-table td {
            vertical-align: middle;
            padding: 3px 8px;
        }

        .sig-table .cs-label {
            width: 32.70%;
        }

        .sig-table .cs-colon {
            width: 1.37%;
            text-align: center;
        }

        .sig-table .cs-value {
            width: 65.93%;
        }

        .sig-outer-top {
            border-top: 2px solid #000 !important;
        }

        .sig-outer-left {
            border-left: 2px solid #000 !important;
        }

        .sig-outer-right {
            border-right: 2px solid #000 !important;
        }

        .sig-outer-bottom {
            border-bottom: 2px solid #000 !important;
        }

        .sig-inner {
            border: 1px solid #000;
        }

        .sig-header-td {
            font-weight: bold;
            border-top: 2px solid #000;
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 8px;
        }

        .sig-box {
            min-height: 52px;
            display: block;
        }

        .sig-box img {
            max-height: 52px;
            max-width: 140px;
            object-fit: contain;
            display: block;
        }

        .sig-date {
            font-size: 10pt;
            margin-top: 2px;
        }

        .sig-ttd-label {
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <table style="margin-bottom:12px; table-layout:fixed; width:100%;">
        <tr>
            <td class="no-border" style="width:56px; padding:0; vertical-align:middle;">
                @if(!empty($logoPath) && file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo" width="44" height="44" style="width:44px; height:44px; object-fit:contain; display:block; margin:0 auto;">
                @endif
            </td>
            <td class="no-border" style="padding:0 0 0 8px; vertical-align:middle;">
                <div style="font-family: Calibri, Arial, sans-serif; font-size: 12pt; font-weight: bold; text-transform: uppercase; margin:0; line-height:1.25;">
                    FR.AK.04. &nbsp;&nbsp; BANDING ASESMEN
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-family: Calibri, Arial, sans-serif; font-size: 11pt; margin-bottom: 8px;">
        <colgroup>
            <col style="width: auto;">
            <col style="width: 60px;">
            <col style="width: 60px;">
        </colgroup>
        <tbody>
            <!-- Nama Asesi -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 4px 6px; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 25%; padding: 0; border: none; font-weight: normal; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">Nama Asesi</td>
                            <td style="width: 3%; padding: 0; border: none; text-align: center; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">:</td>
                            <td style="width: 72%; padding: 0; border: none; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $asesi->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Nama Asesor -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 4px 6px; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 25%; padding: 0; border: none; font-weight: normal; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">Nama Asesor</td>
                            <td style="width: 3%; padding: 0; border: none; text-align: center; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">:</td>
                            <td style="width: 72%; padding: 0; border: none; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $asesor->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Tanggal Asesmen -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 4px 6px; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 25%; padding: 0; border: none; font-weight: normal; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">Tanggal Asesmen</td>
                            <td style="width: 3%; padding: 0; border: none; text-align: center; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">:</td>
                            <td style="width: 72%; padding: 0; border: none; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $pivot->tanggal_selesai ? \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Questionnaire Header -->
            <tr>
                <td style="border: 1px solid #000; padding: 4px 6px; font-weight: normal; background: #f3f4f6; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini</td>
                <td style="border: 1px solid #000; padding: 4px 6px; font-weight: normal; background: #f3f4f6; text-align: center; width: 60px; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">YA</td>
                <td style="border: 1px solid #000; padding: 4px 6px; font-weight: normal; background: #f3f4f6; text-align: center; width: 60px; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">TIDAK</td>
            </tr>
            <!-- Questionnaire Items -->
            @foreach($komponen as $item)
                @php
                    $jawabanItem = collect($existingJawaban)->get($item->id);
                    $val = optional($jawabanItem)->jawaban;
                @endphp
                <tr>
                    <td style="border: 1px solid #000; padding: 4px 6px; vertical-align: top; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $item->pernyataan }}</td>
                    <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: top; width: 60px; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $val === 'ya' ? '☑' : '☐' }}</td>
                    <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: top; width: 60px; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">{{ $val === 'tidak' ? '☑' : '☐' }}</td>
                </tr>
            @endforeach
            <!-- Skema Sertifikasi Info -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 6px 8px; vertical-align: top; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                    <div style="font-weight: normal; font-size: 11pt; margin-bottom: 4px;">Banding ini diajukan atas keputusan asesmen yang dibuat terhadap skema sertifikasi berikut:</div>
                    <div>Skema Sertifikasi: {{ $skema->nama_skema ?? '-' }}</div>
                    <div>No. Skema Sertifikasi: {{ $skema->nomor_skema ?? '-' }}</div>
                    <div>Keputusan Asesmen: {{ ($pivot->rekomendasi ?? '') === 'lanjut' ? 'Asesmen dapat dilanjutkan' : 'Asesmen tidak dapat dilanjutkan' }}</div>
                </td>
            </tr>
            <!-- Alasan Banding -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 6px 8px; vertical-align: top; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                    <div style="font-weight: normal; font-size: 11pt; margin-bottom: 4px;">Banding ini diajukan atas alasan sebagai berikut:</div>
                    <div style="min-height: 50px;">{{ $banding->alasan_banding ?? '-' }}</div>
                </td>
            </tr>
            <!-- Hak Mengajukan Banding -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 6px 8px; font-size: 10pt; font-style: normal; vertical-align: top; font-family: Calibri, Arial, sans-serif;">
                    Anda mempunyai hak mengajukan banding jika menilai Proses Asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                </td>
            </tr>
            <!-- Pemohon Banding (Asesi) Header -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; border-top: 2px solid #000; font-weight: normal; padding: 4px 8px; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">
                    Pemohon Banding (Asesi) :
                </td>
            </tr>
            <!-- Pemohon Banding Details & Signature -->
            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; border: none; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                        <colgroup>
                            <col style="width: 32.70%;">
                            <col style="width: 1.37%;">
                            <col style="width: 65.93%;">
                        </colgroup>
                        <tr>
                            <td style="padding: 4px 8px; border: none; border-bottom: 1px solid #000; font-weight: normal; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">Nama</td>
                            <td style="padding: 4px 2px; text-align: center; border: none; border-bottom: 1px solid #000; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">:</td>
                            <td style="padding: 4px 8px; border: none; border-bottom: 1px solid #000; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: top;">
                                {{ $banding->ttd_asesi_nama ?? $asesi->nama ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 8px; border: none; vertical-align: middle; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">Tanda tangan dan Tanggal</td>
                            <td style="padding: 6px 2px; text-align: center; border: none; vertical-align: middle; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">:</td>
                            <td style="padding: 6px 8px; border: none; vertical-align: middle; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                                <table style="width: 100%; border-collapse: collapse; border: none; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                                    <tr>
                                        @if(!empty($banding->ttd_asesi_file))
                                            <td style="padding: 0; border: none; width: 105px; vertical-align: middle;">
                                                <img src="{{ asset('storage/' . ltrim($banding->ttd_asesi_file, '/')) }}" alt="Ttd Asesi" width="96" height="54" style="width: 96px; height: 54px; object-fit: contain; display: block;">
                                            </td>
                                            <td style="padding: 0 0 0 10px; border: none; vertical-align: middle; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                                                Tanggal: {{ $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->format('d-m-Y') : '-' }}
                                            </td>
                                        @else
                                            <td style="padding: 0; border: none; vertical-align: middle; font-family: Calibri, Arial, sans-serif; font-size: 11pt;">
                                                Tanggal: {{ $banding->ttd_asesi_tanggal ? $banding->ttd_asesi_tanggal->format('d-m-Y') : '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
