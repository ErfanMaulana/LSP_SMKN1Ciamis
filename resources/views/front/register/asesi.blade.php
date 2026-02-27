<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - LSP SMKN1 Ciamis</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            color: #334155;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
        }

        .container-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            width: 100%;
        }

        .navbar-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: auto;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
            background: white;
            padding: 4px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .navbar-brand span {
            font-size: 13px;
            font-weight: 600;
            color: #0F172A;
            white-space: nowrap;
        }

        .navbar-nav {
            display: none;
            gap: 4px;
            align-items: center;
            font-size: 13px;
            font-weight: 500;
        }

        @media (min-width: 768px) {
            .navbar-nav {
                display: flex;
            }
        }

        .navbar-nav a {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #334155;
            transition: all 0.2s;
        }

        .navbar-nav a:hover {
            background: #0073bd;
            color: white;
        }

        .navbar-nav a.active {
            background: #0073bd;
            color: white;
        }

        .btn-login {
            background: #0073bd;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #0061A5;
        }

        .container-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 60px;
        }

        .main-content {
            flex: 1;
            padding: 32px 24px;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            padding: 32px;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-number {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #0073bd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
            flex-shrink: 0;
        }

        .step.inactive .step-number {
            background: #cbd5e1;
            color: #64748b;
        }

        .step-label {
            font-size: 12px;
            font-weight: 500;
            color: #0073bd;
        }

        .step.inactive .step-label {
            color: #94a3b8;
        }

        .step-line {
            width: 50px;
            height: 1px;
            background: #cbd5e1;
        }

        .form-title {
            margin-bottom: 20px;
        }

        .form-title h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .form-title p {
            font-size: 12px;
            color: #64748b;
        }

        .alert-box {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            font-size: 12px;
            line-height: 1.5;
        }

        .alert-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        .alert-info {
            background: #eff6ff;
            border-left: 4px solid #0073bd;
            color: #1e3a8a;
        }

        .alert-box i {
            flex-shrink: 0;
            margin-top: 2px;
            font-size: 15px;
        }

        .form-section {
            margin-bottom: 28px;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0073bd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .form-section-title h3 {
            font-size: 13px;
            font-weight: 600;
            color: #0F172A;
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus {
            border-color: #0073bd;
            box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        textarea.form-control {
            resize: none;
            font-family: inherit;
            line-height: 1.5;
        }

        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 32px;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .form-control.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 12px;
            background: white;
        }

        .radio-label:hover {
            border-color: #0073bd;
            background: #f8fafc;
        }

        .radio-label input[type="radio"] {
            cursor: pointer;
            accent-color: #0073bd;
        }

        .radio-label input[type="radio"]:checked + span {
            color: #0073bd;
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #0073bd;
            color: white;
            flex: 1;
            justify-content: center;
        }

        .btn-primary:hover {
            background: #0061A5;
            box-shadow: 0 4px 12px rgba(0, 115, 189, 0.3);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .error-list {
            list-style: none;
            margin: 6px 0 0 0;
            padding-left: 20px;
        }

        .error-list li {
            font-size: 11px;
            margin-top: 3px;
        }

        .error-list li:before {
            content: "• ";
            margin-right: 4px;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 16px;
            }

            .form-card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-title h2 {
                font-size: 16px;
            }

            .step-indicator {
                gap: 12px;
            }

            .step-line {
                width: 35px;
            }

            .navbar-brand span {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 16px;
            }

            .form-grid {
                gap: 12px;
            }

            .form-title h2 {
                font-size: 14px;
            }

            .navbar-container {
                padding: 0 16px;
            }

            .navbar-brand span {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <navbar>
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="navbar-logo">
                    <img src="{{ asset('images/lsp.png') }}" alt="LSP Logo">
                </div>
                <span>LSP SMKN 1 CIAMIS</span>
            </div>

            <nav class="navbar-nav">
                <a href="{{ route('front.home') }}" class="{{ request()->routeIs('front.home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('front.profil') }}" class="{{ request()->routeIs('front.profil') ? 'active' : '' }}">Profil LSP</a>
                <a href="{{ route('front.kompetensi.index') }}" class="{{ request()->routeIs('front.kompetensi.index') ? 'active' : '' }}">Kompetensi & Data Skema</a>
                <a href="{{ route('front.daftar') }}" class="{{ request()->routeIs('front.daftar') ? 'active' : '' }}">Daftar LSP</a>
                <a href="{{ route('front.kontak') }}" class="{{ request()->routeIs('front.kontak') ? 'active' : '' }}">Kontak</a>
            </nav>

            <a href="{{ route('admin.login') }}" class="btn-login">Login</a>
        </div>
    </navbar>

    <div class="container-main">
        <!-- Main Content -->
        <div class="main-content">
            <div class="form-card">
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

                <!-- Form Title -->
                <div class="form-title">
                    <h2>FR.-APL-01. FORMULIR PERMOHONAN SERTIFIKASI</h2>
                    <p>Bidang Sertifikasi yang akan diuji skema-industri-terkait</p>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert-box alert-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <div>
                            <strong>Terdapat kesalahan dalam pengisian form:</strong>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Info Alert -->
                <div class="alert-box alert-info">
                    <i class="bi bi-info-circle"></i>
                    <p>Melakukan aplikasi dengan mengisi formulir aplikasi pada halaman berikutnya dan melengkapi form FR.AKL.03 (ASESMEN MANDIRI)</p>
                </div>

                <!-- Form -->
                <form action="{{ route('front.register.asesi.store') }}" method="POST">
                    @csrf

                    <!-- Data Pribadi Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="section-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <h3>Data Pribadi</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="form-control" placeholder="Masukkan nama lengkap">
                            </div>

                            <div class="form-group" id="nik-group">
                                <label for="NIK">NIK <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="NIK" name="NIK" value="{{ old('NIK') }}" required minlength="16" maxlength="16" class="form-control" placeholder="Masukkan NIK (16 digit)">
                                <div id="nik-error" style="font-size: 12px; color: #ef4444; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span id="nik-error-message"></span>
                                </div>
                                <div id="nik-success" style="font-size: 12px; color: #16a34a; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-check-circle"></i>
                                    <span>NIK valid (16 digit)</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="form-control" placeholder="Masukkan tempat lahir">
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir <span style="color: #ef4444;">*</span></label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Jenis Kelamin <span style="color: #ef4444;">*</span></label>
                                <div class="radio-group">
                                    <label class="radio-label" class="border: none;">
                                        <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required>
                                        <span>Laki-laki</span>
                                    </label>
                                    <label class="radio-label" class="border : none;">
                                        <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} required>
                                        <span>Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kewarganegaraan">Kewarganegaraan <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" required class="form-control" placeholder="Indonesia">
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="alamat">Alamat Lengkap <span style="color: #ef4444;">*</span></label>
                                <textarea id="alamat" name="alamat" rows="3" required class="form-control" placeholder="Jl. Nama jalan RT/RW - Desa - Kecamatan">{{ old('alamat') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="kode_pos">Kode POS <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required class="form-control" placeholder="XXXXX">
                            </div>

                            <div class="form-group">
                                <label for="telepon_hp">No Telepon/HP <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="telepon_hp" name="telepon_hp" value="{{ old('telepon_hp') }}" required class="form-control" placeholder="0812XXXXXXXX">
                            </div>

                            <div class="form-group" id="email-group">
                                <label for="email">Email <span style="color: #ef4444;">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="nama@gmail.com atau nama@yahoo.com">
                                <div id="email-error" style="font-size: 12px; color: #ef4444; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span id="email-error-message"></span>
                                </div>
                                <div id="email-success" style="font-size: 12px; color: #16a34a; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Email valid</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pekerjaan">Pekerjaan / Profesi <span style="color: #ef4444;">*</span></label>
                                <select id="pekerjaan" name="pekerjaan" required class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="Pelajar" {{ old('pekerjaan') == 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                                    <option value="Mahasiswa" {{ old('pekerjaan') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="Karyawan Swasta" {{ old('pekerjaan') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                                    <option value="PNS" {{ old('pekerjaan') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Wiraswasta" {{ old('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir <span style="color: #ef4444;">*</span></label>
                                <select id="pendidikan_terakhir" name="pendidikan_terakhir" required class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ID_jurusan">Jurusan / Skema Sertifikasi <span style="color: #ef4444;">*</span></label>
                                <select id="ID_jurusan" name="ID_jurusan" required class="form-control">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach($jurusanList as $jurusan)
                                        <option value="{{ $jurusan->ID_jurusan }}" {{ old('ID_jurusan') == $jurusan->ID_jurusan ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pekerjaan/Sekolah Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="section-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <h3>Data Pekerjaan / Sekolah</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nama_lembaga">Nama Lembaga / Perusahaan <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="nama_lembaga" name="nama_lembaga" value="{{ old('nama_lembaga', 'SMKN 1 Ciamis') }}" required class="form-control" placeholder="SMKN 1 Ciamis">
                            </div>

                            <div class="form-group">
                                <label for="jabatan">Jabatan <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required class="form-control" placeholder="Siswa / Staff">
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="alamat_lembaga">Alamat Lembaga <span style="color: #ef4444;">*</span></label>
                                <textarea id="alamat_lembaga" name="alamat_lembaga" rows="3" required class="form-control" placeholder="Jl. Lembaga No. 123...">{{ old('alamat_lembaga') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="no_telepon_lembaga">No. Telepon Lembaga</label>
                                <input type="text" id="no_telepon_lembaga" name="no_fax_lembaga" value="{{ old('no_fax_lembaga') }}" class="form-control" placeholder="[Oxxx] ...">
                            </div>

                            <div class="form-group">
                                <label for="no_fax_lembaga_alt">No. Fax Lembaga</label>
                                <input type="text" id="no_fax_lembaga_alt" name="telepon_rumah" value="{{ old('telepon_rumah') }}" class="form-control" placeholder="[Oxxx] ...">
                            </div>

                            <div class="form-group" id="email_lembaga-group">
                                <label for="email_lembaga">Email Lembaga <span style="color: #ef4444;">*</span></label>
                                <input type="email" id="email_lembaga" name="email_lembaga" value="{{ old('email_lembaga') }}" required class="form-control" placeholder="nama@gmail.com atau nama@yahoo.com">
                                <div id="email_lembaga-error" style="font-size: 12px; color: #ef4444; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span id="email_lembaga-error-message"></span>
                                </div>
                                <div id="email_lembaga-success" style="font-size: 12px; color: #16a34a; margin-top: 4px; display: none; align-items: center; gap: 6px;">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Email valid</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="unit_lembaga">Kode POS Lembaga</label>
                                <input type="text" id="unit_lembaga" name="unit_lembaga" value="{{ old('unit_lembaga') }}" class="form-control" placeholder="XXXXX">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span>Selanjutnya</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

                                <span>Selanjutnya (Langkah 2)</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Info -->
                <div class="bg-gray-50 px-8 py-4 text-xs text-gray-500 border-t">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center space-x-4">
                            <a href="#" class="flex items-center hover:text-blue-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                Beranda
                            </a>
                            <a href="#" class="hover:text-blue-600">© lsp-tkjsmkn1ciamis.sch.id</a>
                            <a href="#" class="hover:text-blue-600">Asesi LSP</a>
                        </div>
                        <div class="text-gray-400">© 2025 LSP SMKN1 Ciamis. All rights reserved.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateRadioStyle() {
            document.querySelectorAll('input[name="jenis_kelamin"]').forEach(function(radio) {
                const label = radio.closest('label');
                if (radio.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'font-semibold');
                    label.classList.remove('border-gray-300', 'text-gray-700');
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700', 'font-semibold');
                    label.classList.add('border-gray-300', 'text-gray-700');
                }
            });
        }

        // Validasi NIK real-time dan sinkronisasi dengan tanggal lahir
        function validateNIK() {
            const nikInput = document.getElementById('NIK');
            const nikError = document.getElementById('nik-error');
            const nikSuccess = document.getElementById('nik-success');
            const nikErrorMsg = document.getElementById('nik-error-message');
            const nikGroup = document.getElementById('nik-group');
            let nikValue = nikInput.value;
            
            // Filter: hanya izinkan angka
            nikValue = nikValue.replace(/[^0-9]/g, '');
            nikInput.value = nikValue;
            
            if (nikValue.length === 0) {
                nikError.style.display = 'none';
                nikSuccess.style.display = 'none';
                nikGroup.classList.remove('has-error');
                nikInput.classList.remove('is-invalid');
                return;
            }
            
            if (nikValue.length < 16) {
                nikErrorMsg.textContent = `NIK kurang (${nikValue.length}/16 digit)`;
                nikError.style.display = 'flex';
                nikSuccess.style.display = 'none';
                nikGroup.classList.add('has-error');
                nikInput.classList.add('is-invalid');
            } else if (nikValue.length === 16) {
                // Check format dan sinkronisasi tanggal lahir
                const tanggalLahirInput = document.getElementById('tanggal_lahir');
                const warningDiv = document.getElementById('nik-warning') || createNIKWarningDiv();
                
                // Ambil jenis kelamin dari radio button
                const jenisKelaminRadios = document.querySelectorAll('input[name="jenis_kelamin"]');
                let jenisKelamin = '';
                jenisKelaminRadios.forEach(radio => {
                    if (radio.checked) {
                        jenisKelamin = radio.value;
                    }
                });
                
                let tanggalLahirMatch = checkNIKTanggalLahir(nikValue, tanggalLahirInput.value, jenisKelamin);
                
                nikError.style.display = 'none';
                nikInput.classList.remove('is-invalid');
                nikGroup.classList.remove('has-error');
                
                if (tanggalLahirMatch) {
                    nikSuccess.style.display = 'flex';
                    if (warningDiv) warningDiv.style.display = 'none';
                } else {
                    // Tampilkan warning, bukan error
                    nikSuccess.style.display = 'flex';
                    if (warningDiv) {
                        warningDiv.style.display = 'flex';
                    }
                }
            } else {
                nikErrorMsg.textContent = 'NIK maksimal 16 digit';
                nikError.style.display = 'flex';
                nikSuccess.style.display = 'none';
                nikGroup.classList.add('has-error');
                nikInput.classList.add('is-invalid');
            }
        }
        
        // Helper function untuk membuat warning div
        function createNIKWarningDiv() {
            const nikGroup = document.getElementById('nik-group');
            if (document.getElementById('nik-warning')) {
                return document.getElementById('nik-warning');
            }
            
            const warningDiv = document.createElement('div');
            warningDiv.id = 'nik-warning';
            warningDiv.style.cssText = 'font-size: 12px; color: #f59e0b; margin-top: 4px; display: none; align-items: center; gap: 6px;';
            warningDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i><span>Apakah ini benar NIK Anda? Tanggal lahir di NIK tidak sesuai dengan tanggal lahir dan jenis kelamin yang diinputkan.</span>';
            
            const successDiv = document.getElementById('nik-success');
            successDiv.parentNode.insertBefore(warningDiv, successDiv.nextSibling);
            
            return warningDiv;
        }
        
        // Check apakah tanggal lahir di NIK sesuai dengan input tanggal lahir dan jenis kelamin
        function checkNIKTanggalLahir(nik, tanggalLahir, jenisKelamin) {
            if (!tanggalLahir) return true; // Skip jika belum ada input tanggal lahir
            if (!jenisKelamin) return true; // Skip jika belum pilih jenis kelamin
            
            // Extract 6 digit kedua (posisi 6-11): DDMMYY
            const digitKedua = nik.substring(6, 12);
            let dd = parseInt(digitKedua.substring(0, 2), 10);
            const mm = parseInt(digitKedua.substring(2, 4), 10);
            let yy = parseInt(digitKedua.substring(4, 6), 10);
            
            // Debug info
            console.log('NIK Validation Debug:', {
                nik: nik,
                digitKedua: digitKedua,
                ddOriginal: dd,
                mm: mm,
                yy: yy,
                jenisKelamin: jenisKelamin,
                tanggalLahir: tanggalLahir
            });
            
            // Handle jenis kelamin
            // Jika perempuan dan DD > 40, kurangi 40
            // Jika laki-laki dan DD > 40, itu invalid format untuk laki-laki
            if (jenisKelamin === 'Perempuan') {
                if (dd > 40) {
                    dd = dd - 40;
                    console.log('Perempuan: DD dikurangi 40, sekarang:', dd);
                }
            } else if (jenisKelamin === 'Laki-laki') {
                // Untuk laki-laki, DD seharusnya <= 31, jika > 40 maka itu mungkin untuk perempuan
                if (dd > 40) {
                    console.log('Laki-laki tapi DD > 40, invalid!');
                    // Invalid - tanggal di NIK terlihat untuk perempuan tapi jenis kelamin dipilih laki-laki
                    return false;
                }
            }
            
            // Parse tanggal dari input
            const [tahun, bulan, tanggal] = tanggalLahir.split('-').map(Number);
            
            // Konversi YY menjadi YYYY
            // Jika YY <= 30 (misalnya 00-30), asumsi 2000-2030
            // Jika YY > 30 (misalnya 31-99), asumsi 1931-1999
            let tahunLengkap = yy > 30 ? 1900 + yy : 2000 + yy;
            
            // Check match
            const match = dd === tanggal && mm === bulan && tahunLengkap === tahun;
            
            console.log('Comparison:', {
                NIK_DD: dd,
                Input_Tanggal: tanggal,
                NIK_MM: mm,
                Input_Bulan: bulan,
                NIK_Tahun: tahunLengkap,
                Input_Tahun: tahun,
                match: match
            });
            
            return match;
        }
        
        // Validasi email - hanya @gmail.com dan @yahoo.com
        function validateEmail(emailInputId) {
            const emailInput = document.getElementById(emailInputId);
            const errorDiv = document.getElementById(emailInputId + '-error');
            const successDiv = document.getElementById(emailInputId + '-success');
            const errorMsg = document.getElementById(emailInputId + '-error-message');
            const emailGroup = document.getElementById(emailInputId + '-group');
            const emailValue = emailInput.value.trim();
            
            if (emailValue.length === 0) {
                errorDiv.style.display = 'none';
                successDiv.style.display = 'none';
                emailGroup.classList.remove('has-error');
                emailInput.classList.remove('is-invalid');
                return;
            }
            
            // Check format email dasar
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                errorMsg.textContent = 'Format email tidak valid';
                errorDiv.style.display = 'flex';
                successDiv.style.display = 'none';
                emailGroup.classList.add('has-error');
                emailInput.classList.add('is-invalid');
                return;
            }
            
            // Check domain hanya @gmail.com atau @yahoo.com
            const allowedDomains = ['gmail.com', 'yahoo.com'];
            const domain = emailValue.split('@')[1].toLowerCase();
            
            if (!allowedDomains.includes(domain)) {
                errorMsg.textContent = 'Email hanya boleh menggunakan @gmail.com atau @yahoo.com';
                errorDiv.style.display = 'flex';
                successDiv.style.display = 'none';
                emailGroup.classList.add('has-error');
                emailInput.classList.add('is-invalid');
                return;
            }
            
            // Valid
            errorDiv.style.display = 'none';
            successDiv.style.display = 'flex';
            emailGroup.classList.remove('has-error');
            emailInput.classList.remove('is-invalid');
        }

        // Run on page load to reflect old() values
        document.addEventListener('DOMContentLoaded', function() {
            updateRadioStyle();
            
            // Setup NIK validation
            const nikInput = document.getElementById('NIK');
            const tanggalLahirInput = document.getElementById('tanggal_lahir');
            const jenisKelaminRadios = document.querySelectorAll('input[name="jenis_kelamin"]');
            
            if (nikInput && tanggalLahirInput) {
                nikInput.addEventListener('input', validateNIK);
                nikInput.addEventListener('change', validateNIK);
                
                // Juga validasi ulang ketika tanggal lahir berubah
                tanggalLahirInput.addEventListener('change', validateNIK);
                
                // Juga validasi ulang ketika jenis kelamin berubah
                jenisKelaminRadios.forEach(radio => {
                    radio.addEventListener('change', validateNIK);
                });
                
                // Validasi awal saat loading
                validateNIK();
            }
            
            // Setup Email validation
            const emailInput = document.getElementById('email');
            const emailLembagaInput = document.getElementById('email_lembaga');
            
            if (emailInput) {
                emailInput.addEventListener('input', function() { validateEmail('email'); });
                emailInput.addEventListener('change', function() { validateEmail('email'); });
                validateEmail('email');
            }
            
            if (emailLembagaInput) {
                emailLembagaInput.addEventListener('input', function() { validateEmail('email_lembaga'); });
                emailLembagaInput.addEventListener('change', function() { validateEmail('email_lembaga'); });
                validateEmail('email_lembaga');
            }
        });
    </script>
</body>

</html>