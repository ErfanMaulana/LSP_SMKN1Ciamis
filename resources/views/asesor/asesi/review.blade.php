@extends('asesor.layout')

@section('title', 'Detail Asesi - ' . $asesi->nama)
@section('page-title', 'Detail Asesi')

@section('styles')
<style>
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: #2563eb; text-decoration: none; font-size: 14px;
        font-weight: 500; margin-bottom: 18px;
    }
    .back-btn:hover { color: #1d4ed8; }

    .header-profile {
        background: linear-gradient(135deg, #0073bd 0%, #005fa3 100%);
        border-radius: 14px; padding: 28px; color: white; margin-bottom: 24px;
        display: flex; gap: 24px; align-items: flex-start;
    }
    .profile-photo {
        width: 120px; height: 120px;
        border-radius: 10px; background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .profile-photo img { width: 100%; height: 100%; object-fit: cover; }
    .profile-info { flex: 1; }
    .profile-info h2 { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
    .profile-info .meta { font-size: 13px; opacity: 0.9; margin-bottom: 6px; }

    .info-section {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; margin-bottom: 20px; overflow: hidden;
    }
    .section-header {
        background: #f8fafc; padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 10px;
    }
    .section-header h3 { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0; }

    .info-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 0;
    }
    .info-item {
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
    }
    .info-item:nth-child(2n) { border-right: none; }
    .info-item:nth-last-child(-n+2) { border-bottom: none; }
    .info-label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .info-value { font-size: 13px; color: #334155; font-weight: 500; }

    .info-full-row {
        padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
        display: grid; grid-template-columns: 200px 1fr;
        gap: 16px; align-items: start;
    }
    .info-full-row:last-child { border-bottom: none; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
    }
    .status-selesai { background: #d1fae5; color: #059669; }
    .status-sedang { background: #fef3c7; color: #b45309; }
    .status-belum { background: #fee2e2; color: #dc2626; }

    .result-table {
        width: 100%; border-collapse: collapse; font-size: 13px;
    }
    .result-table th {
        background: #eff6ff; padding: 10px 14px;
        border-bottom: 1px solid #bfdbfe;
        text-align: left; font-weight: 700; color: #1e3a8a;
    }
    .result-table td {
        padding: 10px 14px; border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .result-table tr:last-child td { border-bottom: none; }
    .result-table .badge-k { background: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 4px; font-weight: 600; }
    .result-table .badge-bk { background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-weight: 600; }

    .file-list {
        padding: 14px 20px;
    }
    .file-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px; background: #f8fafc; border-radius: 8px;
        border: 1px solid #e2e8f0; margin-bottom: 10px;
    }
    .file-item:last-child { margin-bottom: 0; }
    .file-icon { font-size: 20px; color: #3b82f6; }
    .file-name { flex: 1; color: #475569; font-size: 13px; font-weight: 500; }
    .file-link {
        padding: 6px 12px; background: #2563eb; color: white;
        border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;
        transition: background 0.2s;
    }
    .file-link:hover { background: #1d4ed8; }

    .action-row {
        display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;
    }

    @media (max-width: 768px) {
        .header-profile {
            padding: 16px; flex-direction: column;
            align-items: center; text-align: center;
        }
        .profile-photo { width: 100px; height: 100px; }
        .info-full-row { grid-template-columns: 1fr; gap: 4px; }
        .info-label { font-size: 11px; }
    }

    @media print {
        .back-btn, aside, .topbar, .action-row { display: none !important; }
        .main-content { margin-left: 0 !important; }
    }
</style>
@endsection

@section('content')


<a href="{{ route('asesor.asesi.terkait') }}" class="back-btn">
    <i class="fas fa-chevron-left"></i> Kembali ke Daftar Asesi
</a>

<!-- Header Profile -->
<div class="header-profile">
    <div class="profile-photo">
        @if($asesi->pas_foto && \Storage::exists($asesi->pas_foto))
            <img src="{{ \Storage::url($asesi->pas_foto) }}" alt="{{ $asesi->nama }}">
        @else
            <i class="fas fa-user" style="font-size: 48px; opacity: 0.6;"></i>
        @endif
    </div>
    <div class="profile-info">
        <h2>{{ $asesi->nama }}</h2>
        <div class="meta">NIK: {{ $asesi->NIK }}</div>
        <div class="meta">No. Registrasi: {{ $asesi->no_registrasi ?? '-' }}</div>
        <div class="meta">Jurusan: {{ $asesi->jurusan->nama_jurusan ?? '-' }}</div>
        <div class="meta">Skema: {{ $skema->nama_skema ?? '-' }} ({{ $skema->tingkat ?? '-' }})</div>
    </div>
</div>

<!-- Informasi Pribadi -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-id-card" style="color: #2563eb;"></i> Informasi Pribadi</h3>
    </div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nama Lengkap</div>
            <div class="info-value">{{ $asesi->nama ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">NIK</div>
            <div class="info-value">{{ $asesi->NIK ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tempat Lahir</div>
            <div class="info-value">{{ $asesi->tempat_lahir ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tanggal Lahir</div>
            <div class="info-value">{{ $asesi->tanggal_lahir ? date('d-m-Y', strtotime($asesi->tanggal_lahir)) : '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Jenis Kelamin</div>
            <div class="info-value">{{ $asesi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Kebangsaan</div>
            <div class="info-value">{{ $asesi->kebangsaan ?? 'Indonesia' }}</div>
        </div>
    </div>
</div>

<!-- Alamat & Kontak -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-map-marker-alt" style="color: #2563eb;"></i> Alamat & Kontak</h3>
    </div>
    <div class="info-full-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $asesi->email ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Telepon Rumah</span>
        <span class="info-value">{{ $asesi->telepon_rumah ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Telepon Genggam</span>
        <span class="info-value">{{ $asesi->telepon_hp ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Alamat</span>
        <span class="info-value">{{ $asesi->alamat ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Kode Pos</span>
        <span class="info-value">{{ $asesi->kode_pos ?? '-' }}</span>
    </div>
</div>

<!-- Pendidikan & Pekerjaan -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-book" style="color: #2563eb;"></i> Pendidikan & Pekerjaan</h3>
    </div>
    <div class="info-full-row">
        <span class="info-label">Pendidikan Terakhir</span>
        <span class="info-value">{{ $asesi->pendidikan_terakhir ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Pekerjaan</span>
        <span class="info-value">{{ $asesi->pekerjaan ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Nama Lembaga</span>
        <span class="info-value">{{ $asesi->nama_lembaga ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Jabatan</span>
        <span class="info-value">{{ $asesi->jabatan ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Unit Lembaga</span>
        <span class="info-value">{{ $asesi->unit_lembaga ?? '-' }}</span>
    </div>
</div>

<!-- Status Asesmen -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-check-circle" style="color: #2563eb;"></i> Status Asesmen</h3>
    </div>
    <div class="info-full-row">
        <span class="info-label">Skema</span>
        <span class="info-value">{{ $skema->nama_skema ?? '-' }}</span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Status</span>
        <span class="info-value">
            <span class="status-badge status-{{ str_replace('_', '', strtolower($pivot->status)) }}">
                @if($pivot->status == 'selesai')
                    <i class="fas fa-check-circle"></i> Selesai
                @elseif($pivot->status == 'sedang_mengerjakan')
                    <i class="fas fa-spinner"></i> Sedang Mengerjakan
                @else
                    <i class="fas fa-circle"></i> Belum Mulai
                @endif
            </span>
        </span>
    </div>
    <div class="info-full-row">
        <span class="info-label">Periode</span>
        <span class="info-value">
            {{ $pivot->tanggal_mulai ? date('d-m-Y', strtotime($pivot->tanggal_mulai)) : '-' }}
            {{ $pivot->tanggal_selesai ? ' s/d ' . date('d-m-Y', strtotime($pivot->tanggal_selesai)) : '' }}
        </span>
    </div>
</div>

<!-- Hasil Asesmen Kompetensi -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-chart-bar" style="color: #2563eb;"></i> Hasil Asesmen Kompetensi</h3>
    </div>
    <div style="padding: 16px 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
        <div style="background: #eff6ff; padding: 14px; border-radius: 8px; text-align: center;">
            <div style="font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 4px;">UNIT</div>
            <div style="font-size: 20px; font-weight: 700; color: #1e3a8a;">{{ $skema->units->count() ?? 0 }}</div>
        </div>
        <div style="background: #f0fdf4; padding: 14px; border-radius: 8px; text-align: center;">
            <div style="font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 4px;">ELEMEN</div>
            <div style="font-size: 20px; font-weight: 700; color: #15803d;">{{ $skema->units->flatMap(fn($u) => $u->elemens)->count() ?? 0 }}</div>
        </div>
        <div style="background: #f0fdf4; padding: 14px; border-radius: 8px; text-align: center;">
            <div style="font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 4px;">KOMPETEN</div>
            <div style="font-size: 20px; font-weight: 700; color: #15803d;">{{ $answers->where('status', 'kompeten')->count() ?? 0 }}</div>
        </div>
        <div style="background: #fef2f2; padding: 14px; border-radius: 8px; text-align: center;">
            <div style="font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 4px;">BELUM KOMPETEN</div>
            <div style="font-size: 20px; font-weight: 700; color: #dc2626;">{{ $answers->where('status', 'belum_kompeten')->count() ?? 0 }}</div>
        </div>
    </div>
    @if($answers && $answers->count() > 0)
    <div style="overflow-x: auto;">
        <table class="result-table">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Elemen</th>
                    <th>Status</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($skema->units ?? [] as $unit)
                    @foreach($unit->elemens ?? [] as $elemen)
                        @php
                            $answer = $answers->where('elemen_id', $elemen->id)->first();
                            $status = $answer?->status ?? 'belum_penilaian';
                        @endphp
                        <tr>
                            <td>{{ $unit->nama_unit }}</td>
                            <td>{{ $elemen->nama_elemen }}</td>
                            <td>
                                @if($status == 'kompeten')
                                    <span class="badge-k">Kompeten</span>
                                @elseif($status == 'belum_kompeten')
                                    <span class="badge-bk">Belum Kompeten</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px;">Belum Dinilai</span>
                                @endif
                            </td>
                            <td>{{ $answer ? ($status == 'kompeten' ? '100%' : '0%') : '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding: 14px 20px; text-align: center; color: #94a3b8; font-style: italic;">
        Tidak ada data penilaian kompetensi
    </div>
    @endif
</div>

<!-- Bukti Pendukung & Dokumen -->
<div class="info-section">
    <div class="section-header">
        <h3><i class="fas fa-file-alt" style="color: #2563eb;"></i> Bukti Pendukung & Dokumen</h3>
    </div>
    <div class="file-list">
        @php
            $allFiles = [];
            if($transkripNilai && $transkripNilai->count() > 0) {
                foreach($transkripNilai as $file) {
                    $allFiles[] = ['name' => 'Transkrip Nilai', 'file' => $file];
                }
            }
            if($identitasPribadi && $identitasPribadi->count() > 0) {
                foreach($identitasPribadi as $file) {
                    $allFiles[] = ['name' => 'Identitas Pribadi', 'file' => $file];
                }
            }
            if($buktiKompetensi && $buktiKompetensi->count() > 0) {
                foreach($buktiKompetensi as $file) {
                    $allFiles[] = ['name' => 'Bukti Kompetensi', 'file' => $file];
                }
            }
            if($buktiPendukung && $buktiPendukung->count() > 0) {
                foreach($buktiPendukung as $file) {
                    $allFiles[] = ['name' => $file->nama_dokumen ?? 'Dokumen', 'file' => $file];
                }
            }
        @endphp
        @if(count($allFiles) > 0)
            @foreach($allFiles as $item)
                <div class="file-item">
                    <i class="fas fa-file-pdf file-icon"></i>
                    <span class="file-name">{{ $item['name'] }}</span>
                    @if($item['file']->file_path && \Storage::exists($item['file']->file_path))
                        <a href="{{ \Storage::url($item['file']->file_path) }}" target="_blank" class="file-link">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    @else
                        <span style="padding: 6px 12px; color: #94a3b8; font-size: 12px;">Tidak tersedia</span>
                    @endif
                </div>
            @endforeach
        @else
            <div style="padding: 14px; text-align: center; color: #94a3b8; font-style: italic;">
                Tidak ada dokumen pendukung
            </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="action-row">
    <a href="{{ route('asesor.asesi.terkait') }}" class="btn btn-secondary" style="padding: 10px 20px; border-radius: 8px; border: 1.5px solid #e2e8f0; background: white; color: #475569; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="javascript:window.print();" class="btn btn-primary" style="padding: 10px 20px; border-radius: 8px; border: none; background: #2563eb; color: white; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <i class="fas fa-print"></i> Cetak
    </a>
</div>
@endsection

@section('scripts')
<script>
    // Simple script for any future interactivity

<script>
    // Print functionality - handled by CSS @media print
</script>

