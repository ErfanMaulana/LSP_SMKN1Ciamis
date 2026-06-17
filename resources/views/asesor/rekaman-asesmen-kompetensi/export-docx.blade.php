<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.AK.02 - Rekaman Asesmen Kompetensi</title>
    <style>
        @page { margin: 20px 24px; }
        body { font-family: Arial, Calibri, sans-serif; font-size: 13.5px; color: #000; line-height: 1.25; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        td, th { border: 1px solid #000; padding: 5px 6px; vertical-align: top; }
        .no-border { border: none !important; }
        .title { font-weight: bold; font-size: 15px; margin-bottom: 4px; text-transform: uppercase; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        
        .meta-table {
            table-layout: fixed;
            width: 100%;
        }
        .meta-table td {
            padding: 5px 7px;
        }
        .meta-table .left-header {
            width: 32%;
            vertical-align: middle;
            font-weight: bold;
        }
        .meta-table .label-col {
            width: 13%;
            border-right: 1px solid #000;
            font-weight: bold;
        }
        .meta-table .colon-col {
            width: 3%;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            text-align: center;
        }
        .meta-table .value-col {
            border-left: 1px solid #000;
        }

        .checklist-table {
            table-layout: fixed;
            width: 100%;
        }
        .checklist-table th {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 12.5px;
            background: #ffffff;
        }
        .checklist-table td {
            vertical-align: middle;
        }
        .vertical-col {
            writing-mode: tb-rl;
            -webkit-writing-mode: vertical-rl;
            writing-mode: vertical-rl;
            mso-rotate: 90;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            background: #ffffff;
            width: 38px;
            height: 120px;
            padding: 4px 2px;
        }

        .sign-title-row {
            background: #f2f2f2;
            font-weight: bold;
        }

        .signature-table {
            margin-top: 10px;
            table-layout: fixed;
            width: 100%;
        }
        .signature-table td {
            padding: 6px 8px;
        }
        .signature-table .label-width {
            width: 25%;
            font-weight: bold;
        }
        .signature-table .colon-width {
            width: 3%;
            text-align: center;
        }
        
        .signature-box {
            min-height: 54px;
            display: block;
            margin: 4px 0;
        }
        .signature-box img {
            max-height: 54px;
            max-width: 140px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <!-- Header Section with Logo -->
    <table style="margin-bottom:12px; table-layout:fixed; width:100%;">
        <tr>
            <td class="no-border" style="width:56px; padding:0; vertical-align:middle;">
                @if(!empty($logoPath) && file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo" width="46" height="46" style="width:46px; height:46px; object-fit:contain; display:block; margin:0 auto;">
                @endif
            </td>
            <td class="no-border" style="padding:0 0 0 8px; vertical-align:middle; text-align:left;">
                <div class="title" style="margin:0; font-size: 15px;">{{ $item->kode_form }} {{ $item->judul_form }}</div>
            </td>
        </tr>
    </table>

    @php
        $skemaKategori = trim((string) ($item->skema?->jenis_skema ?? $item->kategori_skema ?? ''));
        $skemaHeader = $skemaKategori !== ''
            ? 'Skema Sertifikasi (' . $skemaKategori . ')'
            : 'Skema Sertifikasi (KKNI/Okupasi/Klaster)';
    @endphp

    <!-- Meta Details Table -->
    <table class="meta-table">
        <tr>
            <td class="left-header" rowspan="2" style="font-size:13px; line-height:1.2;">
                Skema Sertifikasi<br>
                @if($skemaKategori !== '')
                    ({{ $skemaKategori }})
                @else
                    (<span style="text-decoration: line-through;">KKNI</span>/<span style="text-decoration: line-through;">Okupasi</span>/<span style="text-decoration: line-through;">Klaster</span>)
                @endif
            </td>
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
            <td class="value-col">{{ $item->tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesor</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $ceklis->ttd_asesor_nama ?? $item->asesor?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" colspan="2">Nama Asesi</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $ceklis->ttd_asesi_nama ?? $item->asesi?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="left-header" rowspan="2">Tanggal Asesmen</td>
            <td class="label-col">Mulai</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Selesai</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $item->tanggal_selesai?->format('d-m-Y') ?? '-' }}</td>
        </tr>
    </table>

    <p style="margin: 8px 0; font-size:13px;">Beri tanda centang (v) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit Kompetensi.</p>

    <!-- Checklist Table -->
    <table class="checklist-table">
        <thead>
            <tr style="height: 120px; background: #ffffff;">
                <th style="text-align: left; padding-left: 10px; vertical-align: middle; background: #ffffff;">Unit Kompetensi</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Observasi Demonstrasi</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Portofolio</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 44px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Pernyataan Pihak Ketiga Pertanyaan Wawancara</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Pertanyaan Lisan</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Pertanyaan Tertulis</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Proyek Kerja</th>
                <th style="writing-mode: tb-rl; mso-rotate: 90; text-align: center; vertical-align: middle; background: #ffffff; width: 38px; height: 120px; font-weight: bold; font-size: 10px; padding: 4px 2px;">Lainnya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $detail)
                <tr>
                    <td style="padding: 6px 10px;">
                        @if($detail->unit?->kode_unit)
                            <strong>{{ $detail->unit->kode_unit }}</strong><br>
                        @endif
                        {{ $detail->unit?->judul_unit ?? '-' }}
                    </td>
                    <td class="center">{!! $detail->observasi_demonstrasi ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->portofolio ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->pernyataan_pihak_ketiga ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->pertanyaan_lisan ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->pertanyaan_tertulis ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->proyek_kerja ? 'v' : '' !!}</td>
                    <td class="center">{!! $detail->lainnya ? 'v' : '' !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada unit kompetensi.</td>
                </tr>
            @endforelse

            <!-- Rekomendasi Row -->
            @php
                $isKompeten = $item->rekomendasi === 'kompeten';
                $isBelumKompeten = $item->rekomendasi === 'belum_kompeten';
            @endphp
            <tr>
                <td class="bold" style="padding: 6px 10px;">Rekomendasi hasil asesmen</td>
                <td colspan="7" style="padding: 6px 10px;">
                    <span style="font-family: DejaVu Sans, Arial; font-size:14px;">{!! $isKompeten ? '☑' : '☐' !!}</span> Kompeten / 
                    <span style="font-family: DejaVu Sans, Arial; font-size:14px;">{!! $isBelumKompeten ? '☑' : '☐' !!}</span> Belum kompeten
                </td>
            </tr>

            <!-- Tindak Lanjut Row -->
            <tr>
                <td class="bold" style="padding: 6px 10px; line-height: 1.2;">
                    Tindak lanjut yang dibutuhkan<br>
                    <span style="font-size: 11px; font-weight: normal; color: #333;">(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</span>
                </td>
                <td colspan="7" style="padding: 6px 10px; vertical-align: top;">
                    {{ $item->tindak_lanjut ?: '-' }}
                </td>
            </tr>

            <!-- Komentar Row -->
            <tr>
                <td class="bold" style="padding: 6px 10px;">Komentar/ Observasi oleh asesor</td>
                <td colspan="7" style="padding: 6px 10px; vertical-align: top;">
                    {{ $item->komentar_observasi ?: '-' }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Signatures Section -->
    <table class="signature-table">
        <tr class="sign-title-row">
            <td colspan="3">Asesi :</td>
        </tr>
        <tr>
            <td class="label-width">Nama</td>
            <td class="colon-width">:</td>
            <td>{{ $ceklis->ttd_asesi_nama ?? $item->asesi?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Tanda tangan dan Tanggal</td>
            <td style="vertical-align: middle;" class="colon-width">:</td>
            <td style="vertical-align: top; padding: 4px 8px;">
                <div class="signature-box">
                    @if(!empty($ceklis->ttd_asesi_file))
                        <img src="{{ asset('storage/' . ltrim($ceklis->ttd_asesi_file, '/')) }}" alt="Ttd Asesi">
                    @endif
                </div>
                <div style="font-size:12px;">
                    {{ $ceklis->ttd_asesi_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr class="sign-title-row">
            <td colspan="3">Asesor :</td>
        </tr>
        <tr>
            <td class="label-width">Nama</td>
            <td class="colon-width">:</td>
            <td>{{ $ceklis->ttd_asesor_nama ?? $item->asesor?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-width">No. Reg</td>
            <td class="colon-width">:</td>
            <td>{{ $ceklis->ttd_asesor_no_reg ?? $item->asesor?->no_met ?? '-' }}</td>
        </tr>
        <tr>
            <td style="vertical-align: middle;">Tanda tangan dan Tanggal</td>
            <td style="vertical-align: middle;" class="colon-width">:</td>
            <td style="vertical-align: top; padding: 4px 8px;">
                <div class="signature-box">
                    @if(!empty($ceklis->ttd_asesor_file))
                        <img src="{{ asset('storage/' . ltrim($ceklis->ttd_asesor_file, '/')) }}" alt="Ttd Asesor">
                    @endif
                </div>
                <div style="font-size:12px;">
                    {{ $ceklis->ttd_asesor_tanggal?->format('d-m-Y') ?? $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Document Attachment Note -->
    <div style="margin-top: 20px; font-size: 13px;">
        <strong>LAMPIRAN DOKUMEN:</strong><br>
        1. Dokumen APL 01 peserta<br>
        2. Dokumen APL 02 peserta<br>
        3. Bukti-bukti berkualitas peserta<br>
        4. Tinjauan proses asesmen
    </div>

</body>
</html>
