<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111827;
            margin: 0;
            padding: 20px;
        }
        
        .document {
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #111827;
            margin-bottom: 20px;
        }

        td {
            border: 1px solid #111827;
            padding: 8px;
            vertical-align: top;
        }

        .title {
            border: none;
            padding: 0 0 10px;
            font-weight: bold;
            font-size: 14px;
            background: #f5f5f5;
            padding: 10px;
        }

        .no-border {
            border: none;
        }

        .check {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #111827;
            text-align: center;
            line-height: 10px;
            margin-right: 6px;
            font-size: 10px;
            font-weight: bold;
        }

        .signature-row {
            padding: 16px;
            text-align: center;
            background: #f8fafc;
        }

        .signature-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
            border-right: 1px solid #e5e7eb;
        }

        .signature-box:last-child {
            border-right: none;
        }

        .signature-box h4 {
            margin: 0 0 12px;
            color: #0f172a;
            font-size: 14px;
            font-weight: bold;
        }

        .signature-frame {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 12px;
            min-height: 120px;
            background: white;
            margin: 0 auto 8px;
            text-align: center;
        }

        .signature-frame img {
            max-width: 100%;
            max-height: 120px;
        }

        .signature-frame.no-signature {
            color: #94a3b8;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-meta {
            margin: 8px 0 0;
            font-size: 13px;
            color: #64748b;
            line-height: 1.35;
        }

        .signature-meta strong {
            font-weight: bold;
            color: #111827;
        }

        .notes {
            margin-top: 12px;
            font-size: 12px;
            color: #334155;
            font-style: italic;
        }

        .grid-2col {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col {
            display: table-cell;
            width: 50%;
            padding: 0 12px 12px 0;
            vertical-align: top;
        }

        .col:nth-child(2n) {
            padding-right: 0;
        }

        .col-item {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="document">
        <table>
            <tbody>
                <tr>
                    <td class="title no-border" colspan="4">{{ $item->kode_form }} &nbsp;&nbsp; {{ $item->judul_form }}</td>
                </tr>
                <tr>
                    <td colspan="4">{{ $item->pengantar }}</td>
                </tr>

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

                <tr>
                    <td>Bukti yang akan dikumpulkan:</td>
                    <td colspan="3">
                        <div class="grid-2col">
                            <div class="col">
                                <div class="col-item"><span class="check">{{ $item->bukti_verifikasi_portofolio ? 'V' : '' }}</span>Hasil Verifikasi Portofolio</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_observasi_langsung ? 'V' : '' }}</span>Hasil Observasi Langsung</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_pertanyaan_lisan ? 'V' : '' }}</span>Hasil Pertanyaan Lisan</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_lainnya ? 'V' : '' }}</span>Lainnya {{ $item->bukti_lainnya_keterangan ? ': ' . $item->bukti_lainnya_keterangan : '' }}</div>
                            </div>
                            <div class="col">
                                <div class="col-item"><span class="check">{{ $item->bukti_reviu_produk ? 'V' : '' }}</span>Hasil Reviu Produk</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_kegiatan_terstruktur ? 'V' : '' }}</span>Hasil Kegiatan Terstruktur</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_pertanyaan_tertulis ? 'V' : '' }}</span>Hasil Pertanyaan Tertulis</div>
                                <div class="col-item"><span class="check">{{ $item->bukti_pertanyaan_wawancara ? 'V' : '' }}</span>Hasil Pertanyaan Wawancara</div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td rowspan="3">Pelaksanaan asesmen disepakati pada:</td>
                    <td>Hari / Tanggal</td>
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

                <tr>
                    <td colspan="4"><strong>Asesi:</strong><br>{{ $item->pernyataan_asesi_1 }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Asesor:</strong><br>{{ $item->pernyataan_asesor }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Asesi:</strong><br>{{ $item->pernyataan_asesi_2 }}</td>
                </tr>

                <tr>
                    <td colspan="2">Tanda tangan Asesor : {{ $item->ttd_asesor_nama ?: '............................' }}</td>
                    <td colspan="2">Tanggal : {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: '............................' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Tanda tangan Asesi : {{ $item->ttd_asesi_nama ?: '............................' }}</td>
                    <td colspan="2">Tanggal : {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: '............................' }}</td>
                </tr>

                <tr>
                    <td colspan="4">
                        <div class="signature-grid">
                            <div class="signature-box">
                                <h4>Tanda Tangan Asesor</h4>
                                <div class="signature-frame {{ !$item->ttd_asesor_file ? 'no-signature' : '' }}">
                                    @if($item->ttd_asesor_file)
                                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesor_file, '/')) }}" alt="Signature Asesor">
                                    @else
                                        Belum ditandatangani
                                    @endif
                                </div>
                                <p class="signature-meta">
                                    <strong>{{ $item->ttd_asesor_nama ?: 'Nama Asesor' }}</strong><br>
                                    {{ $item->ttd_asesor_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                                </p>
                            </div>

                            <div class="signature-box">
                                <h4>Tanda Tangan Asesi</h4>
                                <div class="signature-frame {{ !$item->ttd_asesi_file ? 'no-signature' : '' }}">
                                    @if($item->ttd_asesi_file)
                                        <img src="{{ asset('storage/' . ltrim($item->ttd_asesi_file, '/')) }}" alt="Signature Asesi">
                                    @else
                                        Belum ditandatangani
                                    @endif
                                </div>
                                <p class="signature-meta">
                                    <strong>{{ $item->ttd_asesi_nama ?: 'Nama Asesi' }}</strong><br>
                                    {{ $item->ttd_asesi_tanggal?->locale('id')->translatedFormat('d F Y') ?: 'Tanggal Pendatangan' }}
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        @if($item->catatan_footer)
            <div class="notes"><em>{{ $item->catatan_footer }}</em></div>
        @endif
    </div>
</body>
</html>
