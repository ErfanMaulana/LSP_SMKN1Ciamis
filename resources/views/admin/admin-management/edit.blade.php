@extends('admin.layout')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin')

@section('content')
<div class="page-header">
    <h2>Edit Admin: {{ $admin->name }}</h2>
    <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle"></i>
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.admin-management.update', $admin) }}" method="POST">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-section">
                <h3>Informasi Akun</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $admin->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <input type="text" id="username" name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $admin->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $admin->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Role</h3>
                <p style="font-size:13px;color:#64748b;margin-bottom:16px;">Pilih satu atau lebih role untuk admin ini.</p>
                @if(!empty($superAdminExists) && $superAdminExists && (empty($isCurrentAdminSuperAdmin) || !$isCurrentAdminSuperAdmin))
                    <p style="font-size:12px;color:#b45309;margin-bottom:12px;">
                        Role Super Admin sudah dipakai oleh akun lain. Sistem hanya mengizinkan 1 akun Super Admin.
                    </p>
                @endif

                <div class="role-list">
                    @foreach($roles as $role)
                    @if(
                        !empty($role->is_super_admin)
                        && !empty($superAdminExists)
                        && $superAdminExists
                        && (empty($isCurrentAdminSuperAdmin) || !$isCurrentAdminSuperAdmin)
                    )
                        @continue
                    @endif
                    <label class="role-item">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                               {{ in_array($role->id, old('roles', $adminRoleIds)) ? 'checked' : '' }}>
                        <div class="role-info">
                            <span class="role-name">{{ $role->display_name }}</span>
                            @if($role->is_super_admin)
                                <span class="super-tag"><i class="bi bi-shield-check"></i> Super Admin</span>
                            @endif
                            @if($role->description)
                                <span class="role-desc">{{ $role->description }}</span>
                            @endif
                        </div>
                        <span class="role-perm-count">{{ $role->permissions()->count() }} permissions</span>
                    </label>
                    @endforeach
                </div>

                @error('roles')
                    <div class="invalid-feedback" style="display:block;margin-top:8px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-header h2 { font-size: 24px; color: #0F172A; font-weight: 700; margin: 0; }

    .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .card-body { padding: 30px; }

    .form-section { margin-bottom: 30px; }
    .form-section h3 {
        font-size: 18px; color: #0F172A; font-weight: 600; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .form-section h3:before { content: ''; width: 4px; height: 20px; background: #0073bd; border-radius: 2px; }

    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { display: flex; flex-direction: column; margin-bottom: 20px; }
    .form-group label { font-size: 14px; font-weight: 500; color: #475569; margin-bottom: 8px; }
    .required { color: #ef4444; margin-left: 2px; }

    .form-control {
        padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 6px;
        font-size: 14px; transition: all .3s; background: #f8fafc;
    }
    .form-control:focus { outline: none; border-color: #0073bd; background: white; box-shadow: 0 0 0 3px rgba(0,115,189,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 5px; }

    .form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 25px; border-top: 2px solid #f1f5f9; }

    .btn {
        padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px;
        font-weight: 500; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; transition: all .3s;
    }
    .btn-primary { background: #0073bd; color: white; }
    .btn-primary:hover { background: #005a94; transform: translateY(-1px); }
    .btn-secondary { background: #64748b; color: white; }
    .btn-secondary:hover { background: #475569; }

    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    .role-list { display: flex; flex-direction: column; gap: 10px; }
    .role-item {
        display: flex; align-items: center; gap: 12px; padding: 14px 16px;
        border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer;
        transition: all .2s; background: #fafbfc;
    }
    .role-item:hover { border-color: #0073bd; background: #f0f7ff; }
    .role-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: #0073bd; flex-shrink: 0; }
    .role-info { flex: 1; }
    .role-name { font-weight: 600; color: #334155; font-size: 14px; }
    .role-desc { display: block; font-size: 12px; color: #94a3b8; margin-top: 2px; }
    .super-tag {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 10px; font-weight: 600; padding: 2px 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706); color: white;
        border-radius: 10px; margin-left: 6px;
    }
    .role-perm-count { font-size: 11px; color: #94a3b8; white-space: nowrap; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .card-body { padding: 20px; }
    }
</style>
@endsection
