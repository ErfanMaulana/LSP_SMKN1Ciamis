<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>FR.AK.02 - Rekaman Asesmen Kompetensi</title>
    <style>
        /* A4: 11910×16850 twips. Margins: top=1380, right=260, bottom=280, left=1000 twips
           1 twip = 1/1440 inch. Converting:
           top    = 1380/1440 = 0.958in
           right  = 260/1440  = 0.181in
           bottom = 280/1440  = 0.194in
           left   = 1000/1440 = 0.694in  */
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

        /* ───────────────────────────────
           HEADER: Logo + Title
        ─────────────────────────────── */
        .header-wrap {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .header-logo-cell {
            display: table-cell;
            width: 52px;
            vertical-align: middle;
            padding: 0;
        }

        .header-logo-cell img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            display: block;
        }

        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 8px;
        }

        .header-title {
            font-family: Calibri, Arial, sans-serif;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.25;
        }

        /* ───────────────────────────────
           ALL TABLES — base reset
        ─────────────────────────────── */
        table {
            border-collapse: collapse;
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
        }

        /* ───────────────────────────────
           META TABLE
           gridCol: 2410 / 1065 / 283 / 6592 twips  (total 10350)
           As % of table width:
             col1 = 23.29%  col2 = 10.29%  col3 = 2.73%  col4 = 63.69%
           colspan-2 (col1+col2) = 33.57%
        ─────────────────────────────── */
        .meta-table {
            width: 100%;
            table-layout: fixed;
            border: 1px solid #000;
            /* outer thick border (sz=12) */
            margin-bottom: 5px;
        }

        .meta-table td {
            border: 1px solid #000;
            padding: 2px 6px 2px 6px;
            vertical-align: middle;
            font-size: 11pt;
        }

        .meta-table .c1 {
            width: 23.29%;
            padding-left: 9px;
        }

        .meta-table .c2 {
            width: 10.29%;
            padding-left: 10px;
        }

        .meta-table .c3 {
            width: 2.73%;
            text-align: center;
        }

        .meta-table .c4 {
            width: 63.69%;
            padding-left: 5px;
        }

        .meta-table .c12 {
            width: 33.57%;
            padding-left: 9px;
        }

        /* colspan=2 */

        /* ───────────────────────────────
           INSTRUCTION PARAGRAPH
        ─────────────────────────────── */
        .instr {
            font-size: 11pt;
            margin: 3px 0 3px 0;
            padding: 0;
            line-height: 1.3;
        }

        /* ───────────────────────────────
           CHECKLIST TABLE
           gridCol: 4849/708/708/1280/708/708/712/712  (total 10385)
           As % of table width:
             unit=46.69%  obs=6.82%  port=6.82%  pihak=12.33%
             lisan=6.82%  tulis=6.82%  proyek=6.86%  lain=6.86%

           Header row height = 1540 twips = 1540/1440 in ≈ 108px
           Text direction: btLr (bottom-to-left-right = rotate CCW = 90°)
           → CSS: writing-mode:vertical-lr + rotate(180deg)  gives bottom-to-top
              actually btLr in Word = text reads bottom upward, so we use:
              writing-mode: vertical-rl; transform: rotate(180deg);
              which makes text go from bottom to top ✓
        ─────────────────────────────── */
        .ck-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .ck-table th,
        .ck-table td {
            border: 1px solid #000;
            font-size: 11pt;
            font-family: Calibri, Arial, sans-serif;
        }

        /* Column widths */
        .ck-table .cw-unit {
            width: 46.69%;
        }

        .ck-table .cw-obs {
            width: 6.82%;
        }

        .ck-table .cw-port {
            width: 6.82%;
        }

        .ck-table .cw-pihak {
            width: 12.33%;
        }

        .ck-table .cw-lisan {
            width: 6.82%;
        }

        .ck-table .cw-tulis {
            width: 6.82%;
        }

        .ck-table .cw-proyek {
            width: 6.86%;
        }

        .ck-table .cw-lain {
            width: 6.86%;
        }

        /* Header row: height 108px (1540 twips), vertical text */
        .ck-table thead tr {
            height: 108px;
        }

        .ck-table thead th {
            vertical-align: bottom;
            text-align: center;
            font-weight: bold;
            padding: 4px 0;
            background: #fff;
        }

        /* "Unit Kompetensi" — horizontal, centered, bottom-aligned, bold */
        .th-unit {
            vertical-align: bottom !important;
            text-align: center !important;
            padding: 4px 8px !important;
            font-size: 11pt;
            font-weight: bold;
        }

        /* All other headers: vertical text bottom-to-top (btLr = Word's bottom-left to right)
           In CSS: writing-mode vertical + rotate to make it read upward */
        .th-vert {
            padding: 2px 0 !important;
            vertical-align: bottom !important;
        }

        .th-vert-inner {
            display: block;
            writing-mode: vertical-rl;
            -webkit-writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            font-size: 9.5pt;
            font-weight: bold;
            white-space: normal;
            word-break: keep-all;
            text-align: left;
            /* ensures text aligns to "bottom" of cell visually (= start of rotated text) */
            margin: 0 auto;
            padding: 4px 3px 6px 3px;
        }

        /* Data rows */
        .ck-table tbody td {
            vertical-align: middle;
            text-align: center;
            padding: 3px 2px;
            font-size: 11pt;
            height: 24px;
            /* 340 twips ≈ 24px */
        }

        .ck-table tbody td.td-unit {
            text-align: left;
            padding: 4px 8px 4px 9px;
        }

        /* Bold row labels */
        .ck-table tbody td.td-label {
            text-align: left;
            font-weight: bold;
            padding: 5px 8px 5px 9px;
            vertical-align: top;
        }

        .td-label-sub {
            font-weight: normal;
            font-size: 9.5pt;
            color: #333;
            display: block;
            margin-top: 2px;
        }

        /* Tindak lanjut / Komentar value cell */
        .ck-table tbody td.td-value {
            text-align: left;
            padding: 5px 8px;
            vertical-align: top;
        }

        /* Rekomendasi checkbox */
        .cb-glyph {
            font-family: "MS Gothic", "Segoe UI Symbol", "Arial Unicode MS", sans-serif;
            font-size: 13pt;
            vertical-align: middle;
        }

        .cb-label {
            font-weight: bold;
            font-size: 11pt;
        }

        /* ───────────────────────────────
           SIGNATURE TABLES
           gridCol: 3368 / 141 / 6789 twips  (total 10298)
           As %: 32.70% / 1.37% / 65.93%
           Outer border sz=12 (thick), inner sz=4 (thin)
        ─────────────────────────────── */
        .sig-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .sig-table td {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            vertical-align: middle;
            padding: 3px 8px 3px 9px;
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

        /* outer thick borders */
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

        /* inner thin borders */
        .sig-inner {
            border: 1px solid #000;
        }

        /* Signature row: header spans all 3 cols */
        .sig-header-td {
            font-weight: bold;
            border-top: 2px solid #000;
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 9px;
        }

        /* Signature box area */
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

        /* "Tanda tangan / dan Tanggal" spans two text lines */
        .sig-ttd-label {
            line-height: 1.5;
        }

        /* ───────────────────────────────
           LAMPIRAN
        ─────────────────────────────── */
        .lampiran-title {
            font-size: 11pt;
            font-weight: normal;
            margin: 6px 0 2px 0;
            padding: 0;
        }

        ol.lampiran-list {
            margin: 0 0 0 18px;
            padding: 0;
            list-style-type: decimal;
            font-size: 11pt;
        }

        ol.lampiran-list li {
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        s {
            text-decoration: line-through;
        }
    </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════════════
     HEADER: Logo + Form Title
═══════════════════════════════════════════════════ -->
    <!-- <table style="width:100%; border-collapse:collapse; margin-bottom:6px; border:none; table-layout:fixed;">
        <tr>
            <td style="width:52px; padding:0; vertical-align:middle; border:none;">
                @if(!empty($logoDataUri) || (!empty($logoPath) && file_exists($logoPath)))
                    <img src="{{ $logoDataUri ?? $logoPath }}" alt="Logo" width="44" height="44" style="width:44px; height:44px; object-fit:contain; display:block;">
                @endif
            </td>
            <td style="padding:0 0 0 8px; vertical-align:middle; border:none;"> -->
            <td class="no-border" style="padding:0; vertical-align:middle;">
                <div class="header-title" style="margin:0;">{{ $item->kode_form }} {{ $item->judul_form }}</div>
            </td>
        </tr>
    </table>

    @php
        $skemaKategori = trim((string) ($item->skema?->jenis_skema ?? $item->kategori_skema ?? ''));
    @endphp

    <!-- ═══════════════════════════════════════════════════
     META TABLE
     gridCol: 2410 / 1065 / 283 / 6592 twips
═══════════════════════════════════════════════════ -->
    <table class="meta-table">
        <colgroup>
            <col class="c1">
            <col class="c2">
            <col class="c3">
            <col class="c4">
        </colgroup>
        <!-- Row 1: Skema Sertifikasi — Judul (rowspan 2) -->
        <tr>
            <td class="c1" rowspan="2" style="vertical-align:middle; line-height:1.3;">
                Skema Sertifikasi<br>
                @if($skemaKategori !== '')
                    ({{ $skemaKategori }})
                @else
                    (<s>KKNI</s>/Okupasi/<s>Klaster</s>)
                @endif
            </td>
            <td class="c2">Judul</td>
            <td class="c3">:</td>
            <td class="c4">{{ $item->skema?->nama_skema ?? '' }}</td>
        </tr>
        <!-- Row 2: Nomor -->
        <tr>
            <td class="c2">Nomor</td>
            <td class="c3">:</td>
            <td class="c4"> {{ $item->skema?->nomor_skema ?? '' }}</td>
        </tr>
        <!-- Row 3: TUK (colspan 1+2) -->
        <tr>
            <td class="c12" colspan="2">TUK</td>
            <td class="c3">:</td>
            <td class="c4">
                @if($item->tipe_tuk)
                    {{ $item->tipe_tuk }}
                @else
                    Sewaktu/<s>Tempat Kerja/Mandiri</s>*
                @endif
            </td>
        </tr>
        <!-- Row 4: Nama Asesor -->
        <tr>
            <td class="c12" colspan="2">Nama Asesor</td>
            <td class="c3">:</td>
            <td class="c4">{{ $ceklis->ttd_asesor_nama ?? $item->asesor?->nama ?? '' }}</td>
        </tr>
        <!-- Row 5: Nama Asesi -->
        <tr>
            <td class="c12" colspan="2">Nama Asesi</td>
            <td class="c3">:</td>
            <td class="c4">{{ $ceklis->ttd_asesi_nama ?? $item->asesi?->nama ?? '' }}</td>
        </tr>
        <!-- Row 6: Tanggal Asesmen — Mulai (rowspan 2) -->
        <tr>
            <td class="c1" rowspan="2" style="vertical-align:middle;">Tanggal Asesmen</td>
            <td class="c2">Mulai</td>
            <td class="c3">:</td>
            <td class="c4">{{ $item->tanggal_mulai?->format('d-m-Y') ?? '' }}</td>
        </tr>
        <!-- Row 7: Selesai -->
        <tr>
            <td class="c2">Selesai</td>
            <td class="c3">:</td>
            <td class="c4">{{ $item->tanggal_selesai?->format('d-m-Y') ?? '' }}</td>
        </tr>
    </table>

    <!-- Instruction -->
    <p class="instr">Beri tanda centang (✔) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit
        Kompetensi.</p>

    <!-- ONE UNIFIED TABLE: checklist + rekomendasi + tindak lanjut + komentar + asesi + asesor -->
    <table style="width:100%; border-collapse:collapse; table-layout:fixed; border:1px solid #000; margin:0; padding:0;">
        <colgroup>
            <col style="width:46.69%">
            <col style="width:6.82%">
            <col style="width:6.82%">
            <col style="width:12.33%">
            <col style="width:6.82%">
            <col style="width:6.82%">
            <col style="width:6.86%">
            <col style="width:6.86%">
        </colgroup>
        <!-- ── HEADER ROW (in tbody so it does NOT repeat on page breaks) ── -->
        <tbody>
            <tr style="height:108px;">
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; font-size:11pt; font-weight:bold; padding:4px 8px; background:#fff;">Unit Kompetensi</td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Observasi Demonstrasi</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Portofolio</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Pernyataan Pihak Ketiga Pertanyaan Wawancara</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Pertanyaan Lisan</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Pertanyaan Tertulis</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Proyek Kerja</span>
                </td>
                <td style="border:1px solid #000; vertical-align:bottom; text-align:center; padding:2px 0; background:#fff;">
                    <span style="display:block; writing-mode:vertical-rl; transform:rotate(180deg); font-size:9.5pt; font-weight:bold; white-space:normal; word-break:keep-all; text-align:left; margin:0 auto; padding:4px 3px 6px 3px;">Lainnya</span>
                </td>
            </tr>

            <!-- ── DETAIL ROWS ── -->
            @forelse($details as $detail)
                <tr>
                    <td style="border:1px solid #000; text-align:left; padding:4px 8px 4px 9px; vertical-align:middle; font-size:11pt; height:24px;">
                        @if($detail->unit?->kode_unit)
                            <strong>{{ $detail->unit->kode_unit }}</strong><br>
                        @endif
                        {{ $detail->unit?->judul_unit ?? '-' }}
                    </td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->observasi_demonstrasi ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->portofolio ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->pernyataan_pihak_ketiga ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->pertanyaan_lisan ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->pertanyaan_tertulis ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->proyek_kerja ? '✔' : '' !!}</td>
                    <td style="border:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">{!! $detail->lainnya ? '✔' : '' !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="border:1px solid #000; text-align:center; padding:6px; font-size:11pt;">Tidak ada unit kompetensi.</td>
                </tr>
            @endforelse

            <!-- ── REKOMENDASI ── -->
            @php
                $isKompeten = $item->rekomendasi === 'kompeten';
                $isBelumKompeten = $item->rekomendasi === 'belum_kompeten';
            @endphp
            <tr>
                <td style="border:1px solid #000; text-align:left; font-weight:normal; padding:5px 8px 5px 9px; vertical-align:middle; font-size:11pt;">Rekomendasi hasil asesmen</td>
                <td colspan="7" style="border:1px solid #000; text-align:left; padding:5px 8px; vertical-align:middle; font-size:11pt;">
                    <span style="font-size:11pt; vertical-align:middle;">{!! $isKompeten ? '☑' : '☐' !!}</span>
                    <span style="font-weight:bold; font-size:11pt;"> Kompeten</span>
                    <span style="font-weight:bold; font-size:11pt;"> / </span>
                    <span style="font-size:11pt; vertical-align:middle;">{!! $isBelumKompeten ? '☑' : '☐' !!}</span>
                    <span style="font-weight:bold; font-size:11pt;"> Belum kompeten</span>
                </td>
            </tr>

            <!-- ── TINDAK LANJUT ── -->
            <tr>
                <td style="border:1px solid #000; text-align:left; font-weight:bold; padding:5px 8px 5px 9px; vertical-align:top; font-size:11pt; line-height:1.3;">
                    Tindak lanjut yang dibutuhkan
                    <span style="font-weight:normal; font-size:11pt; color:#333; display:block; margin-top:2px;">(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</span>
                </td>
                <td colspan="7" style="border:1px solid #000; text-align:left; padding:5px 8px; vertical-align:top; font-size:11pt;">{{ $item->tindak_lanjut ?: '' }}</td>
            </tr>

            <!-- ── KOMENTAR ── -->
            <tr>
                <td style="border:1px solid #000; text-align:left; font-weight:normal; padding:5px 8px 5px 9px; vertical-align:top; font-size:11pt;">Komentar/ Observasi oleh asesor</td>
                <td colspan="7" style="border:1px solid #000; text-align:left; padding:5px 8px; vertical-align:top; font-size:11pt;">{{ $item->komentar_observasi ?: '' }}</td>
            </tr>

            <!-- ── ASESI HEADER ── -->
            <tr>
                <td colspan="8" style="border:1px solid #000; font-weight:bold; padding:3px 9px; font-size:11pt;">Asesi :</td>
            </tr>
            <!-- ── ASESI NAMA ── -->
            <tr>
                <td style="border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 9px; font-size:11pt; width:32.70%;">Nama</td>
                <td style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; padding:3px 2px; font-size:11pt; width:1.37%;">:</td>
                <td colspan="6" style="border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 8px; font-size:11pt;">{{ $item->ttd_asesi_nama ?? $item->asesi?->nama ?? '' }}</td>
            </tr>
            <!-- ── ASESI TTD ── -->
            <tr>
                <td style="border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:5px 9px; vertical-align:middle; font-size:11pt; line-height:1.5;">Tanda tangan<br>dan Tanggal</td>
                <td style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">:</td>
                <td colspan="6" style="border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:4px 8px; vertical-align:top; font-size:11pt;">
                    <div style="min-height:54px; display:block;">
                        @if(!empty($ttdAsesiDataUri))
                            <img src="{{ $ttdAsesiDataUri }}" alt="Ttd Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                        @endif
                    </div>
                    <div style="font-size:10pt; margin-top:2px;">{{ $item->ttd_asesi_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '' }}</div>
                </td>
            </tr>

            <!-- ── ASESOR HEADER ── -->
            <tr>
                <td colspan="8" style="border:1px solid #000; font-weight:bold; padding:3px 9px; font-size:11pt;">Asesor :</td>
            </tr>
            <!-- ── ASESOR NAMA ── -->
            <tr>
                <td style="border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 9px; font-size:11pt;">Nama</td>
                <td style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; padding:3px 2px; font-size:11pt;">:</td>
                <td colspan="6" style="border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 8px; font-size:11pt;">{{ $item->ttd_asesor_nama ?? $item->asesor?->nama ?? '' }}</td>
            </tr>
            <!-- ── ASESOR NO REG ── -->
            <tr>
                <td style="border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 9px; font-size:11pt;">No. Reg</td>
                <td style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; padding:3px 2px; font-size:11pt;">:</td>
                <td colspan="6" style="border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:3px 8px; font-size:11pt;">{{ $item->ttd_asesor_no_reg ?? $item->asesor?->no_met ?? '' }}</td>
            </tr>
            <!-- ── ASESOR TTD ── -->
            <tr>
                <td style="border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:5px 9px; vertical-align:middle; font-size:11pt; line-height:1.5;">Tanda tangan<br>dan Tanggal</td>
                <td style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; padding:3px 2px; vertical-align:middle; font-size:11pt;">:</td>
                <td colspan="6" style="border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; padding:4px 8px; vertical-align:top; font-size:11pt;">
                    <div style="min-height:54px; display:block;">
                        @if(!empty($ttdAsesorDataUri))
                            <img src="{{ $ttdAsesorDataUri }}" alt="Ttd Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                        @endif
                    </div>
                    <div style="font-size:10pt; margin-top:2px;">{{ $item->ttd_asesor_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '' }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- ═══════════════════════════════════════════════════
     LAMPIRAN DOKUMEN
     (docx: BodyText bold + ListParagraph numbered list)
═══════════════════════════════════════════════════ -->
    <p class="lampiran-title">LAMPIRAN DOKUMEN:</p>
    <ol class="lampiran-list">
        <li>Dokumen APL 01 peserta</li>
        <li>Dokumen APL 02 peserta</li>
        <li>Bukti-bukti berkualitas peserta</li>
        <li>Tinjauan proses asesmen</li>
    </ol>

</body>

</html>