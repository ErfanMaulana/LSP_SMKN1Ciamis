@extends('asesor.layout')

@section('title', 'Form Banding Asesmen')
@section('page-title', 'Form Banding Asesmen FR.AK.04')

@section('styles')
<style>
    .top-actions { margin-bottom:14px; }
    .btn-back { display:inline-flex; align-items:center; gap:6px; padding:9px 14px; border-radius:8px; background:#e2e8f0; color:#1e293b; text-decoration:none; font-weight:600; font-size:13px; }
    .panel { background:#fff; border:1px solid #111827; border-radius:2px; }
    .panel-head { padding:8px 10px; border-bottom:1px solid #111827; font-size:14px; font-weight:700; color:#0f172a; }
    .meta-table { width:100%; border-collapse:collapse; }
    .meta-table td { border-bottom:1px solid #111827; padding:6px 8px; font-size:13px; }
    .meta-table td:first-child { width:170px; font-weight:600; background:#f8fafc; }

    .check-table { width:100%; border-collapse:collapse; }
    .check-table th, .check-table td { border:1px solid #111827; padding:8px; font-size:13px; vertical-align:top; }
    .check-table th { background:#f8fafc; font-weight:700; }
    .check-table th:nth-child(2), .check-table th:nth-child(3), .check-table td:nth-child(2), .check-table td:nth-child(3) { width:80px; text-align:center; }

    .section { border-top:1px solid #111827; padding:10px; }
    .section h4 { margin:0 0 8px; font-size:13px; color:#0f172a; }
    .section p { margin:0; font-size:13px; color:#1f2937; line-height:1.45; }

    textarea { width:100%; min-height:120px; border:1px solid #94a3b8; border-radius:4px; padding:10px; font-family:inherit; font-size:13px; resize:vertical; }
    textarea:focus { outline:none; border-color:#0073bd; box-shadow:0 0 0 3px rgba(0,115,189,.1); }

    .status-box { margin-bottom:12px; padding:10px 12px; border-radius:10px; border:1px solid #cbd5e1; background:#f8fafc; font-size:13px; }
    .status-box strong { color:#0f172a; }

    .actions { padding:12px 10px; border-top:1px solid #111827; display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .btn { border:none; border-radius:8px; padding:10px 14px; font-size:14px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-secondary { background:#e2e8f0; color:#334155; text-decoration:none; }

    .error-text { margin-top:6px; color:#dc2626; font-size:12px; }

    @media (max-width: 768px) {
        .meta-table td:first-child { width:130px; }
        .check-table th:nth-child(2), .check-table th:nth-child(3), .check-table td:nth-child(2), .check-table td:nth-child(3) { width:58px; }
    }
</style>
@endsection

@section('content')
<div class="top-actions">
    <a href="{{ route('asesor.banding.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Monitoring Banding</a>
</div>

@php
    $bandingStatus = $banding->status ?? 'draft';
    $statusLabel = [
        'draft' => 'Belum Diajukan',
        'diajukan' => 'Diajukan',
        'ditinjau' => 'Ditinjau',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'tidak_banding' => 'Tidak Banding',
    ][$bandingStatus] ?? ucfirst($bandingStatus);

    $isLocked = true;
@endphp

<div class="status-box">
    <div><strong>Status Banding:</strong> {{ $statusLabel }}</div>
    @if($bandingStatus === 'tidak_banding')
        <div style="margin-top:4px;color:#475569;">Asesi memilih Tidak Banding pada skema ini.</div>
    @endif
    <div style="margin-top:4px;color:#475569;">Banding diajukan oleh asesi. Asesor hanya melakukan monitoring pada halaman ini.</div>
    @if($banding && $banding->checked_at)
        <div style="margin-top:4px;color:#475569;">Dicek admin pada {{ $banding->checked_at->format('d-m-Y H:i') }} WIB</div>
    @endif
    @if($banding && $banding->catatan_admin)
        <div style="margin-top:8px;"><strong>Catatan Admin:</strong> {{ $banding->catatan_admin }}</div>
    @endif
</div>

<div class="panel">
    <div class="panel-head">FR.AK.04. BANDING ASESMEN</div>

    <table class="meta-table">
        <tr>
            <td>Nama Asesi</td>
            <td>{{ $asesi->nama }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td>{{ $asesor->nama }}</td>
        </tr>
        <tr>
            <td>Tanggal Asesmen</td>
            <td>{{ $pivot->tanggal_selesai ? \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <div>

        <table class="check-table">
            <thead>
                <tr>
                    <th>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini</th>
                    <th>YA</th>
                    <th>TIDAK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    @php
                        $jawabanItem = collect($existingJawaban)->get($item->id);
                        $selected = old('jawaban.' . $item->id, optional($jawabanItem)->jawaban);
                    @endphp
                    <tr>
                        <td>{{ $item->pernyataan }}</td>
                        <td>
                            <input type="radio" name="jawaban[{{ $item->id }}]" value="ya" {{ $selected === 'ya' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="radio" name="jawaban[{{ $item->id }}]" value="tidak" {{ $selected === 'tidak' ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                    </tr>
                    @error('jawaban.' . $item->id)
                        <tr>
                            <td colspan="3" class="error-text">{{ $message }}</td>
                        </tr>
                    @enderror
                @endforeach
            </tbody>
        </table>

        <div class="section">
            <h4>Banding ini diajukan atas keputusan asesmen yang dibuat terhadap skema sertifikasi berikut:</h4>
            <p>Skema Sertifikasi: {{ $skema->nama_skema }}</p>
            <p>No. Skema Sertifikasi: {{ $skema->nomor_skema }}</p>
            <p style="margin-top:6px;">Keputusan Asesmen: <strong>{{ $pivot->rekomendasi === 'lanjut' ? 'Asesmen dapat dilanjutkan' : 'Asesmen tidak dapat dilanjutkan' }}</strong></p>
        </div>

        <div class="section">
            <h4>Banding ini diajukan atas alasan sebagai berikut:</h4>
            <textarea name="alasan_banding" {{ $isLocked ? 'readonly' : '' }}>{{ old('alasan_banding', $banding->alasan_banding ?? '') }}</textarea>
            @error('alasan_banding')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="section">
            <p>Anda mempunyai hak mengajukan banding jika menilai Proses Asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.</p>
        </div>

        <div class="actions">
            <a href="{{ route('asesor.banding.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
        </div>
    </div>
</div>
@endsection
