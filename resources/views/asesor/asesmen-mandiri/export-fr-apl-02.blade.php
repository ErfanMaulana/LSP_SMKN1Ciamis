<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.APL.02 - Asesmen Mandiri</title>
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
        .section-gap { margin-top: 8px; }
        .kriteria-line { margin-left: 8px; }
        .signature-box { border: none; height: 52px; display: flex; align-items: center; justify-content: center; margin-top: 4px; margin-bottom: 4px; overflow: hidden; }
        .signature-box img { max-height: 44px; max-width: 95%; }
        .signature-left { width: 40%; vertical-align: top; padding: 6px 8px; text-align: left; }
        .signature-right { width: 60%; vertical-align: top; padding: 0; }
        .signature-grid { border-collapse: collapse; width: 100%; table-layout: fixed; font-size: 8px; }
        .signature-grid td { border-top: 1px solid #111; border-right: 1px solid #111; border-bottom: 1px solid #111; border-left: none; padding: 4px 5px; vertical-align: top; }
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
                <div class="title" style="margin:0;">FR.APL.02. ASESMEN MANDIRI</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td rowspan="2" style="width:27%; vertical-align:middle;" class="small">
                Skema Sertifikasi<br>
                <span class="small">(KKNI/Okupasi/Klaster)</span>
            </td>
            <td style="width:12%;" class="small">Judul</td>
            <td style="width:3%;" class="center">:</td>
            <td>{{ $skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td class="small">Nomor</td>
            <td class="center">:</td>
            <td>{{ $skema->nomor_skema ?? '-' }}</td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td class="bold">PANDUAN ASESMEN MANDIRI</td>
        </tr>
        <tr>
            <td>
                <div class="bold" style="margin-bottom:4px;">Instruksi:</div>
                <div style="margin-bottom:3px;">- Baca setiap pertanyaan di kolom sebelah kiri.</div>
                <div style="margin-bottom:3px;">- Beri tanda centang (v) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.</div>
                <div>- Isi kolom bukti yang relevan untuk menunjukkan bahwa Anda mampu melakukan pekerjaan.</div>
            </td>
        </tr>
    </table>

    @foreach($skema->units as $unitIndex => $unit)
        <table class="section-gap">
            <tr>
                <td rowspan="2" style="width:22%; vertical-align:middle;" class="bold">Unit Kompetensi {{ $unitIndex + 1 }}</td>
                <td style="width:14%;">Kode Unit</td>
                <td style="width:3%; text-align:center;">:</td>
                <td>{{ $unit->kode_unit ?? '-' }}</td>
            </tr>
            <tr>
                <td>Judul Unit</td>
                <td style="text-align:center;">:</td>
                <td>{{ $unit->judul_unit ?? '-' }}</td>
            </tr>
        </table>

        <table class="section-gap">
            <tr>
                <th style="width:50%; text-align:left;">{{ $unit->pertanyaan_unit ?? ('Dapatkah Saya ' . ($unit->judul_unit ?? 'melakukan unit ini') . '?') }}</th>
                <th style="width:8%;" class="center">K</th>
                <th style="width:8%;" class="center">BK</th>
                <th style="width:34%;">Bukti yang relevan</th>
            </tr>
            @foreach($unit->elemens as $elemenIndex => $elemen)
                @php $answer = $answers->get($elemen->id); @endphp
                <tr>
                    <td>
                        <div class="bold">{{ $elemenIndex + 1 }}. Elemen: {{ $elemen->nama_elemen }}</div>
                        <div style="margin-top:3px;">* Kriteria Unjuk Kerja:</div>
                        @forelse($elemen->kriteria as $kriteria)
                            <div class="kriteria-line">{{ ($elemenIndex + 1) . '.' . ($loop->iteration) }} {{ $kriteria->deskripsi_kriteria }}</div>
                        @empty
                            <div class="kriteria-line">-</div>
                        @endforelse
                    </td>
                    <td class="center">{{ $answer && $answer->status === 'K' ? '✔' : '' }}</td>
                    <td class="center">{{ $answer && $answer->status === 'BK' ? '✔' : '' }}</td>
                    <td>{{ $answer->bukti ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    <table class="section-gap" style="margin-top:6px; table-layout:fixed;">
        <tr>
            <td class="signature-left" rowspan="9">
                <div style="font-weight:700; font-size:14.5px; margin-bottom:6px;">Rekomendasi Untuk Asesi:</div>
                <div style="font-size:14.5px; line-height:1.2;">
                    @if($pivot->rekomendasi === 'lanjut')
                        Asesmen dapat dilanjutkan
                    @elseif($pivot->rekomendasi === 'tidak_lanjut')
                        Asesmen tidak dapat dilanjutkan
                    @else
                        -
                    @endif
                </div>
            </td>
            <td colspan="2" style="font-weight:700;">Asesi :</td>
        </tr>
        <tr>
            <td style="width:28%;">Nama</td>
            <td>{{ $asesi->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanda tangan</td>
            <td style="padding:2px;">
                <div class="signature-box">
                    @if(!empty($pivot->tanda_tangan))
                        <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>{{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:700;">Ditinjau Oleh Asesor :</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>{{ $asesor->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Reg</td>
            <td>{{ $asesor->no_met ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanda tangan</td>
            <td style="padding:2px;">
                <div class="signature-box">
                    @if(!empty($pivot->tanda_tangan_asesor))
                        <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda Tangan Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>{{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>
</body>
</html>
