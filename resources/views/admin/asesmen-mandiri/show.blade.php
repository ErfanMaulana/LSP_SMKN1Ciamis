@extends('admin.layout')

@section('title', 'Detail Asesmen - ' . $asesi->nama)

@section('styles')
<style>
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500;
        margin-bottom: 20px; transition: color 0.2s;
    }
    .back-link:hover { color: #0061A5; }

    .info-header {
        background: white; border-radius: 12px; padding: 24px;
        margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.08);
        display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;
    }
    .info-header-left { flex: 1; min-width: 280px; }
    .info-header-right { display: flex; gap: 16px; flex-wrap: wrap; }

    .info-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .info-value { font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 12px; }

    .status-box {
        padding: 14px 20px; border-radius: 10px; text-align: center; min-width: 140px;
    }
    .status-box.belum_mulai        { background: #f1f5f9; color: #64748b; }
    .status-box.sedang_mengerjakan { background: #fef3c7; color: #92400e; }
    .status-box.selesai            { background: #d1fae5; color: #065f46; }
    .status-box .status-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .status-box .status-value { font-size: 16px; font-weight: 700; margin-top: 4px; }

    .rekom-box { padding: 14px 20px; border-radius: 10px; text-align: center; min-width: 140px; }
    .rekom-box.lanjut       { background: #d1fae5; color: #065f46; }
    .rekom-box.tidak_lanjut { background: #fee2e2; color: #991b1b; }
    .rekom-box.pending      { background: #f1f5f9; color: #94a3b8; }
    .rekom-box .status-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .rekom-box .status-value { font-size: 16px; font-weight: 700; margin-top: 4px; }

    .unit-card {
        background: white; border-radius: 12px; padding: 0;
        margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden;
    }
    .unit-header {
        background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; gap: 12px;
    }
    .unit-header .unit-num {
        width: 32px; height: 32px; background: #0061a5; color: #fff;
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; flex-shrink: 0;
    }
    .unit-header h3 { font-size: 15px; font-weight: 700; color: #0F172A; margin: 0; }

    .elemen-row {
        display: flex; align-items: flex-start; gap: 16px;
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
    }
    .elemen-row:last-child { border-bottom: none; }
    .elemen-info { flex: 1; }
    .elemen-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .elemen-kriteria { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
    .elemen-bukti { font-size: 12px; color: #64748b; margin-top: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; }

    .jawaban-badge {
        padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
        flex-shrink: 0; min-width: 40px; text-align: center;
    }
    .jawaban-badge.K  { background: #d1fae5; color: #065f46; }
    .jawaban-badge.BK { background: #fee2e2; color: #991b1b; }
    .jawaban-badge.belum { background: #f1f5f9; color: #94a3b8; }

    .reviewer-section {
        background: white; border-radius: 12px; padding: 20px;
        margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .reviewer-section h4 { font-size: 15px; font-weight: 700; color: #0F172A; margin: 0 0 12px; }

    .signature-img {
        max-width: 220px; max-height: 120px; border: 1px solid #e5e7eb;
        border-radius: 8px; padding: 8px; background: #fff;
    }
</style>
@endsection

@section('content')
<a href="{{ route('admin.asesmen-mandiri.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
</a>

<!-- Header Info -->
<div class="info-header">
    <div class="info-header-left">
        <div class="info-label">Nama Asesi</div>
        <div class="info-value">{{ $asesi->nama }}</div>

        <div class="info-label">NIK</div>
        <div class="info-value">{{ $asesi->NIK }}</div>

        <div class="info-label">Skema</div>
        <div class="info-value">{{ $skema->nama_skema }} ({{ $skema->kode_skema }})</div>

        @if($pivot->tanggal_mulai)
            <div class="info-label">Mulai Mengerjakan</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($pivot->tanggal_mulai)->format('d M Y H:i') }}</div>
        @endif

        @if($pivot->tanggal_selesai)
            <div class="info-label">Selesai</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($pivot->tanggal_selesai)->format('d M Y H:i') }}</div>
        @endif
    </div>
    <div class="info-header-right">
        @php
            $statusLabels = [
                'belum_mulai' => 'Belum Mulai',
                'sedang_mengerjakan' => 'Sedang Mengerjakan',
                'selesai' => 'Selesai',
            ];
        @endphp
        <div class="status-box {{ $pivot->status }}">
            <div class="status-label">Status</div>
            <div class="status-value">{{ $statusLabels[$pivot->status] ?? $pivot->status }}</div>
        </div>
        <div class="rekom-box {{ $pivot->rekomendasi ?? 'pending' }}">
            <div class="status-label">Rekomendasi</div>
            <div class="status-value">
                @if($pivot->rekomendasi === 'lanjut')
                    Lanjut
                @elseif($pivot->rekomendasi === 'tidak_lanjut')
                    Tidak Lanjut
                @else
                    Belum Direview
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reviewer Info -->
@if($reviewer || $pivot->catatan_asesor)
    <div class="reviewer-section">
        <h4><i class="bi bi-person-check" style="color:#0061a5"></i> Info Review Asesor</h4>
        @if($reviewer)
            <div class="info-label">Asesor</div>
            <div class="info-value">{{ $reviewer->nama }} ({{ $reviewer->no_reg }})</div>
        @endif
        @if($pivot->reviewed_at)
            <div class="info-label">Tanggal Review</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($pivot->reviewed_at)->format('d M Y H:i') }}</div>
        @endif
        @if($pivot->catatan_asesor)
            <div class="info-label">Catatan Asesor</div>
            <div class="info-value">{{ $pivot->catatan_asesor }}</div>
        @endif
    </div>
@endif

<!-- Tanda Tangan -->
<div class="reviewer-section">
    <h4><i class="bi bi-pen" style="color:#0061a5"></i> Tanda Tangan</h4>
    @if($pivot->tanda_tangan || $pivot->tanda_tangan_asesor)
        <div style="display:flex;gap:24px;flex-wrap:wrap;">
            @if($pivot->tanda_tangan)
                <div>
                    <div class="info-label">Asesi</div>
                    <img src="{{ $pivot->tanda_tangan }}" alt="Tanda Tangan Asesi" class="signature-img">
                    @if($pivot->tanggal_tanda_tangan)
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">{{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan)->format('d M Y H:i') }}</div>
                    @endif
                </div>
            @endif
            @if($pivot->tanda_tangan_asesor)
                <div>
                    <div class="info-label">Asesor</div>
                    <img src="{{ $pivot->tanda_tangan_asesor }}" alt="Tanda Tangan Asesor" class="signature-img">
                    @if($pivot->tanggal_tanda_tangan_asesor)
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">{{ \Carbon\Carbon::parse($pivot->tanggal_tanda_tangan_asesor)->format('d M Y H:i') }}</div>
                    @endif
                </div>
            @endif
        </div>
    @else
        <div style="padding: 16px; background: #f8fafc; border-radius: 8px; color: #94a3b8; text-align: center;">
            <p style="margin: 0; font-size: 13px;">Belum ada tanda tangan</p>
        </div>
    @endif
</div>

<!-- Jawaban per Unit -->
@php $totalK = 0; $totalBK = 0; $totalBelum = 0; @endphp
@foreach($skema->units as $uIndex => $unit)
    <div class="unit-card">
        <div class="unit-header">
            <div class="unit-num">{{ $uIndex + 1 }}</div>
            <h3>{{ $unit->judul_unit ?? $unit->nama_unit ?? 'Unit ' . ($uIndex + 1) }}</h3>
        </div>
        @foreach($unit->elemens as $elemen)
            @php
                $jwb = $jawaban->get($elemen->id);
                $status = $jwb ? $jwb->status : null;
                if ($status === 'K') $totalK++;
                elseif ($status === 'BK') $totalBK++;
                else $totalBelum++;
            @endphp
            <div class="elemen-row">
                <div class="elemen-info">
                    <div class="elemen-title">{{ $elemen->nama_elemen ?? $elemen->judul_elemen ?? 'Elemen' }}</div>
                    @php
                        $kriteria = $elemen->kriteria ?? [];
                    @endphp
                    @if(count($kriteria) > 0)
                        <div class="elemen-kriteria">
                            @foreach($kriteria as $krit)
                                <div>&bull; {{ $krit->deskripsi_kriteria ?? $krit->nama_kriteria ?? $krit->unjuk_kerja ?? '' }}</div>
                            @endforeach
                        </div>
                    @else
                        <div class="elemen-kriteria" style="color: #cbd5e1;">
                            <div>&bull; -</div>
                        </div>
                    @endif
                    @if($jwb && $jwb->bukti)
                        <div class="elemen-bukti">
                            <strong>Bukti:</strong> {{ $jwb->bukti }}
                        </div>
                    @endif
                </div>
                <span class="jawaban-badge {{ $status ?? 'belum' }}">
                    {{ $status ?? '-' }}
                </span>
            </div>
        @endforeach
    </div>
@endforeach

<!-- Summary -->
<div class="reviewer-section" style="margin-top:8px;">
    <h4><i class="bi bi-bar-chart" style="color:#0061a5"></i> Ringkasan Jawaban</h4>
    <div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:12px;">
        <div style="padding:12px 20px;background:#d1fae5;border-radius:10px;text-align:center;min-width:100px;">
            <div style="font-size:24px;font-weight:700;color:#065f46">{{ $totalK }}</div>
            <div style="font-size:11px;font-weight:600;color:#065f46">Kompeten (K)</div>
        </div>
        <div style="padding:12px 20px;background:#fee2e2;border-radius:10px;text-align:center;min-width:100px;">
            <div style="font-size:24px;font-weight:700;color:#991b1b">{{ $totalBK }}</div>
            <div style="font-size:11px;font-weight:600;color:#991b1b">Belum Kompeten (BK)</div>
        </div>
        <div style="padding:12px 20px;background:#f1f5f9;border-radius:10px;text-align:center;min-width:100px;">
            <div style="font-size:24px;font-weight:700;color:#64748b">{{ $totalBelum }}</div>
            <div style="font-size:11px;font-weight:600;color:#64748b">Belum Dijawab</div>
        </div>
    </div>
</div>
@endsection
