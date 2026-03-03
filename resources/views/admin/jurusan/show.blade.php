@extends('admin.layout')

@section('title', 'Detail Jurusan')
@section('page-title', 'Detail Jurusan')

@section('content')
<div class="page-header">
    <h2>Detail Jurusan</h2>
    <div class="header-actions">
        <a href="{{ route('admin.jurusan.edit', $jurusan->ID_jurusan) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="detail-section">
            <h3>Informasi Dasar</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>ID Jurusan</label>
                    <div class="detail-value">{{ $jurusan->ID_jurusan }}</div>
                </div>

                <div class="detail-item">
                    <label>Kode Jurusan</label>
                    <div class="detail-value">
                        <span class="badge badge-code">{{ $jurusan->kode_jurusan ?? '-' }}</span>
                    </div>
                </div>

                <div class="detail-item full-width">
                    <label>Nama Jurusan</label>
                    <div class="detail-value">{{ $jurusan->nama_jurusan }}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Visi & Misi</h3>
            
            <div class="visi-misi-box">
                <div class="vm-item">
                    <div class="vm-label">
                        <i class="bi bi-eye-fill"></i> Visi
                    </div>
                    <div class="vm-content">
                        @if($jurusan->visi)
                            {{ $jurusan->visi }}
                        @else
                            <span class="text-muted">Belum diisi</span>
                        @endif
                    </div>
                </div>

                <div class="vm-item">
                    <div class="vm-label">
                        <i class="bi bi-bullseye"></i> Misi
                    </div>
                    <div class="vm-content">
                        @if($jurusan->misi)
                            {{ $jurusan->misi }}
                        @else
                            <span class="text-muted">Belum diisi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Statistik</h3>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $jurusan->asesi_count }}</div>
                        <div class="stat-label">Total Asesi</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: #065f46;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $jurusan->skemas_count }}</div>
                        <div class="stat-label">Skema Sertifikasi</div>
                    </div>
                </div>
            </div>

            @if($jurusan->skemas_count > 0)
            <div class="skema-list">
                <div class="list-header">
                    <i class="bi bi-patch-check"></i> Daftar Skema Sertifikasi
                </div>
                @foreach($jurusan->skemas as $skema)
                <div class="skema-item">
                    <div class="skema-badge">
                        <span class="jenis-badge" style="background: {{ $skema->jenis_skema == 'KKNI' ? '#dbeafe' : ($skema->jenis_skema == 'Okupasi' ? '#d1fae5' : '#fef3c7') }}; color: {{ $skema->jenis_skema == 'KKNI' ? '#1e40af' : ($skema->jenis_skema == 'Okupasi' ? '#065f46' : '#92400e') }};">
                            {{ $skema->jenis_skema }}
                        </span>
                    </div>
                    <div class="skema-details">
                        <div class="skema-name">{{ $skema->nama_skema }}</div>
                        <div class="skema-number">{{ $skema->nomor_skema }}</div>
                    </div>
                    <a href="{{ route('admin.skema.show', $skema->id) }}" class="skema-link">
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="detail-section">
            <h3>Informasi Sistem</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dibuat pada</label>
                    <div class="detail-value">
                        @if($jurusan->created_at)
                            {{ $jurusan->created_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir diupdate</label>
                    <div class="detail-value">
                        @if($jurusan->updated_at)
                            {{ $jurusan->updated_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.jurusan.edit', $jurusan->ID_jurusan) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Data
            </a>
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section:last-of-type {
        margin-bottom: 0;
    }

    .detail-section h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-section h3:before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-item label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 15px;
        color: #0F172A;
        font-weight: 500;
    }

    .text-muted {
        color: #94a3b8;
        font-style: italic;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        width: fit-content;
    }

    .badge-code {
        background: #f3f4f6;
        color: #374151;
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .visi-misi-box {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .vm-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
    }

    .vm-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #0073bd;
        margin-bottom: 12px;
    }

    .vm-label i {
        font-size: 16px;
    }

    .vm-content {
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
        white-space: pre-line;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .skema-list {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .list-header {
        background: #0073bd;
        color: white;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .skema-item {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    .skema-item:last-child {
        border-bottom: none;
    }

    .skema-item:hover {
        background: white;
    }

    .skema-badge {
        flex-shrink: 0;
    }

    .jenis-badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .skema-details {
        flex: 1;
    }

    .skema-name {
        font-size: 14px;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 4px;
    }

    .skema-number {
        font-size: 12px;
        color: #64748b;
    }

    .skema-link {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: white;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0073bd;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .skema-link:hover {
        background: #0073bd;
        color: white;
        border-color: #0073bd;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f1f5f9;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #005a94;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .stat-card {
            padding: 16px;
        }
    }
</style>
@endsection
