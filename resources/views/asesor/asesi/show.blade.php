@extends('asesor.layout')

@section('title', 'Detail Asesi - ' . $asesi->nama)
@section('page-title', 'Detail Asesi')

@section('content')
<div class="page-header">
    <div>
        <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin: 0;">Detail Asesi</h2>
        <p style="font-size: 13.5px; color: #64748b; margin: 4px 0 0;">Informasi data diri dan perkembangan asesmen kompetensi asesi</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('asesor.asesi.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Asesi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        {{-- Section 1: Informasi Pribadi --}}
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
                    <div class="detail-value" style="font-size: 16px; font-weight: 700; color: #0f172a;">{{ $asesi->nama }}</div>
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
                            {{ \Carbon\Carbon::parse($asesi->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}
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

        {{-- Section 2: Informasi Pendidikan --}}
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
        {{-- Section 3: Informasi Lembaga --}}
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

        {{-- Section 4: Status & Dokumen --}}
        <div class="detail-section">
            <h3>Status & Dokumen Asesi</h3>
            
            <div class="detail-grid" style="margin-bottom: 20px;">
                <div class="detail-item">
                    <label>Status Verifikasi</label>
                    <div class="detail-value">
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'badge-warning'],
                                'approved' => ['label' => 'Terverifikasi (Approved)', 'class' => 'badge-success'],
                                'rejected' => ['label' => 'Ditolak (Rejected)', 'class' => 'badge-danger'],
                                'completed' => ['label' => 'Selesai', 'class' => 'badge-success'],
                            ];
                            $currentStatus = $statusMap[$asesi->status] ?? ['label' => 'Menunggu', 'class' => 'badge-warning'];
                        @endphp
                        <span class="badge {{ $currentStatus['class'] }}">
                            {{ $currentStatus['label'] }}
                        </span>
                    </div>
                </div>
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

        {{-- Section 5: Status & Perkembangan Asesmen Kompetensi --}}
        @if(isset($hasilUjikom) && $hasilUjikom->count())
            <div class="detail-section">
                <h3>Perkembangan Asesmen Kompetensi</h3>
                
                @foreach($hasilUjikom as $row)
                    <div class="skema-section" style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 20px; background: #fff;">
                        <div class="skema-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h4 class="skema-title" style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0;">{{ $row->nama_skema }}</h4>
                                <span class="skema-code" style="font-size: 13px; color: #64748b; font-weight: 500;">{{ $row->nomor_skema }}</span>
                            </div>
                            <span class="overall-badge {{ $row->all_completed ? 'completed' : 'progressing' }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 600; background: {{ $row->all_completed ? '#d1fae5' : '#e0f2fe' }}; color: {{ $row->all_completed ? '#065f46' : '#0369a1' }};">
                                <i class="bi {{ $row->all_completed ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                                {{ $row->all_completed ? 'Tahapan Selesai' : 'Sedang Berlangsung' }}
                            </span>
                        </div>

                        {{-- 1. Final Result Banner if completed --}}
                        @if($row->all_completed)
                            @if($row->rekomendasi === 'kompeten')
                                <div class="result-banner kompeten" style="display: flex; gap: 16px; padding: 18px; border-radius: 8px; margin-bottom: 20px; align-items: flex-start; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                                    <div class="result-icon" style="font-size: 24px; flex-shrink: 0; line-height: 1;"><i class="bi bi-patch-check-fill"></i></div>
                                    <div class="result-info">
                                        <h4 class="result-status-title" style="font-size: 15px; font-weight: 700; margin: 0 0 4px; letter-spacing: 0.5px;">KOMPETEN</h4>
                                        <p class="result-status-desc" style="font-size: 13px; margin: 0 0 10px; line-height: 1.4; opacity: 0.9;">
                                            Berdasarkan evaluasi akhir dan bukti observasi langsung, tim asesor menyatakan bahwa kompetensi asesi pada skema sertifikasi ini memenuhi standar kompetensi kerja nasional.
                                        </p>
                                        <div class="result-meta" style="display: flex; gap: 16px; flex-wrap: wrap; font-size: 12px;">
                                            <div>Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong></div>
                                            <div>Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="result-banner belum-kompeten" style="display: flex; gap: 16px; padding: 18px; border-radius: 8px; margin-bottom: 20px; align-items: flex-start; background: #fffbeb; border: 1px solid #fde68a; color: #92400e;">
                                    <div class="result-icon" style="font-size: 24px; flex-shrink: 0; line-height: 1;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                    <div class="result-info">
                                        <h4 class="result-status-title" style="font-size: 15px; font-weight: 700; margin: 0 0 4px; letter-spacing: 0.5px;">BELUM KOMPETEN</h4>
                                        <p class="result-status-desc" style="font-size: 13px; margin: 0 0 10px; line-height: 1.4; opacity: 0.9;">
                                            Berdasarkan penilaian observasi langsung, terdapat kriteria unjuk kerja yang masih memerlukan pengembangan lebih lanjut untuk mencapai kualifikasi kompetensi penuh.
                                        </p>
                                        <div class="result-meta" style="display: flex; gap: 16px; flex-wrap: wrap; font-size: 12px;">
                                            <div>Asesor Penilai: <strong>{{ $row->asesor_nama ?? '-' }}</strong></div>
                                            <div>Tanggal Keputusan: <strong>{{ $row->tanggal_ceklis ?? '-' }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- Show progress bar based on steps completed --}}
                            @php
                                $completedSteps = collect($row->steps)->where('status', 'completed')->count();
                                $totalSteps = count($row->steps);
                                $progressPercentage = ($completedSteps / $totalSteps) * 100;
                            @endphp
                            <div class="progress-bar-container" style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 9999px; margin-bottom: 20px; overflow: hidden;">
                                <div class="progress-bar-fill" style="height: 100%; background: #0073bd; border-radius: 9999px; width: {{ $progressPercentage }}%; transition: width 0.5s;"></div>
                            </div>
                        @endif

                        {{-- 2. Step Progression Timeline --}}
                        <h4 style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Tahapan Sertifikasi
                        </h4>
                        
                        <div class="timeline-container" style="position: relative; display: flex; flex-direction: column; gap: 20px; padding-left: 20px;">
                            <div class="timeline-line" style="position: absolute; left: 9px; top: 15px; bottom: 15px; width: 2px; background: #e2e8f0; z-index: 1;"></div>
                            
                            @foreach($row->steps as $index => $step)
                                <div class="timeline-step {{ $step['status'] === 'completed' ? 'completed' : 'pending' }}" style="position: relative; display: flex; gap: 16px; z-index: 2;">
                                    <div class="step-marker" style="width: 20px; height: 20px; border-radius: 50%; background: {{ $step['status'] === 'completed' ? '#10b981' : '#ffffff' }}; border: 2px solid {{ $step['status'] === 'completed' ? '#10b981' : '#cbd5e1' }}; color: {{ $step['status'] === 'completed' ? '#ffffff' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; flex-shrink: 0; box-shadow: 0 0 0 4px #ffffff; transition: all 0.3s;">
                                        @if($step['status'] === 'completed')
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="step-content" style="flex: 1; display: flex; justify-content: space-between; align-items: flex-start; background: {{ $step['status'] === 'completed' ? '#ffffff' : '#f8fafc' }}; border: 1px solid {{ $step['status'] === 'completed' ? '#cbd5e1' : '#f1f5f9' }}; border-radius: 8px; padding: 12px 16px; transition: all 0.3s; flex-wrap: wrap; gap: 12px;">
                                        <div class="step-text" style="flex: 1; min-width: 200px;">
                                            <h5 class="step-name" style="font-size: 14px; font-weight: 700; color: {{ $step['status'] === 'completed' ? '#0f172a' : '#334155' }}; margin: 0 0 4px;">{{ $step['name'] }}</h5>
                                            <p class="step-desc" style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.4;">{{ $step['description'] }}</p>
                                            
                                            {{-- Action & Export links for Asesor --}}
                                            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                                                @if($index === 1) {{-- Asesmen Mandiri (FR.APL.02) --}}
                                                    <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $asesi->NIK, 'skemaId' => $row->skema_id]) }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                        <i class="bi bi-eye"></i> Review / Detail FR.APL.02
                                                    </a>
                                                    @if(Route::has('asesor.asesmen-mandiri.export'))
                                                        <a href="{{ route('asesor.asesmen-mandiri.export', ['asesiNik' => $asesi->NIK, 'skemaId' => $row->skema_id]) }}" class="btn-action btn-export" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #10b981; color: #10b981; background: transparent;">
                                                            <i class="bi bi-download"></i> Export FR.APL.02
                                                        </a>
                                                    @endif
                                                @elseif($index === 3) {{-- Persetujuan Asesmen (FR.APL.03) --}}
                                                    @if(Route::has('asesor.persetujuan.front.asesor.show'))
                                                        <a href="{{ route('asesor.persetujuan.front.asesor.show', ['asesiNik' => $asesi->NIK, 'skemaId' => $row->skema_id]) }}?back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                            <i class="bi bi-file-check"></i> Persetujuan Asesmen
                                                        </a>
                                                    @endif
                                                    @if(Route::has('asesor.persetujuan.front.asesor.export'))
                                                        <a href="{{ route('asesor.persetujuan.front.asesor.export', ['asesiNik' => $asesi->NIK, 'skemaId' => $row->skema_id]) }}" class="btn-action btn-export" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #10b981; color: #10b981; background: transparent;">
                                                            <i class="bi bi-download"></i> Export FR.APL.03
                                                        </a>
                                                    @endif
                                                @elseif($index === 4) {{-- Penilaian & Ceklis Observasi (FR.IA.01) --}}
                                                    @if($row->ceklis && !empty($row->ceklis->id))
                                                        @if(Route::has('asesor.ceklis-observasi.show'))
                                                            <a href="{{ route('asesor.ceklis-observasi.show', $row->ceklis->id) }}?back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                                <i class="bi bi-eye"></i> Lihat FR.IA.01
                                                            </a>
                                                        @endif
                                                        @if(Route::has('asesor.ceklis-observasi.export'))
                                                            <a href="{{ route('asesor.ceklis-observasi.export', $row->ceklis->id) }}" class="btn-action btn-export" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #10b981; color: #10b981; background: transparent;">
                                                                <i class="bi bi-download"></i> Export FR.IA.01
                                                            </a>
                                                        @endif
                                                    @elseif(!empty($row->is_persetujuan_selesai))
                                                        @if(Route::has('asesor.ceklis-observasi.create'))
                                                            <a href="{{ route('asesor.ceklis-observasi.create', ['asesi_nik' => $asesi->NIK, 'skema_id' => $row->skema_id]) }}&back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                                <i class="bi bi-pencil-square"></i> Isi FR.IA.01
                                                            </a>
                                                        @endif
                                                    @endif
                                                @elseif($index === 5) {{-- Rekaman Asesmen (FR.AK.02) --}}
                                                    @if($row->rekaman && !empty($row->rekaman->id))
                                                        @if(Route::has('asesor.rekaman-asesmen-kompetensi.show'))
                                                            <a href="{{ route('asesor.rekaman-asesmen-kompetensi.show', $row->rekaman->id) }}?back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                                <i class="bi bi-eye"></i> Lihat FR.AK.02
                                                            </a>
                                                        @endif
                                                        @if(Route::has('asesor.rekaman-asesmen-kompetensi.export'))
                                                            <a href="{{ route('asesor.rekaman-asesmen-kompetensi.export', $row->rekaman->id) }}" class="btn-action btn-export" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #10b981; color: #10b981; background: transparent;">
                                                                <i class="bi bi-download"></i> Export FR.AK.02
                                                            </a>
                                                        @endif
                                                    @elseif(!empty($row->ceklis))
                                                        @if(Route::has('asesor.rekaman-asesmen-kompetensi.create'))
                                                            <a href="{{ route('asesor.rekaman-asesmen-kompetensi.create', ['asesi_nik' => $asesi->NIK, 'skema_id' => $row->skema_id]) }}&back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                                <i class="bi bi-pencil-square"></i> Buat FR.AK.02
                                                            </a>
                                                        @endif
                                                    @endif
                                                @elseif($index === 6) {{-- Nilai Asesor --}}
                                                    @if(Route::has('asesor.entry-penilaian.form'))
                                                        <a href="{{ route('asesor.entry-penilaian.form', $asesi->NIK) }}?back_to=asesi:{{ $asesi->NIK }}" class="btn-action btn-outline-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #0073bd; color: #0073bd; background: transparent;">
                                                            <i class="bi bi-clipboard-check"></i> {{ !empty($row->is_nilai_selesai) ? 'Lihat / Edit Penilaian' : 'Input Penilaian' }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <span class="step-badge {{ $step['status'] === 'completed' ? 'completed' : 'pending' }}" style="font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.5px; background: {{ $step['status'] === 'completed' ? '#e6fcf5' : '#f1f3f5' }}; color: {{ $step['status'] === 'completed' ? '#0ca678' : '#868e96' }}; align-self: flex-start;">
                                            {{ $step['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('asesor.asesi.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Asesi
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
