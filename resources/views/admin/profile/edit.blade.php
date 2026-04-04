@extends('admin.layout')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Admin')

@section('content')
@php
    $roleNames = $admin->roles->pluck('display_name')->filter()->values();
@endphp

<div class="profile-shell">
    <div class="profile-hero">
        <div class="hero-badge">Account Center</div>
        <h2>Profil Admin</h2>
        <p class="subtitle">Kelola informasi akun dan keamanan login Anda dalam satu panel terpadu.</p>
    </div>

    <div class="grid-wrap">
        <div class="card glass-card">
            <div class="card-header">
                <h3><i class="bi bi-person"></i> Informasi Akun</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="avatar-section">
                        <div class="avatar-preview-wrap">
                            @if($admin->foto_profil)
                                <img src="{{ asset('storage/' . $admin->foto_profil) }}" alt="Foto profil admin" class="avatar-preview-image" id="avatarPreviewImage">
                            @else
                                <div class="avatar-preview-placeholder" id="avatarPreviewPlaceholder">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <img src="" alt="Foto profil admin" class="avatar-preview-image" id="avatarPreviewImage" style="display:none;">
                            @endif
                            @if($admin->foto_profil)
                                <div class="avatar-preview-placeholder" id="avatarPreviewPlaceholder" style="display:none;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="avatar-input-wrap">
                            <label for="foto_profil">Foto Profil</label>
                            <input
                                type="file"
                                id="foto_profil"
                                name="foto_profil"
                                class="form-control @error('foto_profil') is-invalid @enderror"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                            >
                            <small class="help-text">Opsional. Format JPG/PNG/WebP, maksimal 5MB.</small>
                            @if($admin->foto_profil)
                                <label class="remove-photo-check">
                                    <input type="checkbox" name="remove_foto_profil" value="1" {{ old('remove_foto_profil') ? 'checked' : '' }}>
                                    Hapus foto profil saat ini
                                </label>
                            @endif
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama <span class="required">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $admin->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $admin->username) }}"
                            required
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $admin->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input
                            type="text"
                            class="form-control"
                            value="{{ $roleNames->isNotEmpty() ? $roleNames->join(', ') : 'Administrator' }}"
                            readonly
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card glass-card secure-card">
            <div class="card-header">
                <h3><i class="bi bi-shield-lock"></i> Ubah Password</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Password Saat Ini <span class="required">*</span></label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required
                        >
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru <span class="required">*</span></label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru <span class="required">*</span></label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-shell {
        background:
            radial-gradient(circle at 0% 0%, rgba(0, 97, 165, 0.08), transparent 40%),
            radial-gradient(circle at 100% 100%, rgba(14, 116, 144, 0.08), transparent 45%);
        border-radius: 18px;
        padding: 20px;
    }

    .profile-hero {
        margin-bottom: 20px;
        padding: 18px 20px;
        border-radius: 16px;
        background: linear-gradient(130deg, #0061a5 0%, #0e7490 100%);
        color: #fff;
        box-shadow: 0 14px 26px rgba(2, 132, 199, 0.18);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.2);
        color: #e0f2fe;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .profile-hero h2 {
        font-size: 30px;
        line-height: 1.15;
        font-weight: 700;
    }

    .subtitle {
        font-size: 14px;
        color: #dbeafe;
        margin-top: 6px;
        max-width: 580px;
    }

    .grid-wrap {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .card {
        border-radius: 16px;
        overflow: hidden;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.86);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(4px);
    }

    .secure-card {
        position: relative;
    }

    .secure-card::before {
        content: '';
        position: absolute;
        inset: 0 auto auto 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0ea5e9, #14b8a6);
    }

    .card-header {
        padding: 16px 18px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #f8fbff 0%, #f8fafc 100%);
    }

    .card-header h3 {
        font-size: 18px;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 18px;
    }

    .avatar-section {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
        padding: 14px;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: linear-gradient(180deg, #f0f9ff 0%, #f8fafc 100%);
    }

    .avatar-preview-wrap {
        flex-shrink: 0;
    }

    .avatar-preview-image,
    .avatar-preview-placeholder {
        width: 74px;
        height: 74px;
        border-radius: 50%;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-preview-image {
        border: 2px solid #e2e8f0;
        background: #fff;
    }

    .avatar-preview-placeholder {
        background: linear-gradient(145deg, #0061a5 0%, #0e7490 100%);
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        box-shadow: 0 10px 18px rgba(2, 132, 199, 0.24);
    }

    .avatar-input-wrap {
        flex: 1;
        min-width: 0;
    }

    .help-text {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 12px;
    }

    .remove-photo-check {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        color: #475569;
        font-size: 12px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        color: #334155;
        font-weight: 600;
    }

    .required { color: #ef4444; }

    .form-control {
        width: 100%;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
        font-family: inherit;
        color: #0f172a;
        background: #fff;
        min-height: 46px;
        transition: all .2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #0061a5;
        box-shadow: 0 0 0 4px rgba(0, 97, 165, 0.14);
        transform: translateY(-1px);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        margin-top: 5px;
        color: #ef4444;
        font-size: 12px;
    }

    .form-actions {
        margin-top: 18px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        border-radius: 10px;
        padding: 11px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: transform .15s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0061a5 0%, #0e7490 100%);
        color: #fff;
        box-shadow: 0 8px 16px rgba(2, 132, 199, 0.22);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.28);
    }

    @media (max-width: 900px) {
        .profile-shell {
            padding: 14px;
            border-radius: 14px;
        }

        .profile-hero {
            padding: 14px;
            margin-bottom: 14px;
        }

        .profile-hero h2 {
            font-size: 24px;
        }

        .grid-wrap {
            grid-template-columns: 1fr;
        }

        .avatar-section {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    (function () {
        const input = document.getElementById('foto_profil');
        const previewImage = document.getElementById('avatarPreviewImage');
        const previewPlaceholder = document.getElementById('avatarPreviewPlaceholder');
        const removeCheckbox = document.querySelector('input[name="remove_foto_profil"]');

        if (!input || !previewImage || !previewPlaceholder) {
            return;
        }

        input.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewPlaceholder.style.display = 'none';

                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }
            };

            reader.readAsDataURL(file);
        });

        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    previewImage.style.display = 'none';
                    previewPlaceholder.style.display = 'flex';
                }
            });
        }
    })();
</script>
@endsection
