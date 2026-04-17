@extends('admin.layout')

@section('title', 'Detail Banding Asesmen')
@section('page-title', 'Detail Banding Asesmen')

@section('styles')
<style>
    .top { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; margin-bottom:14px; }
    .top h2 { margin:0; font-size:22px; color:#0f172a; }
    .top-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-back { border:none; border-radius:8px; padding:9px 14px; background:#e2e8f0; color:#334155; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:6px; }
    .btn-download { border:none; border-radius:8px; padding:9px 14px; background:#0073bd; color:#fff; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:6px; }

    .grid { display:grid; grid-template-columns:2fr 1fr; gap:14px; }
    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; }
    .body { padding:16px; }

    .title { font-size:14px; font-weight:700; color:#0f172a; margin-bottom:10px; }
    .meta { display:grid; grid-template-columns:170px 1fr; gap:8px; font-size:13px; color:#334155; }
    .meta div:nth-child(odd) { color:#64748b; }

    .check-table { width:100%; border-collapse:collapse; margin-top:10px; }
    .check-table th, .check-table td { border:1px solid #e2e8f0; padding:8px 10px; font-size:13px; }
    .check-table th { background:#f8fafc; text-align:left; color:#64748b; text-transform:uppercase; font-size:11px; letter-spacing:.4px; }
    .check-table th:nth-child(2), .check-table th:nth-child(3), .check-table td:nth-child(2), .check-table td:nth-child(3) { width:70px; text-align:center; }

    .reason { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px; font-size:13px; color:#334155; white-space:pre-line; }
    .badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge.diajukan { background:#dbeafe; color:#1e40af; }
    .badge.ditinjau { background:#fef3c7; color:#92400e; }
    .badge.diterima { background:#dcfce7; color:#166534; }
    .badge.ditolak { background:#fee2e2; color:#991b1b; }
    .badge.tidak_banding { background:#e5e7eb; color:#374151; }

    .input, .select, .textarea { width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:9px 11px; font-size:14px; font-family:inherit; }
    .textarea { min-height:110px; resize:vertical; }
    .btn-submit { border:none; border-radius:8px; padding:10px 14px; background:#0073bd; color:#fff; font-size:14px; font-weight:700; cursor:pointer; width:100%; }
    .error-text { font-size:12px; color:#dc2626; margin-top:6px; }

    @media (max-width: 992px) {
        .grid { grid-template-columns:1fr; }
        .meta { grid-template-columns:1fr; }
    }
</style>
@endsection

@section('content')
@php
    $statusLabel = [
        'diajukan' => 'Diajukan',
        'ditinjau' => 'Ditinjau',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'tidak_banding' => 'Tidak Banding',
    ][$banding->status] ?? ucfirst($banding->status);
@endphp

<div class="top">
    <h2>Detail Pengajuan Banding</h2>
    <div class="top-actions">
        <a href="{{ route('admin.banding-asesmen.pdf', $banding->id) }}" class="btn-download"><i class="bi bi-file-earmark-pdf"></i> Unduh PDF FR.AK.04</a>
        <a href="{{ route('admin.banding-asesmen.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <div class="body">
            <div class="title">Informasi Banding</div>
            <div class="meta">
                <div>Nama Asesi</div><div>{{ $banding->asesi->nama ?? '-' }}</div>
                <div>NIK</div><div>{{ $banding->asesi_nik }}</div>
                <div>Jurusan</div><div>{{ $banding->asesi->jurusan->nama_jurusan ?? '-' }}</div>
                <div>Nama Asesor</div><div>{{ $banding->asesor->nama ?? '-' }}</div>
                <div>Skema</div><div>{{ $banding->skema->nama_skema ?? '-' }} ({{ $banding->skema->nomor_skema ?? '-' }})</div>
                <div>Tanggal Asesmen</div><div>{{ $banding->tanggal_asesmen ? $banding->tanggal_asesmen->format('d-m-Y') : '-' }}</div>
                <div>Tanggal Pengajuan</div><div>{{ $banding->tanggal_pengajuan ? $banding->tanggal_pengajuan->format('d-m-Y') : '-' }}</div>
                <div>Status</div><div><span class="badge {{ $banding->status }}">{{ $statusLabel }}</span></div>
            </div>

            <div class="title" style="margin-top:16px;">Ceklis Banding</div>
            <table class="check-table">
                <thead>
                    <tr>
                        <th>Pernyataan</th>
                        <th>YA</th>
                        <th>TIDAK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($komponen as $item)
                        @php $jawab = optional($jawabanMap->get($item->id))->jawaban; @endphp
                        <tr>
                            <td>{{ $item->pernyataan }}</td>
                            <td>{{ $jawab === 'ya' ? '✓' : '' }}</td>
                            <td>{{ $jawab === 'tidak' ? '✓' : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="title" style="margin-top:16px;">Alasan Banding</div>
            <div class="reason">{{ $banding->alasan_banding }}</div>
        </div>
    </div>

    <div class="card">
        <div class="body">
            <div class="title">Pengecekan Admin</div>
            @if($banding->status === 'tidak_banding')
                <div style="font-size:13px;color:#475569;line-height:1.6;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px;">
                    Asesi sudah menetapkan keputusan <strong>Tidak Banding</strong>. Data ini tercatat sebagai keputusan final dari asesi dan tidak memerlukan verifikasi status oleh admin.
                </div>
            @else
                <form method="POST" action="{{ route('admin.banding-asesmen.review', $banding->id) }}">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;color:#334155;">Status</label>
                        <select class="select" name="status">
                            <option value="ditinjau" {{ old('status', $banding->status) === 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                            <option value="diterima" {{ old('status', $banding->status) === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status', $banding->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600;color:#334155;">Catatan Admin</label>
                        <textarea class="textarea" name="catatan_admin" placeholder="Tambahkan catatan hasil pengecekan...">{{ old('catatan_admin', $banding->catatan_admin) }}</textarea>
                        @error('catatan_admin')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    @if($banding->checker)
                        <div style="font-size:12px;color:#64748b;margin-bottom:12px;">
                            Dicek terakhir oleh {{ $banding->checker->name }} pada {{ optional($banding->checked_at)->format('d-m-Y H:i') }} WIB
                        </div>
                    @endif

                    <button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> Simpan Hasil Pengecekan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
