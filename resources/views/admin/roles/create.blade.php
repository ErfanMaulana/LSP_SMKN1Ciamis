@extends('admin.layout')

@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role')

@section('content')
<div class="page-header">
    <h2>Tambah Role Baru</h2>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
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

<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-body">
            <div class="form-section">
                <h3>Informasi Role</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="display_name">Nama Role <span class="required">*</span></label>
                        <input type="text" id="display_name" name="display_name"
                            class="form-control @error('display_name') is-invalid @enderror"
                            value="{{ old('display_name') }}" placeholder="cth: Operator" required autofocus>
                        @error('display_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Slug <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="cth: operator" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Huruf kecil, angka, tanda hubung. Harus unik.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" rows="2"
                        placeholder="Deskripsi singkat tentang role ini...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="form-section">
                <h3>Permissions</h3>
                <p style="font-size:13px;color:#64748b;margin-bottom:20px;">Centang permission yang akan diberikan ke role ini.</p>

                <div class="permission-groups">
                    @foreach($permissions as $group => $perms)
                    <div class="permission-group">
                        <div class="permission-group-header">
                            <label class="group-toggle">
                                <input type="checkbox" class="group-checkbox" data-group="{{ Str::slug($group) }}"
                                    onchange="toggleGroup(this)">
                                <span>{{ $group }}</span>
                                <small>({{ $perms->count() }})</small>
                            </label>
                        </div>
                        <div class="permission-group-body">
                            @foreach($perms as $perm)
                            <label class="permission-item">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                       class="perm-checkbox group-{{ Str::slug($group) }}"
                                       {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}
                                       onchange="updateGroupCheckbox('{{ Str::slug($group) }}')">
                                <span>{{ $perm->display_name }}</span>
                                <code>{{ $perm->name }}</code>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>

<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;
    }
    .page-header h2 { font-size: 24px; color: #0F172A; font-weight: 700; margin: 0; }

    .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .card-body { padding: 30px; }

    .form-section { margin-bottom: 30px; }
    .form-section h3 {
        font-size: 18px; color: #0F172A; font-weight: 600; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .form-section h3:before {
        content: ''; width: 4px; height: 20px; background: #0073bd; border-radius: 2px;
    }

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
    .form-text { font-size: 12px; color: #64748b; margin-top: 5px; }

    .form-actions {
        display: flex; gap: 12px; margin-top: 30px; padding-top: 25px; border-top: 2px solid #f1f5f9;
    }

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

    /* Permission Groups */
    .permission-groups { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }

    .permission-group {
        border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: #fafbfc;
    }
    .permission-group-header {
        padding: 12px 16px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
    }
    .group-toggle {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        font-size: 14px; font-weight: 600; color: #334155;
    }
    .group-toggle small { color: #94a3b8; font-weight: 400; }

    .permission-group-body { padding: 12px 16px; display: flex; flex-direction: column; gap: 8px; }

    .permission-item {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        font-size: 13px; color: #475569; padding: 4px 0;
    }
    .permission-item code {
        font-size: 10px; background: #e2e8f0; padding: 1px 6px;
        border-radius: 3px; color: #64748b; margin-left: auto;
    }

    input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: #0073bd; cursor: pointer; flex-shrink: 0;
    }

    textarea.form-control { resize: vertical; font-family: inherit; }

    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .card-body { padding: 20px; }
        .permission-groups { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('scripts')
<script>
    // Auto-generate slug from display_name
    document.getElementById('display_name').addEventListener('input', function() {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        document.getElementById('name').value = slug;
    });

    function toggleGroup(checkbox) {
        const group = checkbox.dataset.group;
        const items = document.querySelectorAll('.perm-checkbox.group-' + group);
        items.forEach(item => item.checked = checkbox.checked);
    }

    function updateGroupCheckbox(group) {
        const items = document.querySelectorAll('.perm-checkbox.group-' + group);
        const groupCb = document.querySelector('.group-checkbox[data-group="' + group + '"]');
        const allChecked = [...items].every(i => i.checked);
        const someChecked = [...items].some(i => i.checked);
        groupCb.checked = allChecked;
        groupCb.indeterminate = someChecked && !allChecked;
    }

    // Init group checkboxes on page load
    document.querySelectorAll('.group-checkbox').forEach(cb => updateGroupCheckbox(cb.dataset.group));
</script>
@endsection
