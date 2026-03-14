@extends('admin.layout')

@section('title', 'Tambah Asesi')
@section('page-title', 'Tambah Asesi')

@section('content')
<div class="page-header">
    <h2>Tambah Data Asesi</h2>
    <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.asesi.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Informasi Dasar</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="NIK">NIK <span class="required">*</span></label>
                        <input type="text" id="NIK" name="NIK" class="form-control @error('NIK') is-invalid @enderror" value="{{ old('NIK') }}" required maxlength="16" minlength="16" pattern="\d{16}" inputmode="numeric" placeholder="16 digit NIK">
                        @error('NIK')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ID_jurusan">Jurusan <span class="required">*</span></label>
                        <select id="ID_jurusan" name="ID_jurusan" class="form-control @error('ID_jurusan') is-invalid @enderror" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $item)
                                <option value="{{ $item->ID_jurusan }}" {{ old('ID_jurusan') == $item->ID_jurusan ? 'selected' : '' }}>
                                    {{ $item->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('ID_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror">
                            <option value="">Pilih Jurusan dulu</option>
                        </select>
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin <span class="required">*</span></label>
                        <div class="radio-group" id="jenis-kelamin-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin_radio" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} disabled>
                                <span>Laki-laki</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin_radio" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} disabled>
                                <span>Perempuan</span>
                            </label>
                        </div>
                        <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" required>
                        <small id="jenis-kelamin-feedback" style="font-size:11px;color:#64748b;margin-top:4px;display:block;">Diisi otomatis dari NIK</small>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kebangsaan">Kebangsaan <span class="required">*</span></label>
                        <input type="text" id="kebangsaan" name="kebangsaan" class="form-control @error('kebangsaan') is-invalid @enderror" value="{{ old('kebangsaan', 'Indonesia') }}" required>
                        @error('kebangsaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan / Profesi <span class="required">*</span></label>
                        <select id="pekerjaan" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" required>
                            <option value="">Pilih</option>
                            @foreach(['Pelajar', 'Mahasiswa', 'Karyawan Swasta', 'PNS', 'Wiraswasta', 'Lainnya'] as $p)
                                <option value="{{ $p }}" {{ old('pekerjaan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir <span class="required">*</span></label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" required>
                            <option value="">Pilih</option>
                            @foreach(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_terakhir') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('pendidikan_terakhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="skema_id">Skema Sertifikasi <span class="required">*</span></label>
                        <select id="skema_id" name="skema_id" class="form-control @error('skema_id') is-invalid @enderror" required>
                            <option value="">Pilih Jurusan dulu</option>
                            @foreach($skemaList as $sk)
                                <option value="{{ $sk->id }}" data-jurusan="{{ $sk->jurusan_id }}" {{ old('skema_id') == $sk->id ? 'selected' : '' }}>
                                    {{ $sk->nama_skema }}
                                </option>
                            @endforeach
                        </select>
                        @error('skema_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Tempat & Tanggal Lahir</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir <span class="required">*</span></label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" required readonly style="background:#f1f5f9;color:#475569;cursor:default;">
                        <small id="tanggal-lahir-feedback" style="font-size:11px;color:#64748b;margin-top:4px;display:block;">Diisi otomatis dari NIK (6 digit kedua)</small>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Alamat & Kontak</h3>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_kota">Kode Kota</label>
                        <input type="text" id="kode_kota" name="kode_kota" class="form-control" value="{{ old('kode_kota') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_provinsi">Kode Provinsi</label>
                        <input type="text" id="kode_provinsi" name="kode_provinsi" class="form-control" value="{{ old('kode_provinsi') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_pos">Kode Pos <span class="required">*</span></label>
                        <input type="text" id="kode_pos" name="kode_pos" class="form-control @error('kode_pos') is-invalid @enderror" value="{{ old('kode_pos') }}" required>
                        @error('kode_pos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telepon_rumah">Telepon Rumah</label>
                        <input type="text" id="telepon_rumah" name="telepon_rumah" class="form-control" value="{{ old('telepon_rumah') }}">
                    </div>

                    <div class="form-group">
                        <label for="telepon_hp">Telepon HP <span class="required">*</span></label>
                        <input type="text" id="telepon_hp" name="telepon_hp" class="form-control @error('telepon_hp') is-invalid @enderror" value="{{ old('telepon_hp') }}" required>
                        @error('telepon_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Data Pekerjaan / Sekolah</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_lembaga">Nama Lembaga / Sekolah <span class="required">*</span></label>
                        <input type="text" id="nama_lembaga" name="nama_lembaga" class="form-control @error('nama_lembaga') is-invalid @enderror" value="{{ old('nama_lembaga') }}" required>
                        @error('nama_lembaga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan">Jabatan <span class="required">*</span></label>
                        <input type="text" id="jabatan" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat_lembaga">Alamat Lembaga / Sekolah <span class="required">*</span></label>
                    <textarea id="alamat_lembaga" name="alamat_lembaga" class="form-control @error('alamat_lembaga') is-invalid @enderror" rows="3" required>{{ old('alamat_lembaga') }}</textarea>
                    @error('alamat_lembaga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email_lembaga">Email Lembaga <span class="required">*</span></label>
                        <input type="email" id="email_lembaga" name="email_lembaga" class="form-control @error('email_lembaga') is-invalid @enderror" value="{{ old('email_lembaga') }}" required>
                        @error('email_lembaga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_fax_lembaga">No Fax Lembaga</label>
                        <input type="text" id="no_fax_lembaga" name="no_fax_lembaga" class="form-control" value="{{ old('no_fax_lembaga') }}">
                    </div>

                    <div class="form-group">
                        <label for="unit_lembaga">Unit Lembaga</label>
                        <input type="text" id="unit_lembaga" name="unit_lembaga" class="form-control" value="{{ old('unit_lembaga') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_kementrian">Kode Kementrian</label>
                        <input type="text" id="kode_kementrian" name="kode_kementrian" class="form-control" value="{{ old('kode_kementrian') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_anggaran">Kode Anggaran</label>
                        <input type="text" id="kode_anggaran" name="kode_anggaran" class="form-control" value="{{ old('kode_anggaran') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
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

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3:before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row:last-child {
        margin-bottom: 0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 0;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-section > .form-group {
        margin-bottom: 20px;
    }

    .form-section > .form-group:last-of-type {
        margin-bottom: 0;
    }

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
    }

    .radio-label input[type="radio"] {
        accent-color: #0073bd;
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #0073bd;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
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
        background: #0073bd;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    select.form-control {
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .card-body {
            padding: 20px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    (function () {
        var kelasSelect = document.getElementById('kelas');
        var savedKelas = '{{ old('kelas') }}';
        var jurusanKelasMap = @json(
            $jurusan->mapWithKeys(function ($j) {
                return [
                    (string) $j->ID_jurusan => $j->kelasItems->pluck('nama_kelas')->values(),
                ];
            })
        );

        var nikInput = document.getElementById('NIK');
        var tglInput = document.getElementById('tanggal_lahir');
        var jkHidden = document.getElementById('jenis_kelamin');
        var jkRadios = Array.from(document.querySelectorAll('input[name="jenis_kelamin_radio"]'));
        var tglFeedback = document.getElementById('tanggal-lahir-feedback');
        var jkFeedback = document.getElementById('jenis-kelamin-feedback');

        function setJkRadio(value) {
            jkRadios.forEach(function (radio) {
                radio.checked = radio.value === value;
            });
        }

        function clearAutoNikFields(showHint) {
            if (tglInput) tglInput.value = '';
            if (jkHidden) jkHidden.value = '';
            setJkRadio('');
            if (showHint) {
                if (tglFeedback) tglFeedback.textContent = 'Diisi otomatis dari NIK (6 digit kedua)';
                if (jkFeedback) jkFeedback.textContent = 'Diisi otomatis dari NIK';
            }
        }

        function parseNik(nik) {
            if (!nik || nik.length !== 16 || !/^\d{16}$/.test(nik)) return null;

            var ddRaw = parseInt(nik.slice(6, 8), 10);
            var mm = parseInt(nik.slice(8, 10), 10);
            var yy = parseInt(nik.slice(10, 12), 10);

            var isFemale = ddRaw > 40;
            var dd = isFemale ? ddRaw - 40 : ddRaw;
            var currentYY = parseInt(new Date().getFullYear().toString().slice(-2), 10);
            var year = yy <= currentYY ? 2000 + yy : 1900 + yy;

            var dateObj = new Date(year, mm - 1, dd);
            var isValidDate = dateObj.getFullYear() === year && (dateObj.getMonth() + 1) === mm && dateObj.getDate() === dd;
            if (!isValidDate) return null;

            var dateIso = year + '-' + String(mm).padStart(2, '0') + '-' + String(dd).padStart(2, '0');
            return {
                tanggal: dateIso,
                jk: isFemale ? 'Perempuan' : 'Laki-laki',
                jkLabel: isFemale ? 'Perempuan' : 'Laki-laki'
            };
        }

        function applyNikAutoFill() {
            var nik = nikInput ? (nikInput.value || '') : '';
            if (!nik || nik.length < 16) {
                clearAutoNikFields(true);
                return;
            }

            var parsed = parseNik(nik);
            if (!parsed) {
                clearAutoNikFields(false);
                if (tglFeedback) tglFeedback.textContent = 'Format tanggal lahir pada NIK tidak valid';
                if (jkFeedback) jkFeedback.textContent = 'Format NIK tidak valid untuk menentukan jenis kelamin';
                return;
            }

            if (tglInput) tglInput.value = parsed.tanggal;
            if (jkHidden) jkHidden.value = parsed.jk;
            setJkRadio(parsed.jk);

            if (tglFeedback) tglFeedback.textContent = 'Terisi otomatis dari NIK: ' + parsed.tanggal;
            if (jkFeedback) jkFeedback.textContent = 'Terisi otomatis dari NIK: ' + parsed.jkLabel;
        }

        if (nikInput) {
            nikInput.addEventListener('input', function () {
                this.value = (this.value || '').replace(/\D/g, '').slice(0, 16);
                applyNikAutoFill();
            });
            applyNikAutoFill();
        }

        var jurusanSelect = document.getElementById('ID_jurusan');
        var skemaSelect = document.getElementById('skema_id');
        if (!jurusanSelect || !skemaSelect) return;

        var placeholder = skemaSelect.querySelector('option[value=""]');

        function syncKelasByJurusan() {
            if (!kelasSelect) return;
            var selectedJurusan = jurusanSelect.value || '';
            var kelasList = jurusanKelasMap[selectedJurusan] || [];

            kelasSelect.innerHTML = '';

            var defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = selectedJurusan
                ? (kelasList.length ? 'Pilih Kelas' : 'Belum ada kelas pada jurusan ini')
                : 'Pilih Jurusan dulu';
            kelasSelect.appendChild(defaultOpt);

            kelasList.forEach(function (kelasNama) {
                var opt = document.createElement('option');
                opt.value = kelasNama;
                opt.textContent = kelasNama;
                if (savedKelas && savedKelas === kelasNama) opt.selected = true;
                kelasSelect.appendChild(opt);
            });
        }

        function syncSkemaByJurusan() {
            var selectedJurusan = jurusanSelect.value;
            var hasVisibleOption = false;

            Array.from(skemaSelect.options).forEach(function (opt) {
                if (!opt.value) return;
                var jurusanId = opt.getAttribute('data-jurusan') || '';
                var visible = selectedJurusan && jurusanId === selectedJurusan;
                opt.hidden = !visible;
                if (!visible && opt.selected) opt.selected = false;
                if (visible) hasVisibleOption = true;
            });

            if (placeholder) {
                placeholder.textContent = selectedJurusan
                    ? (hasVisibleOption ? 'Pilih Skema' : 'Tidak ada skema untuk jurusan ini')
                    : 'Pilih Jurusan dulu';
            }
        }

        jurusanSelect.addEventListener('change', function () {
            syncSkemaByJurusan();
            syncKelasByJurusan();
        });
        syncSkemaByJurusan();
        syncKelasByJurusan();
    })();
</script>
@endsection
