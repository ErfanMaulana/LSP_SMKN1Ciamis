<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.APL.02 - Asesmen Mandiri</title>
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
        .kriteria-line { margin-left: 8px; }
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
                <div class="title" style="margin:0;">FR.APL.02. ASESMEN MANDIRI</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:16%;" class="small">Skema Sertifikasi<br><span class="small">(KKNI/Okupasi/Klaster)</span></td>
            <td style="width:9%;" class="small">Judul</td>
            <td>{{ $skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td class="small"></td>
            <td class="small">Nomor</td>
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
                <td style="width:30%;" class="bold">Unit Kompetensi {{ $unitIndex + 1 }}</td>
                <td class="small"></td>
            </tr>
            <tr>
                <td class="small">Kode Unit:</td>
                <td>{{ $unit->kode_unit ?? '-' }}</td>
            </tr>
            <tr>
                <td class="small">Judul Unit:</td>
                <td>{{ $unit->judul_unit ?? '-' }}</td>
            </tr>
        </table>

        <table class="section-gap">
            <tr>
                <th style="width:50%;">{{ $unit->pertanyaan_unit ?? ('Dapatkah Saya ' . ($unit->judul_unit ?? 'melakukan unit ini') . '?') }}</th>
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
                    <td class="center">{{ $answer && $answer->status === 'K' ? 'v' : '' }}</td>
                    <td class="center">{{ $answer && $answer->status === 'BK' ? 'v' : '' }}</td>
                    <td>{{ $answer->bukti ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

    <table class="section-gap" style="margin-top:6px; table-layout:fixed;">
        <tr>
            <td style="width:40%; vertical-align:top;">
                <div style="font-weight:700; font-size:8px; margin-bottom:4px;">Rekomendasi Untuk Asesi:</div>
                <div style="font-size:8px;">
                    @if($pivot->rekomendasi === 'lanjut')
                        Asesmen dapat dilanjutkan
                    @elseif($pivot->rekomendasi === 'tidak_lanjut')
                        Asesmen tidak dapat dilanjutkan
                    @else
                        -
                    @endif
                </div>
            </td>
            <td style="width:60%; vertical-align:top; padding:0;">
                <table style="border-collapse:collapse; width:100%; table-layout:fixed; font-size:8px;">
                    <tr>
                        <td colspan="2" style="font-weight:700;">Asesi :</td>
                    </tr>
                    <tr>
                        <td style="width:28%; font-weight:700;">Nama</td>
                        <td>{{ $asesi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Tanda tangan</td>
                        <td style="padding:2px;">
                            <div style="width:96px; height:54px; overflow:hidden; border:1px solid #111; display:flex; align-items:center; justify-content:center;">
                                @if(!empty($pivot->tanda_tangan))
                                    <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Tanggal</td>
                        <td>{{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight:700;">Ditinjau Oleh Asesor :</td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Nama</td>
                        <td>{{ $asesor->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">No. Reg</td>
                        <td>{{ $asesor->no_met ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Tanda tangan</td>
                        <td style="padding:2px;">
                            <div style="width:96px; height:54px; overflow:hidden; border:1px solid #111; display:flex; align-items:center; justify-content:center;">
                                @if(!empty($pivot->tanda_tangan_asesor))
                                    <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda Tangan Asesor" width="96" height="54" style="width:96px; height:54px; object-fit:contain; display:block;">
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:700;">Tanggal</td>
                        <td>{{ $pivot->reviewed_at ? \Carbon\Carbon::parse($pivot->reviewed_at)->format('d-m-Y') : '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
