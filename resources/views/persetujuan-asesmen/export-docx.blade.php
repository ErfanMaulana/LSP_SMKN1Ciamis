<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.AK.01 - Persetujuan Asesmen dan Kerahasiaan</title>
    <style>
        @page { margin: 18px 16px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #000; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #111; padding: 4px 5px; vertical-align: top; }
        .no-border { border: none !important; }
        .title { font-weight: 700; font-size: 11px; margin-bottom: 6px; }
        .small { font-size: 9px; }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .section-gap { margin-top: 8px; }
        .signature-box {
            border: 1px solid #111;
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
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table style="margin-bottom:6px; table-layout:fixed;">
        <tr>
            <td class="no-border" style="width:56px; padding:0; vertical-align:middle;">
                @if(!empty($logoPath) && file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo" width="44" height="44" style="width:44px; height:44px; object-fit:contain; display:block; margin:0 auto;">
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
            <td style="width:30%;">Skema Sertifikasi<br>{{ $item->kategori_skema }}</td>
            <td style="width:12%;">Judul</td>
            <td style="width:2%;">:</td>
            <td>{{ $item->judul_skema ?: ($skema->nama_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $item->nomor_skema ?: ($skema->nomor_skema ?? '-') }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td colspan="2">:</td>
            <td>{{ $item->tuk }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td colspan="2">:</td>
            <td>{{ $item->nama_asesor }}</td>
        </tr>
        <tr>
            <td>Nama Asesi</td>
            <td colspan="2">:</td>
            <td>{{ $item->nama_asesi }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700;">Bukti yang akan dikumpulkan:</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:5%;" class="center">{{ $item->bukti_verifikasi_portofolio ? '✓' : '☐' }}</td>
            <td>Hasil Verifikasi Portofolio</td>
            <td style="width:5%;" class="center">{{ $item->bukti_reviu_produk ? '✓' : '☐' }}</td>
            <td>Hasil Reviu Produk</td>
        </tr>
        <tr>
            <td class="center">{{ $item->bukti_observasi_langsung ? '✓' : '☐' }}</td>
            <td>Hasil Observasi Langsung</td>
            <td class="center">{{ $item->bukti_kegiatan_terstruktur ? '✓' : '☐' }}</td>
            <td>Hasil Kegiatan Terstruktur</td>
        </tr>
        <tr>
            <td class="center">{{ $item->bukti_pertanyaan_lisan ? '✓' : '☐' }}</td>
            <td>Hasil Pertanyaan Lisan</td>
            <td class="center">{{ $item->bukti_pertanyaan_tertulis ? '✓' : '☐' }}</td>
            <td>Hasil Pertanyaan Tertulis</td>
        </tr>
        <tr>
            <td class="center">{{ $item->bukti_pertanyaan_wawancara ? '✓' : '☐' }}</td>
            <td>Hasil Pertanyaan Wawancara</td>
            <td class="center">{{ $item->bukti_lainnya ? '✓' : '☐' }}</td>
            <td>Lainnya{{ $item->bukti_lainnya_keterangan ? ' - ' . $item->bukti_lainnya_keterangan : '' }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700;">Pelaksanaan asesmen disepakati pada:</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:30%;">Hari / Tanggal</td>
            <td>:</td>
            <td>{{ $item->hari_tanggal }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ $item->waktu }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td>:</td>
            <td>{{ $item->tuk_pelaksanaan }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700;">Asesi:</td>
        </tr>
        <tr>
            <td>{{ $item->pernyataan_asesi_1 }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700;">Asesor:</td>
        </tr>
        <tr>
            <td>{{ $item->pernyataan_asesor }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td style="font-weight:700;">Asesi:</td>
        </tr>
        <tr>
            <td>{{ $item->pernyataan_asesi_2 }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td colspan="4" style="font-weight:700;">Tanda Tangan</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:50%; text-align:center; font-weight:700; padding:6px;">Tanda Tangan Asesor</td>
            <td style="width:50%; text-align:center; font-weight:700; padding:6px;">Tanda Tangan Asesi</td>
        </tr>
        <tr>
            <td style="width:50%; text-align:center; padding:6px;">
                <div style="width:96px; height:54px; overflow:hidden; border:1px solid #111; display:flex; align-items:center; justify-content:center; margin:0 auto 3px;">
                    @if($item->ttd_asesor_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
            </td>
            <td style="width:50%; text-align:center; padding:6px;">
                <div style="width:96px; height:54px; overflow:hidden; border:1px solid #111; display:flex; align-items:center; justify-content:center; margin:0 auto 3px;">
                    @if($item->ttd_asesi_file)
                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:50%; text-align:center; padding:4px 3px 2px; font-size:8px;">
                <div style="font-weight:700; margin-bottom:1px;">{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</div>
                <div>{{ $item->ttd_asesor_tanggal?->format('d-m-Y') ?: 'Tanggal Pendatangan' }}</div>
            </td>
            <td style="width:50%; text-align:center; padding:4px 3px 2px; font-size:8px;">
                <div style="font-weight:700; margin-bottom:1px;">{{ $item->ttd_asesi_nama ?: 'Nama Asesi' }}</div>
                <div>{{ $item->ttd_asesi_tanggal?->format('d-m-Y') ?: 'Tanggal Pendatangan' }}</div>
            </td>
        </tr>
    </table>

    @if($item->catatan_footer)
        <div style="margin-top: 8px; font-size: 9px; color: #333;">
            <em>{{ $item->catatan_footer }}</em>
        </div>
    @endif
</body>
</html>
