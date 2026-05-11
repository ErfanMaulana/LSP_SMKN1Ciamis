<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.APL.03 - Hasil Asesmen Mandiri</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11.5px; color: #111827; }
        .header { display: table; width: 100%; margin-bottom: 14px; }
        .header-cell { display: table-cell; vertical-align: middle; }
        .header-left { width: 18%; }
        .header-right { width: 82%; padding-left: 10px; }
        .title { font-size: 15px; font-weight: 700; margin: 0; }
        .subtitle { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .box { border: 1px solid #111827; border-radius: 4px; padding: 10px 12px; margin-bottom: 10px; }
        .section-title { font-size: 12.5px; font-weight: 700; margin: 0 0 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 6px 7px; vertical-align: top; }
        .label { width: 30%; font-weight: 700; }
        .value { width: 70%; }
        .result-table th, .result-table td { border: 1px solid #111827; }
        .result-table th { background: #f3f4f6; text-align: left; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; }
        .badge-k { background: #dcfce7; color: #166534; }
        .badge-bk { background: #fee2e2; color: #991b1b; }
        .badge-empty { background: #e5e7eb; color: #374151; }
        .footer { margin-top: 18px; font-size: 10px; color: #6b7280; }
        .signature-table { margin-top: 18px; }
        .signature-cell { width: 50%; text-align: center; }
        .sign-space { height: 52px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-cell header-left">
            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="logo" style="width:72px;height:auto;">
            @endif
        </div>
        <div class="header-cell header-right">
            <p class="title">FR.APL.03 - HASIL ASESMEN MANDIRI</p>
            <div class="subtitle">Rekap hasil jawaban asesmen mandiri yang telah diselesaikan</div>
        </div>
    </div>

    <div class="box">
        <div class="section-title">Data Asesi</div>
        <table>
            <tr><td class="label">Nama</td><td class="value">{{ $asesi->nama ?? '-' }}</td></tr>
            <tr><td class="label">NIK</td><td class="value">{{ $asesi->NIK ?? '-' }}</td></tr>
            <tr><td class="label">Skema</td><td class="value">{{ $skema->nama_skema ?? '-' }}</td></tr>
            <tr><td class="label">Nomor Skema</td><td class="value">{{ $skema->nomor_skema ?? '-' }}</td></tr>
            <tr><td class="label">Status</td><td class="value">Selesai</td></tr>
        </table>
    </div>

    <div class="box">
        <div class="section-title">Ringkasan Jawaban</div>
        <table class="result-table">
            <thead>
                <tr>
                    <th style="width:6%;">No</th>
                    <th>Unit / Elemen</th>
                    <th style="width:12%;">Status</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($skema->units as $unit)
                    @foreach($unit->elemens as $elemen)
                        @php $answer = $answers->get($elemen->id); @endphp
                        <tr>
                            <td style="text-align:center;">{{ $no++ }}</td>
                            <td>
                                <strong>{{ $unit->nama_unit ?? $unit->judul_unit ?? $unit->kode_unit ?? '-' }}</strong><br>
                                <span style="color:#6b7280;">{{ $elemen->nama_elemen }}</span>
                            </td>
                            <td style="text-align:center;">
                                @if($answer)
                                    <span class="badge {{ $answer->status === 'K' ? 'badge-k' : 'badge-bk' }}">{{ $answer->status }}</span>
                                @else
                                    <span class="badge badge-empty">-</span>
                                @endif
                            </td>
                            <td>{{ $answer->bukti ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                Pemohon,<br>
                <div class="sign-space"></div>
                ({{ $asesi->nama ?? '-' }})
            </td>
            <td class="signature-cell">
                Asesor / Verifikator,<br>
                <div class="sign-space"></div>
                ({{ optional($pivot)->reviewed_by ?? '-' }})
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>