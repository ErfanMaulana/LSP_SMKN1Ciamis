@extends('asesi.layout')

@section('title', 'Jadwal Ujikom')
@section('page-title', 'Jadwal Uji Kompetensi')

@section('styles')
<style>
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 56px; display: block; margin-bottom: 16px; color: #cbd5e1; }
    .empty-state h3 { font-size: 18px; font-weight: 700; color: #475569; margin-bottom: 8px; }
    .empty-state p  { font-size: 14px; }

    .jadwal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
    }

    .jadwal-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .2s, box-shadow .2s;
    }
    .jadwal-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.10);
    }

    .card-status-bar {
        height: 5px;
    }
    .card-status-bar.dijadwalkan { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .card-status-bar.berlangsung { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .card-status-bar.selesai     { background: linear-gradient(90deg, #10b981, #34d399); }
    .card-status-bar.dibatalkan  { background: linear-gradient(90deg, #ef4444, #f87171); }

    .card-header-area {
        padding: 18px 20px 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-status.dijadwalkan { background: #dbeafe; color: #1e40af; }
    .badge-status.berlangsung { background: #fef3c7; color: #92400e; }
    .badge-status.selesai     { background: #d1fae5; color: #065f46; }
    .badge-status.dibatalkan  { background: #fee2e2; color: #991b1b; }

    .card-body-area {
        padding: 16px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #374151;
    }
    .info-row i {
        font-size: 15px;
        color: #14532d;
        margin-top: 1px;
        flex-shrink: 0;
        width: 18px;
        text-align: center;
    }
    .info-row .label {
        font-weight: 600;
        color: #6b7280;
        font-size: 11px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 1px;
    }

    .divider-row { border-top: 1px solid #f1f5f9; margin: 4px 0; }

    .card-footer-area {
        padding: 12px 20px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        color: #6b7280;
    }

    .countdown-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }
    .countdown-badge.upcoming { background: #eff6ff; color: #2563eb; }
    .countdown-badge.today    { background: #fef3c7; color: #92400e; }
    .countdown-badge.past     { background: #f1f5f9; color: #64748b; }
    .countdown-badge.ongoing  { background: #fef3c7; color: #92400e; }
    .countdown-badge.canceled { background: #fee2e2; color: #991b1b; }

    .page-info-banner {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #14532d;
    }
    .page-info-banner i { font-size: 20px; flex-shrink: 0; }

    @media (max-width: 640px) {
        .jadwal-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-info-banner">
    <i class="bi bi-info-circle-fill"></i>
    <span>
        Halaman ini menampilkan jadwal uji kompetensi yang telah didaftarkan oleh admin untuk Anda.
        Pastikan hadir tepat waktu di TUK yang tertera.
    </span>
</div>

@if($jadwalTerdaftar->isEmpty())
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <h3>Belum Ada Jadwal</h3>
        <p>Anda belum terdaftar pada jadwal uji kompetensi manapun.<br>
           Jadwal akan ditambahkan oleh admin setelah asesmen mandiri Anda selesai direview.</p>
    </div>
@else
    <div class="jadwal-grid">
        @foreach($jadwalTerdaftar as $jadwal)
        @php
            $today     = now()->toDateString();
            $tglJadwal = $jadwal->tanggal;
            $diffDays  = now()->diffInDays($tglJadwal, false);

            if ($jadwal->status === 'dibatalkan') {
                $countdownLabel = 'Dibatalkan';
                $countdownClass = 'canceled';
            } elseif ($jadwal->status === 'berlangsung') {
                $countdownLabel = 'Sedang Berlangsung';
                $countdownClass = 'ongoing';
            } elseif ($jadwal->status === 'selesai') {
                $countdownLabel = 'Sudah Selesai';
                $countdownClass = 'past';
            } elseif ($tglJadwal === $today) {
                $countdownLabel = 'Hari Ini!';
                $countdownClass = 'today';
            } elseif ($diffDays > 0) {
                $countdownLabel = $diffDays . ' hari lagi';
                $countdownClass = 'upcoming';
            } else {
                $countdownLabel = abs($diffDays) . ' hari lalu';
                $countdownClass = 'past';
            }

            $tipeLabel = match($jadwal->tipe_tuk ?? '') {
                'sewaktu'      => 'TUK Sewaktu',
                'tempat_kerja' => 'TUK Tempat Kerja',
                'mandiri'      => 'TUK Mandiri',
                default        => 'TUK',
            };

            $statusIcon = match($jadwal->status) {
                'dijadwalkan' => 'bi-calendar-check',
                'berlangsung' => 'bi-play-circle-fill',
                'selesai'     => 'bi-check-circle-fill',
                'dibatalkan'  => 'bi-x-circle-fill',
                default       => 'bi-calendar',
            };
        @endphp

        <div class="jadwal-card">
            <div class="card-status-bar {{ $jadwal->status }}"></div>

            <div class="card-header-area">
                <div class="card-title">{{ $jadwal->judul_jadwal }}</div>
                <span class="badge-status {{ $jadwal->status }}">
                    <i class="bi {{ $statusIcon }}"></i>
                    {{ match($jadwal->status) {
                        'dijadwalkan' => 'Dijadwalkan',
                        'berlangsung' => 'Berlangsung',
                        'selesai'     => 'Selesai',
                        'dibatalkan'  => 'Dibatalkan',
                        default       => $jadwal->status,
                    } }}
                </span>
            </div>

            <div class="card-body-area">
                {{-- Skema --}}
                <div class="info-row">
                    <i class="bi bi-award-fill"></i>
                    <div>
                        <span class="label">Skema Kompetensi</span>
                        {{ $jadwal->nama_skema ?? '-' }}
                    </div>
                </div>

                {{-- Tanggal & Waktu --}}
                <div class="info-row">
                    <i class="bi bi-calendar-event-fill"></i>
                    <div>
                        <span class="label">Tanggal & Waktu</span>
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                        &nbsp;&bull;&nbsp;
                        {{ substr($jadwal->waktu_mulai, 0, 5) }} – {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB
                    </div>
                </div>

                <div class="divider-row"></div>

                {{-- TUK --}}
                <div class="info-row">
                    <i class="bi bi-building-fill"></i>
                    <div>
                        <span class="label">Tempat Uji Kompetensi (TUK)</span>
                        {{ $jadwal->nama_tuk ?? '-' }}
                        @if($jadwal->kota)
                            <span style="color:#6b7280;"> — {{ $jadwal->kota }}</span>
                        @endif
                        @if($jadwal->tipe_tuk)
                            <span style="font-size:11px;color:#14532d;margin-left:4px;font-weight:600;">({{ $tipeLabel }})</span>
                        @endif
                    </div>
                </div>

                @if($jadwal->tuk_alamat)
                <div class="info-row" style="margin-top:-4px;">
                    <i class="bi bi-geo-alt-fill"></i>
                    <div>
                        <span class="label">Alamat TUK</span>
                        {{ $jadwal->tuk_alamat }}
                    </div>
                </div>
                @endif

                @if($jadwal->keterangan)
                <div class="info-row" style="margin-top:2px;">
                    <i class="bi bi-chat-left-text-fill"></i>
                    <div>
                        <span class="label">Keterangan</span>
                        {{ $jadwal->keterangan }}
                    </div>
                </div>
                @endif
            </div>

            <div class="card-footer-area">
                <span>
                    <i class="bi bi-people-fill" style="color:#14532d;margin-right:4px;"></i>
                    {{ $jadwal->peserta_terdaftar }} / {{ $jadwal->kuota }} peserta
                </span>
                <span class="countdown-badge {{ $countdownClass }}">
                    @if($countdownClass === 'today')
                        <i class="bi bi-alarm-fill"></i>
                    @elseif($countdownClass === 'ongoing')
                        <i class="bi bi-play-circle-fill"></i>
                    @elseif($countdownClass === 'upcoming')
                        <i class="bi bi-hourglass-split"></i>
                    @elseif($countdownClass === 'canceled')
                        <i class="bi bi-x-circle-fill"></i>
                    @else
                        <i class="bi bi-check2"></i>
                    @endif
                    {{ $countdownLabel }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
