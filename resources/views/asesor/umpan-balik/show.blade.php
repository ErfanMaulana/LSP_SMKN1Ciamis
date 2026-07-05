@extends('asesor.layout')

@section('title', 'Detail Umpan Balik Asesi')
@section('page-title', 'Detail Umpan Balik Asesi')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #dbe4ef;
        color: #475569;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-back:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .info-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }
    .info-section h3 {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 12px;
        padding-bottom: 6px;
        border-bottom: 2px solid #f1f5f9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .info-row:last-child {
        margin-bottom: 0;
    }
    .info-label {
        width: 110px;
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }
    .info-value {
        color: #334155;
        font-weight: 600;
    }

    .komponen-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .komponen-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .komponen-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
    }
    .komponen-text {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.5;
    }
    .komponen-num {
        font-size: 11px;
        font-weight: 700;
        color: #0073bd;
        background: #eff6ff;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .komponen-body {
        padding: 20px;
    }

    .response-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .response-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }
    .badge-response {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 30px;
    }
    .badge-response.ya {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .badge-response.tidak {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }
    .badge-response.empty {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .catatan-box {
        background: #f8fafc;
        border-left: 4px solid #0073bd;
        border-radius: 0 8px 8px 0;
        padding: 12px 16px;
    }
    .catatan-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }
    .catatan-content {
        font-size: 13px;
        color: #334155;
        line-height: 1.5;
        white-space: pre-line;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Detail Umpan Balik Asesi</h2>
    <a href="{{ route('asesor.umpan-balik.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="info-card">
    <div class="info-grid">
        <div class="info-section">
            <h3>Informasi Asesi</h3>
            <div class="info-row">
                <div class="info-label">Nama Asesi</div>
                <div class="info-value">: {{ $asesi->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIK</div>
                <div class="info-value">: {{ $asesi->NIK }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">: {{ $asesi->email }}</div>
            </div>
        </div>
        <div class="info-section">
            <h3>Informasi Skema</h3>
            <div class="info-row">
                <div class="info-label">Nama Skema</div>
                <div class="info-value">: {{ $skema->nama_skema }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor Skema</div>
                <div class="info-value">: {{ $skema->nomor_skema }}</div>
            </div>
        </div>
    </div>
</div>

<div class="komponen-list">
    @forelse($komponenList as $index => $komponen)
        @php
            $result = $results->get($komponen->id);
            $jawaban = $result ? strtolower($result->jawaban) : null;
            $catatan = $result ? $result->catatan : '';
        @endphp
        <div class="komponen-card">
            <div class="komponen-header">
                <div class="komponen-text">{{ $komponen->pernyataan }}</div>
                <div class="komponen-num">Komponen {{ $index + 1 }}</div>
            </div>
            <div class="komponen-body">
                <div class="response-row">
                    <span class="response-label">Penilaian Asesi:</span>
                    @if($jawaban === 'ya')
                        <span class="badge-response ya">
                            <i class="bi bi-check-circle-fill"></i> YA
                        </span>
                    @elseif($jawaban === 'tidak')
                        <span class="badge-response tidak">
                            <i class="bi bi-x-circle-fill"></i> TIDAK
                        </span>
                    @else
                        <span class="badge-response empty">
                            <i class="bi bi-question-circle"></i> Belum Diisi
                        </span>
                    @endif
                </div>

                <div class="catatan-box">
                    <div class="catatan-title">Catatan / Komentar Asesi</div>
                    <div class="catatan-content">
                        @if($catatan)
                            {{ $catatan }}
                        @else
                            <em style="color:#94a3b8;">Tidak ada catatan</em>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="info-card" style="text-align:center; padding:30px; color:#94a3b8;">
            <i class="bi bi-info-circle" style="font-size:36px; display:block; margin-bottom:10px;"></i>
            Tidak ada komponen umpan balik aktif untuk skema ini.
        </div>
    @endforelse
</div>
@endsection
