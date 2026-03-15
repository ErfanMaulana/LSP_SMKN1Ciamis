@extends('admin.layout')

@section('title', 'Detail Asesor')
@section('page-title', 'Detail Asesor')

@section('content')
<div class="page-header">
    <h2>Detail Asesor</h2>
    <div class="header-actions">
        <a href="{{ route('admin.asesor.edit', $asesor->ID_asesor) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="detail-section">
            <h3>Informasi Asesor</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Nama Lengkap</label>
                    <div class="detail-value">{{ $asesor->nama }}</div>
                </div>

                <div class="detail-item">
                    <label>No. Registrasi</label>
                    <div class="detail-value">
                        @if($asesor->no_met)
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle-fill"></i> {{ $asesor->no_met }}
                            </span>
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Skema Sertifikasi</label>
                    <div class="detail-value">
                        @if($asesor->skemas->count())
                            <div class="skema-list">
                                @foreach($asesor->skemas as $skema)
                                    <div class="skema-item">
                                        <div class="skema-name">{{ $skema->nama_skema }}</div>
                                        @if($skema->nomor_skema)
                                            <div class="skema-number">{{ $skema->nomor_skema }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">Belum ditentukan</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Status Login</label>
                    <div class="detail-value">
                        @if($asesor->no_met)
                            <span class="badge badge-active">
                                <i class="bi bi-person-check-fill"></i> Akun Aktif
                            </span>
                        @else
                            <span class="badge badge-inactive">
                                <i class="bi bi-person-x-fill"></i> Tidak Ada Akun
                            </span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Status Asesor</label>
                    <div class="detail-value">
                        @if($asesor->skemas->count())
                            <span class="badge badge-active">
                                <i class="bi bi-check-circle-fill"></i> Aktif
                            </span>
                        @else
                            <span class="badge badge-inactive">
                                <i class="bi bi-x-circle-fill"></i> Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($asesor->no_met)
        <div class="detail-section">
            <h3>Informasi Akun Login</h3>
            
            <div class="info-box">
                <div class="info-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="info-content">
                    <div class="info-title">Akses Login Tersedia</div>
                    <div class="info-desc">
                        Asesor dapat login menggunakan <strong>No. Registrasi: {{ $asesor->no_met }}</strong>
                    </div>
                    <div class="info-note">
                        <i class="bi bi-info-circle"></i>
                        Password default sama dengan No. Registrasi. Asesor dapat mengubah password setelah login pertama kali.
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="detail-section">
            <h3>Informasi Sistem</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dibuat pada</label>
                    <div class="detail-value">
                        @if($asesor->created_at)
                            {{ \Carbon\Carbon::parse($asesor->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir diupdate</label>
                    <div class="detail-value">
                        @if($asesor->updated_at)
                            {{ \Carbon\Carbon::parse($asesor->updated_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.asesor.edit', $asesor->ID_asesor) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Data
            </a>
            <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
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

    .skema-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .skema-name {
        font-size: 15px;
        color: #0F172A;
        font-weight: 600;
    }

    .skema-number {
        font-size: 13px;
        color: #64748b;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        width: fit-content;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-active {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        gap: 16px;
    }

    .info-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: #0073bd;
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .info-content {
        flex: 1;
    }

    .info-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 6px;
    }

    .info-desc {
        font-size: 14px;
        color: #475569;
        margin-bottom: 10px;
    }

    .info-note {
        font-size: 13px;
        color: #64748b;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .info-note i {
        margin-top: 2px;
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

        .info-box {
            flex-direction: column;
        }
    }
</style>
@endsection
