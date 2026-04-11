@extends('admin.layout')

@section('title', 'Detail Persetujuan Asesmen')
@section('page-title', 'Detail Persetujuan Asesmen')

@section('styles')
<style>
    .top-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .doc-wrap {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 18px;
        overflow-x: auto;
    }

    .doc {
        min-width: 760px;
        border: 1px solid #111827;
        font-size: 13px;
        color: #111827;
        border-collapse: collapse;
        width: 100%;
    }

    .doc td {
        border: 1px solid #111827;
        padding: 6px;
        vertical-align: top;
    }

    .doc .title {
        border: none;
        padding: 0 0 10px;
        font-weight: 700;
        font-size: 14px;
    }

    .doc .no-border { border: none; }

    .check {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #111827;
        text-align: center;
        line-height: 10px;
        margin-right: 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .notes {
        margin-top: 8px;
        font-size: 12px;
        color: #334155;
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    <a href="{{ route('admin.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    @if(Auth::guard('admin')->user()->hasPermission('persetujuan-asesmen.edit'))
        <a href="{{ route('admin.persetujuan-asesmen.edit', $item->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
    @endif
</div>

<div class="doc-wrap">
    <table class="doc">
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
            <td>{{ $item->judul_skema }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $item->nomor_skema }}</td>
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
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;">
                    <div><span class="check">{{ $item->bukti_verifikasi_portofolio ? 'V' : '' }}</span>Hasil Verifikasi Portofolio</div>
                    <div><span class="check">{{ $item->bukti_reviu_produk ? 'V' : '' }}</span>Hasil Reviu Produk</div>
                    <div><span class="check">{{ $item->bukti_observasi_langsung ? 'V' : '' }}</span>Hasil Observasi Langsung</div>
                    <div><span class="check">{{ $item->bukti_kegiatan_terstruktur ? 'V' : '' }}</span>Hasil Kegiatan Terstruktur</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_lisan ? 'V' : '' }}</span>Hasil Pertanyaan Lisan</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_tertulis ? 'V' : '' }}</span>Hasil Pertanyaan Tertulis</div>
                    <div><span class="check">{{ $item->bukti_lainnya ? 'V' : '' }}</span>Lainnya {{ $item->bukti_lainnya_keterangan ? ': ' . $item->bukti_lainnya_keterangan : '' }}</div>
                    <div><span class="check">{{ $item->bukti_pertanyaan_wawancara ? 'V' : '' }}</span>Hasil Pertanyaan Wawancara</div>
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
    </table>

    @if($item->catatan_footer)
        <div class="notes"><em>{{ $item->catatan_footer }}</em></div>
    @endif
</div>
@endsection
