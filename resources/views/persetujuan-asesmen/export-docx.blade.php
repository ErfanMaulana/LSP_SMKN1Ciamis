<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.AK.01 - Persetujuan Asesmen dan Kerahasiaan</title>
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
        .signature-box {
            height: 52px;
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
        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #111;
            margin-right: 3px;
        }
        .checkbox.checked::before {
            content: "✓";
            font-size: 14.5px;
            font-weight: bold;
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

    <table>
        <tr>
            <td colspan="4">{{ $item->pengantar }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="width:30%; vertical-align:middle; text-align:left;" rowspan="2">Skema Sertifikasi<br>{{ $item->kategori_skema }}</td>
            <td style="width:12%; border-right:none;">Judul</td>
            <td style="width:2%; border-left:none;">:</td>
            <td>{{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">Nomor</td>
            <td style="border-left:none;">:</td>
            <td>{{ $item->nomor_skema ?: ($skema->nomor_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">TUK</td>
            <td colspan="2" style="text-align:right; border-left:none;">:</td>
            <td>{{ $item->tuk }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">Nama Asesor</td>
            <td colspan="2" style="text-align:right; border-left:none;">:</td>
            <td>{{ $item->nama_asesor }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">Nama Asesi</td>
            <td colspan="2" style="text-align:right; border-left:none;">:</td>
            <td>{{ $item->nama_asesi }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="width:30%; vertical-align:middle;">Bukti yang akan dikumpulkan :</td>
            <td style="width:70%; padding:8px 10px;">
                <table style="width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed;">
                    <tr>
                        <td class="no-border" style="width:50%; padding:4px 2px 4px 0;">
                            {{ $item->bukti_verifikasi_portofolio ? '☑' : '☐' }} Hasil Verifikasi Portofolio
                        </td>
                        <td class="no-border" style="width:50%; padding:4px 0 4px 8px;">
                            {{ $item->bukti_reviu_produk ? '☑' : '☐' }} Hasil Reviu Produk
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border" style="padding:4px 2px 4px 0;">
                            {{ $item->bukti_observasi_langsung ? '☑' : '☐' }} Hasil Observasi Langsung
                        </td>
                        <td class="no-border" style="padding:4px 0 4px 8px;">
                            {{ $item->bukti_kegiatan_terstruktur ? '☑' : '☐' }} Hasil Kegiatan Terstruktur
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border" style="padding:4px 2px 4px 0;">
                            {{ $item->bukti_pertanyaan_lisan ? '☑' : '☐' }} Hasil Pertanyaan Lisan
                        </td>
                        <td class="no-border" style="padding:4px 0 4px 8px;">
                            {{ $item->bukti_pertanyaan_tertulis ? '☑' : '☐' }} Hasil Pertanyaan Tertulis
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border" style="padding:4px 2px 4px 0;">
                            {{ $item->bukti_lainnya ? '☑' : '☐' }} Lainnya {{ $item->bukti_lainnya_keterangan ?: '......' }}
                        </td>
                        <td class="no-border" style="padding:4px 0 4px 8px;">
                            {{ $item->bukti_pertanyaan_wawancara ? '☑' : '☐' }} Hasil Pertanyaan Wawancara
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td rowspan="3" style="width:30%; vertical-align:middle;">Pelaksanaan asesmen disepakati pada:</td>
            <td style="width:12%; border-right:none;">Hari / Tanggal</td>
            <td style="width:3%; border-left:none;">:</td>
            <td>{{ $item->hari_tanggal }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">Waktu</td>
            <td style="border-left:none;">:</td>
            <td>{{ $item->waktu }}</td>
        </tr>
        <tr>
            <td style="border-right:none;">TUK</td>
            <td style="border-left:none;">:</td>
            <td>{{ $item->tuk_pelaksanaan }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700; border-bottom:none;">Asesi:</td>
        </tr>
        <tr>
            <td style="border-top:none;">{{ $item->pernyataan_asesi_1 }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700; border-bottom:none;">Asesor:</td>
        </tr>
        <tr>
            <td style="border-top:none;">{{ $item->pernyataan_asesor }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700; border-bottom:none;">Asesi:</td>
        </tr>
        <tr>
            <td style="border-top:none;">{{ $item->pernyataan_asesi_2 }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <!-- <tr>
            <td colspan="4">Tanda Tangan</td>
        </tr> -->
        {{-- Baris 1: Asesor --}}
        <tr>
            <td style="width:50%; padding:4px 8px; border-right:none; vertical-align:middle;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td class="no-border" style="white-space:nowrap; vertical-align:middle; padding:2px 4px 2px 0;">Tanda tangan Asesor &nbsp;:</td>
                        <td class="no-border" style="vertical-align:middle; padding:2px 0;">
                            @if(!empty($ttdAsesorDataUri) || !empty($item->ttd_asesor_file))
                                <img src="{{ $ttdAsesorDataUri ?? asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="TTD Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                            @else
                                ......................................
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%; padding:4px 8px; border-left:none; vertical-align:middle;">
                Tanggal &nbsp;:
                @if($item->ttd_asesor_tanggal)
                    {{ $item->ttd_asesor_tanggal->format('d-m-Y') }}
                @else
                    ......................................
                @endif
            </td>
        </tr>
        {{-- Baris 2: Asesi --}}
        <tr>
            <td style="padding:4px 8px; border-right:none; vertical-align:middle;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td class="no-border" style="white-space:nowrap; vertical-align:middle; padding:2px 4px 2px 0;">Tanda tangan Asesi &nbsp;&nbsp;:</td>
                        <td class="no-border" style="vertical-align:middle; padding:2px 0;">
                            @if(!empty($ttdAsesiDataUri) || !empty($item->ttd_asesi_file))
                                <img src="{{ $ttdAsesiDataUri ?? asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="TTD Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                            @else
                                ......................................
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding:4px 8px; border-left:none; vertical-align:middle;">
                Tanggal &nbsp;:
                @if($item->ttd_asesi_tanggal)
                    {{ $item->ttd_asesi_tanggal->format('d-m-Y') }}
                @else
                    ......................................
                @endif
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
