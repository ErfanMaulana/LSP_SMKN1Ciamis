@extends('asesi.layout')

@section('title', 'Status & Hasil Asesmen')
@section('page-title', 'Status & Hasil Asesmen')

@section('styles')
<style>
    .skema-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 30px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .skema-section:hover {
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.06);
    }

    .skema-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 24px;
        gap: 16px;
    }

    .skema-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    .skema-code {
        display: inline-block;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        color: #0073bd;
        background: rgba(0, 115, 189, 0.08);
        padding: 4px 10px;
        border-radius: 6px;
        margin-top: 6px;
    }

    .overall-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .overall-badge.completed {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .overall-badge.progressing {
        background: #eff6ff;
        color: #0073bd;
        border: 1px solid #bfdbfe;
    }

    /* Result Card Styles */
    .result-banner {
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .result-banner.kompeten {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1px solid #10b981;
        color: #065f46;
    }

    .result-banner.belum-kompeten {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #ef4444;
        color: #991b1b;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .result-banner.kompeten .result-icon {
        background: #10b981;
        color: #ffffff;
    }

    .result-banner.belum-kompeten .result-icon {
        background: #ef4444;
        color: #ffffff;
    }

    .result-info {
        flex: 1;
    }

    .result-status-title {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 6px 0;
    }

    .result-status-desc {
        font-size: 14px;
        line-height: 1.5;
        opacity: 0.9;
        margin: 0;
    }

    .result-meta {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        font-size: 13px;
    }

    .result-meta-item strong {
        font-weight: 700;
    }

    /* Steps Timeline Styles */
    .timeline-container {
        position: relative;
        padding-left: 32px;
        margin-top: 10px;
    }

    .timeline-line {
        position: absolute;
        left: 12px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #e2e8f0;
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        margin-bottom: 24px;
        z-index: 2;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .step-marker {
        position: absolute;
        left: -32px;
        top: 2px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.25s ease;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        color: #94a3b8;
    }

    .timeline-step.completed .step-marker {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }

    .timeline-step.pending .step-marker {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #64748b;
    }

    .step-content {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease;
    }

    .step-content:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .step-text {
        flex: 1;
    }

    .step-name {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px 0;
    }

    .step-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .step-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        white-space: nowrap;
    }

    .step-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .step-badge.pending {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .step-badge.warning {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        width: fit-content;
        margin-top: 10px;
    }

    .btn-primary-action {
        color: #ffffff;
        background: #0073bd;
        border: 1px solid #0073bd;
        box-shadow: 0 2px 6px rgba(0, 115, 189, 0.15);
    }

    .btn-primary-action:hover {
        background: #00568e;
        border-color: #00568e;
        transform: translateY(-1px);
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
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 60px 24px;
        text-align: center;
    }

    .empty-state i {
        font-size: 56px;
        color: #cbd5e1;
        margin-bottom: 20px;
        display: block;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-subtitle {
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    .progress-bar-container {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #0073bd;
        transition: width 0.5s ease-out;
    }
</style>
@endsection

@section('content')
@if($hasilUjikom->count())
    @foreach($hasilUjikom as $row)
        <div class="skema-section">
            <div class="skema-header">
                <div>
                    <h3 class="skema-title">{{ $row->nama_skema }}</h3>
                    <span class="skema-code">{{ $row->nomor_skema }}</span>
                </div>
                <span class="overall-badge {{ $row->all_completed ? 'completed' : 'progressing' }}">
                    <i class="bi {{ $row->all_completed ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                    {{ $row->all_completed ? 'Tahapan Selesai' : 'Sedang Berlangsung' }}
                </span>
            </div>

            {{-- 1. Final Result Banner if completed --}}
            @if($row->all_completed)
                @if($row->rekomendasi === 'kompeten')
                    <div class="result-banner kompeten">
                        <div class="result-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="result-info">
                            <h4 class="result-status-title">Selamat! Anda Dinyatakan KOMPETEN</h4>
                            <p class="result-status-desc">
                                Berdasarkan evaluasi akhir dan bukti observasi langsung, tim asesor menyatakan bahwa kompetensi Anda pada skema sertifikasi ini memenuhi standar kompetensi kerja nasional.
                            </p>
                            <div class="result-meta">
                                <div class="result-meta-item">
                                    Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong>
                                </div>
                                <div class="result-meta-item">
                                    Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="result-banner belum-kompeten">
                        <div class="result-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="result-info">
                            <h4 class="result-status-title">Anda Dinyatakan BELUM KOMPETEN</h4>
                            <p class="result-status-desc">
                                Berdasarkan penilaian observasi langsung, terdapat kriteria unjuk kerja yang masih memerlukan pengembangan lebih lanjut untuk mencapai kualifikasi kompetensi penuh.
                            </p>
                            <div class="result-meta">
                                <div class="result-meta-item">
                                    Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong>
                                </div>
                                <div class="result-meta-item">
                                    Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong>
                                </div>
                            </div>
                            <div style="margin-top: 14px; display: flex; gap: 10px;">
                                @if(Route::has('asesi.banding.index'))
                                <a href="{{ route('asesi.banding.index') }}" class="btn-action btn-primary-action" style="margin-top:0;">
                                    <i class="bi bi-pencil-square"></i> Ajukan Banding Asesmen
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- Show progress bar based on steps completed --}}
                @php
                    $completedSteps = collect($row->steps)->where('status', 'completed')->count();
                    $totalSteps = count($row->steps);
                    $progressPercentage = ($completedSteps / $totalSteps) * 100;
                @endphp
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $progressPercentage }}%;"></div>
                </div>

                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px 20px; color: #0073bd; margin-bottom: 25px; font-size: 13.5px; line-height: 1.5; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="bi bi-info-circle-fill" style="font-size: 18px; color: #0073bd; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <strong>Hasil Uji Kompetensi Belum Tersedia.</strong>
                        <p style="margin: 4px 0 0 0; opacity: 0.95;">
                            Silakan selesaikan seluruh tahapan asesmen yang ditandai dengan status "Belum Selesai" atau "Menunggu Tindakan" di bawah ini agar keputusan akhir kompetensi dapat diproses.
                        </p>
                    </div>
                </div>
            @endif

            {{-- 2. Step Progression Timeline --}}
            <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                Tahapan Sertifikasi
            </h4>
            
            <div class="timeline-container">
                <div class="timeline-line"></div>
                
                @foreach($row->steps as $index => $step)
                    <div class="timeline-step {{ $step['status'] === 'completed' ? 'completed' : 'pending' }}">
                        <div class="step-marker">
                            @if($step['status'] === 'completed')
                                <i class="bi bi-check-lg"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="step-content">
                            <div class="step-text">
                                <h5 class="step-name">{{ $step['name'] }}</h5>
                                <p class="step-desc">{{ $step['description'] }}</p>
                                
                                {{-- Provide direct links/buttons for pending actions --}}
                                @if($step['status'] !== 'completed')
                                    @if($index === 1) {{-- Asesmen Mandiri --}}
                                        <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="btn-action btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Isi Asesmen Mandiri
                                        </a>
                                    @elseif($index === 2) {{-- Persetujuan Asesmen --}}
                                        <a href="{{ route('asesi.persetujuan-asesmen.index') }}" class="btn-action btn-outline-primary">
                                            <i class="bi bi-file-earmark-check"></i> Buka Persetujuan Asesmen
                                        </a>
                                    @elseif($index === 4 && $row->ceklis && empty($row->ceklis->ttd_asesi_file)) {{-- Ceklis Observasi --}}
                                        <a href="{{ route('asesi.ceklis-observasi.view', $row->ceklis->id) }}" class="btn-action btn-outline-primary">
                                            <i class="bi bi-pen"></i> Tanda Tangani Ceklis Observasi
                                        </a>
                                    @elseif($index === 5 && $row->rekaman && empty($row->rekaman->ttd_asesi_file)) {{-- Rekaman Asesmen --}}
                                        <a href="{{ route('asesi.rekaman-asesmen.view', $row->rekaman->id) }}" class="btn-action btn-outline-primary">
                                            <i class="bi bi-pen"></i> Tanda Tangani Rekaman Asesmen
                                        </a>
                                    @endif
                                @endif
                            </div>
                            
                            @php
                                $badgeClass = 'pending';
                                if ($step['status'] === 'completed') {
                                    $badgeClass = 'completed';
                                } elseif ($step['label'] === 'Menunggu Tanda Tangan Anda') {
                                    $badgeClass = 'warning';
                                }
                            @endphp
                            <span class="step-badge {{ $badgeClass }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="empty-card">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 class="empty-title">Belum Ada Skema Terdaftar</h4>
            <p class="empty-subtitle">Anda belum terdaftar pada skema sertifikasi apapun. Silakan hubungi admin LSP.</p>
        </div>
    </div>
@endif
@endsection
