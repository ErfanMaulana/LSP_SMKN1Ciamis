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
            font-weight: bold;
            margin: 6px 0 2px 0.29in;
            padding: 0;
        }

        ol.lampiran-list {
            margin: 0 0 0 0.54in;
            padding: 0 0 0 0;
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
    <div class="header-wrap">
        <div class="header-logo-cell">
            @if(!empty($logoPath) && file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="header-title">{{ $item->kode_form }} {{ $item->judul_form }}</div>
        </div>
    </div>

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
            <td class="c4" style="padding-left:20px;">
                @if($item->tuk)
                    {{ $item->tuk }}
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
    <p class="instr">Beri tanda centang (√) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit
        Kompetensi.</p>

    <!-- ═══════════════════════════════════════════════════
     CHECKLIST TABLE
     gridCol: 4849/708/708/1280/708/708/712/712 twips
     Header row height: 1540 twips ≈ 108px
     textDirection: btLr (bottom-to-top in CSS)
═══════════════════════════════════════════════════ -->
    <table class="ck-table">
        <colgroup>
            <col class="cw-unit">
            <col class="cw-obs">
            <col class="cw-port">
            <col class="cw-pihak">
            <col class="cw-lisan">
            <col class="cw-tulis">
            <col class="cw-proyek">
            <col class="cw-lain">
        </colgroup>
        <thead>
            <tr>
                <!-- "Unit Kompetensi" — horizontal, bottom-aligned, bold, centered -->
                <th class="th-unit">Unit Kompetensi</th>

                <!-- All other headers: vertical btLr (reads bottom → top) -->
                <th class="th-vert">
                    <span class="th-vert-inner">Observasi Demonstrasi</span>
                </th>
                <th class="th-vert">
                    <span class="th-vert-inner">Portofolio</span>
                </th>
                <!-- "Pernyataan Pihak Ketiga Pertanyaan Wawancara" — wider col, same direction -->
                <th class="th-vert">
                    <span class="th-vert-inner">Pernyataan Pihak Ketiga Pertanyaan Wawancara</span>
                </th>
                <th class="th-vert">
                    <span class="th-vert-inner">Pertanyaan Lisan</span>
                </th>
                <th class="th-vert">
                    <span class="th-vert-inner">Pertanyaan Tertulis</span>
                </th>
                <th class="th-vert">
                    <span class="th-vert-inner">Proyek Kerja</span>
                </th>
                <th class="th-vert">
                    <span class="th-vert-inner">Lainnya</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $detail)
                <tr>
                    <td class="td-unit">
                        @if($detail->unit?->kode_unit)
                            <strong>{{ $detail->unit->kode_unit }}</strong><br>
                        @endif
                        {{ $detail->unit?->judul_unit ?? '-' }}
                    </td>
                    <td>{!! $detail->observasi_demonstrasi ? '√' : '' !!}</td>
                    <td>{!! $detail->portofolio ? '√' : '' !!}</td>
                    <td>{!! $detail->pernyataan_pihak_ketiga ? '√' : '' !!}</td>
                    <td>{!! $detail->pertanyaan_lisan ? '√' : '' !!}</td>
                    <td>{!! $detail->pertanyaan_tertulis ? '√' : '' !!}</td>
                    <td>{!! $detail->proyek_kerja ? '√' : '' !!}</td>
                    <td>{!! $detail->lainnya ? '√' : '' !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:6px;">Tidak ada unit kompetensi.</td>
                </tr>
            @endforelse

            <!-- Rekomendasi hasil asesmen -->
            @php
                $isKompeten = $item->rekomendasi === 'kompeten';
                $isBelumKompeten = $item->rekomendasi === 'belum_kompeten';
            @endphp
            <tr>
                <td class="td-label" style="vertical-align:middle;">Rekomendasi hasil asesmen</td>
                <td colspan="7" class="td-value" style="vertical-align:middle;">
                    <span class="cb-glyph">{!! $isKompeten ? '☑' : '☐' !!}</span>
                    <span class="cb-label"> Kompeten</span>
                    <span class="cb-label"> / </span>
                    <span class="cb-glyph">{!! $isBelumKompeten ? '☑' : '☐' !!}</span>
                    <span class="cb-label"> Belum kompeten</span>
                </td>
            </tr>

            <!-- Tindak lanjut yang dibutuhkan -->
            <tr>
                <td class="td-label" style="vertical-align:top; line-height:1.3;">
                    Tindak lanjut yang dibutuhkan
                    <span class="td-label-sub">(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai
                        kompetensi)</span>
                </td>
                <td class="td-value" colspan="7">{{ $item->tindak_lanjut ?: '' }}</td>
            </tr>

            <!-- Komentar / Observasi oleh asesor -->
            <tr>
                <td class="td-label" style="vertical-align:top;">Komentar/ Observasi oleh asesor</td>
                <td class="td-value" colspan="7">{{ $item->komentar_observasi ?: '' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ═══════════════════════════════════════════════════
     SIGNATURE TABLE — ASESI
     gridCol: 3368 / 141 / 6789 twips
     Outer border sz=12 (thick=2px), inner sz=4 (1px)
═══════════════════════════════════════════════════ -->
    <table class="sig-table">
        <colgroup>
            <col class="cs-label">
            <col class="cs-colon">
            <col class="cs-value">
        </colgroup>
        <!-- Section header: "Asesi :" -->
        <tr>
            <td colspan="3" class="sig-header-td">Asesi :</td>
        </tr>
        <!-- Nama -->
        <tr>
            <td class="sig-inner sig-outer-left" style="padding:3px 8px 3px 9px;">Nama</td>
            <td class="sig-inner" style="text-align:center; padding:3px 2px;">:</td>
            <td class="sig-inner sig-outer-right" style="padding:3px 8px;">
                {{ $ceklis->ttd_asesi_nama ?? $item->asesi?->nama ?? '' }}</td>
        </tr>
        <!-- Tanda tangan dan Tanggal -->
        <tr>
            <td class="sig-inner sig-outer-left sig-outer-bottom"
                style="padding:5px 8px 5px 9px; vertical-align:middle;">
                <span class="sig-ttd-label">Tanda tangan<br>dan Tanggal</span>
            </td>
            <td class="sig-inner sig-outer-bottom" style="text-align:center; padding:3px 2px; vertical-align:middle;">:
            </td>
            <td class="sig-inner sig-outer-right sig-outer-bottom" style="padding:4px 8px; vertical-align:top;">
                <div class="sig-box">
                    @if(!empty($ceklis->ttd_asesi_file))
                        <img src="{{ asset('storage/' . ltrim($ceklis->ttd_asesi_file, '/')) }}" alt="Ttd Asesi">
                    @endif
                </div>
                <div class="sig-date">
                    {{ $ceklis->ttd_asesi_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════════════════
     SIGNATURE TABLE — ASESOR
═══════════════════════════════════════════════════ -->
    <table class="sig-table">
        <colgroup>
            <col class="cs-label">
            <col class="cs-colon">
            <col class="cs-value">
        </colgroup>
        <!-- Section header: "Asesor :" -->
        <tr>
            <td colspan="3" class="sig-header-td">Asesor :</td>
        </tr>
        <!-- Nama -->
        <tr>
            <td class="sig-inner sig-outer-left" style="padding:3px 8px 3px 9px;">Nama</td>
            <td class="sig-inner" style="text-align:center; padding:3px 2px;">:</td>
            <td class="sig-inner sig-outer-right" style="padding:3px 8px;">
                {{ $ceklis->ttd_asesor_nama ?? $item->asesor?->nama ?? '' }}</td>
        </tr>
        <!-- No. Reg -->
        <tr>
            <td class="sig-inner sig-outer-left" style="padding:3px 8px 3px 9px;">No. Reg</td>
            <td class="sig-inner" style="text-align:center; padding:3px 2px;">:</td>
            <td class="sig-inner sig-outer-right" style="padding:3px 8px;">
                {{ $ceklis->ttd_asesor_no_reg ?? $item->asesor?->no_met ?? '' }}</td>
        </tr>
        <!-- Tanda tangan dan Tanggal -->
        <tr>
            <td class="sig-inner sig-outer-left sig-outer-bottom"
                style="padding:5px 8px 5px 9px; vertical-align:middle;">
                <span class="sig-ttd-label">Tanda tangan<br>dan Tanggal</span>
            </td>
            <td class="sig-inner sig-outer-bottom" style="text-align:center; padding:3px 2px; vertical-align:middle;">:
            </td>
            <td class="sig-inner sig-outer-right sig-outer-bottom" style="padding:4px 8px; vertical-align:top;">
                <div class="sig-box">
                    @if(!empty($ceklis->ttd_asesor_file))
                        <img src="{{ asset('storage/' . ltrim($ceklis->ttd_asesor_file, '/')) }}" alt="Ttd Asesor">
                    @endif
                </div>
                <div class="sig-date">
                    {{ $ceklis->ttd_asesor_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '' }}
                </div>
            </td>
        </tr>
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