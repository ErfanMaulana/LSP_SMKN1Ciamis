@extends('asesi.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Asesi')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        border-radius: 12px;
        padding: 32px;
        color: white;
        margin-bottom: 24px;
    }

    .welcome-card h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .welcome-card p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.yellow { background: #fef3c7; color: #d97706; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }

    .stat-info h3 {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-info .value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .quick-actions h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .action-item:hover {
        background: #f0fdf4;
        border-color: #22c55e;
        transform: translateX(4px);
    }

    .action-item .icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .action-item .text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .action-item .text p {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        margin-top: 24px;
    }

    .info-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .info-item label {
        display: block;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-item span {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <h2>Selamat Datang, {{ $asesi->nama ?? 'Asesi' }}! 👋</h2>
    <p>Anda berhasil masuk ke sistem LSP SMKN 1 Ciamis. Mulailah persiapan sertifikasi Anda dengan mengerjakan Asesmen Mandiri.</p>
</div>

@php
    $skemaCount = $asesi ? $asesi->skemas()->count() : 0;
    $selesaiCount = $asesi ? $asesi->skemas()->wherePivot('status', 'selesai')->count() : 0;
    $sedangCount = $asesi ? $asesi->skemas()->wherePivot('status', 'sedang_mengerjakan')->count() : 0;
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="bi bi-patch-check"></i>
        </div>
        <div class="stat-info">
            <h3>Total Skema Diambil</h3>
            <div class="value">{{ $skemaCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <h3>Sedang Dikerjakan</h3>
            <div class="value">{{ $sedangCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Asesmen Selesai</h3>
            <div class="value">{{ $selesaiCount }}</div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h3><i class="bi bi-lightning"></i> Aksi Cepat</h3>
    <div class="action-list">
        <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="action-item">
            <div class="icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="text">
                <h4>Asesmen Mandiri</h4>
                <p>Isi form penilaian diri untuk persiapan sertifikasi</p>
            </div>
        </a>
        <a href="#" class="action-item" style="opacity:0.5;pointer-events:none;">
            <div class="icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="text">
                <h4>Lihat Hasil Asesmen</h4>
                <p>Cek hasil penilaian asesmen mandiri Anda</p>
            </div>
        </a>
    </div>
</div>

@if($asesi)
<div class="info-card">
    <h3><i class="bi bi-person-circle"></i> Informasi Akun</h3>
    <div class="info-grid">
        <div class="info-item">
            <label>Nomor Registrasi</label>
            <span>{{ $account->no_reg }}</span>
        </div>
        <div class="info-item">
            <label>NIK</label>
            <span>{{ $asesi->NIK }}</span>
        </div>
        <div class="info-item">
            <label>Nama Lengkap</label>
            <span>{{ $asesi->nama }}</span>
        </div>
        <div class="info-item">
            <label>Email</label>
            <span>{{ $asesi->email ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Jurusan</label>
            <span>{{ $asesi->jurusan->nama_jurusan ?? '-' }}</span>
        </div>
        <div class="info-item">
            <label>Status Akun</label>
            <span style="color:#16a34a;font-weight:600;">{{ ucfirst($asesi->status) }}</span>
        </div>
    </div>
</div>
@endif
@endsection
