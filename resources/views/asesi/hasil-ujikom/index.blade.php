@extends('asesi.layout')

@section('title', 'Hasil Ujikom')
@section('page-title', 'Hasil Ujikom')

@section('styles')
<style>
    .result-screen {
        min-height: calc(100vh - 180px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .result-content {
        width: 100%;
        max-width: 980px;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 50px 40px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .result-status-label {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .result-title {
        margin: 0;
        font-size: clamp(34px, 5vw, 58px);
        line-height: 1.12;
        font-weight: 800;
    }

    .result-subtitle {
        margin-top: 14px;
        font-size: clamp(16px, 2vw, 22px);
        color: #334155;
        font-weight: 600;
    }

    .result-message {
        margin: 20px auto 0;
        max-width: 760px;
        color: #475569;
        font-size: 18px;
        line-height: 1.8;
    }

    .result-meta {
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid #cbd5e1;
        display: flex;
        flex-wrap: wrap;
        gap: 18px 26px;
        justify-content: center;
    }

    .result-meta span {
        font-size: 14px;
        color: #334155;
    }

    .result-meta strong {
        color: #0f172a;
    }

    .tone-competent .result-status-label,
    .tone-competent .result-title {
        color: #166534;
    }

    .tone-not-yet .result-status-label,
    .tone-not-yet .result-title {
        color: #991b1b;
    }

    .tone-pending .result-status-label,
    .tone-pending .result-title {
        color: #92400e;
    }

    .other-results {
        margin-top: 26px;
        text-align: left;
        border-top: 1px dashed #cbd5e1;
        padding-top: 14px;
    }

    .other-results h4 {
        margin: 0 0 8px;
        color: #475569;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .other-results ul {
        margin: 0;
        padding-left: 18px;
        color: #475569;
        font-size: 14px;
        line-height: 1.7;
    }

    .empty {
        text-align: center;
        color: #94a3b8;
        padding: 70px 20px;
    }

    .empty i {
        font-size: 54px;
        display: block;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .result-screen {
            min-height: calc(100vh - 210px);
            align-items: flex-start;
            padding: 12px 0;
        }

        .result-content {
            padding: 20px 16px;
        }

        .result-message {
            font-size: 15px;
            line-height: 1.65;
        }

        .result-meta {
            justify-content: flex-start;
            text-align: left;
            gap: 10px;
            margin-top: 18px;
        }

        .result-content {
            text-align: left;
        }

        .result-title {
            line-height: 1.2;
        }

        .other-results {
            margin-top: 20px;
            padding-top: 12px;
        }

        .other-results ul {
            padding-left: 16px;
        }
    }
</style>
@endsection

@section('content')
@if($hasilUjikom->count())
    @php $utama = $hasilUjikom->first(); @endphp

    @php
        $toneClass = $utama->status_penilaian !== 'sudah_dinilai'
            ? 'tone-pending'
            : ($utama->hasil_ujikom === 'kompeten' ? 'tone-competent' : 'tone-not-yet');
    @endphp

    <div class="result-screen {{ $toneClass }}">
        <div class="result-content">
            <div class="result-status-label">
                @if($utama->status_penilaian !== 'sudah_dinilai')
                    Menunggu Penilaian Asesor
                @elseif($utama->hasil_ujikom === 'kompeten')
                    Pengumuman Hasil Ujikom
                @else
                    Pengumuman Hasil Ujikom
                @endif
            </div>

            <h1 class="result-title">
                @if($utama->status_penilaian !== 'sudah_dinilai')
                    Hasil Ujikom Anda Belum Tersedia
                @elseif($utama->hasil_ujikom === 'kompeten')
                    SELAMAT! ANDA DINYATAKAN KOMPETEN
                @else
                    MOHON MAAF, ANDA BELUM KOMPETEN
                @endif
            </h1>

            <div class="result-subtitle">{{ $utama->nama_skema }}</div>

            <p class="result-message">
                @if($utama->status_penilaian !== 'sudah_dinilai')
                    Penilaian dari asesor masih diproses. Silakan cek halaman ini secara berkala untuk melihat hasil akhir ujikom Anda.
                @elseif($utama->hasil_ujikom === 'kompeten')
                    Berdasarkan hasil penilaian asesor, Anda telah memenuhi kriteria kompetensi pada skema ini.
                @else
                    Berdasarkan hasil penilaian asesor, Anda belum memenuhi seluruh kriteria kompetensi pada skema ini.
                @endif
            </p>

            <div class="result-meta">
                <span><strong>Nomor Skema:</strong> {{ $utama->nomor_skema }}</span>
                <span><strong>Asesor:</strong> {{ $utama->asesor_nama ?? '-' }}</span>
                <span>
                    <strong>Tanggal Penilaian:</strong>
                    @if($utama->terakhir_dinilai)
                        {{ \Carbon\Carbon::parse($utama->terakhir_dinilai)->format('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                </span>
            </div>

            @if($hasilUjikom->count() > 1)
                <div class="other-results">
                    <h4>Skema Lainnya</h4>
                    <ul>
                        @foreach($hasilUjikom->skip(1) as $row)
                            <li>
                                <strong>{{ $row->nama_skema }}</strong>:
                                @if($row->status_penilaian !== 'sudah_dinilai')
                                    Menunggu penilaian asesor.
                                @elseif($row->hasil_ujikom === 'kompeten')
                                    Dinyatakan Kompeten.
                                @else
                                    Dinyatakan Belum Kompeten.
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="empty">
        <i class="bi bi-inbox"></i>
        <p>Belum ada data ujikom selesai untuk ditampilkan.</p>
    </div>
@endif
@endsection
