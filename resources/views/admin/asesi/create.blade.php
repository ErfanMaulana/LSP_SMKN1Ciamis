@extends('admin.layout')

@section('title', 'Tambah Asesi')
@section('page-title', 'Tambah Asesi')

@section('content')
<div class="page-header">
    <h2>Tambah Data Asesi</h2>
    <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.asesi.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Informasi Dasar</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="NIK">NIK <span class="required">*</span></label>
                        <input type="text" id="NIK" name="NIK" class="form-control @error('NIK') is-invalid @enderror" value="{{ old('NIK') }}" required>
                        @error('NIK')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ID_jurusan">Jurusan <span class="required">*</span></label>
                        <select id="ID_jurusan" name="ID_jurusan" class="form-control @error('ID_jurusan') is-invalid @enderror" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $item)
                                <option value="{{ $item->ID_jurusan }}" {{ old('ID_jurusan') == $item->ID_jurusan ? 'selected' : '' }}>
                                    {{ $item->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('ID_jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" id="kelas" name="kelas" class="form-control" value="{{ old('kelas') }}">
                    </div>

                    <div class="form-group">
                        <label for="kebangsaan">Kebangsaan</label>
                        <input type="text" id="kebangsaan" name="kebangsaan" class="form-control" value="{{ old('kebangsaan', 'Indonesia') }}">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Tempat & Tanggal Lahir</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Alamat & Kontak</h3>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_kota">Kode Kota</label>
                        <input type="text" id="kode_kota" name="kode_kota" class="form-control" value="{{ old('kode_kota') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_provinsi">Kode Provinsi</label>
                        <input type="text" id="kode_provinsi" name="kode_provinsi" class="form-control" value="{{ old('kode_provinsi') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" class="form-control" value="{{ old('kode_pos') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telepon_rumah">Telepon Rumah</label>
                        <input type="text" id="telepon_rumah" name="telepon_rumah" class="form-control" value="{{ old('telepon_rumah') }}">
                    </div>

                    <div class="form-group">
                        <label for="telepon_hp">Telepon HP</label>
                        <input type="text" id="telepon_hp" name="telepon_hp" class="form-control" value="{{ old('telepon_hp') }}">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Informasi Tambahan</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_kementrian">Kode Kementrian</label>
                        <input type="text" id="kode_kementrian" name="kode_kementrian" class="form-control" value="{{ old('kode_kementrian') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_anggaran">Kode Anggaran</label>
                        <input type="text" id="kode_anggaran" name="kode_anggaran" class="form-control" value="{{ old('kode_anggaran') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.asesi.index') }}" class="btn btn-secondary">
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
        padding-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
    }

    .form-section:last-of-type {
        border-bottom: none;
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
        background: #3b82f6;
        border-radius: 2px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row:last-child {
        margin-bottom: 0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
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
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
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

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
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
        min-height: 80px;
    }

    select.form-control {
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .card-body {
            padding: 20px;
        }
    }
</style>
@endsection
