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

    {{-- Tanda Tangan Tersimpan --}}
    <div class="card glass-card signature-manage-card" style="margin-top:18px;">
        <div class="card-header">
            <h3><i class="bi bi-pen"></i> Tanda Tangan Tersimpan</h3>
        </div>
        <div class="card-body">
            <p style="font-size:13px;color:#64748b;margin-bottom:16px;">
                Simpan tanda tangan Anda agar dapat digunakan kembali dengan cepat pada setiap form yang memerlukan tanda tangan admin.
            </p>

            @if($admin->tanda_tangan)
                <div id="savedSignatureSection">
                    <p style="font-size:13px;font-weight:600;color:#334155;margin-bottom:10px;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Tanda tangan sudah tersimpan</p>
                    <div style="border:1px solid #e5e7eb;border-radius:10px;background:#fafafa;padding:12px;display:inline-block;margin-bottom:14px;">
                        <img src="{{ $admin->tanda_tangan }}" id="savedSignatureImg" alt="Tanda Tangan Tersimpan" style="max-width:220px;height:auto;display:block;">
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button type="button" class="btn btn-outline-danger" id="btnGantiTTD" onclick="toggleReplaceMode()">
                            <i class="bi bi-arrow-repeat"></i> Ganti Tanda Tangan
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnHapusTTD" onclick="hapusTandaTangan()">
                            <i class="bi bi-trash"></i> Hapus Tanda Tangan
                        </button>
                    </div>
                </div>
                <div id="replaceSignatureSection" style="display:none;margin-top:16px;">
                    <p style="font-size:13px;font-weight:600;color:#334155;margin-bottom:8px;">Gambar tanda tangan baru:</p>
                    @include('admin.profile._signature_canvas_widget')
                    <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;">
                        <button type="button" class="btn btn-primary" id="btnSimpanGantiTTD" onclick="simpanTandaTangan()">
                            <i class="bi bi-save"></i> Simpan Tanda Tangan Baru
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="cancelReplaceMode()">
                            <i class="bi bi-x"></i> Batal
                        </button>
                    </div>
                </div>
            @else
                <div id="newSignatureSection">
                    <p style="font-size:13px;font-weight:600;color:#334155;margin-bottom:8px;">Gambar tanda tangan Anda:</p>
                    @include('admin.profile._signature_canvas_widget')
                    <div style="margin-top:14px;">
                        <button type="button" class="btn btn-primary" id="btnSimpanTTD" onclick="simpanTandaTangan()">
                            <i class="bi bi-save"></i> Simpan Tanda Tangan
                        </button>
                    </div>
                </div>
            @endif

            <div id="ttdStatusMsg" style="display:none;margin-top:12px;padding:10px 14px;border-radius:8px;font-size:13px;"></div>
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

    .btn-outline-danger {
        background: #fff;
        color: #dc2626;
        border: 1.5px solid #fca5a5;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-outline-danger:hover { background: #fee2e2; border-color: #ef4444; }

    .btn-outline-secondary {
        background: #fff;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-outline-secondary:hover { background: #f1f5f9; }

    /* Signature canvas widget */
    .sig-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        max-width: 320px;
        height: 160px;
        transition: border-color .2s;
        cursor: crosshair;
    }
    .sig-canvas-wrapper.has-sig {
        border-style: solid;
        border-color: #0061a5;
    }
    .sig-canvas-wrapper canvas {
        width: 100%;
        height: 100%;
        display: block;
    }
    .sig-canvas-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        pointer-events: none;
        gap: 6px;
        transition: opacity .2s;
    }
    .sig-canvas-placeholder i { font-size: 24px; }
    .sig-canvas-placeholder span { font-size: 12px; }
    .sig-canvas-wrapper.has-sig .sig-canvas-placeholder { opacity: 0; }
    .sig-canvas-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }
    .btn-clear-sig {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 12px;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all .2s;
    }
    .btn-clear-sig:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }

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

        if (input && previewImage && previewPlaceholder) {
            input.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    previewPlaceholder.style.display = 'none';
                    if (removeCheckbox) removeCheckbox.checked = false;
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
        }
    })();

    // ─── Tanda Tangan Admin ───────────────────────────
    const _saveUrl   = @json(route('admin.profile.save-signature'));
    const _deleteUrl = @json(route('admin.profile.delete-signature'));
    const _csrf      = @json(csrf_token());

    let _profileSigHas = false;

    function initProfileSigPad() {
        const wrapper = document.getElementById('profileSigWrapper');
        const canvas  = document.getElementById('profileSigCanvas');
        if (!wrapper || !canvas) return;

        const ctx = canvas.getContext('2d');
        let drawing = false;
        let pts = [];

        const resize = () => {
            const r = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width  = rect.width  * r;
            canvas.height = rect.height * r;
            ctx.setTransform(r, 0, 0, r, 0, 0);
            ctx.lineCap = 'round'; ctx.lineJoin = 'round';
            ctx.lineWidth = 2.5; ctx.strokeStyle = '#111827';
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            _profileSigHas = false;
            wrapper.classList.remove('has-sig');
        };

        const pt = e => { const r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; };

        canvas.addEventListener('pointerdown', e => { drawing = true; pts = [pt(e)]; canvas.setPointerCapture?.(e.pointerId); });
        canvas.addEventListener('pointermove', e => {
            if (!drawing) return;
            const p = pt(e); pts.push(p);
            if (pts.length < 2) return;
            const prev = pts[pts.length - 2];
            ctx.beginPath(); ctx.moveTo(prev.x, prev.y); ctx.lineTo(p.x, p.y); ctx.stroke();
            if (!_profileSigHas) { _profileSigHas = true; wrapper.classList.add('has-sig'); }
        });
        canvas.addEventListener('pointerup',    () => { drawing = false; pts = []; });
        canvas.addEventListener('pointerleave', () => { drawing = false; pts = []; });

        document.getElementById('profileSigClear')?.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            _profileSigHas = false;
            wrapper.classList.remove('has-sig');
        });

        window.addEventListener('resize', resize);
        resize();
    }

    function simpanTandaTangan() {
        const canvas = document.getElementById('profileSigCanvas');
        if (!canvas || !_profileSigHas) {
            showTTDStatus('Silakan gambar tanda tangan terlebih dahulu.', 'error');
            return;
        }
        const dataUrl = canvas.toDataURL('image/png');
        fetch(_saveUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ tanda_tangan: dataUrl }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showTTDStatus('Tanda tangan berhasil disimpan!', 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showTTDStatus(data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(() => showTTDStatus('Terjadi kesalahan jaringan.', 'error'));
    }

    function hapusTandaTangan() {
        if (!confirm('Yakin ingin menghapus tanda tangan tersimpan?')) return;
        fetch(_deleteUrl, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': _csrf, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showTTDStatus('Tanda tangan dihapus.', 'success');
                setTimeout(() => location.reload(), 900);
            } else {
                showTTDStatus(data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(() => showTTDStatus('Terjadi kesalahan jaringan.', 'error'));
    }

    function toggleReplaceMode() {
        document.getElementById('replaceSignatureSection').style.display = 'block';
        document.getElementById('savedSignatureSection').querySelector('#btnGantiTTD').style.display = 'none';
        document.getElementById('savedSignatureSection').querySelector('#btnHapusTTD').style.display = 'none';
        setTimeout(() => initProfileSigPad(), 50);
    }

    function cancelReplaceMode() {
        document.getElementById('replaceSignatureSection').style.display = 'none';
        document.getElementById('savedSignatureSection').querySelector('#btnGantiTTD').style.display = '';
        document.getElementById('savedSignatureSection').querySelector('#btnHapusTTD').style.display = '';
    }

    function showTTDStatus(msg, type) {
        const el = document.getElementById('ttdStatusMsg');
        if (!el) return;
        el.textContent = msg;
        el.style.display = 'block';
        if (type === 'success') {
            el.style.background = '#f0fdf4'; el.style.color = '#166534'; el.style.border = '1px solid #bbf7d0';
        } else {
            el.style.background = '#fef2f2'; el.style.color = '#991b1b'; el.style.border = '1px solid #fecaca';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Init canvas only for new signature section (no existing TTD)
        const newSec = document.getElementById('newSignatureSection');
        if (newSec) initProfileSigPad();
    });
</script>
@endsection
