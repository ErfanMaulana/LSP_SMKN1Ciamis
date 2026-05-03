@extends('admin.layout')

@section('title', 'Detail Jadwal Ujikom')
@section('page-title', 'Detail Jadwal Uji Kompetensi')

@section('styles')
<style>
    .detail-wrap {
        display: grid;
        gap: 18px;
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }

    .btn-back:hover {
        background: #f8fafc;
    }

    .hero-card,
    .section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    }

    .hero-card {
        padding: 20px;
    }

    .hero-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .hero-subtitle {
        margin-top: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        margin-top: 10px;
    }

    .status-badge.dijadwalkan { background: #dbeafe; color: #1d4ed8; }
    .status-badge.berlangsung { background: #fef3c7; color: #92400e; }
    .status-badge.selesai { background: #d1fae5; color: #065f46; }
    .status-badge.dibatalkan { background: #fee2e2; color: #991b1b; }

    .meta-grid {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
    }

    .meta-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .meta-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #64748b;
    }

    .meta-value {
        margin-top: 4px;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .section-head {
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .section-head h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-note {
        font-size: 12px;
        color: #64748b;
    }

    .jadwal-body {
        padding: 18px;
    }

    .group-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 14px;
    }

    .group-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .group-card-head {
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f7;
    }

    .group-name {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .group-meta {
        margin-top: 4px;
        font-size: 12px;
        color: #64748b;
    }

    .group-body {
        padding: 14px 16px;
    }

    .group-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 12px;
    }

    .stat-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        background: #ffffff;
    }

    .stat-box .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
    }

    .stat-box .value {
        margin-top: 4px;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .asesi-list {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .asesi-item {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: flex-start;
    }

    .asesi-item:last-child {
        border-bottom: none;
    }

    .asesi-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
    }

    .asesi-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 3px;
    }

    .empty-state {
        padding: 24px 16px;
        text-align: center;
        color: #94a3b8;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 20px;
        }

        .group-grid {
            grid-template-columns: 1fr;
        }

        .group-stats {
            grid-template-columns: 1fr;
        }

        .asesi-item {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-wrap">
    <div class="top-actions">
        <a href="{{ route('admin.jadwal-ujikom.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="hero-card">
        <h2 class="hero-title">{{ $jadwal->judul_jadwal }}</h2>
        <div class="hero-subtitle">Detail jadwal, daftar kelompok, dan asesi yang terlibat.</div>
        <span class="status-badge {{ $jadwal->status }}">{{ $jadwal->status_label }}</span>

        <div class="meta-grid">
            <div class="meta-box">
                <div class="meta-label">Tanggal</div>
                <div class="meta-value">
                    @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                        @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                            {{ $jadwal->tanggal_mulai->translatedFormat('d F Y') }}
                        @else
                            {{ $jadwal->tanggal_mulai->translatedFormat('d F Y') }} s/d {{ $jadwal->tanggal_selesai->translatedFormat('d F Y') }}
                        @endif
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Waktu</div>
                <div class="meta-value">{{ $jadwal->waktu_mulai ?? '-' }} - {{ $jadwal->waktu_selesai ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Skema</div>
                <div class="meta-value">{{ $jadwal->skema?->nama_skema ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">TUK</div>
                <div class="meta-value">{{ $jadwal->tuk?->nama_tuk ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Asesor</div>
                <div class="meta-value">{{ $jadwal->asesor?->nama ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Kelompok</div>
                <div class="meta-value">{{ $jadwal->kelompoks->count() }} Kelompok</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Peserta</div>
                <div class="meta-value">{{ $jadwal->peserta->count() }} Asesi</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Kuota</div>
                <div class="meta-value">{{ $jadwal->kuota }} | Sisa {{ $jadwal->sisa_kuota }}</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-head">
            <div>
                <h3>Kelompok di Jadwal Ini</h3>
                <div class="section-note">Setiap kelompok ditampilkan bersama asesi yang ada di dalamnya.</div>
            </div>
            <div class="section-note">{{ $jadwal->kelompoks->count() }} kelompok terdaftar</div>
        </div>
        <div class="jadwal-body">
            @if($jadwal->kelompoks->count())
                <div class="group-grid">
                    @foreach($jadwal->kelompoks as $kelompok)
                        <div class="group-card">
                            <div class="group-card-head">
                                <h4 class="group-name">{{ $kelompok->nama_kelompok }}</h4>
                                <div class="group-meta">Skema: {{ $kelompok->skema?->nama_skema ?? '-' }}</div>
                                <div class="group-meta">Asesor: {{ $kelompok->asesors->first()?->nama ?? '-' }}</div>
                            </div>
                            <div class="group-body">
                                <div class="group-stats">
                                    <div class="stat-box">
                                        <div class="label">Total Asesi</div>
                                        <div class="value">{{ $kelompok->asesis->count() }}</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="label">Jadwal</div>
                                        <div class="value">{{ $jadwal->status_label }}</div>
                                    </div>
                                </div>

                                <div class="asesi-list">
                                    @forelse($kelompok->asesis as $asesi)
                                        <div class="asesi-item">
                                            <div>
                                                <div class="asesi-name">{{ $asesi->nama }}</div>
                                                <div class="asesi-meta">NIK: {{ $asesi->NIK }}</div>
                                            </div>
                                            <div class="asesi-meta" style="text-align:right;">{{ $asesi->jurusan?->nama_jurusan ?? '-' }}</div>
                                        </div>
                                    @empty
                                        <div class="empty-state">Belum ada asesi di kelompok ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    Belum ada kelompok yang masuk ke jadwal ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
