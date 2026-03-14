@extends('asesi.layout')

@section('title', 'Formulir Pendaftaran')
@section('page-title', 'Formulir Pendaftaran Asesi')

@section('styles')
<style>
    .reg-card {
        background: white; border-radius: 12px; padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08); max-width: 900px;
    }
    .reg-card h3 {
        font-size: 16px; font-weight: 700; color: #0F172A;
        margin-bottom: 6px;
    }
    .reg-card .subtitle {
        font-size: 12px; color: #64748b; margin-bottom: 20px;
    }

    /* Step indicator */
    .step-indicator {
        display: flex; align-items: center; justify-content: center;
        gap: 16px; margin-bottom: 24px;
    }
    .step { display: flex; align-items: center; gap: 8px; }
    .step-number {
        width: 36px; height: 36px; border-radius: 50%;
        background: #14532d; color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }
    .step.inactive .step-number { background: #cbd5e1; color: #64748b; }
    .step-label { font-size: 12px; font-weight: 600; color: #14532d; }
    .step.inactive .step-label { color: #94a3b8; }
    .step-line { width: 50px; height: 2px; background: #cbd5e1; }

    /* Section titles */
    .section-header {
        display: flex; align-items: center; gap: 12px;
        margin: 24px 0 16px; padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    .section-icon {
        width: 36px; height: 36px; border-radius: 50%;
        background: #14532d; color: white;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 16px;
    }
    .section-header h4 { font-size: 14px; font-weight: 700; color: #0F172A; margin: 0; }

    /* Form grid */
    .reg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .reg-full { grid-column: 1 / -1; }
    .reg-group { display: flex; flex-direction: column; gap: 6px; }
    .reg-group label { font-size: 12px; font-weight: 600; color: #334155; }
    .reg-control {
        padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px;
        font-size: 13px; font-family: inherit; color: #334155;
        transition: border-color .2s; outline: none; background: white;
    }
    .reg-control:focus { border-color: #14532d; box-shadow: 0 0 0 3px rgba(20,83,45,.1); }
    .reg-control.is-invalid { border-color: #ef4444; background: #fef2f2; }
    textarea.reg-control { resize: vertical; min-height: 80px; }
    select.reg-control { cursor: pointer; }
    .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 2px; }
    .required { color: #ef4444; }

    /* Radio group */
    .radio-group { display: flex; gap: 10px; }
    .radio-label {
        display: flex; align-items: center; gap: 6px;
        padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 8px;
        cursor: pointer; transition: all .2s; font-size: 12px;
    }
    .radio-label:hover { border-color: #14532d; background: #f0fdf4; }
    .radio-label input[type="radio"] { accent-color: #14532d; cursor: pointer; }

    /* Alerts */
    .alert-box {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
        display: flex; gap: 12px; font-size: 12px; line-height: 1.5;
    }
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert-info { background: #f0fdf4; border-left: 4px solid #14532d; color: #14532d; }
    .alert-box i { flex-shrink: 0; margin-top: 2px; font-size: 15px; }
    .error-list { list-style: none; margin: 4px 0 0; padding-left: 16px; }
    .error-list li { font-size: 11px; margin-top: 2px; }
    .error-list li:before { content: "• "; }

    /* Buttons */
    .reg-actions {
        display: flex; gap: 12px; justify-content: flex-end;
        margin-top: 28px; padding-top: 20px; border-top: 1px solid #e2e8f0;
    }
    .btn-reg {
        padding: 10px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;
        cursor: pointer; border: none; display: inline-flex;
        align-items: center; gap: 8px; transition: all .2s;
    }
    .btn-reg-primary { background: #14532d; color: white; flex: 1; justify-content: center; }
    .btn-reg-primary:hover { background: #166534; }

    /* NIK validation */
    .nik-feedback { font-size: 12px; margin-top: 4px; display: none; align-items: center; gap: 6px; }
    .nik-error { color: #ef4444; }
    .nik-success { color: #16a34a; }
    .nik-warning { color: #f59e0b; }

    @media(max-width:640px) { .reg-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

{{-- Banned state: permanent block --}}
@if($asesi && $asesi->status === 'banned')
<div class="reg-card" style="max-width:640px;margin:0 auto;text-align:center;padding:56px 40px;">
    <div style="
        width:88px;height:88px;border-radius:50%;
        background:linear-gradient(135deg,#1e293b,#374151);
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 28px;
        box-shadow:0 4px 16px rgba(0,0,0,.25);
    ">
        <i class="bi bi-slash-circle" style="font-size:38px;color:#f8fafc;"></i>
    </div>
    <h3 style="font-size:22px;font-weight:700;color:#1e293b;margin-bottom:12px;">Pendaftaran Ditolak Permanen</h3>
    <p style="color:#64748b;margin-bottom:28px;line-height:1.7;font-size:14.5px;">
        Akun Anda telah diblokir secara permanen oleh admin dan tidak dapat melakukan pendaftaran.
        Silakan hubungi pihak LSP untuk informasi lebih lanjut.
    </p>
    @if($asesi->catatan_admin)
    <div style="
        background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;
        padding:14px 18px;color:#991b1b;font-size:13.5px;
        display:flex;align-items:flex-start;gap:10px;text-align:left;
    ">
        <i class="bi bi-info-circle" style="font-size:20px;flex-shrink:0;margin-top:1px;"></i>
        <span><strong>Catatan admin:</strong> {{ $asesi->catatan_admin }}</span>
    </div>
    @endif
</div>

{{-- Pending state: show waiting card instead of form --}}
@elseif($asesi && $asesi->status === 'pending')
<div class="reg-card" style="max-width:640px;margin:0 auto;text-align:center;padding:56px 40px;">
    <div style="
        width:88px;height:88px;border-radius:50%;
        background:linear-gradient(135deg,#fef9c3,#fde047);
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 28px;
        box-shadow:0 4px 16px rgba(234,179,8,.25);
    ">
        <i class="bi bi-hourglass-split" style="font-size:38px;color:#854d0e;"></i>
    </div>
    <h3 style="font-size:22px;font-weight:700;color:#1e293b;margin-bottom:12px;">Menunggu Verifikasi Admin</h3>
    <p style="color:#64748b;margin-bottom:28px;line-height:1.7;font-size:14.5px;">
        Formulir pendaftaran Anda telah berhasil dikirim dan sedang dalam proses<br>peninjauan oleh admin.
        Cek email secara berkala untuk mendapat notifikasi setelah proses verifikasi selesai.
    </p>
    <div style="
        background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;
        padding:14px 18px;color:#166534;font-size:13.5px;
        display:flex;align-items:center;gap:10px;text-align:left;
    ">
        <i class="bi bi-shield-check" style="font-size:20px;flex-shrink:0;"></i>
        <span>Akses ke fitur lainnya akan dibuka otomatis setelah admin menyetujui pendaftaran Anda.</span>
    </div>
</div>
@else
{{-- Normal/Rejected: show the registration form --}}
<div class="reg-card">
    {{-- Rejection notice --}}
    @if($asesi && $asesi->status === 'rejected')
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:16px;margin-bottom:24px;color:#991b1b;font-size:14px;">
        <strong><i class="bi bi-x-circle" style="margin-right:6px;"></i>Pendaftaran Ditolak</strong>
        @if($asesi->catatan_admin)
            <p style="margin:8px 0 0;">Catatan admin: <em>{{ $asesi->catatan_admin }}</em></p>
        @endif
        <p style="margin:8px 0 0;">Silakan perbaiki data di bawah ini dan kirim ulang formulir.</p>
    </div>
    @endif
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step">
            <div class="step-number">1</div>
            <span class="step-label">Formulir</span>
        </div>
        <div class="step-line"></div>
        <div class="step inactive">
            <div class="step-number">2</div>
            <span class="step-label">Dokumen/Berkas</span>
        </div>
    </div>

    <h3><i class="bi bi-file-earmark-text" style="color:#14532d;"></i> FR.-APL-01. FORMULIR PERMOHONAN SERTIFIKASI</h3>
    <p class="subtitle">Lengkapi formulir pendaftaran asesi berikut ini. NIK Anda sudah terisi otomatis dari akun.</p>

    @if ($errors->any())
        <div class="alert-box alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="alert-box alert-info">
        <i class="bi bi-info-circle"></i>
        <p>NIK Anda: <strong>{{ $account->NIK }}</strong>. Setelah mengisi formulir, Anda akan diminta upload dokumen pendukung di langkah berikutnya.</p>
    </div>

    <form action="{{ route('asesi.pendaftaran.formulir.store') }}" method="POST">
        @csrf

        <!-- Data Pribadi -->
        <div class="section-header">
            <div class="section-icon"><i class="bi bi-person"></i></div>
            <h4>Data Pribadi</h4>
        </div>

        <div class="reg-grid">
            <div class="reg-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $account->nama ?? '') }}" required readonly
                       class="reg-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" style="background:#f1f5f9;color:#475569;cursor:default;" title="Nama diambil dari data akun dan tidak dapat diubah">
                <!-- <small style="font-size:11px;color:#64748b;margin-top:4px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-lock-fill" style="color:#94a3b8;"></i> Diambil dari data akun, tidak dapat diubah
                </small> -->
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>NIK</label>
                <input type="text" value="{{ $account->NIK }}" class="reg-control" disabled style="background:#f1f5f9;color:#64748b;">
            </div>

            <div class="reg-group">
                <label>Tempat Lahir <span class="required">*</span></label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                       class="reg-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan tempat lahir">
                @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>Tanggal Lahir <span class="required">*</span></label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $nikData['tanggal_lahir'] ?? '') }}" required readonly
                       class="reg-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" style="background:#f1f5f9;color:#475569;cursor:default;">
                <small style="font-size:11px;color:#16a34a;margin-top:4px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-magic"></i> Diisi otomatis dari NIK
                </small>
                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>Jenis Kelamin <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-label" style="background:#f1f5f9;cursor:default;opacity:0.85;">
                        <input type="radio" name="jenis_kelamin" value="Laki-laki"
                            {{ old('jenis_kelamin', $nikData['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'checked' : '' }} required disabled>
                        <span>Laki-laki</span>
                    </label>
                    <label class="radio-label" style="background:#f1f5f9;cursor:default;opacity:0.85;">
                        <input type="radio" name="jenis_kelamin" value="Perempuan"
                            {{ old('jenis_kelamin', $nikData['jenis_kelamin'] ?? '') == 'Perempuan' ? 'checked' : '' }} disabled>
                        <span>Perempuan</span>
                    </label>
                </div>
                {{-- Hidden input so value still submits when disabled --}}
                <input type="hidden" name="jenis_kelamin" value="{{ old('jenis_kelamin', $nikData['jenis_kelamin'] ?? '') }}">
                <small style="font-size:11px;color:#16a34a;margin-top:4px;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-magic"></i> Diisi otomatis dari NIK
                </small>
            </div>

            <div class="reg-group">
                <label>Kewarganegaraan <span class="required">*</span></label>
                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required
                       class="reg-control" placeholder="Indonesia">
            </div>

            <div class="reg-group reg-full">
                <label>Alamat Lengkap <span class="required">*</span></label>
                <textarea name="alamat" rows="3" required class="reg-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                          placeholder="Jl. Nama jalan RT/RW - Desa - Kecamatan">{{ old('alamat') }}</textarea>
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>Kode POS <span class="required">*</span></label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" required
                       class="reg-control" placeholder="XXXXX">
            </div>

            <div class="reg-group">
                <label>No Telepon/HP <span class="required">*</span></label>
                <input type="text" name="telepon_hp" value="{{ old('telepon_hp') }}" required
                       class="reg-control" placeholder="0812XXXXXXXX">
            </div>

            <div class="reg-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="reg-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="nama@gmail.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>Pekerjaan / Profesi <span class="required">*</span></label>
                <select name="pekerjaan" required class="reg-control">
                    <option value="">Pilih</option>
                    @foreach(['Pelajar', 'Mahasiswa', 'Karyawan Swasta', 'PNS', 'Wiraswasta', 'Lainnya'] as $p)
                        <option value="{{ $p }}" {{ old('pekerjaan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="reg-group">
                <label>Pendidikan Terakhir <span class="required">*</span></label>
                <select name="pendidikan_terakhir" required class="reg-control">
                    <option value="">Pilih</option>
                    @foreach(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $p)
                        <option value="{{ $p }}" {{ old('pendidikan_terakhir') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="reg-group">
                <label>Jurusan <span class="required">*</span></label>
                <select name="ID_jurusan" id="jurusan-select" required class="reg-control">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan->ID_jurusan }}" {{ old('ID_jurusan', $asesi->ID_jurusan ?? '') == $jurusan->ID_jurusan ? 'selected' : '' }}>
                            {{ $jurusan->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="reg-group">
                <label>Skema Sertifikasi <span class="required">*</span></label>
                <select name="skema_id" id="skema-select" required class="reg-control">
                    <option value="">Pilih Jurusan dulu</option>
                    @foreach($skemaList as $sk)
                        <option value="{{ $sk->id }}"
                            data-jurusan="{{ $sk->jurusan_id }}"
                            {{ old('skema_id', $asesi?->skemas->first()?->id ?? '') == $sk->id ? 'selected' : '' }}>
                            {{ $sk->nama_skema }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="reg-group">
                <label>Kelas</label>
                <select name="kelas" id="kelas-select" class="reg-control {{ $errors->has('kelas') ? 'is-invalid' : '' }}">
                    <option value="">Pilih Jurusan dulu</option>
                </select>
                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <!-- Data Pekerjaan/Sekolah -->
        <div class="section-header">
            <div class="section-icon"><i class="bi bi-building"></i></div>
            <h4>Data Pekerjaan / Sekolah</h4>
        </div>

        <div class="reg-grid">
            <div class="reg-group">
                <label>Nama Lembaga / Perusahaan <span class="required">*</span></label>
                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', 'SMKN 1 Ciamis') }}" required
                       class="reg-control" placeholder="SMKN 1 Ciamis">
            </div>

            <div class="reg-group">
                <label>Jabatan <span class="required">*</span></label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}" required
                       class="reg-control" placeholder="Siswa / Staff">
            </div>

            <div class="reg-group reg-full">
                <label>Alamat Lembaga <span class="required">*</span></label>
                <textarea name="alamat_lembaga" rows="3" required class="reg-control"
                          placeholder="Jl. Lembaga No. 123...">{{ old('alamat_lembaga') }}</textarea>
            </div>

            <div class="reg-group">
                <label>No. Telepon Lembaga</label>
                <input type="text" name="no_fax_lembaga" value="{{ old('no_fax_lembaga') }}"
                       class="reg-control" placeholder="[0xxx] ...">
            </div>

            <div class="reg-group">
                <label>No. Fax Lembaga</label>
                <input type="text" name="telepon_rumah" value="{{ old('telepon_rumah') }}"
                       class="reg-control" placeholder="[0xxx] ...">
            </div>

            <div class="reg-group">
                <label>Email Lembaga <span class="required">*</span></label>
                <input type="email" name="email_lembaga" value="{{ old('email_lembaga') }}" required
                       class="reg-control {{ $errors->has('email_lembaga') ? 'is-invalid' : '' }}" placeholder="nama@lembaga.sch.id">
                @error('email_lembaga')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="reg-group">
                <label>Kode POS Lembaga</label>
                <input type="text" name="unit_lembaga" value="{{ old('unit_lembaga') }}"
                       class="reg-control" placeholder="XXXXX">
            </div>
        </div>

        <!-- Actions -->
        <div class="reg-actions">
            <button type="submit" class="btn-reg btn-reg-primary">
                <span>Selanjutnya</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>
@endif
@endsection

@section('scripts')
<script>
(function () {
    const skemaData = @json($skemaList->map(fn($s) => ['id' => $s->id, 'jurusan_id' => $s->jurusan_id, 'nama' => $s->nama_skema]));
    const jurusanKelasMap = @json(
        $jurusanList->mapWithKeys(function ($j) {
            return [
                (string) $j->ID_jurusan => $j->kelasItems->pluck('nama_kelas')->values(),
            ];
        })
    );
    const jurusanSel = document.getElementById('jurusan-select');
    const skemaSel   = document.getElementById('skema-select');
    const kelasSel   = document.getElementById('kelas-select');
    const savedSkema = '{{ old('skema_id', $asesi?->skemas->first()?->id ?? '') }}';
    const savedKelas = '{{ old('kelas', $asesi->kelas ?? '') }}';

    function filterSkema(jurusanId) {
        skemaSel.innerHTML = '';
        const filtered = skemaData.filter(s => String(s.jurusan_id) === String(jurusanId));
        if (!filtered.length) {
            skemaSel.innerHTML = '<option value="">Tidak ada skema untuk jurusan ini</option>';
            return;
        }
        const def = document.createElement('option');
        def.value = ''; def.textContent = 'Pilih Skema';
        skemaSel.appendChild(def);
        filtered.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.nama;
            if (String(s.id) === String(savedSkema)) opt.selected = true;
            skemaSel.appendChild(opt);
        });
    }

    function syncKelas(jurusanId) {
        if (!kelasSel) return;

        kelasSel.innerHTML = '';
        const kelasList = jurusanKelasMap[String(jurusanId)] || [];

        const def = document.createElement('option');
        def.value = '';
        def.textContent = jurusanId
            ? (kelasList.length ? 'Pilih Kelas' : 'Belum ada kelas pada jurusan ini')
            : 'Pilih Jurusan dulu';
        kelasSel.appendChild(def);

        kelasList.forEach(function (kelasNama) {
            const opt = document.createElement('option');
            opt.value = kelasNama;
            opt.textContent = kelasNama;
            if (savedKelas && savedKelas === kelasNama) opt.selected = true;
            kelasSel.appendChild(opt);
        });
    }

    // Init on page load
    if (jurusanSel.value) {
        filterSkema(jurusanSel.value);
        syncKelas(jurusanSel.value);
    }

    jurusanSel.addEventListener('change', function () {
        filterSkema(this.value);
        syncKelas(this.value);
    });
})();
</script>
@endsection
