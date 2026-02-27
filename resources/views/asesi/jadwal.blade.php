@extends('asesi.layout')

@section('title', 'Jadwal Ujikom')
@section('page-title', 'Jadwal Uji Kompetensi')

@section('styles')
<style>
    .content-wrapper {
        padding: 0 !important;
        max-width: none !important;
    }

    .empty-state {
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 72px; display: block; margin-bottom: 20px; color: #cbd5e1; }
    .empty-state h3 { font-size: 22px; font-weight: 700; color: #475569; margin-bottom: 10px; }
    .empty-state p  { font-size: 15px; max-width: 500px; line-height: 1.6; }

    /* Full screen jadwal item */
    .jadwal-fullscreen {
        min-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        background: #ffffff;
        position: relative;
        overflow: hidden;
        padding: 40px;
        color: #1e293b;
    }

    .jadwal-fullscreen::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(102, 126, 234, 0.03) 0%, transparent 50%);
        pointer-events: none;
    }

    .jadwal-content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 30px;
        flex: 1;
    }

    /* Countdown Section */
    .countdown-section {
        text-align: center;
        padding: 30px 0;
    }

    .countdown-label {
        font-size: 18px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .countdown-display {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .countdown-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 20px;
        min-width: 100px;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
    }

    .countdown-box.started {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        box-shadow: 0 8px 32px rgba(20, 83, 45, 0.3);
    }

    .countdown-number {
        font-size: 48px;
        font-weight: 700;
        line-height: 1;
        display: block;
        color: white;
    }

    .countdown-unit {
        font-size: 13px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.9);
        margin-top: 8px;
        letter-spacing: 1px;
    }

    .jadwal-title-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .jadwal-title-main {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e293b;
    }

    .jadwal-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .info-card-icon {
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
        color: #667eea;
    }

    .info-card-label {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .info-card-value {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;
        color: #1e293b;
    }

    /* Peserta Section */
    .peserta-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .peserta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .peserta-title {
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1e293b;
    }

    .peserta-count {
        background: #667eea;
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .peserta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .peserta-grid::-webkit-scrollbar {
        width: 6px;
    }

    .peserta-grid::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .peserta-grid::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .peserta-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .peserta-item:hover {
        background: #f8fafc;
        border-color: #667eea;
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }

    .peserta-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        flex-shrink: 0;
        color: white;
        border: 2px solid #e2e8f0;
    }

    .peserta-info {
        flex: 1;
        min-width: 0;
    }

    .peserta-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #1e293b;
    }

    .peserta-details {
        font-size: 12px;
        color: #64748b;
    }

    /* Navigation untuk multiple jadwal */
    .jadwal-nav {
        position: fixed;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10;
    }

    .jadwal-nav-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #cbd5e1;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .jadwal-nav-dot.active {
        background: #667eea;
        transform: scale(1.3);
    }

    .jadwal-nav-dot:hover {
        background: #94a3b8;
    }

    @media (max-width: 768px) {
        .jadwal-fullscreen {
            padding: 20px;
        }

        .countdown-number {
            font-size: 36px;
        }

        .countdown-box {
            min-width: 80px;
            padding: 15px;
        }

        .jadwal-title-main {
            font-size: 24px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .peserta-grid {
            grid-template-columns: 1fr;
            max-height: 250px;
        }

        .jadwal-nav {
            right: 15px;
        }
    }
</style>
@endsection

@section('content')

@if($jadwalWithPeserta->isEmpty())
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <h3>Belum Ada Jadwal</h3>
        <p>Anda belum terdaftar pada jadwal uji kompetensi manapun. Jadwal akan ditambahkan oleh admin setelah asesmen mandiri Anda selesai direview.</p>
    </div>
@else
    @foreach($jadwalWithPeserta as $index => $jadwal)
    @php
        $tglJadwal = \Carbon\Carbon::parse($jadwal->tanggal_mulai . ' ' . $jadwal->waktu_mulai);
        $now = now();
        
        $tipeLabel = match($jadwal->tipe_tuk ?? '') {
            'sewaktu'      => 'TUK Sewaktu',
            'tempat_kerja' => 'TUK Tempat Kerja',
            'mandiri'      => 'TUK Mandiri',
            default        => 'TUK',
        };
        
        // Check if multi-day event
        $tglMulai = \Carbon\Carbon::parse($jadwal->tanggal_mulai);
        $tglSelesai = \Carbon\Carbon::parse($jadwal->tanggal_selesai);
        $isMultiDay = !$tglMulai->eq($tglSelesai);
    @endphp

    <div class="jadwal-fullscreen status-{{ $jadwal->status }}" id="jadwal-{{ $index }}" data-jadwal-date="{{ $tglJadwal->toIso8601String() }}">
        <div class="jadwal-content">
            <!-- Title Section -->
            <div class="jadwal-title-section">
                <h1 class="jadwal-title-main">{{ $jadwal->judul_jadwal }}</h1>
                <span class="jadwal-badge">
                    <i class="bi {{ match($jadwal->status) {
                        'dijadwalkan' => 'bi-calendar-check',
                        'berlangsung' => 'bi-play-circle-fill',
                        'selesai' => 'bi-check-circle-fill',
                        'dibatalkan' => 'bi-x-circle-fill',
                        default => 'bi-calendar'
                    } }}"></i>
                    {{ match($jadwal->status) {
                        'dijadwalkan' => 'Dijadwalkan',
                        'berlangsung' => 'Sedang Berlangsung',
                        'selesai' => 'Sudah Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => $jadwal->status
                    } }}
                </span>
            </div>

            <!-- Countdown -->
            @if($jadwal->status === 'dijadwalkan')
            <div class="countdown-section">
                <div class="countdown-label">Waktu Tersisa</div>
                <div class="countdown-display" id="countdown-{{ $index }}">
                    <div class="countdown-box">
                        <span class="countdown-number days">0</span>
                        <span class="countdown-unit">Hari</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number hours">0</span>
                        <span class="countdown-unit">Jam</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number minutes">0</span>
                        <span class="countdown-unit">Menit</span>
                    </div>
                    <div class="countdown-box">
                        <span class="countdown-number seconds">0</span>
                        <span class="countdown-unit">Detik</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <i class="bi bi-award-fill info-card-icon"></i>
                    <div class="info-card-label">Skema Kompetensi</div>
                    <div class="info-card-value">{{ $jadwal->nama_skema ?? '-' }}</div>
                </div>

                <div class="info-card">
                    <i class="bi bi-calendar-event-fill info-card-icon"></i>
                    <div class="info-card-label">Tanggal & Waktu</div>
                    <div class="info-card-value">
                        @if($isMultiDay)
                            {{ $tglMulai->translatedFormat('d M') }} - {{ $tglSelesai->translatedFormat('d M Y') }}<br>
                            {{ substr($jadwal->waktu_mulai, 0, 5) }} – {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB
                            <small style="display:block;margin-top:4px;opacity:0.85;">{{ $tglMulai->diffInDays($tglSelesai) + 1 }} Hari</small>
                        @else
                            {{ $tglJadwal->translatedFormat('l, d F Y') }}<br>
                            {{ substr($jadwal->waktu_mulai, 0, 5) }} – {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB
                        @endif
                    </div>
                </div>

                <div class="info-card">
                    <i class="bi bi-building-fill info-card-icon"></i>
                    <div class="info-card-label">Tempat Uji Kompetensi</div>
                    <div class="info-card-value">
                        {{ $jadwal->nama_tuk ?? '-' }}
                        @if($jadwal->tipe_tuk)
                            <br><small style="opacity:0.85;">({{ $tipeLabel }})</small>
                        @endif
                    </div>
                </div>

                @if($jadwal->tuk_alamat)
                <div class="info-card">
                    <i class="bi bi-geo-alt-fill info-card-icon"></i>
                    <div class="info-card-label">Alamat Lokasi</div>
                    <div class="info-card-value">{{ $jadwal->tuk_alamat }}</div>
                </div>
                @endif
            </div>

            <!-- Peserta Section -->
            <div class="peserta-section">
                <div class="peserta-header">
                    <div class="peserta-title">
                        <i class="bi bi-people-fill"></i>
                        Peserta Terdaftar
                    </div>
                    <div class="peserta-count">
                        {{ $jadwal->peserta->count() }} / {{ $jadwal->kuota }} Peserta
                    </div>
                </div>

                @if($jadwal->peserta->count() > 0)
                <div class="peserta-grid">
                    @foreach($jadwal->peserta as $peserta)
                    <div class="peserta-item">
                        <div class="peserta-avatar">
                            {{ strtoupper(substr($peserta->nama, 0, 1)) }}
                        </div>
                        <div class="peserta-info">
                            <div class="peserta-name">{{ $peserta->nama }}</div>
                            <div class="peserta-details">
                                @if($peserta->kode_jurusan)
                                    {{ $peserta->kode_jurusan }}
                                @endif
                                @if($peserta->kelas)
                                    {{ $peserta->kelas ? ' • ' . $peserta->kelas : '' }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:20px;opacity:0.7;">
                    <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    Belum ada peserta terdaftar
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <!-- Navigation dots -->
    @if($jadwalWithPeserta->count() > 1)
    <div class="jadwal-nav">
        @foreach($jadwalWithPeserta as $i => $j)
        <div class="jadwal-nav-dot {{ $i === 0 ? 'active' : '' }}" onclick="scrollToJadwal({{ $i }})"></div>
        @endforeach
    </div>
    @endif
@endif

@endsection

@section('scripts')
<script>
// Countdown timers
document.querySelectorAll('[id^="countdown-"]').forEach(countdownEl => {
    const jadwalEl = countdownEl.closest('.jadwal-fullscreen');
    const targetDate = new Date(jadwalEl.dataset.jadwalDate);

    function updateCountdown() {
        const now = new Date();
        const diff = targetDate - now;

        if (diff <= 0) {
            countdownEl.innerHTML = '<div class="countdown-box started"><span class="countdown-number" style="font-size:24px;">Waktu Dimulai!</span></div>';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        countdownEl.querySelector('.days').textContent = days;
        countdownEl.querySelector('.hours').textContent = String(hours).padStart(2, '0');
        countdownEl.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
        countdownEl.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});

// Scroll navigation
function scrollToJadwal(index) {
    const target = document.getElementById('jadwal-' + index);
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Update active dot
        document.querySelectorAll('.jadwal-nav-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
}

// Update active dot on scroll
let scrollTimeout;
window.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
        const jadwals = document.querySelectorAll('.jadwal-fullscreen');
        const scrollPos = window.scrollY + window.innerHeight / 2;
        
        jadwals.forEach((jadwal, index) => {
            const top = jadwal.offsetTop;
            const bottom = top + jadwal.offsetHeight;
            
            if (scrollPos >= top && scrollPos < bottom) {
                document.querySelectorAll('.jadwal-nav-dot').forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
            }
        });
    }, 100);
});
</script>
@endsection
