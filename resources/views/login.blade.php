<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LSP SMKN 1 Ciamis</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --brand: #0061A5;
            --brand-dark: #004f87;
            --brand-shadow: rgba(0, 97, 165, 0.30);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            background: white;
            border-radius: 14px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 860px;
            width: 100%;
            height: auto;
            max-height: calc(100vh - 32px);
        }

        /* ── Left panel ─────────────────────────────────────── */
        .left-panel {
            flex: 1;
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: var(--brand);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%,100% { transform: scale(1); }
            50%      { transform: scale(1.1); }
        }

        .left-panel h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .left-panel p {
            color: rgba(255,255,255,0.85);
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* ── Right panel ────────────────────────────────────── */
        .right-panel {
            flex: 1;
            padding: 28px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* LSP logo circle */
        .logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 10px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px var(--brand-shadow);
            background: white;
            border: 2px solid #e5e7eb;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-header { text-align: center; margin-bottom: 16px; }
        .login-header h2 { color: #1a2332; font-size: 22px; font-weight: 700; margin-bottom: 3px; }
        .login-header p  { color: #6b7280; font-size: 12px; }

        /* ── Form elements ──────────────────────────────────── */
        .form-group { margin-bottom: 12px; }

        .form-group label {
            display: block;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 17px;
            pointer-events: none;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 9px 14px 9px 38px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
            background: #f9fafb;
            font-family: inherit;
            color: #1a2332;
            appearance: none;
        }

        .form-group select { cursor: pointer; }

        .select-arrow {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 13px;
            pointer-events: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--brand);
            background: white;
            box-shadow: 0 0 0 3px var(--brand-shadow);
        }

        .form-group input::placeholder { color: #9ca3af; }

        .error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .remember-me input[type="checkbox"] {
            width: 15px; height: 15px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: var(--brand);
        }

        .remember-me label {
            color: #6b7280;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, filter 0.2s;
            background: var(--brand);
            box-shadow: 0 4px 14px var(--brand-shadow);
        }

        .btn-login:hover:not(:disabled)  { transform: translateY(-2px); filter: brightness(1.1); }
        .btn-login:active:not(:disabled) { transform: translateY(0); }

        .form-group input:disabled,
        .form-group select:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            background: #f0f0f0;
        }

        .btn-login:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            transform: none;
            filter: none;
            box-shadow: none;
        }

        .remember-me input[type="checkbox"]:disabled + label {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .alert {
            padding: 11px 14px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .alert-success { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
        .alert-error   { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }

        .back-link { text-align:center; margin-top:10px; }
        .back-link a { color: var(--brand); font-size:13px; text-decoration:none; font-weight:500; }
        .back-link a:hover { text-decoration:underline; }

        @media (max-width: 768px) {
            body { overflow: auto; }
            .login-wrapper { flex-direction: column; max-height: none; }
            .left-panel    { padding: 28px 24px; }
            .left-panel h1 { font-size: 24px; }
            .right-panel   { padding: 24px 24px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left panel (fixed colour) -->
        <div class="left-panel">
            <h1 id="panelTitle">Selamat Datang</h1>
            <p id="panelDesc">LSP SMKN 1 Ciamis menyelenggarakan uji kompetensi dan sertifikasi sesuai standar nasional dan industri.</p>
        </div>

        <!-- Right panel -->
        <div class="right-panel">
            <!-- LSP Logo -->
            <div class="logo">
                <img src="{{ asset('images/lsp.png') }}" alt="Logo LSP SMKN 1 Ciamis">
            </div>

            <div class="login-header">
                <h2>Login</h2>
                <p>Pilih role dan masukkan kredensial Anda.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error) {{ $error }} @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                {{-- Role dropdown --}}
                <div class="form-group">
                    <label for="role">ROLE</label>
                    <div class="input-wrapper">
                        <i class="bi bi-people-fill input-icon"></i>
                        <select id="role" name="role" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                            <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                            <option value="asesi"  {{ old('role') === 'asesi'  ? 'selected' : '' }}>Asesi</option>
                            <option value="asesor" {{ old('role') === 'asesor' ? 'selected' : '' }}>Asesor</option>
                        </select>
                        <i class="bi bi-chevron-down select-arrow"></i>
                    </div>
                    @error('role')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Identifier (Username / No Reg) --}}
                <div class="form-group">
                    <label for="identifier" id="identifierLabel">IDENTIFIER</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person-fill input-icon" id="identifierIcon"></i>
                        <input
                            type="text"
                            id="identifier"
                            name="identifier"
                            value="{{ old('identifier') }}"
                            required
                            autofocus
                            placeholder="Masukkan identifier"
                            {{ old('role') ? '' : 'disabled' }}
                        >
                    </div>
                    @error('identifier')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Masukkan password"
                            {{ old('role') ? '' : 'disabled' }}
                        >
                    </div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" {{ old('role') ? '' : 'disabled' }}>
                    <label for="remember">Ingat saya selama 30 hari</label>
                </div>

                <button type="submit" class="btn-login" id="btnLogin" {{ old('role') ? '' : 'disabled' }}>Login</button>
            </form>

            <div class="back-link">
                <a href="{{ url('/') }}"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <script>
        const roleConfig = {
            admin: {
                panelTitle:            'Admin Panel',
                panelDesc:             'Kelola seluruh data LSP SMKN 1 Ciamis melalui panel administrator.',
                identifierLabel:       'USERNAME',
                identifierIcon:        'bi-person-fill',
                identifierPlaceholder: 'Masukkan username',
            },
            asesi: {
                panelTitle:            'Portal Asesi',
                panelDesc:             'Akses portal uji kompetensi dan kelola berkas pendaftaran Anda.',
                identifierLabel:       'NOMOR REGISTRASI',
                identifierIcon:        'bi-card-text',
                identifierPlaceholder: 'Masukkan nomor registrasi',
            },
            asesor: {
                panelTitle:            'Portal Asesor',
                panelDesc:             'Kelola penilaian dan hasil uji kompetensi peserta.',
                identifierLabel:       'NOMOR REGISTRASI',
                identifierIcon:        'bi-card-text',
                identifierPlaceholder: 'Masukkan nomor registrasi',
            },
        };

        const roleSelect      = document.getElementById('role');
        const panelTitle      = document.getElementById('panelTitle');
        const panelDesc       = document.getElementById('panelDesc');
        const identifierLabel = document.getElementById('identifierLabel');
        const identifierIcon  = document.getElementById('identifierIcon');
        const identifierInput = document.getElementById('identifier');
        const passwordInput   = document.getElementById('password');
        const rememberInput   = document.getElementById('remember');
        const btnLogin        = document.getElementById('btnLogin');

        function setFieldsDisabled(disabled) {
            identifierInput.disabled = disabled;
            passwordInput.disabled   = disabled;
            rememberInput.disabled   = disabled;
            btnLogin.disabled        = disabled;
        }

        function applyRole(role) {
            const cfg = roleConfig[role];
            if (!cfg) {
                setFieldsDisabled(true);
                return;
            }

            // Enable semua field
            setFieldsDisabled(false);

            // Teks panel kiri
            panelTitle.textContent = cfg.panelTitle;
            panelDesc.textContent  = cfg.panelDesc;

            // Label & icon field identifier
            identifierLabel.textContent = cfg.identifierLabel;
            identifierIcon.className    = `bi ${cfg.identifierIcon} input-icon`;
            identifierInput.placeholder = cfg.identifierPlaceholder;
        }

        roleSelect.addEventListener('change', () => applyRole(roleSelect.value));

        // Terapkan jika ada nilai lama (validasi gagal)
        const oldRole = '{{ old("role") }}';
        if (oldRole && roleConfig[oldRole]) applyRole(oldRole);
    </script>
</body>
</html>
