@extends('admin.layout')

@section('title', 'Edit Asesi - ' . $asesi->nama)
@section('page-title', 'Edit Asesi')

@section('content')
<div class="page-header">
    <h2>Edit Data Asesi</h2>
    <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.asesi.update', $asesi->NIK) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ═══════════════════════════════════════════
                 1. INFORMASI DASAR
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-person-badge"></i> Informasi Dasar</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="NIK">NIK <span class="required">*</span></label>
                        <input type="text" id="NIK" name="NIK"
                               class="form-control @error('NIK') is-invalid @enderror"
                               value="{{ old('NIK', $asesi->NIK) }}" required maxlength="16" minlength="16" pattern="\d{16}" inputmode="numeric" placeholder="16 digit NIK">
                        @error('NIK')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $asesi->nama) }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $asesi->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="ID_jurusan">Jurusan <span class="required">*</span></label>
                        <select id="ID_jurusan" name="ID_jurusan"
                                class="form-control @error('ID_jurusan') is-invalid @enderror" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $item)
                                <option value="{{ $item->ID_jurusan }}"
                                    {{ old('ID_jurusan', $asesi->ID_jurusan) == $item->ID_jurusan ? 'selected' : '' }}>
                                    {{ $item->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('ID_jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror">
                            <option value="">Pilih Jurusan dulu</option>
                        </select>
                        @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="required">*</span></label>
                        <div class="radio-group" id="jenis-kelamin-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin_radio" value="Laki-laki" {{ old('jenis_kelamin', $asesi->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }}>
                                <span>Laki-laki</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin_radio" value="Perempuan" {{ old('jenis_kelamin', $asesi->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}>
                                <span>Perempuan</span>
                            </label>
                        </div>
                        <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="{{ old('jenis_kelamin', $asesi->jenis_kelamin) }}" required>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kebangsaan">Kebangsaan <span class="required">*</span></label>
                        <input type="text" id="kebangsaan" name="kebangsaan" class="form-control @error('kebangsaan') is-invalid @enderror" value="{{ old('kebangsaan', $asesi->kebangsaan) }}" required>
                        @error('kebangsaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 2. TEMPAT & TANGGAL LAHIR
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-calendar-heart"></i> Tempat & Tanggal Lahir</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                               value="{{ old('tempat_lahir', $asesi->tempat_lahir) }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                               value="{{ old('tanggal_lahir', $asesi->tanggal_lahir ? $asesi->tanggal_lahir->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 3. ALAMAT & KONTAK
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-geo-alt"></i> Alamat & Kontak</h3>

                <div class="form-group" style="margin-bottom:20px;">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat', $asesi->alamat) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_kota">Kode Kota</label>
                        <input type="text" id="kode_kota" name="kode_kota" class="form-control"
                               value="{{ old('kode_kota', $asesi->kode_kota) }}">
                    </div>
                    <div class="form-group">
                        <label for="kode_provinsi">Kode Provinsi</label>
                        <input type="text" id="kode_provinsi" name="kode_provinsi" class="form-control"
                               value="{{ old('kode_provinsi', $asesi->kode_provinsi) }}">
                    </div>
                    <div class="form-group">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" class="form-control"
                               value="{{ old('kode_pos', $asesi->kode_pos) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telepon_rumah">Telepon Rumah</label>
                        <input type="text" id="telepon_rumah" name="telepon_rumah" class="form-control"
                               value="{{ old('telepon_rumah', $asesi->telepon_rumah) }}">
                    </div>
                    <div class="form-group">
                        <label for="telepon_hp">Telepon HP</label>
                        <input type="text" id="telepon_hp" name="telepon_hp" class="form-control"
                               value="{{ old('telepon_hp', $asesi->telepon_hp) }}">
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 4. PEKERJAAN & LEMBAGA
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-building"></i> Pekerjaan & Lembaga</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control"
                               value="{{ old('pendidikan_terakhir', $asesi->pendidikan_terakhir) }}"
                               placeholder="Contoh: SMK, D3, S1">
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control"
                               value="{{ old('pekerjaan', $asesi->pekerjaan) }}">
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" class="form-control"
                               value="{{ old('jabatan', $asesi->jabatan) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_lembaga">Nama Lembaga / Perusahaan</label>
                        <input type="text" id="nama_lembaga" name="nama_lembaga" class="form-control"
                               value="{{ old('nama_lembaga', $asesi->nama_lembaga) }}">
                    </div>
                    <div class="form-group">
                        <label for="unit_lembaga">Unit / Divisi Lembaga</label>
                        <input type="text" id="unit_lembaga" name="unit_lembaga" class="form-control"
                               value="{{ old('unit_lembaga', $asesi->unit_lembaga) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label for="alamat_lembaga">Alamat Lembaga</label>
                    <textarea id="alamat_lembaga" name="alamat_lembaga" class="form-control" rows="2">{{ old('alamat_lembaga', $asesi->alamat_lembaga) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="no_fax_lembaga">No. Fax Lembaga</label>
                        <input type="text" id="no_fax_lembaga" name="no_fax_lembaga" class="form-control"
                               value="{{ old('no_fax_lembaga', $asesi->no_fax_lembaga) }}">
                    </div>
                    <div class="form-group">
                        <label for="email_lembaga">Email Lembaga</label>
                        <input type="email" id="email_lembaga" name="email_lembaga"
                               class="form-control @error('email_lembaga') is-invalid @enderror"
                               value="{{ old('email_lembaga', $asesi->email_lembaga) }}">
                        @error('email_lembaga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 6. SKEMA SERTIFIKASI
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-patch-check"></i> Skema Sertifikasi</h3>
                <p class="section-hint">
                    <i class="bi bi-info-circle"></i>
                    Centang skema yang diikuti asesi. Skema yang sudah dikerjakan (sedang berjalan/selesai) tidak akan kehilangan datanya jika tetap dicentang. Hapus centang hanya jika ingin membatalkan pendaftaran skema tersebut.
                </p>

                @php
                    $assignedSkemaIds = $asesi->skemas->pluck('id')->toArray();
                    $assignedSkemas   = $asesi->skemas->keyBy('id');
                @endphp

                @if($skemas->isEmpty())
                    <p style="color:#94a3b8;font-size:14px;">Belum ada skema tersedia.</p>
                @else
                <div id="skemaEmptyJurusan" style="display:none;color:#94a3b8;font-size:14px;padding:12px 0;">
                    <i class="bi bi-arrow-up-circle"></i> Pilih jurusan terlebih dahulu untuk melihat daftar skema.
                </div>
                <div id="skemaEmptyState" style="display:none;color:#94a3b8;font-size:14px;padding:12px 0;">
                    <i class="bi bi-inbox"></i> Tidak ada skema untuk jurusan ini.
                </div>
                <div class="skema-grid" id="skemaGrid">
                    @foreach($skemas as $skema)
                    @php
                        $isAssigned = in_array($skema->id, $assignedSkemaIds);
                        $pivot = $isAssigned ? $assignedSkemas[$skema->id]->pivot : null;
                        $pivotStatus = $pivot ? $pivot->status : null;
                        $oldSkemaIds = old('skema_ids', $assignedSkemaIds);
                        $isChecked = in_array($skema->id, (array) $oldSkemaIds);
                    @endphp
                    <label class="skema-card {{ $isAssigned ? 'assigned' : '' }}"
                           for="skema_{{ $skema->id }}"
                           data-jurusan="{{ $skema->jurusan_id }}">
                        <div class="skema-card-top">
                            <input type="checkbox"
                                   id="skema_{{ $skema->id }}"
                                   name="skema_ids[]"
                                   value="{{ $skema->id }}"
                                   {{ $isChecked ? 'checked' : '' }}>
                            <div class="skema-card-name">{{ $skema->nama_skema }}</div>
                        </div>
                        <div class="skema-card-meta">
                            <span class="skema-code">{{ $skema->nomor_skema }}</span>
                            @if($skema->jenis_skema)
                                <span class="skema-type">{{ $skema->jenis_skema }}</span>
                            @endif
                            @if($skema->jurusan)
                                <span class="skema-jurusan"><i class="bi bi-tag"></i> {{ $skema->jurusan->nama_jurusan }}</span>
                            @endif
                        </div>
                        @if($isAssigned && $pivot)
                        <div class="skema-status-badge status-{{ $pivotStatus }}">
                            @if($pivotStatus === 'belum_mulai')
                                <i class="bi bi-clock"></i> Belum Mulai
                            @elseif($pivotStatus === 'sedang_mengerjakan')
                                <i class="bi bi-pencil-square"></i> Sedang Dikerjakan
                            @elseif($pivotStatus === 'selesai')
                                <i class="bi bi-check-circle-fill"></i> Selesai
                                @if($pivot->rekomendasi === 'lanjut')
                                    &nbsp;· <span style="color:#15803d;">Direkomendasikan</span>
                                @elseif($pivot->rekomendasi === 'tidak_lanjut')
                                    &nbsp;· <span style="color:#be123c;">Tidak Lanjut</span>
                                @endif
                            @endif
                        </div>
                        @endif
                    </label>
                    @endforeach
                </div>
                @endif

                @error('skema_ids')
                    <div class="invalid-feedback" style="display:block;margin-top:8px;">{{ $message }}</div>
                @enderror
                @error('skema_ids.*')
                    <div class="invalid-feedback" style="display:block;margin-top:8px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- ═══════════════════════════════════════════
                 7. INFORMASI TAMBAHAN
            ════════════════════════════════════════════ --}}
            <div class="form-section">
                <h3><i class="bi bi-info-circle"></i> Informasi Tambahan</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_kementrian">Kode Kementrian</label>
                        <input type="text" id="kode_kementrian" name="kode_kementrian" class="form-control"
                               value="{{ old('kode_kementrian', $asesi->kode_kementrian) }}">
                    </div>
                    <div class="form-group">
                        <label for="kode_anggaran">Kode Anggaran</label>
                        <input type="text" id="kode_anggaran" name="kode_anggaran" class="form-control"
                               value="{{ old('kode_anggaran', $asesi->kode_anggaran) }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
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
    .alert {
        padding: 12px 18px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .alert-danger  { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .card-body { padding: 30px; }
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
    }
    .form-section h3 {
        font-size: 16px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .section-hint {
        font-size: 13px;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-row:last-child { margin-bottom: 0; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
    }
    .required { color: #ef4444; margin-left: 2px; }
    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
        color: #1e293b;
    }
    .form-control:focus {
        outline: none;
        border-color: #0073bd;
        background: white;
        box-shadow: 0 0 0 3px rgba(0,115,189,0.1);
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 4px; }
    textarea.form-control { resize: vertical; min-height: 80px; }
    select.form-control { cursor: pointer; }

    .radio-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .radio-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        font-size: 13px;
        color: #334155;
        cursor: pointer;
    }

    .radio-label input[type="radio"] {
        accent-color: #0073bd;
    }

    /* Skema Grid */
    .skema-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }
    .skema-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .skema-card:hover { border-color: #93c5fd; background: #eff6ff; }
    .skema-card:has(input:checked) {
        border-color: #0073bd;
        background: #eff6ff;
    }
    .skema-card.assigned:has(input:checked) {
        border-color: #0073bd;
    }
    .skema-card-top {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .skema-card-top input[type="checkbox"] {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        accent-color: #0073bd;
        margin-top: 2px;
    }
    .skema-card-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
    }
    .skema-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding-left: 28px;
    }
    .skema-code {
        font-size: 11px;
        font-family: monospace;
        background: #e2e8f0;
        color: #475569;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .skema-type {
        font-size: 11px;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .skema-jurusan {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .skema-status-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 28px;
        width: fit-content;
    }
    .skema-status-badge.status-belum_mulai  { background: #f1f5f9; color: #64748b; }
    .skema-status-badge.status-sedang_mengerjakan { background: #fef3c7; color: #92400e; }
    .skema-status-badge.status-selesai { background: #dcfce7; color: #166534; }

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
        transition: all 0.2s;
    }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #005f99; transform: translateY(-1px); }
    .btn-secondary { background: #64748b; color: white; }
    .btn-secondary:hover { background: #475569; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .card-body { padding: 20px; }
        .skema-grid { grid-template-columns: 1fr; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jurusanSelect = document.getElementById('ID_jurusan');
    const kelasSelect   = document.getElementById('kelas');
    const skemaGrid     = document.getElementById('skemaGrid');
    const emptyState    = document.getElementById('skemaEmptyState');
    const emptyJurusan  = document.getElementById('skemaEmptyJurusan');
    const savedKelas    = @json(old('kelas', $asesi->kelas));
    const jurusanKelasMap = @json(
        $jurusan->mapWithKeys(function ($j) {
            return [
                (string) $j->ID_jurusan => $j->kelasItems->pluck('nama_kelas')->values(),
            ];
        })
    );

    // Handle jenis_kelamin radio buttons
    const jeniKelaminRadios = document.querySelectorAll('input[name="jenis_kelamin_radio"]');
    const jeniKelaminHidden = document.getElementById('jenis_kelamin');
    
    jeniKelaminRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            jeniKelaminHidden.value = this.value;
        });
    });

    if (!jurusanSelect || !skemaGrid) return;

    function syncKelasByJurusan() {
        if (!kelasSelect) return;

        const selectedJurusan = jurusanSelect.value || '';
        const kelasList = jurusanKelasMap[selectedJurusan] || [];

        kelasSelect.innerHTML = '';

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = selectedJurusan
            ? (kelasList.length ? 'Pilih Kelas' : 'Belum ada kelas pada jurusan ini')
            : 'Pilih Jurusan dulu';
        kelasSelect.appendChild(defaultOpt);

        kelasList.forEach(function (kelasNama) {
            const opt = document.createElement('option');
            opt.value = kelasNama;
            opt.textContent = kelasNama;
            if (savedKelas && savedKelas === kelasNama) opt.selected = true;
            kelasSelect.appendChild(opt);
        });
    }

    function filterSkemas() {
        const selectedJurusan = jurusanSelect.value;
        const cards = skemaGrid.querySelectorAll('.skema-card');

        if (!selectedJurusan) {
            // No jurusan selected — hide everything
            cards.forEach(card => {
                card.style.display = 'none';
                // uncheck hidden cards so they don't get submitted
                card.querySelector('input[type="checkbox"]').checked = false;
            });
            skemaGrid.style.display = 'none';
            if (emptyState)   emptyState.style.display = 'none';
            if (emptyJurusan) emptyJurusan.style.display = 'block';
            return;
        }

        if (emptyJurusan) emptyJurusan.style.display = 'none';

        let visibleCount = 0;
        cards.forEach(card => {
            const cardJurusan = card.getAttribute('data-jurusan');
            const matches = (cardJurusan == selectedJurusan);
            card.style.display = matches ? '' : 'none';
            if (!matches) {
                // Uncheck cards that are now hidden
                card.querySelector('input[type="checkbox"]').checked = false;
            }
            if (matches) visibleCount++;
        });

        skemaGrid.style.display = visibleCount > 0 ? '' : 'none';
        if (emptyState) emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    jurusanSelect.addEventListener('change', function () {
        filterSkemas();
        syncKelasByJurusan();
    });

    // Run on load to reflect current jurusan value
    filterSkemas();
    syncKelasByJurusan();
});
</script>
@endsection