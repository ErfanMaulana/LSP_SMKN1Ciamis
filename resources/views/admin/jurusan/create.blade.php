@extends('admin.layout')

@section('title', 'Tambah Jurusan')
@section('page-title', 'Tambah Jurusan')

@section('content')
<div class="page-header">
    <h2>Tambah Jurusan Baru</h2>
    <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.jurusan.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Informasi Jurusan</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan <span class="required">*</span></label>
                        <input type="text" id="nama_jurusan" name="nama_jurusan"
                            class="form-control @error('nama_jurusan') is-invalid @enderror"
                            value="{{ old('nama_jurusan') }}"
                            placeholder="cth: Rekayasa Perangkat Lunak" required autofocus>
                        @error('nama_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan <span class="required">*</span></label>
                        <input type="text" id="kode_jurusan" name="kode_jurusan"
                            class="form-control @error('kode_jurusan') is-invalid @enderror"
                            value="{{ old('kode_jurusan') }}"
                            placeholder="cth: RPL" maxlength="10" required>
                        @error('kode_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Maksimal 10 karakter, harus unik.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="visi">Visi</label>
                    <textarea id="visi" name="visi" class="form-control @error('visi') is-invalid @enderror"
                        rows="3" placeholder="Tuliskan visi jurusan ini...">{{ old('visi') }}</textarea>
                    @error('visi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="misi">Misi</label>
                    <textarea id="misi" name="misi" class="form-control @error('misi') is-invalid @enderror"
                        rows="4" placeholder="Tuliskan misi jurusan ini...">{{ old('misi') }}</textarea>
                    @error('misi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:8px;">
                        <label style="margin-bottom:0;">Data Kelas (One-to-Many)</label>
                        <button type="button" id="addKelasBtn" class="btn btn-secondary btn-sm">
                            <i class="bi bi-plus-lg"></i> Tambah Kelas
                        </button>
                    </div>
                    <div id="kelasContainer" class="kelas-container">
                        @php $kelasOld = old('kelas', ['']); @endphp
                        @foreach($kelasOld as $idx => $kelasNama)
                            <div class="kelas-row">
                                <input type="text" name="kelas[]" class="form-control @error('kelas.' . $idx) is-invalid @enderror"
                                    value="{{ $kelasNama }}" placeholder="Contoh: XII RPL 1">
                                <button type="button" class="btn btn-danger btn-sm remove-kelas-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            @error('kelas.' . $idx)
                                <div class="invalid-feedback" style="display:block; margin-top:-10px; margin-bottom:10px;">{{ $message }}</div>
                            @enderror
                        @endforeach
                    </div>
                    <small class="form-text">Isi satu atau lebih kelas untuk jurusan ini. Data kosong akan diabaikan.</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3:before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #0073bd;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f1f5f9;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .btn-sm {
        padding: 8px 12px;
        font-size: 12px;
    }

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #005a94;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }

    .kelas-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .kelas-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        align-items: center;
    }

    .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .card-body {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<script>
    (function () {
        const container = document.getElementById('kelasContainer');
        const addBtn = document.getElementById('addKelasBtn');

        function bindRemove(btn) {
            btn.addEventListener('click', function () {
                const rows = container.querySelectorAll('.kelas-row');
                if (rows.length <= 1) {
                    const input = rows[0].querySelector('input[name="kelas[]"]');
                    if (input) input.value = '';
                    return;
                }
                btn.closest('.kelas-row').remove();
            });
        }

        container.querySelectorAll('.remove-kelas-btn').forEach(bindRemove);

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'kelas-row';
            row.innerHTML = '<input type="text" name="kelas[]" class="form-control" placeholder="Contoh: XII RPL 1">' +
                '<button type="button" class="btn btn-danger btn-sm remove-kelas-btn"><i class="bi bi-trash"></i></button>';
            container.appendChild(row);
            bindRemove(row.querySelector('.remove-kelas-btn'));
        });
    })();
</script>
@endsection
