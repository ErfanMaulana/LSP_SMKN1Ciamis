@extends('admin.layout')

@section('title', 'Detail Asesi')
@section('page-title', 'Detail Asesi')

@section('content')
<div class="page-header">
    <h2>Detail Asesi</h2>
    <div class="header-actions">
        <a href="{{ route('admin.asesi.edit', $asesi->NIK) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="detail-section">
            <h3>Informasi Pribadi</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>NIK</label>
                    <div class="detail-value">{{ $asesi->NIK }}</div>
                </div>

                <div class="detail-item">
                    <label>No. Registrasi</label>
                    <div class="detail-value">
                        @if($asesi->no_reg)
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle-fill"></i> {{ $asesi->no_reg }}
                            </span>
                        @else
                            <span class="text-muted">Belum diatur</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item full-width">
                    <label>Nama Lengkap</label>
                    <div class="detail-value">{{ $asesi->nama }}</div>
                </div>

                <div class="detail-item">
                    <label>Email</label>
                    <div class="detail-value">
                        @if($asesi->email)
                            <a href="mailto:{{ $asesi->email }}" class="link">{{ $asesi->email }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>No. Telepon/HP</label>
                    <div class="detail-value">
                        @if($asesi->telepon_hp)
                            <a href="tel:{{ $asesi->telepon_hp }}" class="link">{{ $asesi->telepon_hp }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Tempat Lahir</label>
                    <div class="detail-value">{{ $asesi->tempat_lahir ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Tanggal Lahir</label>
                    <div class="detail-value">
                        @if($asesi->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d F Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Jenis Kelamin</label>
                    <div class="detail-value">
                        @if($asesi->jenis_kelamin)
                            {{ $asesi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Kebangsaan</label>
                    <div class="detail-value">{{ $asesi->kebangsaan ?? '-' }}</div>
                </div>

                <div class="detail-item full-width">
                    <label>Alamat</label>
                    <div class="detail-value">{{ $asesi->alamat ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Kode Pos</label>
                    <div class="detail-value">{{ $asesi->kode_pos ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Telepon Rumah</label>
                    <div class="detail-value">{{ $asesi->telepon_rumah ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Informasi Pendidikan</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Jurusan</label>
                    <div class="detail-value">
                        @if($asesi->jurusan)
                            <div class="jurusan-info">
                                <div class="jurusan-name">{{ $asesi->jurusan->nama_jurusan }}</div>
                                <div class="jurusan-code">Kode: {{ $asesi->jurusan->kode_jurusan }}</div>
                            </div>
                        @else
                            <span class="text-muted">Belum ditentukan</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Kelas</label>
                    <div class="detail-value">{{ $asesi->kelas ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Pendidikan Terakhir</label>
                    <div class="detail-value">{{ $asesi->pendidikan_terakhir ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Pekerjaan</label>
                    <div class="detail-value">{{ $asesi->pekerjaan ?? '-' }}</div>
                </div>
            </div>
        </div>

        @if($asesi->nama_lembaga || $asesi->alamat_lembaga || $asesi->jabatan)
        <div class="detail-section">
            <h3>Informasi Lembaga/Instansi</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Nama Lembaga</label>
                    <div class="detail-value">{{ $asesi->nama_lembaga ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Jabatan</label>
                    <div class="detail-value">{{ $asesi->jabatan ?? '-' }}</div>
                </div>

                <div class="detail-item full-width">
                    <label>Alamat Lembaga</label>
                    <div class="detail-value">{{ $asesi->alamat_lembaga ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Email Lembaga</label>
                    <div class="detail-value">
                        @if($asesi->email_lembaga)
                            <a href="mailto:{{ $asesi->email_lembaga }}" class="link">{{ $asesi->email_lembaga }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>No. Fax Lembaga</label>
                    <div class="detail-value">{{ $asesi->no_fax_lembaga ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Unit Lembaga</label>
                    <div class="detail-value">{{ $asesi->unit_lembaga ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="detail-section">
            <h3>Status & Dokumen</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Status</label>
                    <div class="detail-value">
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Menunggu', 'class' => 'badge-warning'],
                                'approved' => ['label' => 'Dalam Proses', 'class' => 'badge-info'],
                                'rejected' => ['label' => 'Ditolak', 'class' => 'badge-danger'],
                                'completed' => ['label' => 'Selesai', 'class' => 'badge-success'],
                            ];
                            $currentStatus = $statusMap[$asesi->status] ?? ['label' => 'Menunggu', 'class' => 'badge-warning'];
                        @endphp
                        <span class="badge {{ $currentStatus['class'] }}">
                            {{ $currentStatus['label'] }}
                        </span>
                    </div>
                </div>

                @if($asesi->catatan_admin)
                <div class="detail-item full-width">
                    <label>Catatan Admin</label>
                    <div class="detail-value">
                        <div class="note-box">
                            {{ $asesi->catatan_admin }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="documents-grid">
                <div class="document-item">
                    <div class="document-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="document-info">
                        <div class="document-label">Pas Foto</div>
                        <div class="document-status">
                            @if($asesi->pas_foto)
                                <a href="{{ asset('storage/' . $asesi->pas_foto) }}" target="_blank" class="btn-view-doc">
                                    <i class="bi bi-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="document-item">
                    <div class="document-icon">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <div class="document-info">
                        <div class="document-label">Identitas Pribadi</div>
                        <div class="document-status">
                            @if($asesi->identitas_pribadi)
                                <a href="{{ asset('storage/' . $asesi->identitas_pribadi) }}" target="_blank" class="btn-view-doc">
                                    <i class="bi bi-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="document-item">
                    <div class="document-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div class="document-info">
                        <div class="document-label">Bukti Kompetensi</div>
                        <div class="document-status">
                            @if($asesi->bukti_kompetensi)
                                <a href="{{ asset('storage/' . $asesi->bukti_kompetensi) }}" target="_blank" class="btn-view-doc">
                                    <i class="bi bi-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="document-item">
                    <div class="document-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="document-info">
                        <div class="document-label">Transkrip Nilai</div>
                        <div class="document-status">
                            @if($asesi->transkrip_nilai)
                                <a href="{{ asset('storage/' . $asesi->transkrip_nilai) }}" target="_blank" class="btn-view-doc">
                                    <i class="bi bi-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($asesi->skemas && $asesi->skemas->count() > 0)
        <div class="detail-section">
            <h3>Skema Sertifikasi</h3>
            
            <div class="skema-list">
                @foreach($asesi->skemas as $skema)
                <div class="skema-card">
                    <div class="skema-header">
                        <div class="skema-title">{{ $skema->nama_skema }}</div>
                        <span class="badge badge-info">{{ $skema->pivot->status ?? 'Belum dimulai' }}</span>
                    </div>
                    <div class="skema-number">{{ $skema->nomor_skema }}</div>
                    @if($skema->pivot->rekomendasi)
                    <div class="skema-rekomendasi">
                        Rekomendasi: <strong>{{ ucfirst($skema->pivot->rekomendasi) }}</strong>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="detail-section">
            <h3>Informasi Sistem</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Kode Kementrian</label>
                    <div class="detail-value">{{ $asesi->kode_kementrian ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Kode Anggaran</label>
                    <div class="detail-value">{{ $asesi->kode_anggaran ?? '-' }}</div>
                </div>

                <div class="detail-item">
                    <label>Dibuat pada</label>
                    <div class="detail-value">
                        @if($asesi->created_at)
                            {{ $asesi->created_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir diupdate</label>
                    <div class="detail-value">
                        @if($asesi->updated_at)
                            {{ $asesi->updated_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.asesi.edit', $asesi->NIK) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Data
            </a>
            <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
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
        padding-bottom: 30px;
        border-bottom: 2px solid #f1f5f9;
    }

    .detail-section:last-of-type {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
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

    .link {
        color: #0073bd;
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }

    .jurusan-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .jurusan-name {
        font-size: 15px;
        color: #0F172A;
        font-weight: 600;
    }

    .jurusan-code {
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

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .note-box {
        background: #fef3c7;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 12px 16px;
        color: #78350f;
        font-size: 14px;
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .document-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .document-icon {
        width: 40px;
        height: 40px;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .document-info {
        flex: 1;
    }

    .document-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .document-status {
        font-size: 13px;
    }

    .btn-view-doc {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #0073bd;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    .btn-view-doc:hover {
        text-decoration: underline;
    }

    .skema-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .skema-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
    }

    .skema-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 6px;
    }

    .skema-title {
        font-size: 15px;
        font-weight: 600;
        color: #0F172A;
    }

    .skema-number {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .skema-rekomendasi {
        font-size: 13px;
        color: #475569;
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

        .detail-item.full-width {
            grid-column: 1;
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

        .documents-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
