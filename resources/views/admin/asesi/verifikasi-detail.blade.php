@extends('admin.layout')

@section('title', 'Review Asesi - ' . $asesi->nama)
@section('page-title', 'Review & permohonan sertifkasi')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h2 {
        font-size: 22px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .review-page {
        width: 100%;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
    }

    .signature-section {
        margin-top: 18px;
        padding-top: 18px;
        border-top: 1px solid #e5e7eb;
    }

    .signature-section h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 8px;
    }

    .signature-box {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        overflow: hidden;
        position: relative;
        width: 260px;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .signature-box canvas {
        width: 100%;
        height: 100%;
        display: block;
        touch-action: none;
        cursor: crosshair;
        background: transparent;
    }

    .signature-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        color: #94a3b8;
        font-size: 13px;
        gap: 8px;
        text-align: center;
        padding: 0 16px;
    }

    .signature-box.has-signature .signature-placeholder {
        display: none;
    }

    /* Checklist table: closed boxes */
    .checklist-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .checklist-table th,
    .checklist-table td {
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 12px;
        vertical-align: middle;
        background: #fff;
    }

    .checklist-table th:last-child,
    .checklist-table td:last-child {
        border-right: none;
    }

    .checklist-table tbody tr:last-child td {
        border-bottom: none;
    }

    .checklist-table thead th {
        background: #f8fafc;
        text-transform: none;
        color: #475569;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .signature-note {
        font-size: 12px;
        color: #64748b;
    }

    .btn-clear-signature {
        padding: 8px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-clear-signature:hover {
        background: #f8fafc;
    }

    .signature-error {
        display: none;
        margin-top: 8px;
        font-size: 12px;
        color: #dc2626;
    }

    .back-link {
        display: none;
    }

    .profile-header {
        background: #fff;
        border-radius: 12px;
        padding: 28px;
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .profile-photo {
        width: 140px;
        aspect-ratio: 3 / 4;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
        border: 4px solid #e5e7eb;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .profile-photo-placeholder {
        width: 140px;
        aspect-ratio: 3 / 4;
        height: auto;
        border-radius: 8px;
        background: linear-gradient(135deg, #0073bd, #0073bd);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .profile-info h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
    }

    .profile-info .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: #6b7280;
    }

    .profile-info .meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-banned { background: #1e293b; color: #f8fafc; }

    .section-card {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .section-card .section-title {
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
    }

    .section-card .section-body {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .data-pribadi-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
        align-items: start;
    }

    .signature-right {
        display: flex;
        align-items: start;
        justify-content: center;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .info-item.full-width {
        grid-column: span 2;
    }

    .info-label {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    /* Documents */
    .doc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
    }

    .doc-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .doc-item:hover {
        border-color: #0073bd;
        background: #eff6ff;
    }

    .doc-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .doc-icon.image { background: #dbeafe; color: #0073bd; }
    .doc-icon.pdf { background: #fee2e2; color: #dc2626; }

    .doc-details {
        flex: 1;
        min-width: 0;
    }

    .doc-name {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .doc-link {
        font-size: 11px;
        color: #0073bd;
        text-decoration: none;
        font-weight: 500;
    }

    .doc-link:hover {
        text-decoration: underline;
    }

    .checklist-card {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .checklist-card .section-title {
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
    }

    .checklist-card .section-body {
        padding: 24px;
    }

    .checklist-note {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 16px;
    }

    .checklist-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        outline: none;
        border-radius: 10px;
        background: #fff;
    }

    .checklist-table th,
    .checklist-table td {
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 8px;
        font-size: 13px;
        vertical-align: middle;
        background: #fff;
    }

    .checklist-table th:last-child,
    .checklist-table td:last-child {
        border-right: none;
    }

    .checklist-table tbody tr:last-child td {
        border-bottom: none;
    }

    .checklist-table th {
        background: #f8fafc;
        font-weight: 700;
        text-align: center;
    }

    .checklist-table .row-label {
        text-align: left;
        font-weight: 500;
    }

    .checklist-radio-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .checklist-radio {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #1e293b;
        white-space: nowrap;
    }

    .checklist-radio input {
        accent-color: #0073bd;
        width: 14px;
        height: 14px;
    }

    .checklist-error {
        display: none;
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
    }

    .checklist-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .checklist-summary-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        background: #f8fafc;
    }

    .checklist-summary-card h5 {
        margin: 0 0 10px;
        font-size: 14px;
        color: #0f172a;
    }

    .checklist-summary-list {
        margin: 0;
        padding-left: 18px;
        font-size: 13px;
        color: #334155;
    }

    /* Action Section */
    .action-card {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 2px solid #e5e7eb;
        overflow: hidden;
    }

    .action-card .section-title {
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fefce8;
    }

    .action-card .section-body {
        padding: 24px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-approve {
        flex: 1;
        padding: 12px 24px;
        background: #0073bd;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-approve:hover {
        background: #005a9e;
    }

    .btn-reject {
        flex: 1;
        padding: 12px 24px;
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-reject:hover {
        background: #dc2626;
    }

    .btn-delete-registration {
        flex: 1;
        padding: 12px 24px;
        background: #991b1b;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-delete-registration:hover {
        background: #7f1d1d;
    }

    textarea.catatan {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
        outline: none;
        transition: border-color 0.2s;
    }

    textarea.catatan:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0,115,189,0.1);
    }

    .catatan-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
        display: block;
    }

    .verified-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .verified-info.rejected-info {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .verified-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .verified-icon.approved {
        background: #d1fae5;
        color: #059669;
    }

    .verified-icon.rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .verified-details h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 4px;
    }

    .verified-details p {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 12px;
        padding: 28px;
        width: 100%;
        max-width: 480px;
        margin: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-box-approve {
        max-width: 620px;
    }

    .modal-box h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 8px;
    }

    .modal-box p {
        font-size: 13px;
        color: #6b7280;
        margin: 0 0 18px;
    }

    .modal-box .catatan-label {
        display: block;
        margin-top: 16px;
        margin-bottom: 8px;
    }

    .modal-box textarea.catatan {
        margin-bottom: 0;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 18px;
    }

    .btn-cancel {
        padding: 10px 20px;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-confirm-approve {
        padding: 10px 20px;
        background: #0073bd;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-confirm-reject {
        padding: 10px 20px;
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item.full-width {
            grid-column: span 1;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-photo,
        .profile-photo-placeholder {
            width: 120px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .doc-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Review & permohonan sertifkasi</h2>
    <a href="{{ route('admin.asesi.verifikasi') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@php
    $selectedSkema = $asesi->skemas->first();
    $dynamicChecklistItems = data_get($selectedSkema, 'buktiPersyaratanDasarPemohon.items', []);
    $staticAdministrativeItems = [
        'Fotocopy Kartu Pelajar',
        'Fotocopy Kartu Keluarga/KTP',
        'Pas foto 3 x 4 berwarna sebanyak 2 lembar',
    ];

    $savedDynamicChecklist = is_array($asesi->verifikasi_bukti_persyaratan_dasar ?? null) ? $asesi->verifikasi_bukti_persyaratan_dasar : [];
    $savedAdministrativeChecklist = is_array($asesi->verifikasi_bukti_administratif ?? null) ? $asesi->verifikasi_bukti_administratif : [];
@endphp

<div class="review-page">
    <!-- Profile Header -->
    <div class="profile-header">
        @if($asesi->pas_foto)
            <img src="{{ asset('storage/' . $asesi->pas_foto) }}" alt="Foto {{ $asesi->nama }}" class="profile-photo">
        @else
            <div class="profile-photo-placeholder">
                {{ strtoupper(substr($asesi->nama, 0, 1)) }}
            </div>
        @endif
        <div class="profile-info">
            <h2>{{ $asesi->nama }}</h2>
            <div class="meta">
                <span><i class="bi bi-credit-card"></i> {{ $asesi->NIK }}</span>
                <span><i class="bi bi-envelope"></i> {{ $asesi->email ?? '-' }}</span>
                <span><i class="bi bi-telephone"></i> {{ $asesi->telepon_hp ?? '-' }}</span>
                <span>
                    <i class="bi bi-book"></i>
                    @if($asesi->skemas && $asesi->skemas->count())
                        @foreach($asesi->skemas as $skey => $s)
                            <span class="badge" style="background:#eef2ff;color:#0b4d84;margin-left:6px;">{{ $s->nama_skema ?? '-' }}@if(!empty($s->nomor_skema)) ({{ $s->nomor_skema }})@endif</span>
                        @endforeach
                    @else
                        <span style="color:#9ca3af; margin-left:6px;">Belum terdaftar ke skema</span>
                    @endif
                </span>
                <span>
                    @if($asesi->status === 'pending')
                        <span class="badge badge-pending">Menunggu Verifikasi</span>
                    @elseif($asesi->status === 'approved')
                        <span class="badge badge-approved">Disetujui</span>
                    @elseif($asesi->status === 'banned')
                        <span class="badge badge-banned">Ditolak Permanen</span>
                    @else
                        <span class="badge badge-rejected">Ditolak</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Data Pribadi -->
    <div class="section-card">
        <div class="section-title">
            <i class="bi bi-person" style="color:#0073bd;"></i> Data Pribadi
        </div>
        <div class="section-body">
            <div class="data-pribadi-grid">
                <div>
                    <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">NIK</span>
                    <span class="info-value">{{ $asesi->NIK }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $asesi->nama }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tempat, Tanggal Lahir</span>
                    <span class="info-value">{{ $asesi->tempat_lahir ?? '-' }}, {{ $asesi->tanggal_lahir ? \Carbon\Carbon::parse($asesi->tanggal_lahir)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">{{ $asesi->jenis_kelamin ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $asesi->email ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. Telepon</span>
                    <span class="info-value">{{ $asesi->telepon_hp ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kewarganegaraan</span>
                    <span class="info-value">{{ $asesi->kewarganegaraan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kode Pos</span>
                    <span class="info-value">{{ $asesi->kode_pos ?? '-' }}</span>
                </div>
                        <div class="info-item full-width">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">{{ $asesi->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="signature-right">
                    <div style="width:100%;max-width:320px;">
                        <div class="info-label" style="margin-bottom:8px;">Tanda Tangan Asesi</div>
                        @if($asesi->tanda_tangan_pendaftar)
                            <div style="border:1px solid #e5e7eb;border-radius:8px;background:#fff;padding:8px;display:flex;flex-direction:column;align-items:center;">
                                <img src="{{ $asesi->tanda_tangan_pendaftar }}" alt="Tanda tangan {{ $asesi->nama }}" style="max-width:260px;width:100%;height:auto;">
                                @if($asesi->tanggal_tanda_tangan_pendaftar)
                                    <div style="font-size:11px;color:#94a3b8;margin-top:8px;">{{ \Carbon\Carbon::parse($asesi->tanggal_tanda_tangan_pendaftar)->locale('id')->translatedFormat('d M Y H:i') }}</div>
                                @endif
                            </div>
                        @else
                            <div style="color:#9ca3af;border:1px dashed #e5e7eb;border-radius:8px;padding:18px;text-align:center;">Belum ada tanda tangan</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pendidikan & Pekerjaan -->
    <div class="section-card">
        <div class="section-title">
            <i class="bi bi-mortarboard" style="color:#8b5cf6;"></i> Pendidikan & Pekerjaan
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Pendidikan Terakhir</span>
                    <span class="info-value">{{ $asesi->pendidikan_terakhir ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jurusan</span>
                    <span class="info-value">{{ $asesi->jurusan->nama_jurusan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Pekerjaan</span>
                    <span class="info-value">{{ $asesi->pekerjaan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">{{ $asesi->jabatan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Lembaga / Instansi</span>
                    <span class="info-value">{{ $asesi->nama_lembaga ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Lembaga</span>
                    <span class="info-value">{{ $asesi->email_lembaga ?? '-' }}</span>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">Alamat Lembaga</span>
                    <span class="info-value">{{ $asesi->alamat_lembaga ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung -->
    <div class="section-card">
        <div class="section-title">
            <i class="bi bi-folder2-open" style="color:#f59e0b;"></i> Dokumen Pendukung
        </div>
        <div class="section-body">
            {{-- Transkrip Nilai --}}
            <h4 style="font-size:14px;font-weight:600;color:#374151;margin:0 0 12px;">
                <i class="bi bi-file-earmark-text" style="color:#8b5cf6;"></i> Transkrip Nilai
            </h4>
            @if($asesi->transkripNilai->count() > 0)
                <div class="doc-grid" style="margin-bottom:24px;">
                    @foreach($asesi->transkripNilai as $doc)
                        <div class="doc-item">
                            <div class="doc-icon {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'pdf' : 'image' }}">
                                <i class="bi {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image' }}"></i>
                            </div>
                            <div class="doc-details">
                                <div class="doc-name" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</div>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="doc-link">Lihat File</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#9ca3af;font-size:13px;margin:0 0 24px;">Tidak ada file transkrip.</p>
            @endif

            {{-- Identitas Pribadi --}}
            <h4 style="font-size:14px;font-weight:600;color:#374151;margin:0 0 12px;">
                <i class="bi bi-person-badge" style="color:#f59e0b;"></i> Identitas Pribadi (KTP/Kartu Pelajar/KK)
            </h4>
            @if($asesi->identitasPribadi->count() > 0)
                <div class="doc-grid" style="margin-bottom:24px;">
                    @foreach($asesi->identitasPribadi as $doc)
                        <div class="doc-item">
                            <div class="doc-icon {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'pdf' : 'image' }}">
                                <i class="bi {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image' }}"></i>
                            </div>
                            <div class="doc-details">
                                <div class="doc-name" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</div>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="doc-link">Lihat File</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#9ca3af;font-size:13px;margin:0 0 24px;">Tidak ada file identitas.</p>
            @endif

            {{-- Bukti Kompetensi --}}
            <h4 style="font-size:14px;font-weight:600;color:#374151;margin:0 0 12px;">
                <i class="bi bi-award" style="color:#10b981;"></i> Bukti Kompetensi
            </h4>
            @if($asesi->buktiKompetensi->count() > 0)
                <div class="doc-grid">
                    @foreach($asesi->buktiKompetensi as $doc)
                        <div class="doc-item">
                            <div class="doc-icon {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'pdf' : 'image' }}">
                                <i class="bi {{ str_ends_with(strtolower($doc->file_path), '.pdf') ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image' }}"></i>
                            </div>
                            <div class="doc-details">
                                <div class="doc-name" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</div>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="doc-link">Lihat File</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#9ca3af;font-size:13px;margin:0;">Tidak ada file bukti kompetensi.</p>
            @endif
        </div>
    </div>

    @if($asesi->status === 'pending')
        <div class="checklist-card">
            <div class="section-title">
                <i class="bi bi-check2-square" style="color:#0073bd;"></i> Ceklis Verifikasi Persyaratan
            </div>
            <div class="section-body">
                <p class="checklist-note">
                    Pilih status untuk setiap bukti persyaratan dasar pemohon dan bukti administratif sebelum menyetujui atau menolak pendaftaran.
                </p>

                <div class="checklist-error" id="checklistErrorBoxTop" style="display:none;margin-bottom:16px;position:sticky;top:12px;z-index:5;">
                    Masih ada checklist yang belum diisi. Lengkapi semua item terlebih dahulu.
                </div>

                @if(!$selectedSkema)
                    <div class="checklist-error" style="display:block;">
                        Asesi ini belum terhubung ke skema. Checklist dinamis tidak dapat ditampilkan.
                    </div>
                @endif

                @if($selectedSkema)
                    <h4 style="font-size:14px;font-weight:600;color:#374151;margin:0 0 12px;">3.1 Bukti Persyaratan Dasar Pemohon</h4>
                    <table class="checklist-table" style="margin-bottom:20px;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:58px;">No.</th>
                                <th rowspan="2">Bukti Persyaratan Dasar</th>
                                <th colspan="2">Ada</th>
                                <th rowspan="2" style="width:120px;"> 
                                    <input type="checkbox" class="check-all-column" data-index="5" data-value="tidak_ada" style="margin-right:8px;vertical-align:middle;">Tidak Ada
                                </th>
                            </tr>
                            <tr>
                                <th style="width:150px;"> 
                                    <input type="checkbox" class="check-all-column" data-index="3" data-value="memenuhi" style="margin-right:8px;vertical-align:middle;">Memenuhi Syarat
                                </th>
                                <th style="width:170px;"> 
                                    <input type="checkbox" class="check-all-column" data-index="4" data-value="tidak_memenuhi" style="margin-right:8px;vertical-align:middle;">Tidak Memenuhi Syarat
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($dynamicChecklistItems ?? []) as $index => $itemLabel)
                                @php
                                    $rowName = 'dynamic_check_' . $index;
                                    $savedValue = $savedDynamicChecklist[$index]['status'] ?? null;
                                    $label = is_array($itemLabel) ? ($itemLabel['label'] ?? $itemLabel['nama'] ?? '') : $itemLabel;
                                @endphp
                                <tr data-checklist-row="dynamic">
                                    <td style="text-align:center;">{{ $index + 1 }}.</td>
                                    <td class="row-label" data-checklist-label="{{ $label }}">{{ $label }}</td>
                                    <td>
                                        <label class="checklist-radio">
                                            <input type="radio" name="{{ $rowName }}" value="memenuhi" {{ $savedValue === 'memenuhi' ? 'checked' : '' }}>
                                            <span>Memenuhi</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="checklist-radio">
                                            <input type="radio" name="{{ $rowName }}" value="tidak_memenuhi" {{ $savedValue === 'tidak_memenuhi' ? 'checked' : '' }}>
                                            <span>Tidak Memenuhi</span>
                                        </label>
                                    </td>
                                    <td style="text-align:center;">
                                        <label class="checklist-radio">
                                            <input type="radio" name="{{ $rowName }}" value="tidak_ada" {{ $savedValue === 'tidak_ada' ? 'checked' : '' }}>
                                            <span>Tidak Ada</span>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;color:#64748b;">Belum ada master persyaratan dasar untuk skema ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

                <h4 style="font-size:14px;font-weight:600;color:#374151;margin:0 0 12px;">3.2 Bukti Administratif</h4>
                <table class="checklist-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:58px;">No.</th>
                            <th rowspan="2">Bukti Administratif</th>
                            <th colspan="2">Ada</th>
                            <th rowspan="2" style="width:120px;"> 
                                <input type="checkbox" class="check-all-column" data-index="5" data-value="tidak_ada" style="margin-right:8px;vertical-align:middle;">Tidak Ada
                            </th>
                        </tr>
                        <tr>
                            <th style="width:150px;"> 
                                <input type="checkbox" class="check-all-column" data-index="3" data-value="memenuhi" style="margin-right:8px;vertical-align:middle;">Memenuhi Syarat
                            </th>
                            <th style="width:170px;"> 
                                <input type="checkbox" class="check-all-column" data-index="4" data-value="tidak_memenuhi" style="margin-right:8px;vertical-align:middle;">Tidak Memenuhi Syarat
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staticAdministrativeItems as $index => $itemLabel)
                            @php
                                $rowName = 'administrative_check_' . $index;
                                $savedValue = $savedAdministrativeChecklist[$index]['status'] ?? null;
                            @endphp
                            <tr data-checklist-row="administrative">
                                <td style="text-align:center;">{{ $index + 1 }}.</td>
                                <td class="row-label" data-checklist-label="{{ $itemLabel }}">{{ $itemLabel }}</td>
                                <td>
                                    <label class="checklist-radio">
                                        <input type="radio" name="{{ $rowName }}" value="memenuhi" {{ $savedValue === 'memenuhi' ? 'checked' : '' }}>
                                        <span>Memenuhi</span>
                                    </label>
                                </td>
                                <td>
                                    <label class="checklist-radio">
                                        <input type="radio" name="{{ $rowName }}" value="tidak_memenuhi" {{ $savedValue === 'tidak_memenuhi' ? 'checked' : '' }}>
                                        <span>Tidak Memenuhi</span>
                                    </label>
                                </td>
                                <td style="text-align:center;">
                                    <label class="checklist-radio">
                                        <input type="radio" name="{{ $rowName }}" value="tidak_ada" {{ $savedValue === 'tidak_ada' ? 'checked' : '' }}>
                                        <span>Tidak Ada</span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="checklist-error" id="checklistErrorBox">Semua item checklist harus dipilih sebelum menyimpan verifikasi.</div>
            </div>
        </div>
    @endif

    <!-- Action / Verification -->
    @if($asesi->status === 'pending')
        <div class="action-card">
            <div class="section-title">
                <i class="bi bi-shield-check" style="color:#d97706;"></i> Tindakan Verifikasi
            </div>
            <div class="section-body">
                @if($errors->any())
                    <div class="error-text" style="margin-bottom:12px;">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="action-buttons">
                    <button type="button" class="btn-approve" onclick="showApproveModal()">
                        <i class="bi bi-check-circle"></i> Setujui Pendaftaran
                    </button>
                    <button type="button" class="btn-reject" onclick="showRejectModal()">
                        <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                    </button>
                    <button type="button" class="btn-delete-registration" onclick="showDeleteRegistrationModal()">
                        <i class="bi bi-trash"></i> Hapus Data Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Already Verified -->
        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-info-circle" style="color:#0073bd;"></i> Informasi Verifikasi
            </div>
            <div class="section-body">
                <div class="verified-info {{ in_array($asesi->status, ['rejected','banned']) ? 'rejected-info' : '' }}">
                    <div class="verified-icon {{ $asesi->status }}">
                        <i class="bi {{ $asesi->status === 'approved' ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                    </div>
                    <div class="verified-details">
                        <h4>
                            @if($asesi->status === 'approved')
                                Pendaftaran Disetujui
                            @elseif($asesi->status === 'banned')
                                Ditolak Permanen (Tidak Dapat Mendaftar Ulang)
                            @else
                                Pendaftaran Ditolak
                            @endif
                        </h4>
                        <p>
                            Diverifikasi oleh <strong>{{ $asesi->verifiedBy->name ?? 'Admin' }}</strong>
                            pada {{ $asesi->verified_at ? \Carbon\Carbon::parse($asesi->verified_at)->locale('id')->translatedFormat('d M Y, H:i') : '-' }}
                        </p>
                        @if($asesi->catatan_admin)
                            <p style="margin-top:8px;"><strong>Catatan:</strong> {{ $asesi->catatan_admin }}</p>
                        @endif
                        @if($asesi->tanda_tangan_admin)
                            <div style="margin-top:12px;padding-top:12px;border-top:1px dashed #e5e7eb;">
                                <p style="font-size:12px;color:#64748b;margin:0 0 8px;">Tanda tangan admin</p>
                                <img src="{{ $asesi->tanda_tangan_admin }}" alt="Tanda tangan admin" style="max-width:260px;width:100%;height:auto;border:1px solid #e5e7eb;border-radius:8px;background:#fff;padding:8px;">
                                @if($asesi->tanggal_tanda_tangan_admin)
                                    <p style="font-size:11px;color:#94a3b8;margin-top:6px;">{{ \Carbon\Carbon::parse($asesi->tanggal_tanda_tangan_admin)->locale('id')->translatedFormat('d M Y H:i') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if($asesi->status !== 'approved')
                    <div class="action-buttons" style="margin-top:16px;">
                        <button type="button" class="btn-delete-registration" onclick="showDeleteRegistrationModal()">
                            <i class="bi bi-trash"></i> Hapus Data Pendaftaran
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div class="modal-overlay" id="approveModal">
    <div class="modal-box modal-box-approve">
        <h3><i class="bi bi-check-circle" style="color:#10b981;"></i> Konfirmasi Persetujuan</h3>
        <p>Apakah Anda yakin ingin <strong>menyetujui</strong> pendaftaran <strong>{{ $asesi->nama }}</strong>? Email notifikasi akan dikirim ke <strong>{{ $asesi->email }}</strong>.</p>

        @php $adminTTD = auth()->guard('admin')->user()->tanda_tangan ?? null; @endphp

        <div class="signature-section" style="margin-top:14px;padding-top:0;border-top:none;">
            <h4 style="display:flex;align-items:center;gap:8px;justify-content:center;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda Tangan Admin</h4>

            @if($adminTTD)
                {{-- Mode pilihan: tersimpan vs baru --}}
                <div id="sigOptionWrap" style="margin:10px 0 12px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;margin-bottom:8px;" id="optSavedLabel">
                        <input type="radio" name="sig_choice" value="saved" checked id="optSaved" onchange="toggleSigChoice()" style="accent-color:#10b981;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#166534;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Gunakan tanda tangan tersimpan</div>
                            <div style="font-size:12px;color:#64748b;">Otomatis menggunakan TTD yang sudah disimpan di profil Anda</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;background:#f8fafc;" id="optNewLabel">
                        <input type="radio" name="sig_choice" value="new" id="optNew" onchange="toggleSigChoice()" style="accent-color:#0073bd;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda tangan baru</div>
                            <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk form ini</div>
                        </div>
                    </label>
                </div>

                {{-- Preview TTD tersimpan --}}
                <div id="savedSigPreview" style="text-align:center;">
                    <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;">
                        <img src="{{ $adminTTD }}" alt="TTD Tersimpan" style="max-width:280px;height:auto;display:block;">
                    </div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:6px;">Tanda tangan tersimpan dari profil Anda</div>
                </div>

                {{-- Canvas tanda tangan baru (tersembunyi awalnya) --}}
                <div id="newSigDraw" style="display:none;">
                    <div class="signature-box" id="signatureBoxAdmin">
                        <canvas id="signatureCanvasAdmin"></canvas>
                        <div class="signature-placeholder" id="signaturePlaceholderAdmin">
                            <i class="bi bi-pen"></i>
                            <span>Silakan tanda tangan di sini.</span>
                        </div>
                    </div>
                    <div class="signature-actions" style="justify-content:space-between;margin-top:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" id="saveSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                            <label for="saveSigCheck" style="font-size:12px;color:#475569;cursor:pointer;">Simpan sebagai tanda tangan saya</label>
                        </div>
                        <button type="button" class="btn-clear-signature" id="clearSignatureAdmin">
                            <i class="bi bi-arrow-counterclockwise"></i> Hapus
                        </button>
                    </div>
                </div>

            @else
                {{-- Belum ada TTD tersimpan: langsung tampilkan canvas --}}
                <div class="signature-box" id="signatureBoxAdmin">
                    <canvas id="signatureCanvasAdmin"></canvas>
                    <div class="signature-placeholder" id="signaturePlaceholderAdmin">
                        <i class="bi bi-pen"></i>
                        <span>Silakan tanda tangan admin sebelum menyetujui pendaftaran.</span>
                    </div>
                </div>
                <div class="signature-actions" style="justify-content:space-between;margin-top:8px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" id="saveSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                        <label for="saveSigCheck" style="font-size:12px;color:#475569;cursor:pointer;">Simpan sebagai tanda tangan saya</label>
                    </div>
                    <button type="button" class="btn-clear-signature" id="clearSignatureAdmin">
                        <i class="bi bi-arrow-counterclockwise"></i> Hapus Tanda Tangan
                    </button>
                </div>
            @endif

            <div class="signature-note" style="text-align:center;margin-top:8px;">Tanda tangan ini akan tersimpan pada data permohonan sertifkasi.</div>
            <div class="signature-error" id="signatureErrorAdmin">Tanda tangan admin wajib diisi.</div>
        </div>

        <form method="POST" action="{{ route('admin.asesi.approve', $asesi->NIK) }}" onsubmit="return prepareChecklistPayload('approve')" id="approveForm">
            @csrf
            <input type="hidden" name="tanda_tangan_admin" class="signature-admin-input">
            <input type="hidden" name="verifikasi_bukti_persyaratan_dasar" id="approveChecklistPersyaratan">
            <input type="hidden" name="verifikasi_bukti_administratif" id="approveChecklistAdministratif">
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('approveModal')">Batal</button>
                <button type="submit" class="btn-confirm-approve"><i class="bi bi-check-lg"></i> Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <h3><i class="bi bi-x-circle" style="color:#ef4444;"></i> Konfirmasi Penolakan</h3>
        <p>Apakah Anda yakin ingin <strong>menolak</strong> pendaftaran <strong>{{ $asesi->nama }}</strong>? Email notifikasi akan dikirim ke <strong>{{ $asesi->email }}</strong>.</p>
        <form method="POST" action="{{ route('admin.asesi.reject', $asesi->NIK) }}" id="rejectForm" onsubmit="return validateReject() && prepareChecklistPayload('reject')">
            @csrf
            <input type="hidden" name="tanda_tangan_admin" class="signature-admin-input">
            <input type="hidden" name="verifikasi_bukti_persyaratan_dasar" id="rejectChecklistPersyaratan">
            <input type="hidden" name="verifikasi_bukti_administratif" id="rejectChecklistAdministratif">
            {{-- Reject type --}}
            <label class="catatan-label" style="margin-bottom:8px;">Jenis Penolakan <span style="color:#ef4444;">*</span></label>
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:10px 14px;">
                    <input type="radio" name="reject_type" value="rejected" checked id="rt_rejected" onchange="updateRejectBtn()" style="margin-top:3px;accent-color:#ef4444;">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#1e293b;">Tolak Sementara</div>
                        <div style="font-size:12px;color:#64748b;">Asesi dapat memperbaiki dan mendaftar ulang.</div>
                    </div>
                </label>
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:10px 14px;">
                    <input type="radio" name="reject_type" value="banned" id="rt_banned" onchange="updateRejectBtn()" style="margin-top:3px;accent-color:#1e293b;">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#991b1b;">Tolak Permanen <span style="font-size:11px;background:#fee2e2;color:#991b1b;padding:1px 6px;border-radius:4px;margin-left:4px;">Tidak bisa daftar lagi</span></div>
                        <div style="font-size:12px;color:#64748b;">Asesi tidak dapat mendaftar ulang selamanya.</div>
                    </div>
                </label>
            </div>
            <label class="catatan-label">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
            <textarea name="catatan_admin" id="reject_catatan" class="catatan" placeholder="Tulis alasan penolakan di sini..." required></textarea>
            <div id="reject-error" class="error-text" style="display:none;margin-top:6px;">Alasan penolakan wajib diisi.</div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn-confirm-reject" id="btnConfirmReject"><i class="bi bi-x-lg"></i> Ya, Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Registration Modal -->
<div class="modal-overlay" id="deleteRegistrationModal">
    <div class="modal-box">
        <h3><i class="bi bi-trash" style="color:#dc2626;"></i> Konfirmasi Hapus Data Pendaftaran</h3>
        <p style="margin-bottom:10px;">Anda akan menghapus data pendaftaran <strong>{{ $asesi->nama }}</strong>.</p>
        <p style="font-size:13px;color:#991b1b;background:#fee2e2;border:1px solid #fecaca;padding:10px 12px;border-radius:8px;">
            Data formulir dan dokumen pendukung akan dihapus. Akun login tetap ada agar asesi bisa isi ulang formulir dari awal.
        </p>
        <form method="POST" action="{{ route('admin.asesi.delete-registration', $asesi->NIK) }}">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('deleteRegistrationModal')">Batal</button>
                <button type="submit" class="btn-confirm-reject"><i class="bi bi-trash"></i> Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showApproveModal() {
        document.getElementById('checklistErrorBox').style.display = 'none';
        document.getElementById('approveModal').classList.add('show');
        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                if (typeof window.adminSignatureResize === 'function') {
                    window.adminSignatureResize();
                }
            });
        });
    }

    function toggleSigChoice() {
        const saved = document.getElementById('optSaved');
        const savedPreview = document.getElementById('savedSigPreview');
        const newDraw = document.getElementById('newSigDraw');
        const optSavedLabel = document.getElementById('optSavedLabel');
        const optNewLabel = document.getElementById('optNewLabel');

        if (!saved) return;

        if (saved.checked) {
            // Gunakan TTD tersimpan
            if (savedPreview) savedPreview.style.display = '';
            if (newDraw) newDraw.style.display = 'none';
            optSavedLabel.style.borderColor = '#d1fae5';
            optSavedLabel.style.background = '#f0fdf4';
            optNewLabel.style.borderColor = '#e2e8f0';
            optNewLabel.style.background = '#f8fafc';
            // Isi input dengan TTD tersimpan
            const savedSrc = document.querySelector('#savedSigPreview img')?.src || '';
            document.querySelectorAll('.signature-admin-input').forEach(el => el.value = savedSrc);
        } else {
            // Tanda tangan baru
            if (savedPreview) savedPreview.style.display = 'none';
            if (newDraw) newDraw.style.display = 'block';
            optSavedLabel.style.borderColor = '#e2e8f0';
            optSavedLabel.style.background = '#f8fafc';
            optNewLabel.style.borderColor = '#bfdbfe';
            optNewLabel.style.background = '#eff6ff';
            // Kosongkan input (akan diisi saat draw)
            document.querySelectorAll('.signature-admin-input').forEach(el => el.value = '');
            // Init canvas jika belum
            setTimeout(() => {
                if (typeof window.adminSignatureResize === 'function') {
                    window.adminSignatureResize();
                } else {
                    initSignaturePadAdmin();
                }
            }, 50);
        }
    }

    function updateRejectBtn() {
        const isBanned = document.getElementById('rt_banned').checked;
        const btn = document.getElementById('btnConfirmReject');
        if (isBanned) {
            btn.style.background = '#1e293b';
            btn.innerHTML = '<i class="bi bi-slash-circle"></i> Ya, Tolak Permanen';
        } else {
            btn.style.background = '#ef4444';
            btn.innerHTML = '<i class="bi bi-x-lg"></i> Ya, Tolak';
        }
    }

    function showRejectModal() {
        // Reset error state
        document.getElementById('reject-error').style.display = 'none';
        document.getElementById('checklistErrorBox').style.display = 'none';
        document.getElementById('rt_rejected').checked = true;
        updateRejectBtn();
        document.getElementById('reject_catatan').style.borderColor = '#d1d5db';
        document.getElementById('reject_catatan').value = '';
        document.getElementById('rejectModal').classList.add('show');
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('reject_catatan').focus();
        }, 100);
    }

    function showDeleteRegistrationModal() {
        document.getElementById('deleteRegistrationModal').classList.add('show');
    }

    function validateReject() {
        const catatan = document.getElementById('reject_catatan').value;
        if (!catatan.trim()) {
            document.getElementById('reject-error').style.display = 'block';
            document.getElementById('reject_catatan').style.borderColor = '#ef4444';
            document.getElementById('reject_catatan').focus();
            return false;
        }
        return true;
    }

    function collectChecklistItems() {
        const rows = Array.from(document.querySelectorAll('[data-checklist-row]'));

        return rows.map((row) => {
            const labelEl = row.querySelector('[data-checklist-label]');
            const checked = row.querySelector('input[type="radio"]:checked');

            return {
                label: labelEl ? labelEl.textContent.trim() : '',
                status: checked ? checked.value : null,
            };
        });
    }

    function prepareChecklistPayload(prefix) {
        const items = collectChecklistItems();
        const dynamicCount = {{ count($dynamicChecklistItems ?? []) }};
        const hasMissing = items.some((item) => !item.status);

        if (hasMissing) {
            const topErrorBox = document.getElementById('checklistErrorBoxTop');
            const errorBox = document.getElementById('checklistErrorBox');
            if (topErrorBox) {
                topErrorBox.style.display = 'block';
                topErrorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (errorBox) {
                errorBox.style.display = 'block';
            }
            return false;
        }

        const targetPersyaratan = document.getElementById(`${prefix}ChecklistPersyaratan`);
        const targetAdministratif = document.getElementById(`${prefix}ChecklistAdministratif`);

        if (targetPersyaratan) {
            targetPersyaratan.value = JSON.stringify(items.filter((item, index) => index < dynamicCount));
        }

        if (targetAdministratif) {
            targetAdministratif.value = JSON.stringify(items.filter((item, index) => index >= dynamicCount));
        }

        return true;
    }

    function initChecklistHeaderToggles() {
        const tables = document.querySelectorAll('.checklist-table');
        if (!tables.length) return;

        tables.forEach(function(table) {
            const headerChecks = table.querySelectorAll('.check-all-column');
            if (!headerChecks.length) return;

            headerChecks.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    const colIndex = parseInt(this.dataset.index, 10);

                    if (this.checked) {
                        headerChecks.forEach(function(h) { if (h !== cb) h.checked = false; });
                    }

                    const rows = table.querySelectorAll('tbody tr[data-checklist-row]');
                    rows.forEach(function(row) {
                        const td = row.children[colIndex - 1];
                        if (!td) return;
                        const radio = td.querySelector('input[type="radio"]');
                        if (cb.checked) {
                            if (radio) radio.checked = true;
                        } else {
                            const all = row.querySelectorAll('input[type="radio"]');
                            all.forEach(function(r) { r.checked = false; });
                        }
                    });
                });
            });
        });
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        // Reset reject form if closing reject modal
        if (id === 'rejectModal') {
            document.getElementById('reject-error').style.display = 'none';
            document.getElementById('reject_catatan').style.borderColor = '#d1d5db';
        }
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                const modalId = overlay.id;
                closeModal(modalId);
            }
        });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(function(modal) {
                closeModal(modal.id);
            });
        }
    });

    function initSignaturePadAdmin() {
        const canvas = document.getElementById('signatureCanvasAdmin');
        const box = document.getElementById('signatureBoxAdmin');
        const inputEls = document.querySelectorAll('.signature-admin-input');
        const clearButton = document.getElementById('clearSignatureAdmin');
        const errorBox = document.getElementById('signatureErrorAdmin');
        const placeholder = document.getElementById('signaturePlaceholderAdmin');
        if (!canvas || !box) return;
        const ctx = canvas.getContext('2d');

        window.adminSignatureInput = inputEls[0] || null;

        let drawing = false;
        let hasSignature = false;
        let points = [];

        const resizeCanvas = () => {
            const data = inputEls[0] ? inputEls[0].value : '';
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.lineWidth = 2.5;
            ctx.strokeStyle = '#111827';
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (data && data.startsWith('data:')) {
                const img = new Image();
                img.onload = () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, rect.width, rect.height);
                    hasSignature = true;
                    box.classList.add('has-signature');
                    if (placeholder) placeholder.style.display = 'none';
                };
                img.src = data;
            } else {
                hasSignature = false;
                box.classList.remove('has-signature');
                if (placeholder) placeholder.style.display = 'flex';
            }
        };

        window.adminSignatureResize = resizeCanvas;

        const getPoint = (event) => {
            const rect = canvas.getBoundingClientRect();
            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top,
            };
        };

        const syncInputs = () => {
            const data = canvas.toDataURL('image/png');
            inputEls.forEach(function(el) {
                el.value = hasSignature ? data : '';
            });
            window.adminSignatureInput = inputEls[0] || null;
        };

        const start = (event) => {
            drawing = true;
            points = [getPoint(event)];
            if (errorBox) errorBox.style.display = 'none';
            canvas.setPointerCapture?.(event.pointerId);
        };

        const draw = (event) => {
            if (!drawing) return;
            const point = getPoint(event);
            points.push(point);
            if (points.length < 2) return;
            const prev = points[points.length - 2];
            ctx.beginPath();
            ctx.moveTo(prev.x, prev.y);
            ctx.lineTo(point.x, point.y);
            ctx.stroke();
            if (!hasSignature) {
                hasSignature = true;
                box.classList.add('has-signature');
                if (placeholder) placeholder.style.display = 'none';
            }
            syncInputs();
        };

        const stop = () => {
            drawing = false;
            if (points.length > 1) {
                syncInputs();
            }
            points = [];
        };

        if (clearButton) {
            clearButton.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                box.classList.remove('has-signature');
                if (placeholder) placeholder.style.display = 'flex';
                if (errorBox) errorBox.style.display = 'none';
                inputEls.forEach(function(el) { el.value = ''; });
                window.adminSignatureInput = inputEls[0] || null;
            });
        }

        canvas.addEventListener('pointerdown', start);
        canvas.addEventListener('pointermove', draw);
        canvas.addEventListener('pointerup', stop);
        canvas.addEventListener('pointerleave', stop);

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSignaturePadAdmin();
        initChecklistHeaderToggles();

        // Jika ada TTD tersimpan, pre-fill hidden input dengan TTD tersimpan saat modal dibuka
        const optSaved = document.getElementById('optSaved');
        if (optSaved && optSaved.checked) {
            const savedSrc = document.querySelector('#savedSigPreview img')?.src || '';
            if (savedSrc) {
                document.querySelectorAll('.signature-admin-input').forEach(el => el.value = savedSrc);
            }
        }

        // Handle form submit untuk approve
        const approveForm = document.getElementById('approveForm');
        if (approveForm) {
            approveForm.addEventListener('submit', function(event) {
                const adminInput = approveForm.querySelector('.signature-admin-input');
                if (!adminInput || !adminInput.value) {
                    event.preventDefault();
                    const errBox = document.getElementById('signatureErrorAdmin');
                    if (errBox) errBox.style.display = 'block';
                    const canvas = document.getElementById('signatureCanvasAdmin');
                    if (canvas) canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                // Jika menggunakan TTD baru dan checkbox simpan aktif, simpan via AJAX setelah submit
                const optNew = document.getElementById('optNew');
                const saveCheck = document.getElementById('saveSigCheck');
                if (optNew && optNew.checked && saveCheck && saveCheck.checked && adminInput.value) {
                    // Simpan TTD baru ke profil (fire-and-forget, tidak memblokir submit)
                    fetch(@json(route('admin.profile.save-signature')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ tanda_tangan: adminInput.value }),
                    }).catch(() => {});
                } else if (!optNew && saveCheck && saveCheck.checked && adminInput.value) {
                    // Belum ada TTD tersimpan, simpan TTD baru
                    fetch(@json(route('admin.profile.save-signature')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ tanda_tangan: adminInput.value }),
                    }).catch(() => {});
                }
            });
        }
    });
</script>
@endsection
