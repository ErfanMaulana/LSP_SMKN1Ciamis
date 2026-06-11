<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>FR.APL.01 - permohonan sertifkasi</title>

    <style>
        @page {
            margin-top: 14mm;
            margin-right: 12mm;
            margin-bottom: 14mm;
            margin-left: 12mm;
        }

        body {
            font-family: Calibri, sans-serif;
            font-size: 10pt;
            line-height: 1.15;
            color: #000;
        }

        .sheet {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            vertical-align: top;
            padding: 1.2mm 1mm;
            word-wrap: break-word;
        }

        .title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 3mm;
            margin-bottom: 1.5mm;
        }

        .section-subtitle {
            font-size: 9pt;
            margin-bottom: 2mm;
        }

        .small {
            font-size: 8.5pt;
        }

        .label {
            width: 26%;
        }

        .colon {
            width: 2%;
            text-align: center;
        }

        .value {
            width: 72%;
        }

        .line {
            border-bottom: 0.6pt solid #000;
            min-height: 11pt;
            padding-bottom: 1mm;
        }

        .inline-line {
            display: inline-block;
            border-bottom: 0.6pt solid #000;
            min-height: 11pt;
            vertical-align: bottom;
        }

        .grid-table {
            margin-top: 2mm;
        }

        .grid-table th,
        .grid-table td {
            border: 0.7pt solid #000;
            font-size: 8.8pt;
        }

        .grid-table th {
            text-align: center;
            font-weight: bold;
        }

        .grid-table td {
            padding-top: 1.5mm;
            padding-bottom: 1.5mm;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .mt2 {
            margin-top: 2mm;
        }

        .mt4 {
            margin-top: 4mm;
        }

        .mt6 {
            margin-top: 6mm;
        }

        .mt8 {
            margin-top: 8mm;
        }

        .mt10 {
            margin-top: 10mm;
        }

        .page-break {
            page-break-after: always;
        }

        .checkbox {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 10pt;
            height: 10pt;

            border: 1px solid #000;

            font-size: 8pt;
            font-weight: bold;
            font-family: DejaVu Sans, sans-serif;

            line-height: 1;

            vertical-align: middle;
        }

        .sign-line {
            border-top: 0.6pt solid #000;
            margin-top: 12mm;
            padding-top: 1mm;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .sign-box {
            height: 54px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-top: 10px;
            margin-bottom: 2px;
        }

        .sign-box img {
            display: block;
        }

        .closing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8mm;
            table-layout: fixed;
        }

        .closing-table td {
            border: 1px solid #000;
            font-size: 9pt;
            padding: 2mm;
            vertical-align: top;
        }

        .closing-title {
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .recommend-text {
            line-height: 1.35;
        }

        .recommend-bold {
            font-weight: bold;
            margin-top: 1mm;
            margin-bottom: 1mm;
        }

        .strike {
            text-decoration: line-through;
        }

        .choice-active {
            font-weight: bold;
        }

        .choice-inline {
            white-space: nowrap;
        }

        .note-box {
            height: 58px;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .sig-table td {
            border: 1px solid #000;
            padding: 1.5mm;
            font-size: 9pt;
            vertical-align: top;
        }

        .sig-label {
            width: 38%;
        }

        .sig-value {
            width: 62%;
        }

        .ttd-area {
            height: 72px;
            position: relative;
            text-align: center;
        }

        .ttd-area img {
            max-height: 64px;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .tanggal {
            margin-top: 2mm;
        }
    </style>
</head>

<body>

    @php
        $skema = $skema ?? null;
        $unitList = $skema ? $skema->units : collect();

        $dokumenList = is_array($bukti_persyaratan ?? null)
            ? $bukti_persyaratan
            : [];

        $administratifList = is_array($bukti_administratif ?? null)
            ? $bukti_administratif
            : [];

        $jenisKelamin = strtolower((string) ($asesi->jenis_kelamin ?? ''));

        $isLaki = str_contains($jenisKelamin, 'laki');
        $isPerempuan = str_contains($jenisKelamin, 'perempuan') || $jenisKelamin === 'p';

        $renderBox = function ($checked) {
            return $checked
                ? '<span class="checkbox">✔</span>'
                : '<span class="checkbox"></span>';
        };

        $selectedSkemaType = strtolower(trim((string) ($skema->jenis_skema ?? '')));

        $renderChoice = function (string $label, bool $active) {
            return $active
                ? '<span class="choice-active">' . e($label) . '</span>'
                : '<span class="strike">' . e($label) . '</span>';
        };

        $persyaratanDefaults = [
            'Fotocopy Rapor pada kesesuaian/hasil nilai yang relevan',
            'Fotocopy Sertifikat/Surat Keterangan telah melaksanakan PKL bidang Multimedia',
            'Portofolio / Bukti pendukung kompetensi lain',
        ];

        $adminDefaults = [
            'Fotocopy Kartu Pelajar',
            'Fotocopy Kartu Keluarga/KTP',
            'Pas foto 3 x 4 berwarna sebanyak 2 lembar',
        ];
    @endphp

    <div class="sheet">

        <table>
            <tr>

                <!-- <td style="width:14%;">
                    @if(!empty($logoUrl))
                        <img src="{{ $logoUrl }}" alt="logo" style="width:58px; height:auto;">
                    @endif
                </td> -->

                <td style="width:86%;">

                    <div class="title">
                        FR.APL.01. permohonan sertifkasi
                    </div>

                    <div class="section-title" style="margin-top:0;">
                        Bagian 1 : Rincian Data Pemohon Sertifikasi
                    </div>

                    <div class="section-subtitle">
                        Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada
                        saat ini.
                    </div>

                </td>

            </tr>
        </table>

        <div class="section-title">
            a. Data Pribadi
        </div>

        <table class="mt2">

            <tr>
                <td class="label">Nama lengkap</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->nama ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">No. KTP/NIK/Paspor</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->NIK ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Tempat / tgl. Lahir</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">
                        {{ trim(($asesi->tempat_lahir ?? '') . ' / ' . (optional($asesi->tanggal_lahir)->format('d-m-Y') ?? '')) }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="label">Jenis kelamin</td>
                <td class="colon">:</td>

                <td class="value">
                    {!! $renderBox($isLaki) !!} Laki-laki
                    &nbsp;&nbsp;&nbsp;
                    {!! $renderBox($isPerempuan) !!} Wanita *)
                </td>
            </tr>

            <tr>
                <td class="label">Kebangsaan</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->kebangsaan ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Alamat rumah</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->alamat ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Kode pos</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->kode_pos ?? '' }}</div>
                </td>
            </tr>

            <tr>

                <td class="label">No. Telepon/E-mail</td>
                <td class="colon">:</td>

                <td class="value">

                    <table>

                        <tr>

                            <td style="width:50%; padding-left:0;">
                                Rumah :
                                <span class="inline-line" style="width:78%;">
                                    {{ $asesi->telepon_rumah ?? '' }}
                                </span>
                            </td>

                            <td style="width:50%; padding-right:0;">
                                Kantor :
                                <span class="inline-line" style="width:78%;">
                                    {{ $asesi->no_fax_lembaga ?? '' }}
                                </span>
                            </td>

                        </tr>

                        <tr>

                            <td style="width:50%; padding-left:0;">
                                HP :
                                <span class="inline-line" style="width:78%;">
                                    {{ $asesi->telepon_hp ?? '' }}
                                </span>
                            </td>

                            <td style="width:50%; padding-right:0;">
                                E-mail :
                                <span class="inline-line" style="width:78%;">
                                    {{ $asesi->email ?? '' }}
                                </span>
                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

            <tr>
                <td class="label">Kualifikasi Pendidikan</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->pendidikan_terakhir ?? '' }}</div>
                </td>
            </tr>

        </table>

        <div class="small mt2">
            *Coret yang tidak perlu
        </div>

        <div class="section-title mt6">
            b. Data Pekerjaan Sekarang
        </div>

        <table class="mt2">

            <tr>
                <td class="label">Nama Institusi / Perusahaan</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->nama_lembaga ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->jabatan ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Alamat Kantor</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->alamat_lembaga ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td class="label">Kode pos</td>
                <td class="colon">:</td>
                <td class="value">
                    <div class="line">{{ $asesi->unit_lembaga ?? '' }}</div>
                </td>
            </tr>

            <tr>

                <td class="label">No. Telp/Fax/E-mail</td>
                <td class="colon">:</td>

                <td class="value">

                    <table>

                        <tr>

                            <td style="width:33.3%; padding-left:0;">
                                Telp :
                                <span class="inline-line" style="width:72%;">
                                    {{ $asesi->telepon_rumah ?? '' }}
                                </span>
                            </td>

                            <td style="width:33.3%;">
                                Fax :
                                <span class="inline-line" style="width:72%;">
                                    {{ $asesi->no_fax_lembaga ?? '' }}
                                </span>
                            </td>

                            <td style="width:33.3%; padding-right:0;">
                                E-mail :
                                <span class="inline-line" style="width:70%;">
                                    {{ $asesi->email_lembaga ?? '' }}
                                </span>
                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

        <div class="section-title mt8">
            Bagian 2 : Data Sertifikasi
        </div>

        <div class="section-subtitle">
            Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan
            pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta
            pengalaman kerja yang anda miliki.
        </div>

        <table class="grid-table mt2">

            <tr>

                <td rowspan="2" style="width:22%;">
                    Skema Sertifikasi<br>
                    ({!! $renderChoice('KKNI', $selectedSkemaType === 'kkni') !!}/{!! $renderChoice('Okupasi', $selectedSkemaType === 'okupasi') !!}/{!! $renderChoice('Klaster', $selectedSkemaType === 'klaster') !!})
                </td>

                <td style="width:18%;">
                    Judul
                </td>

                <td>
                    {{ $skema->nama_skema ?? '-' }}
                </td>

            </tr>

            <tr>

                <td>
                    Nomor
                </td>

                <td>
                    {{ $skema->nomor_skema ?? '-' }}
                </td>

            </tr>

            <tr>

                <td rowspan="4">
                    Tujuan Asesmen
                </td>

                <td colspan="2">
                    {!! $renderBox(true) !!} Sertifikasi
                </td>

            </tr>

            <tr>
                <td colspan="2">
                    {!! $renderBox(false) !!} Pengakuan Kompetensi Terkini (PKT)
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    {!! $renderBox(false) !!} Rekognisi Pembelajaran Lampau (RPL)
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    {!! $renderBox(false) !!} Lainnya
                </td>
            </tr>

        </table>

        <div class="small mt2 choice-inline">
            Skema yang dipakai: {!! $renderChoice('KKNI', $selectedSkemaType === 'kkni') !!} /
            {!! $renderChoice('Okupasi', $selectedSkemaType === 'okupasi') !!} /
            {!! $renderChoice('Klaster', $selectedSkemaType === 'klaster') !!}
        </div>

        <div class="small mt4">
            <strong>Daftar Unit Kompetensi sesuai kemasan:</strong>
        </div>

        <table class="grid-table mt2">

            <thead>

                <tr>

                    <th style="width:5%;">
                        No.
                    </th>

                    <th style="width:20%;">
                        Kode Unit
                    </th>

                    <th>
                        Judul Unit
                    </th>

                    <th style="width:20%;">
                        Standar Kompetensi Kerja
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($unitList as $index => $unit)

                    <tr>

                        <td class="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $unit->kode_unit ?? $unit->kode ?? '-' }}
                        </td>

                        <td>
                            {{ $unit->judul_unit ?? $unit->nama_unit ?? $unit->judul ?? '-' }}
                        </td>

                        <td>
                            {{ $unit->standar_kompetensi ?? 'SKKNI' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td class="center">1</td>
                        <td>...</td>
                        <td>...</td>
                        <td>...</td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="section-title mt8">
            Bagian 3 : Bukti Kelengkapan Pemohon
        </div>

        <div class="small mt4">
            <strong>3.1 Bukti Persyaratan Dasar Pemohon</strong>
        </div>

        <table class="grid-table mt2">

            <thead>

                <tr>

                    <th style="width:5%;">No.</th>

                    <th>
                        Bukti Persyaratan Dasar
                    </th>

                    <th style="width:12%;">
                        Ada Memenuhi Syarat
                    </th>

                    <th style="width:12%;">
                        Tidak Memenuhi Syarat
                    </th>

                    <th style="width:12%;">
                        Tidak Ada
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach(!empty($dokumenList) ? $dokumenList : $persyaratanDefaults as $index => $row)

                    @php
                        $label = is_string($row)
                            ? $row
                            : ($row['label'] ?? $row['nama'] ?? '');

                        $state = is_array($row)
                            ? ($row['status'] ?? '')
                            : '';
                    @endphp

                    <tr>

                        <td class="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $label }}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'memenuhi') !!}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'tidak_memenuhi') !!}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'tidak_ada') !!}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="small mt4">
            <strong>3.2 Bukti Administratif</strong>
        </div>

        <table class="grid-table mt2">

            <thead>

                <tr>

                    <th style="width:5%;">No.</th>

                    <th>
                        Bukti Administratif
                    </th>

                    <th style="width:12%;">
                        Ada Memenuhi Syarat
                    </th>

                    <th style="width:12%;">
                        Tidak Memenuhi Syarat
                    </th>

                    <th style="width:12%;">
                        Tidak Ada
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach(!empty($administratifList) ? $administratifList : $adminDefaults as $index => $row)

                    @php
                        $label = is_string($row)
                            ? $row
                            : ($row['label'] ?? $row['nama'] ?? '');

                        $state = is_array($row)
                            ? ($row['status'] ?? '')
                            : '';
                    @endphp

                    <tr>

                        <td class="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $label }}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'memenuhi') !!}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'tidak_memenuhi') !!}
                        </td>

                        <td class="center">
                            {!! $renderBox($state === 'tidak_ada') !!}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <table class="closing-table">

            <tr>

                <!-- KIRI -->
                <td style="width:56%;" rowspan="2">

                    <div class="closing-title">
                        Rekomendasi (diisi oleh LSP):
                    </div>

                    <div class="recommend-text">
                        Berdasarkan ketentuan persyaratan dasar, maka pemohon:
                    </div>

                    <div class="recommend-bold">
                        {!! $renderChoice('Diterima', strtolower(trim((string) ($rekomendasiText ?? 'Diterima'))) === 'diterima') !!}
                        /
                        {!! $renderChoice('Tidak diterima', strtolower(trim((string) ($rekomendasiText ?? 'Diterima'))) !== 'diterima') !!}
                        *)
                        sebagai peserta sertifikasi
                    </div>

                    <div>
                        * coret yang tidak sesuai
                    </div>

                </td>

                <!-- KANAN ATAS -->
                <td style="width:44%; padding:0;">

                    <table class="sig-table">

                        <tr>
                            <td colspan="2">
                                <strong>Pemohon/ Kandidat :</strong>
                            </td>
                        </tr>

                        <tr>
                            <td class="sig-label">
                                Nama
                            </td>

                            <td class="sig-value">
                                {{ $asesi->nama ?? '-' }}
                            </td>
                        </tr>

                        <tr>

                            <td class="sig-label">
                                Tanda tangan/<br>
                                Tanggal
                            </td>

                            <td class="sig-value">

                                <div class="ttd-area">

                                    @if(!empty($pendaftarSignature['src']))
                                        <img src="{{ $pendaftarSignature['src'] }}"
                                            style="{{ $pendaftarSignature['style'] }}" alt="ttd pemohon">
                                    @endif

                                </div>

                                <div class="tanggal">
                                    {{ $pendaftarSignedAt ?? '-' }}
                                </div>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

            <tr>

                <!-- CATATAN -->
                <td style="padding:0;">

                    <table class="sig-table">

                        <tr>
                            <td colspan="2">
                                <strong>Admin LSP :</strong>
                            </td>
                        </tr>

                        <tr>

                            <td class="sig-label">
                                Nama :
                            </td>

                            <td class="sig-value">
                                {{ $adminSignerName ?? optional($asesi->verifiedBy)->name ?? '-' }}
                            </td>

                        </tr>

                        <tr>

                            <td class="sig-label">
                                Tanda tangan/<br>
                                Tanggal
                            </td>

                            <td class="sig-value">

                                <div class="ttd-area">

                                    @if(!empty($verifikatorSignature['src']))
                                        <img src="{{ $verifikatorSignature['src'] }}"
                                            style="{{ $verifikatorSignature['style'] }}" alt="ttd admin">
                                    @endif

                                </div>

                                <div class="tanggal">
                                    {{ $adminSignedAt ?? '-' }}
                                </div>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    </div>

</body>

</html>