@extends('asesi.layout')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('styles')
<style>
    .profile-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 24px;
    }

    .profile-tab {
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        font-family: inherit;
    }

    .profile-tab:hover { color: #14532d; }

    .profile-tab.active {
        color: #14532d;
        border-bottom-color: #14532d;
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-icon {
        width: 40px;
        height: 40px;
        background: #d1fae5;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #14532d;
        font-size: 18px;
        flex-shrink: 0;
    }

    .card-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }

    .card-header p {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .card-body { padding: 24px; }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-grid .full-span { grid-column: 1 / -1; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-group label .required { color: #ef4444; margin-left: 2px; }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        color: #1a2332;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #14532d;
        background: white;
        box-shadow: 0 0 0 3px rgba(20, 83, 45, 0.1);
    }

    .form-control::placeholder { color: #9ca3af; }

    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
    }

    select.form-control { cursor: pointer; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 36px;
    }

    .input-icon-wrap { position: relative; }
    .input-icon-wrap .bi {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        pointer-events: none;
    }
    .input-icon-wrap .form-control { padding-left: 36px; }

    .section-divider {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 8px 0 12px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 16px;
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-divider::before {
        content: '';
        display: inline-block;
        width: 3px;
        height: 14px;
        background: #14532d;
        border-radius: 2px;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        margin-top: 24px;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #14532d;
        color: white;
        box-shadow: 0 2px 8px rgba(20, 83, 45, 0.3);
    }

    .btn-primary:hover { background: #166534; transform: translateY(-1px); }

    .btn-outline {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover { background: #f3f4f6; }

    /* Password strength */
    .password-strength {
        height: 4px;
        border-radius: 2px;
        background: #e5e7eb;
        margin-top: 8px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        border-radius: 2px;
        transition: all 0.3s;
        width: 0;
    }

    .strength-text {
        font-size: 11px;
        margin-top: 4px;
    }

    .toggle-pw {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        font-size: 16px;
    }

    .toggle-pw:hover { color: #374151; }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        font-size: 13px;
        color: #14532d;
        margin-bottom: 20px;
    }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .full-span { grid-column: 1; }
        .section-divider { grid-column: 1; }
        .profile-tab span { display: none; }
    }
</style>
@endsection

@section('content')
<div class="profile-tabs">
    <button class="profile-tab {{ $tab === 'password' ? '' : 'active' }}" onclick="switchTab('profil', this)">
        <i class="bi bi-person-circle"></i>
        <span>Data Pribadi</span>
    </button>
    <button class="profile-tab {{ $tab === 'password' ? 'active' : '' }}" onclick="switchTab('password', this)">
        <i class="bi bi-key"></i>
        <span>Ubah Password</span>
    </button>
</div>

{{-- ─────────────────── TAB PROFIL ─────────────────── --}}
<div id="tab-profil" class="tab-content {{ $tab === 'password' ? '' : 'active' }}">
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-person-vcard"></i></div>
            <div>
                <h3>Data Pribadi</h3>
                <p>Perbarui informasi profil yang tercatat di LSP</p>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->hasBag('default') && !session('tab'))
                <div class="info-badge" style="background:#fef2f2;border-color:#fca5a5;color:#991b1b;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Terdapat kesalahan pada form. Silakan periksa kembali.
                </div>
            @endif

            <form method="POST" action="{{ route('asesi.profil.update') }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    {{-- Identitas --}}
                    <div class="section-divider">Identitas Diri</div>

                    <div class="form-group full-span">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                value="{{ old('nama', $asesi->nama ?? '') }}" placeholder="Nama lengkap sesuai KTP" required>
                        </div>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old('email', $asesi->email ?? '') }}" placeholder="email@example.com">
                        </div>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>No. HP</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-phone-fill"></i>
                            <input type="text" name="telepon_hp" class="form-control {{ $errors->has('telepon_hp') ? 'is-invalid' : '' }}"
                                value="{{ old('telepon_hp', $asesi->telepon_hp ?? '') }}" placeholder="08xxxxxxxxxx">
                        </div>
                        @error('telepon_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-geo-alt-fill"></i>
                            <input type="text" name="tempat_lahir" class="form-control"
                                value="{{ old('tempat_lahir', $asesi->tempat_lahir ?? '') }}" placeholder="Kota kelahiran">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-calendar-fill"></i>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                value="{{ old('tanggal_lahir', $asesi->tanggal_lahir ? $asesi->tanggal_lahir->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $asesi->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $asesi->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group full-span">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"
                            placeholder="Alamat lengkap">{{ old('alamat', $asesi->alamat ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control"
                            value="{{ old('kode_pos', $asesi->kode_pos ?? '') }}" placeholder="Kode pos">
                    </div>

                    <div class="form-group">
                        <label>No. Telepon Rumah</label>
                        <input type="text" name="telepon_rumah" class="form-control"
                            value="{{ old('telepon_rumah', $asesi->telepon_rumah ?? '') }}" placeholder="02x-xxxxxxx">
                    </div>

                    {{-- Pekerjaan --}}
                    <div class="section-divider">Informasi Pekerjaan</div>

                    <div class="form-group">
                        <label>Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','S1','S2','S3'] as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_terakhir', $asesi->pendidikan_terakhir ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            value="{{ old('pekerjaan', $asesi->pekerjaan ?? '') }}" placeholder="Jabatan / profesi">
                    </div>

                    <div class="form-group">
                        <label>Nama Lembaga / Perusahaan</label>
                        <input type="text" name="nama_lembaga" class="form-control"
                            value="{{ old('nama_lembaga', $asesi->nama_lembaga ?? '') }}" placeholder="Nama institusi">
                    </div>

                    <div class="form-group">
                        <label>Jabatan di Lembaga</label>
                        <input type="text" name="jabatan" class="form-control"
                            value="{{ old('jabatan', $asesi->jabatan ?? '') }}" placeholder="Jabatan">
                    </div>

                    <div class="form-group full-span">
                        <label>Alamat Lembaga</label>
                        <textarea name="alamat_lembaga" class="form-control" rows="2"
                            placeholder="Alamat lembaga / perusahaan">{{ old('alamat_lembaga', $asesi->alamat_lembaga ?? '') }}</textarea>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="reset" class="btn btn-outline">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─────────────────── TAB PASSWORD ─────────────────── --}}
<div id="tab-password" class="tab-content {{ $tab === 'password' ? 'active' : '' }}">
    <div class="card" style="max-width:520px;">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-shield-lock-fill"></i></div>
            <div>
                <h3>Ubah Password</h3>
                <p>Gunakan password yang kuat dan tidak mudah ditebak</p>
            </div>
        </div>
        <div class="card-body">
            <div class="info-badge">
                <i class="bi bi-info-circle-fill"></i>
                No. Registrasi: <strong>{{ $account->no_reg }}</strong>
            </div>

            <form method="POST" action="{{ route('asesi.profil.password') }}">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Password Saat Ini <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password saat ini" style="padding-right: 40px;">
                        <button type="button" class="toggle-pw" onclick="togglePw('current_password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom:8px;">
                    <label>Password Baru <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-key-fill"></i>
                        <input type="password" id="new_password" name="password"
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Minimal 8 karakter" style="padding-right: 40px;"
                            oninput="checkStrength(this.value)">
                        <button type="button" class="toggle-pw" onclick="togglePw('new_password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText" style="color:#9ca3af;"></div>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label>Konfirmasi Password Baru <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-key-fill"></i>
                        <input type="password" id="confirm_password" name="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password baru" style="padding-right: 40px;">
                        <button type="button" class="toggle-pw" onclick="togglePw('confirm_password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-check"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tab, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.profile-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        btn.classList.add('active');
    }

    function togglePw(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash-fill';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye-fill';
        }
    }

    function checkStrength(val) {
        const bar  = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        let score  = 0;
        if (val.length >= 8)   score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '#e5e7eb', label: '' },
            { pct: '25%',  color: '#ef4444', label: 'Sangat lemah' },
            { pct: '50%',  color: '#f97316', label: 'Lemah' },
            { pct: '75%',  color: '#eab308', label: 'Cukup kuat' },
            { pct: '100%', color: '#22c55e', label: 'Kuat' },
        ];

        bar.style.width       = levels[score].pct;
        bar.style.background  = levels[score].color;
        text.style.color      = levels[score].color;
        text.textContent      = levels[score].label;
    }
</script>
@endsection
