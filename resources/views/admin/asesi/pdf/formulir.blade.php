<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FR.APL.01 - Formulir Pendaftaran</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#111; }
        .header { text-align:center; margin-bottom:12px; }
        .title { font-weight:700; font-size:14px; }
        .section { margin-top:10px; }
        table { width:100%; border-collapse:collapse; }
        td, th { padding:6px; vertical-align:top; }
        .label { width:28%; font-weight:700; }
        .value { width:72%; }
        .box { display:inline-block; width:12px; height:12px; border:1px solid #000; margin-right:6px; vertical-align:middle; }
        .small { font-size:11px; }
        .units-table, .bukti-table { border:1px solid #000; }
        .units-table th, .units-table td, .bukti-table th, .bukti-table td { border:1px solid #000; padding:6px; }
    </style>
</head>
<body>
    <div class="header" style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:12px;">
            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="logo" style="width:72px;height:auto;" />
            @else
                <div style="width:72px;height:72px;border:1px solid #000;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">LOGO</div>
            @endif
            <div style="text-align:left;">
                <div style="font-size:13px;font-weight:700;">FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI</div>
                <div class="small">Formulir Pendaftaran Asesi</div>
            </div>
        </div>
        <div style="text-align:right;font-size:11px;">
            <div>No. Dokumen: FR.APL.01</div>
            <div>Tgl Cetak: {{ now()->format('d-m-Y') }}</div>
        </div>
    </div>

    <div style="border-top:2px solid #000;margin-top:10px;padding-top:10px;">
        <table style="width:100%;margin-bottom:6px;">
            <tr>
                <td style="vertical-align:top;width:48%;padding-right:10px;">
                    <strong>1. Rincian Data Pemohon</strong>
                    <table style="width:100%;margin-top:6px;">
                        <tr><td class="label">Nama</td><td class="value">{{ $asesi->nama ?? '-' }}</td></tr>
                        <tr><td class="label">NIK</td><td class="value">{{ $asesi->NIK ?? '-' }}</td></tr>
                        <tr><td class="label">Tempat/Tgl Lahir</td><td class="value">{{ $asesi->tempat_lahir ?? '-' }} / {{ optional($asesi->tanggal_lahir)->format('d-m-Y') ?? '-' }}</td></tr>
                        <tr><td class="label">Jenis Kelamin</td><td class="value">{{ $asesi->jenis_kelamin ?? '-' }}</td></tr>
                        <tr><td class="label">Alamat</td><td class="value">{{ $asesi->alamat ?? '-' }}</td></tr>
                        <tr><td class="label">Telepon / Email</td><td class="value">{{ $asesi->telepon_hp ?? '-' }} / {{ $asesi->email ?? '-' }}</td></tr>
                        <tr><td class="label">Pendidikan</td><td class="value">{{ $asesi->pendidikan_terakhir ?? '-' }}</td></tr>
                    </table>
                </td>
                <td style="vertical-align:top;width:52%;padding-left:10px;">
                    <strong>2. Data Sertifikasi</strong>
                    <table style="width:100%;margin-top:6px;">
                        <tr><td class="label">Skema Sertifikasi</td><td class="value">{{ $skema->nama_skema ?? '-' }} @if(!empty($skema->nomor_skema)) ({{ $skema->nomor_skema }}) @endif</td></tr>
                        <tr><td class="label">Tujuan Asesmen</td><td class="value">Sertifikasi</td></tr>
                        <tr><td class="label">Tanggal Daftar</td><td class="value">{{ optional($asesi->created_at)->format('d-m-Y') ?? '-' }}</td></tr>
                    </table>

                    @if($skema && ($skema->units ?? false))
                        <div style="margin-top:8px;">
                            <table class="units-table">
                                <thead>
                                    <tr><th style="width:6%;">No</th><th>Kode</th><th>Judul Unit</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($skema->units ?? [] as $i => $unit)
                                        <tr>
                                            <td style="text-align:center;">{{ $i+1 }}</td>
                                            <td>{{ $unit['kode'] ?? ($unit->kode ?? '-') }}</td>
                                            <td>{{ $unit['judul'] ?? ($unit->judul ?? '-') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <strong>3. Bukti Persyaratan</strong>
        <table class="bukti-table" style="margin-top:6px;">
            <thead>
                <tr><th style="width:6%;">No</th><th>Uraian Bukti</th><th style="width:20%;">Ada / Tidak</th></tr>
            </thead>
            <tbody>
                @php $idx=1; @endphp
                @foreach(($bukti_persyaratan ?? []) as $key => $val)
                    <tr>
                        <td style="text-align:center;">{{ $idx++ }}</td>
                        <td>{{ is_string($key) ? $key : ($val['label'] ?? $key) }}</td>
                        <td style="text-align:center;">@php $state = is_array($val) && isset($val['status']) ? $val['status'] : (is_string($val) ? $val : null); @endphp
                            @if($state === 'memenuhi') v @elseif($state === 'tidak_memenuhi') x @else - @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width:100%;margin-top:18px;">
            <tr>
                <td style="width:50%;text-align:center;">
                    Pemohon,<br/>
                    @if(!empty($pendaftarSignature))
                        <img src="{{ $pendaftarSignature }}" alt="signature" style="width:80px;height:auto;margin:6px 0;" />
                    @else
                        <div style="height:40px;"></div>
                    @endif
                    <br/>({{ $asesi->nama ?? '-' }})
                </td>
                <td style="width:50%;text-align:center;">
                    Verifikator,<br/>
                    @if(!empty($verifikatorSignature))
                        <img src="{{ $verifikatorSignature }}" alt="signature" style="width:80px;height:auto;margin:6px 0;" />
                    @else
                        <div style="height:40px;"></div>
                    @endif
                    <br/>({{ optional($asesi->verifiedBy)->nama ?? '-' }})
                </td>
            </tr>
        </table>
    </div>
</body>
</html>