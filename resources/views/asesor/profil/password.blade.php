@extends('asesor.layout')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@section('content')
<div class="password-page">
    <div class="page-header">
        <h2>Ubah Password</h2>
        <p>Gunakan password yang kuat untuk menjaga keamanan akun asesor Anda.</p>
    </div>

    <div class="card">
        <form action="{{ route('asesor.password.update') }}" method="POST" class="password-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="password_lama">Password Lama</label>
                <div class="input-wrap">
                    <i class="bi bi-lock"></i>
                    <input id="password_lama" type="password" name="password_lama" class="form-input @error('password_lama') input-error @enderror" required>
                    <button type="button" class="toggle-password" data-target="password_lama" aria-label="Tampilkan password lama">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password_lama')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_baru">Password Baru</label>
                <div class="input-wrap">
                    <i class="bi bi-shield-lock"></i>
                    <input id="password_baru" type="password" name="password_baru" class="form-input @error('password_baru') input-error @enderror" required>
                    <button type="button" class="toggle-password" data-target="password_baru" aria-label="Tampilkan password baru">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <p class="hint">Minimal 8 karakter dan harus berbeda dari password lama.</p>
                @error('password_baru')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
                <div class="input-wrap">
                    <i class="bi bi-shield-check"></i>
                    <input id="password_baru_confirmation" type="password" name="password_baru_confirmation" class="form-input" required>
                    <button type="button" class="toggle-password" data-target="password_baru_confirmation" aria-label="Tampilkan konfirmasi password baru">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-check2-circle"></i> Simpan Password Baru
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .password-page {
        width: 100%;
        max-width: none;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .page-header p {
        color: #64748b;
        font-size: 14px;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .password-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
    }

    .form-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 42px 10px 38px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #94a3b8;
        width: 24px;
        height: 24px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .toggle-password:hover {
        color: #475569;
    }

    .form-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.12);
    }

    .input-error {
        border-color: #ef4444;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
    }

    .hint {
        color: #64748b;
        font-size: 12px;
    }

    .btn-primary {
        border: none;
        border-radius: 8px;
        background: #0073bd;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        padding: 12px 16px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #00558e;
    }
</style>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            var targetId = button.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;

            var icon = button.querySelector('i');
            var isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            if (icon) {
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            }
        });
    });
</script>
@endsection
