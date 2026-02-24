@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="welcome-card">
    <h2>Selamat Datang, {{ Auth::guard('admin')->user()->name }}!</h2>
    <p>Anda login sebagai administrator sistem LSP SMKN 1 Ciamis</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-content">
            <h3>Total Asesi</h3>
            <div class="number">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon asesor">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div class="stat-content">
            <h3>Total Asesor</h3>
            <div class="number">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon siswa">
            <i class="bi bi-journal-text"></i>
        </div>
        <div class="stat-content">
            <h3>Total Siswa</h3>
            <div class="number">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon unit">
            <i class="bi bi-building"></i>
        </div>
        <div class="stat-content">
            <h3>Total Unit</h3>
            <div class="number">0</div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h3>Aksi Cepat</h3>
    <div class="action-grid">
        <a href="#" class="action-card">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Asesor</span>
        </a>
        <a href="#" class="action-card">
            <i class="bi bi-people-fill"></i>
            <span>Tambah Asesi</span>
        </a>
        <a href="#" class="action-card">
            <i class="bi bi-pencil-square"></i>
            <span>Entry Penilaian</span>
        </a>
        <a href="#" class="action-card">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Lihat Laporan</span>
        </a>
    </div>
</div>

<style>
    .welcome-card {
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
        padding: 35px;
        border-radius: 12px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .welcome-card h2 {
        font-size: 26px;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .welcome-card p {
        font-size: 15px;
        opacity: 0.95;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.asesor {
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
    }

    .stat-icon.siswa {
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
    }

    .stat-icon.unit {
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
    }

    .stat-content {
        flex: 1;
    }

    .stat-content h3 {
        color: #95a5a6;
        font-size: 13px;
        margin-bottom: 8px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .number {
        color: #2c3e50;
        font-size: 32px;
        font-weight: 700;
    }

    .quick-actions {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .quick-actions h3 {
        color: #2c3e50;
        font-size: 18px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .action-card {
        background: linear-gradient(135deg, #0073bd 0%, #0061A5 100%);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        color: white;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-card i {
        font-size: 32px;
        color: white;
    }

    .action-card span {
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection
