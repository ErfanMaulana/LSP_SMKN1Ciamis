@extends('asesi.layout')

@section('title', 'Hasil Ujikom')
@section('page-title', 'Hasil Ujikom')

@section('styles')
<style>
    .page-header {
        margin-bottom: 20px;
    }

    .page-header h2 {
        margin: 0 0 6px 0;
        font-size: 22px;
        color: #1e293b;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .announcement-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 26px;
        margin-bottom: 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .announcement-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }

    .announcement-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    .announcement-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }

    .announcement-subtitle {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .announcement-message {
        margin: 12px 0 0;
        font-size: 15px;
        line-height: 1.7;
        color: #1e293b;
    }

    .announcement-meta {
        margin-top: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
    }

    .meta-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
    }

    .meta-label {
        display: block;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 4px;
    }

    .meta-value {
        color: #0f172a;
        font-size: 13px;
        font-weight: 600;
    }

    .announcement-card.kompeten {
        border-color: #86efac;
        background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
    }

    .announcement-card.kompeten .announcement-icon {
        background: #d1fae5;
        color: #065f46;
    }

    .announcement-card.belum-kompeten {
        border-color: #fca5a5;
        background: linear-gradient(180deg, #ffffff 0%, #fef2f2 100%);
    }

    .announcement-card.belum-kompeten .announcement-icon {
        background: #fee2e2;
        color: #991b1b;
    }

    .announcement-card.pending {
        border-color: #fcd34d;
        background: linear-gradient(180deg, #ffffff 0%, #fffbeb 100%);
    }

    .announcement-card.pending .announcement-icon {
        background: #fef3c7;
        color: #92400e;
    }

    .other-title {
        margin: 4px 0 10px;
        font-size: 15px;
        color: #475569;
        font-weight: 700;
    }

    .other-list {
        display: grid;
        gap: 10px;
    }

    .other-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .other-item h4 {
        margin: 0 0 3px;
        font-size: 14px;
        color: #0f172a;
    }

    .other-item p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
    }

    .empty {
        text-align: center;
        padding: 64px 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #94a3b8;
    }

    .empty i {
        font-size: 42px;
        display: block;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="bi bi-award"></i> Hasil Ujikom</h2>
    <p>Pengumuman hasil ujikom Anda ditampilkan langsung di halaman ini tanpa nilai angka.</p>
</div>

@if($hasilUjikom->count())
    @php $utama = $hasilUjikom->first(); @endphp

    <div class="announcement-card {{ $utama->status_penilaian === 'sudah_dinilai' ? ($utama->hasil_ujikom === 'kompeten' ? 'kompeten' : 'belum-kompeten') : 'pending' }}">
        <div class="announcement-head">
            <div class="announcement-icon">
                @if($utama->status_penilaian !== 'sudah_dinilai')
                    <i class="bi bi-hourglass-split"></i>
                @elseif($utama->hasil_ujikom === 'kompeten')
                    <i class="bi bi-patch-check-fill"></i>
                @else
                    <i class="bi bi-exclamation-circle-fill"></i>
                @endif
            </div>

            <div>
                @if($utama->status_penilaian !== 'sudah_dinilai')
                    <h3 class="announcement-title">Pengumuman Hasil Belum Tersedia</h3>
                    <p class="announcement-subtitle">Status: Menunggu penilaian asesor</p>
                @elseif($utama->hasil_ujikom === 'kompeten')
                    <h3 class="announcement-title">SELAMAT! Anda Dinyatakan KOMPETEN</h3>
                    <p class="announcement-subtitle">Skema: {{ $utama->nama_skema }}</p>
                @else
                    <h3 class="announcement-title">MOHON MAAF, Anda Belum Kompeten</h3>
                    <p class="announcement-subtitle">Skema: {{ $utama->nama_skema }}</p>
                @endif
            </div>
        </div>

        <p class="announcement-message">
            @if($utama->status_penilaian !== 'sudah_dinilai')
                Hasil ujikom Anda sedang dalam proses review oleh asesor. Silakan cek kembali secara berkala.
            @elseif($utama->hasil_ujikom === 'kompeten')
                Berdasarkan hasil penilaian asesor, Anda telah memenuhi kriteria kompetensi pada skema ini.
            @else
                Berdasarkan hasil penilaian asesor, Anda belum memenuhi seluruh kriteria kompetensi pada skema ini.
            @endif
        </p>

        <div class="announcement-meta">
            <div class="meta-item">
                <span class="meta-label">Skema</span>
                <span class="meta-value">{{ $utama->nama_skema }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Nomor Skema</span>
                <span class="meta-value">{{ $utama->nomor_skema }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Asesor Penilai</span>
                <span class="meta-value">{{ $utama->asesor_nama ?? '-' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Tanggal Penilaian</span>
                <span class="meta-value">
                    @if($utama->terakhir_dinilai)
                        {{ \Carbon\Carbon::parse($utama->terakhir_dinilai)->format('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>
    </div>

    @if($hasilUjikom->count() > 1)
        <h4 class="other-title">Pengumuman Skema Lainnya</h4>
        <div class="other-list">
            @foreach($hasilUjikom->skip(1) as $row)
                <div class="other-item">
                    <h4>{{ $row->nama_skema }} ({{ $row->nomor_skema }})</h4>
                    <p>
                        @if($row->status_penilaian !== 'sudah_dinilai')
                            Menunggu penilaian asesor.
                        @elseif($row->hasil_ujikom === 'kompeten')
                            Dinyatakan Kompeten.
                        @else
                            Dinyatakan Belum Kompeten.
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
    @endif
@else
    <div class="empty">
        <i class="bi bi-inbox"></i>
        <p>Belum ada data ujikom selesai untuk ditampilkan.</p>
    </div>
@endif
@endsection
