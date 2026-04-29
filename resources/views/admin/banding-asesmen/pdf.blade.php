<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.AK.04 BANDING ASESMEN</title>
    <style>
        @page {
            margin: 20px 20px 22px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .title {
            font-weight: 700;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            border: 1px solid #111827;
            padding: 6px;
        }

        .meta td:first-child {
            width: 160px;
        }

        .check th,
        .check td {
            border: 1px solid #111827;
            padding: 6px;
            vertical-align: top;
        }

        .check th {
            background: #f3f4f6;
            text-align: left;
        }

        .check th:nth-child(2),
        .check th:nth-child(3),
        .check td:nth-child(2),
        .check td:nth-child(3) {
            width: 50px;
            text-align: center;
        }

        .section {
            border: 1px solid #111827;
            border-top: none;
            padding: 8px;
            line-height: 1.5;
        }

        .label {
            font-weight: 700;
        }

        .reason-box {
            border: 1px solid #111827;
            border-top: none;
            min-height: 100px;
            padding: 8px;
            white-space: pre-line;
            line-height: 1.5;
        }

        .footer {
            margin-top: 12px;
            border: 1px solid #111827;
            padding: 8px;
            line-height: 1.5;
        }

        .admin-note {
            margin-top: 10px;
            border: 1px solid #111827;
            padding: 8px;
        }

        .admin-note h4 {
            margin: 0 0 6px;
            font-size: 11px;
        }

        .muted {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="title">FR.AK.04. BANDING ASESMEN</div>

    <table class="meta">
        <tr>
            <td>Nama Asesi</td>
            <td>{{ $banding->asesi->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td>{{ $banding->asesor->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Asesmen</td>
            <td>{{ $banding->tanggal_asesmen ? $banding->tanggal_asesmen->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <table class="check" style="margin-top: 0;">
        <thead>
            <tr>
                <th>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini:</th>
                <th>YA</th>
                <th>TIDAK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komponen as $item)
                @php $jawab = optional($jawabanMap->get($item->id))->jawaban; @endphp
                <tr>
                    <td>{{ $item->pernyataan }}</td>
                    <td>{{ $jawab === 'ya' ? 'X' : '' }}</td>
                    <td>{{ $jawab === 'tidak' ? 'X' : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section">
        <div><span class="label">Banding ini diajukan atas keputusan asesmen yang dibuat terhadap skema sertifikasi (kualifikasi/klaster/okupasi) berikut:</span></div>
        <div>Skema Sertifikasi: {{ $banding->skema->nama_skema ?? '-' }}</div>
        <div>No. Skema Sertifikasi: {{ $banding->skema->nomor_skema ?? '-' }}</div>
        <div>Keputusan Asesmen: {{ $rekomendasiAsesmen === 'lanjut' ? 'Asesmen dapat dilanjutkan' : ($rekomendasiAsesmen === 'tidak_lanjut' ? 'Asesmen tidak dapat dilanjutkan' : '-') }}</div>
    </div>

    <div class="section">
        <span class="label">Banding ini diajukan atas alasan sebagai berikut:</span>
    </div>
    <div class="reason-box">{{ $banding->alasan_banding }}</div>

    <div class="footer">
        Anda mempunyai hak mengajukan banding jika Anda menilai Proses Asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
    </div>

    <div class="admin-note">
        <h4>Catatan Pengecekan Admin</h4>
        <div>Status: {{ ucfirst($banding->status) }}</div>
        <div>Catatan: {{ $banding->catatan_admin ?: '-' }}</div>
        <div class="muted" style="margin-top: 4px;">
            Dicek oleh: {{ $banding->checker->name ?? '-' }}
            @if($banding->checked_at)
                | Tanggal cek: {{ $banding->checked_at->format('d-m-Y H:i') }} WIB
            @endif
        </div>
    </div>
</body>
</html>
