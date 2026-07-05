@extends('admin.layout')

@section('title', 'Detail Hasil Umpan Balik Asesi')
@section('page-title', 'Detail Hasil Umpan Balik Asesi')

@section('styles')
<style>
    .top { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
    .top h2 { margin:0; font-size:22px; font-weight:700; color:#0f172a; }
    .btn-back { border:none; border-radius:8px; padding:9px 14px; background:#e2e8f0; color:#334155; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:6px; }
    .btn-back:hover { background:#cbd5e1; }

    .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:16px; }
    .body { padding:18px; }

    .title { font-size:15px; font-weight:700; color:#0f172a; margin-bottom:12px; }
    .meta-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; font-size:13px; }
    .meta-item small { display:block; font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700; margin-bottom:3px; }
    .meta-item strong { display:block; color:#0f172a; font-size:14px; }

    .results-table { width:100%; border-collapse:collapse; }
    .results-table th, .results-table td { border:1px solid #e2e8f0; padding:10px 14px; font-size:13px; vertical-align:top; }
    .results-table th { background:#f8fafc; font-weight:700; color:#64748b; text-transform:uppercase; font-size:11px; letter-spacing:.4px; text-align:left; }

    .badge-ya { padding:4px 10px; background:#dcfce7; color:#15803d; border-radius:999px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:4px; }
    .badge-tidak { padding:4px 10px; background:#fee2e2; color:#b91c1c; border-radius:999px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:4px; }
    .badge-empty { padding:4px 10px; background:#f1f5f9; color:#64748b; border-radius:999px; font-size:12px; font-weight:600; }
</style>
@endsection

@section('content')
<div class="top">
    <div>
        <h2>Detail Umpan Balik Kinerja Asesor</h2>
        <p style="margin:4px 0 0; font-size:13px; color:#64748b;">Hasil tanggapan FR.AK.03 dari asesi {{ $asesi->nama }}.</p>
    </div>
    <a href="{{ route('admin.umpan-balik-hasil.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="body">
        <div class="title">Informasi Asesi & Skema</div>
        <div class="meta-grid">
            <div class="meta-item">
                <small>Nama Asesi</small>
                <strong>{{ $asesi->nama }}</strong>
            </div>
            <div class="meta-item">
                <small>NIK Asesi</small>
                <strong>{{ $asesi->NIK }}</strong>
            </div>
            <div class="meta-item">
                <small>Jurusan</small>
                <strong>{{ $asesi->jurusan->nama_jurusan ?? '-' }}</strong>
            </div>
            <div class="meta-item">
                <small>Skema Sertifikasi</small>
                <strong>{{ $skema->nama_skema }}</strong>
                <span style="font-size:12px; color:#64748b;">{{ $skema->nomor_skema }}</span>
            </div>
            <div class="meta-item">
                <small>Asesor Terkait</small>
                <strong>{{ $asesor->nama ?? ($asesi->asesor->nama ?? '-') }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="body">
        <div class="title">Tanggapan Komponen Umpan Balik</div>
        <table class="results-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pernyataan Komponen</th>
                    <th style="width: 110px; text-align: center;">Hasil</th>
                    <th>Catatan / Komentar Asesi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponenList as $index => $komponen)
                    @php
                        $jawabanItem = $results->get($komponen->id);
                        $jawaban = strtolower((string) optional($jawabanItem)->jawaban);
                        $catatan = optional($jawabanItem)->catatan;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">{{ $komponen->pernyataan }}</div>
                        </td>
                        <td style="text-align: center;">
                            @if($jawaban === 'ya')
                                <span class="badge-ya"><i class="bi bi-check-lg"></i> Ya</span>
                            @elseif($jawaban === 'tidak')
                                <span class="badge-tidak"><i class="bi bi-x-lg"></i> Tidak</span>
                            @else
                                <span class="badge-empty">Belum diisi</span>
                            @endif
                        </td>
                        <td>
                            @if($catatan)
                                <div style="color: #334155; line-height: 1.45;">{{ $catatan }}</div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Tidak ada catatan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
