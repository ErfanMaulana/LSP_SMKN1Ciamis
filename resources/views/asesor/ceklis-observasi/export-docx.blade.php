<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>FR.IA.01 - Ceklis Observasi Aktivitas Praktik</title>
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
            height: 54px;
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
                <div class="title" style="margin:0;">{{ $item->kode_form }} &nbsp;&nbsp; {{ $item->judul_form }}</div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:28%;">Skema Sertifikasi</td>
            <td style="width:2%;">:</td>
            <td>{{ $item->skema?->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Skema</td>
            <td>:</td>
            <td>{{ $item->skema?->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td>Asesi</td>
            <td>:</td>
            <td>{{ $item->asesi?->nama ?? $item->asesi_nik ?? '-' }}</td>
        </tr>
        <tr>
            <td>Asesor</td>
            <td>:</td>
            <td>{{ $item->ttd_asesor_nama ?? $item->asesor?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>TUK / Tanggal</td>
            <td>:</td>
            <td>{{ $item->tuk ?? '-' }} / {{ $item->tanggal?->format('d-m-Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td>Rekomendasi</td>
            <td>:</td>
            <td>{{ strtoupper($item->rekomendasi ?? '-') }}</td>
        </tr>
    </table>

    @forelse($detailsByUnit as $unitDetails)
        @php $unit = $unitDetails->first()?->unit; @endphp
        <table class="section-gap">
            <tr>
                <td colspan="5" class="bold">{{ $unit?->kode_unit }} - {{ $unit?->judul_unit }}</td>
            </tr>
            <tr>
                <th style="width:40px;">No.</th>
                <th style="width:220px;">Elemen</th>
                <th>Kriteria Unjuk Kerja</th>
                <th style="width:70px;">Capaian</th>
                <th style="width:180px;">Penilaian Lanjut</th>
            </tr>
            @foreach($unitDetails as $idx => $detail)
                <tr>
                    <td class="center">{{ $idx + 1 }}</td>
                    <td>{{ $detail->elemen?->nama_elemen ?? '-' }}</td>
                    <td>{{ $detail->kriteria?->deskripsi_kriteria ?? '-' }}</td>
                    <td class="center">{{ strtoupper($detail->pencapaian ?? '-') }}</td>
                    <td>{{ $detail->penilaian_lanjut ?: '-' }}</td>
                </tr>
            @endforeach
        </table>
    @empty
        <table class="section-gap">
            <tr>
                <td>Belum ada detail checklist.</td>
            </tr>
        </table>
    @endforelse

    @php
        $parseMultiValue = function ($raw) {
            $raw = trim((string) $raw);

            if ($raw === '') {
                return collect();
            }

            return collect(preg_split('/\s*(?:\||,|\r\n|\r|\n)\s*/', $raw))
                ->filter(fn ($item) => trim((string) $item) !== '')
                ->map(fn ($item) => trim((string) $item))
                ->values();
        };

        $belumKompetenKelompok = $parseMultiValue($item->belum_kompeten_kelompok_pekerjaan ?? '');
        $belumKompetenUnit = $parseMultiValue($item->belum_kompeten_unit ?? '');
        $belumKompetenElemen = $parseMultiValue($item->belum_kompeten_elemen ?? '');
        $belumKompetenKuk = $parseMultiValue($item->belum_kompeten_kuk ?? '');
    @endphp

    <table class="section-gap">
        <tr>
            <td class="bold">Belum Kompeten Pada</td>
        </tr>
        <tr>
            <td>
                Kelompok: {{ $belumKompetenKelompok->isNotEmpty() ? $belumKompetenKelompok->implode(', ') : '-' }}<br>
                Unit: {{ $belumKompetenUnit->isNotEmpty() ? $belumKompetenUnit->implode(', ') : '-' }}<br>
                Elemen: {{ $belumKompetenElemen->isNotEmpty() ? $belumKompetenElemen->implode(', ') : '-' }}<br>
                KUK: {{ $belumKompetenKuk->isNotEmpty() ? $belumKompetenKuk->implode(', ') : '-' }}
            </td>
        </tr>
    </table>

    <table class="section-gap">
        <tr>
            <td colspan="4" class="bold">Tanda Tangan</td>
        </tr>
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
                <div>{{ $item->ttd_asesor_tanggal?->format('d-m-Y') ?: 'Tanggal Tanda Tangan' }}</div>
            </td>
            <td style="width:50%; text-align:center; padding:4px 3px 2px; font-size:8px;">
                <div style="font-weight:700; margin-bottom:1px;">{{ $item->ttd_asesi_nama ?: 'Nama Asesi' }}</div>
                <div>{{ $item->ttd_asesi_tanggal?->format('d-m-Y') ?: 'Tanggal Tanda Tangan' }}</div>
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