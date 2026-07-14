@extends('admin.layout')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@section('content')
<div class="page-header">
    <div>
        <h2 style="margin:0;font-size:24px;font-weight:700;color:#0F172A;">Pengaturan Sistem</h2>
        <p style="margin:4px 0 0;font-size:14px;color:#64748b;">Konfigurasi global yang berlaku untuk seluruh asesor</p>
    </div>
</div>

@if(session('success'))
    <div style="margin-bottom:20px;padding:12px 16px;background:#f0fdf4;border:1px solid #86efac;border-radius:10px;display:flex;align-items:center;gap:10px;font-size:14px;color:#166534;">
        <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="settings-card">
        <div class="settings-section-head">
            <i class="bi bi-people-fill" style="color:#0073bd;"></i>
            Batas Kapasitas Asesor
        </div>
        <p style="font-size:13px;color:#64748b;margin:0 0 20px;">
            Atur jumlah maksimal asesi yang dapat ditangani oleh setiap asesor secara global.
            Jika asesor memiliki pengaturan individual, maka nilai individual tersebut yang akan diutamakan.
        </p>

        <div class="form-group">
            <label for="max_asesi_per_asesor">
                Jumlah Maksimal Asesi per Asesor
                <span style="font-weight:400;font-size:12px;color:#94a3b8;">(Kosongkan jika tidak dibatasi)</span>
            </label>
            <div style="display:flex;align-items:center;gap:12px;max-width:320px;">
                <input
                    type="number"
                    id="max_asesi_per_asesor"
                    name="max_asesi_per_asesor"
                    class="form-control @error('max_asesi_per_asesor') is-invalid @enderror"
                    value="{{ old('max_asesi_per_asesor', $settings['max_asesi_per_asesor']) }}"
                    placeholder="Contoh: 10"
                    min="1"
                    max="9999"
                    style="max-width:200px;"
                >
                @if($settings['max_asesi_per_asesor'])
                    <span style="font-size:13px;color:#16a34a;font-weight:600;">
                        <i class="bi bi-check-circle-fill"></i>
                        Aktif: {{ $settings['max_asesi_per_asesor'] }} asesi
                    </span>
                @else
                    <span style="font-size:13px;color:#94a3b8;">
                        <i class="bi bi-dash-circle"></i>
                        Tidak dibatasi
                    </span>
                @endif
            </div>
            @error('max_asesi_per_asesor')
                <div style="color:#ef4444;font-size:12px;margin-top:5px;">{{ $message }}</div>
            @enderror
            <small style="font-size:12px;color:#64748b;margin-top:6px;display:block;">
                <i class="bi bi-info-circle"></i>
                Nilai ini berlaku untuk semua asesor. Asesor yang memiliki batas individu di profil mereka akan menggunakan nilai individu tersebut sebagai prioritas.
            </small>
        </div>
    </div>

    {{-- KKM Nilai --}}
    <div class="settings-card">
        <div class="settings-section-head">
            <i class="bi bi-clipboard2-check-fill" style="color:#0073bd;"></i>
            KKM Nilai (Kriteria Ketuntasan Minimal)
        </div>
        <p style="font-size:13px;color:#64748b;margin:0 0 20px;">
            Atur nilai minimum yang harus dicapai oleh asesi agar dinyatakan <strong>Kompeten</strong>.
            Nilai ini berlaku secara global untuk seluruh skema dan ujian kompetensi.
        </p>

        <div class="form-group">
            <label for="kkm_nilai">
                Nilai KKM (0 – 100)
                <span style="font-weight:400;font-size:12px;color:#94a3b8;">(Default: 70)</span>
            </label>
            <div style="display:flex;align-items:center;gap:12px;max-width:320px;">
                <input
                    type="number"
                    id="kkm_nilai"
                    name="kkm_nilai"
                    class="form-control @error('kkm_nilai') is-invalid @enderror"
                    value="{{ old('kkm_nilai', $settings['kkm_nilai']) }}"
                    placeholder="Contoh: 70"
                    min="0"
                    max="100"
                    style="max-width:200px;"
                >
                <span style="font-size:13px;color:#0073bd;font-weight:600;">
                    <i class="bi bi-check-circle-fill"></i>
                    KKM saat ini: {{ $settings['kkm_nilai'] ?? 70 }}
                </span>
            </div>
            @error('kkm_nilai')
                <div style="color:#ef4444;font-size:12px;margin-top:5px;">{{ $message }}</div>
            @enderror
            <small style="font-size:12px;color:#64748b;margin-top:6px;display:block;">
                <i class="bi bi-info-circle"></i>
                Nilai ini berlaku global. Asesi yang mencapai atau melampaui nilai KKM akan dinyatakan kompeten secara otomatis.
            </small>
        </div>
    </div>

    <div style="margin-top:24px;display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Pengaturan
        </button>
    </div>
</form>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .settings-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        padding: 28px 30px;
        margin-bottom: 18px;
    }

    .settings-section-head {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 0;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
        outline: none;
    }

    .form-control:focus {
        border-color: #0073bd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,115,189,.12);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .btn {
        padding: 10px 22px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: #0073bd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #0060a0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,115,189,.25);
    }
</style>
@endsection
