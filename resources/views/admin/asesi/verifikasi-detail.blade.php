@extends('admin.layout')

@section('title', 'Review Asesi - ' . $asesi->nama)
@section('page-title', 'Review & Verifikasi Asesi')

@section('styles')
<style>
    .review-page {
        max-width: 1000px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #2563eb;
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
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e5e7eb;
        flex-shrink: 0;
    }

    .profile-photo-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
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
        border-color: #3b82f6;
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

    .doc-icon.image { background: #dbeafe; color: #2563eb; }
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
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }

    .doc-link:hover {
        text-decoration: underline;
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
        background: #10b981;
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
        background: #059669;
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
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
        background: #10b981;
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
<div class="review-page">
    <a href="{{ route('admin.asesi.verifikasi') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Verifikasi
    </a>

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
                    @if($asesi->status === 'pending')
                        <span class="badge badge-pending">Menunggu Verifikasi</span>
                    @elseif($asesi->status === 'approved')
                        <span class="badge badge-approved">Disetujui</span>
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
            <i class="bi bi-person" style="color:#2563eb;"></i> Data Pribadi
        </div>
        <div class="section-body">
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
                    <span class="info-value">{{ $asesi->tempat_lahir ?? '-' }}, {{ $asesi->tanggal_lahir ? $asesi->tanggal_lahir->format('d M Y') : '-' }}</span>
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
                </div>
            </div>
        </div>
    @else
        <!-- Already Verified -->
        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-info-circle" style="color:#2563eb;"></i> Informasi Verifikasi
            </div>
            <div class="section-body">
                <div class="verified-info {{ $asesi->status === 'rejected' ? 'rejected-info' : '' }}">
                    <div class="verified-icon {{ $asesi->status }}">
                        <i class="bi {{ $asesi->status === 'approved' ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                    </div>
                    <div class="verified-details">
                        <h4>
                            @if($asesi->status === 'approved')
                                Pendaftaran Disetujui
                            @else
                                Pendaftaran Ditolak
                            @endif
                        </h4>
                        <p>
                            Diverifikasi oleh <strong>{{ $asesi->verifiedBy->name ?? 'Admin' }}</strong>
                            pada {{ $asesi->verified_at ? $asesi->verified_at->format('d M Y, H:i') : '-' }}
                        </p>
                        @if($asesi->catatan_admin)
                            <p style="margin-top:8px;"><strong>Catatan:</strong> {{ $asesi->catatan_admin }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div class="modal-overlay" id="approveModal">
    <div class="modal-box">
        <h3><i class="bi bi-check-circle" style="color:#10b981;"></i> Konfirmasi Persetujuan</h3>
        <p>Apakah Anda yakin ingin <strong>menyetujui</strong> pendaftaran <strong>{{ $asesi->nama }}</strong>? Email notifikasi akan dikirim ke <strong>{{ $asesi->email }}</strong>.</p>
        <form method="POST" action="{{ route('admin.asesi.approve', $asesi->NIK) }}">
            @csrf
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
        <form method="POST" action="{{ route('admin.asesi.reject', $asesi->NIK) }}" id="rejectForm" onsubmit="return validateReject()">
            @csrf
            <label class="catatan-label">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
            <textarea name="catatan_admin" id="reject_catatan" class="catatan" placeholder="Tulis alasan penolakan di sini..." required></textarea>
            <div id="reject-error" class="error-text" style="display:none;margin-top:6px;">Alasan penolakan wajib diisi.</div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn-confirm-reject"><i class="bi bi-x-lg"></i> Ya, Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showApproveModal() {
        document.getElementById('approveModal').classList.add('show');
    }

    function showRejectModal() {
        // Reset error state
        document.getElementById('reject-error').style.display = 'none';
        document.getElementById('reject_catatan').style.borderColor = '#d1d5db';
        document.getElementById('reject_catatan').value = '';
        document.getElementById('rejectModal').classList.add('show');
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('reject_catatan').focus();
        }, 100);
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
</script>
@endsection
