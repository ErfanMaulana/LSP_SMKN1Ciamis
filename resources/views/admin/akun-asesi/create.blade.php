@extends('admin.layout')

@section('title', 'Tambah Akun Asesi')
@section('page-title', 'Tambah Akun Asesi')

@section('content')
<div class="page-header">
    <h2>Buat Akun Asesi Baru</h2>
    <a href="{{ route('admin.akun-asesi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin:4px 0 0;padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div style="background:#f0fdf4;border-left:4px solid #14532d;padding:14px 18px;border-radius:6px;margin-bottom:24px;font-size:13px;color:#14532d;">
            <i class="bi bi-info-circle"></i>
            <strong>Alur:</strong> Buat akun dengan NIK → Siswa login menggunakan NIK → Siswa mengisi formulir pendaftaran → Admin memverifikasi.
        </div>

        <form action="{{ route('admin.akun-asesi.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Data Akun</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="NIK">NIK (Nomor Induk Kependudukan) <span class="required">*</span></label>
                        <input type="text" id="NIK" name="NIK" class="form-control @error('NIK') is-invalid @enderror" 
                               value="{{ old('NIK') }}" required maxlength="16" minlength="16"
                               placeholder="Masukkan 16 digit NIK" inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        @error('NIK')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color:#64748b;font-size:11px;">NIK terdiri dari 16 digit angka</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               required minlength="6" placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                               required minlength="6" placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Buat Akun
                </button>
                <a href="{{ route('admin.akun-asesi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
