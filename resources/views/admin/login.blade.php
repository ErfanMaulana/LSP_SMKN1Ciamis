<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - LSP SMKN 1 Ciamis</title>
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
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 860px;
            width: 100%;
            min-height: 480px;
        }

        .left-panel {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: #0F172A url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=60') center/cover no-repeat;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(10, 15, 30, 0.72);
            z-index: 0;
        }

        .left-panel h1 {
            color: white;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .left-panel p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .right-panel {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .logo i {
            font-size: 32px;
            color: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            color: #1a2332;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .password-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .forgot-password {
            color: #0061A5;
            font-size: 12px;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #0061A5;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: #f9fafb;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0061A5;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 97, 165, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #0061A5;
        }

        .remember-me label {
            color: #6b7280;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #0F172A;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.5);
            background: #1e293b;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .left-panel {
                padding: 40px 30px;
                min-height: 200px;
            }

            .left-panel h1 {
                font-size: 28px;
            }

            .left-panel p {
                font-size: 14px;
            }

            .right-panel {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="left-panel">
            <h1>Welcome</h1>
            <p>LSP SMKN 1 Ciamis provides competency assessment and certification aligned with national and industry standards.</p>
        </div>

        <div class="right-panel">
            <div class="logo">
                <img src="{{ asset('images/lsp.png') }}" alt="LSP Logo" style="width:80px;height:80px;object-fit:contain;display:block;" />
            </div>

            <div class="login-header">
                <h2>Login</h2>
                <p>Enter your username and password to continue.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label for="username">USERNAME</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person-fill input-icon"></i>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            required 
                            autofocus
                            placeholder="Enter your username"
                        >
                    </div>
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="password-wrapper">
                        <label for="password">PASSWORD</label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Enter your password"
                        >
                        <button type="button" class="toggle-password" id="togglePassword" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const icon = toggleButton.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        }
    </script>
</body>
</html>
