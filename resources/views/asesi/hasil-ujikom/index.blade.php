@extends('asesi.layout')

@section('title', 'Status Asesmen')
@section('page-title', 'Status Asesmen')

@section('styles')
<style>
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .results-grid {
            grid-template-columns: 1fr;
        }
    }

    .result-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        padding: 24px;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .skema-info {
        flex: 1;
        min-width: 0;
    }

    .skema-name {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
        line-height: 1.4;
    }

    .skema-number {
        font-size: 11px;
        color: #64748b;
        font-family: monospace;
        background: #f8fafc;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-badge.dinilai {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .result-alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-alert.kompeten {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .result-alert.belum-kompeten {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .details-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
        background: #f8fafc;
        padding: 14px;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-label {
        font-size: 10px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }

    .detail-value {
        font-size: 13px;
        color: #1e293b;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .pending-msg {
        background: #eff6ff;
        color: #0073bd;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 13px;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 0;
    }

    .pending-msg i {
        font-size: 18px;
        color: #0073bd;
        flex-shrink: 0;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        width: fit-content;
    }

    .btn-outline-primary {
        color: #0073bd;
        background: #ffffff;
        border: 1px solid #0073bd;
    }

    .btn-outline-primary:hover {
        background: #eff6ff;
        color: #00568e;
        border-color: #00568e;
    }

    .empty-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        padding: 48px 24px;
        text-align: center;
    }

    .empty-state {
        max-width: 360px;
        margin: 0 auto;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .empty-subtitle {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }
</style>
@endsection

@section('content')
@if($hasilUjikom->count())
    <div class="results-grid">
        @foreach($hasilUjikom as $row)
            <div class="result-card">
                <div>
                    <div class="card-header">
                        <div class="skema-info">
                            <h6 class="skema-name">{{ $row->nama_skema }}</h6>
                            <span class="skema-number">{{ $row->nomor_skema }}</span>
                        </div>
                        <span class="status-badge {{ $row->status_penilaian === 'sudah_dinilai' ? 'dinilai' : 'pending' }}">
                            <i class="bi {{ $row->status_penilaian === 'sudah_dinilai' ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                            {{ $row->status_penilaian === 'sudah_dinilai' ? 'Sudah Dinilai' : 'Menunggu Penilaian' }}
                        </span>
                    </div>

                    @if($row->status_penilaian === 'sudah_dinilai')
                        <div class="result-alert {{ $row->hasil_ujikom === 'kompeten' ? 'kompeten' : 'belum-kompeten' }}">
                            @if($row->hasil_ujikom === 'kompeten')
                                <i class="bi bi-check-circle-fill"></i> Anda Dinyatakan Kompeten
                            @else
                                <i class="bi bi-exclamation-circle-fill"></i> Anda Belum Kompeten
                            @endif
                        </div>

                        <div class="details-row">
                            <div class="detail-item">
                                <span class="detail-label">Asesor</span>
                                <span class="detail-value" title="{{ $row->asesor_nama ?? '-' }}">{{ $row->asesor_nama ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Penilaian</span>
                                <span class="detail-value">
                                    @if($row->terakhir_dinilai)
                                        {{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="pending-msg">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Asesmen Anda sedang diproses oleh asesor. Silakan periksa kembali halaman ini secara berkala untuk melihat hasil penilaian.</span>
                        </div>
                    @endif
                </div>

                @if($row->status_penilaian === 'sudah_dinilai' && $row->hasil_ujikom !== 'kompeten')
                    <div style="margin-top: 8px;">
                        <a href="{{ route('asesi.banding.index') }}" class="btn-action btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Ajukan Banding
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="empty-card">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="empty-title">Belum Ada Data Asesmen</h4>
            <p class="empty-subtitle">Anda belum menyelesaikan asesmen. Silakan kerjakan asesmen mandiri terlebih dahulu.</p>
        </div>
    </div>
@endif
@endsection
