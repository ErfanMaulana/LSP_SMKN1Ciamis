@extends('asesi.layout')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('styles')
<style>
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

    .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }

    .form-group:last-of-type { margin-bottom: 0; }

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

    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
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

    .form-footer {
        display: flex;
        justify-content: flex-end;
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
</style>
@endsection

@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <div>
            <h3>Change Password</h3>
            <p>Use a strong password that is hard to guess</p>
        </div>
    </div>
    <div class="card-body">
        <div class="info-badge">
            <i class="bi bi-info-circle-fill"></i>
            Registration No: <strong>{{ $account->id }}</strong>
        </div>

        <form method="POST" action="{{ route('asesi.profil.password') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Current Password <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" id="current_password" name="current_password"
                        class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                        placeholder="Enter your current password" style="padding-right: 40px;">
                    <button type="button" class="toggle-pw" onclick="togglePw('current_password', this)">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>New Password <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="bi bi-key-fill"></i>
                    <input type="password" id="new_password" name="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Minimum 8 characters" style="padding-right: 40px;"
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

            <div class="form-group">
                <label>Confirm New Password <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <i class="bi bi-key-fill"></i>
                    <input type="password" id="confirm_password" name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter new password" style="padding-right: 40px;">
                    <button type="button" class="toggle-pw" onclick="togglePw('confirm_password', this)">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-shield-check"></i> Save Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
        if (val.length >= 8)               score++;
        if (/[A-Z]/.test(val))             score++;
        if (/[0-9]/.test(val))             score++;
        if (/[^A-Za-z0-9]/.test(val))      score++;

        const levels = [
            { pct: '0%',   color: '#e5e7eb', label: '' },
            { pct: '25%',  color: '#ef4444', label: 'Very weak' },
            { pct: '50%',  color: '#f97316', label: 'Weak' },
            { pct: '75%',  color: '#eab308', label: 'Fair' },
            { pct: '100%', color: '#22c55e', label: 'Strong' },
        ];

        bar.style.width      = levels[score].pct;
        bar.style.background = levels[score].color;
        text.style.color     = levels[score].color;
        text.textContent     = levels[score].label;
    }
</script>
@endsection

