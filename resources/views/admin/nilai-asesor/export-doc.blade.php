<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nilai Asesmen – {{ $asesi->nama }}</title>
    <style>
        @page { margin: 20px 18px; }
        body {
            font-family: DejaVu Sans, Calibri, Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td, th {
            border: 1px solid #111;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .no-border { border: none !important; }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .title-main {
            font-size: 12pt;
            font-weight: 700;
            text-align: left;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .subtitle {
            font-size: 11pt;
            text-align: left;
            margin-bottom: 8px;
            color: #333;
        }
        .section-gap { margin-top: 0; }

        th {
            background: #ffffffff;
            font-size: 11pt;
        }
        td { font-size: 11pt; }
        .unit-header td {
            background: #ffffffff;
            font-weight: 700;
            font-size: 11pt;
        }
    </style>
</head>
<body>

{{-- ===== HEADER JUDUL ===== --}}
<div class="title-main">NILAI ASESMEN</div>
<div class="subtitle">Formulir Penilaian Asesor</div>

{{-- ===== TABEL INFO SKEMA DAN ASESI ===== --}}
<table class="section-gap" style="margin-bottom:0;">
    <tr>
        <td rowspan="2" style="width:28%; vertical-align:middle; font-size:10.5pt;">
            Skema Sertifikasi<br>
            @php $j = $skema->jenis_skema ?? ''; @endphp
            <span style="font-size:11pt;">(
                {!! $j === 'KKNI'    ? 'KKNI'    : '<s>KKNI</s>'    !!}/
                {!! $j === 'Okupasi' ? 'Okupasi' : '<s>Okupasi</s>' !!}/
                {!! $j === 'Klaster' ? 'Klaster' : '<s>Klaster</s>' !!}
            )</span>
        </td>
        <td style="width:12%;">Judul</td>
        <td style="width:3%; text-align:center;">:</td>
        <td>{{ $skema->nama_skema ?? '-' }}</td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td class="center">:</td>
        <td>{{ $skema->nomor_skema ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2">TUK</td>
        <td class="center">:</td>
        <td>{{ $tuk }}</td>
    </tr>
    <tr>
        <td colspan="2">Nama Asesor</td>
        <td class="center">:</td>
        <td>{{ $namaAsesor }}</td>
    </tr>
    <tr>
        <td colspan="2">Nama Asesi</td>
        <td class="center">:</td>
        <td>{{ $asesi->nama ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2">Tanggal</td>
        <td class="center">:</td>
        <td>{{ $tanggal }}</td>
    </tr>
</table>

{{-- ===== TABEL NILAI PER UNIT DAN ELEMEN ===== --}}
@foreach($skema->units as $unitIndex => $unit)
    @php
        $unitElemens = $unit->elemens;
    @endphp

    {{-- Unit header --}}
    <table class="section-gap" style="margin-top:8px;">
        <tr class="unit-header">
            <td colspan="5">
                Unit Kompetensi {{ $unitIndex + 1 }} &nbsp;|&nbsp;
                <span style="font-weight:400;">{{ $unit->kode_unit ?? '-' }}</span> &nbsp;–&nbsp;
                {{ $unit->judul_unit ?? '-' }}
            </td>
        </tr>
        {{-- Column header --}}
        <tr>
            <th style="width:40px;" class="center">No.</th>
            <th>Elemen Kompetensi</th>
            <th style="width:60px;" class="center">Nilai</th>
            <th style="width:50px;" class="center">K</th>
            <th style="width:50px;" class="center">BK</th>
        </tr>

        @forelse($unitElemens as $elemenIndex => $elemen)
            @php
                $nilai = $nilaiByElemen->get($elemen->id);
                $isK   = $nilai && $nilai->status === 'K';
                $isBK  = $nilai && $nilai->status === 'BK';
            @endphp
            <tr>
                <td class="center">{{ $elemenIndex + 1 }}</td>
                <td>{{ $elemen->nama_elemen }}</td>
                <td class="center">{{ $nilai ? (int) $nilai->nilai : '-' }}</td>
                <td class="center">{{ $isK ? '✔' : '' }}</td>
                <td class="center">{{ $isBK ? '✔' : '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="center" style="color:#888;">Tidak ada elemen pada unit ini.</td>
            </tr>
        @endforelse
    </table>
@endforeach

{{-- Keterangan --}}
<table style="margin-top:10px; width:auto; border-collapse:collapse;">
    <tr>
        <td style="border:none; padding:4px 6px 4px 0; font-size:11pt;"><strong>Keterangan:</strong></td>
        <td style="border:none; width:18px; text-align:center; font-size:11pt; padding:2px 4px;">✔</td>
        <td style="border:none; padding:4px 6px; font-size:11pt;">K = Kompeten</td>
        <td style="border:none; width:18px; text-align:center; font-size:11pt; padding:2px 4px;"> </td>
        <td style="border:none; padding:4px 6px; font-size:11pt;">BK = Belum Kompeten</td>
    </tr>
</table>

</body>
</html>
