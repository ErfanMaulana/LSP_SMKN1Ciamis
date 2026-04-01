@extends('asesi.layout')

@section('title', 'Form Umpan Balik Asesor')
@section('page-title', 'Form Umpan Balik Asesor')

@section('styles')
<style>
    .form-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .form-header h2 {
        font-size: 20px;
        margin-bottom: 4px;
        color: #1e293b;
    }

    .form-header p {
        font-size: 14px;
        color: #475569;
        margin: 0;
        line-height: 1.6;
    }

    .form-header .meta {
        margin-top: 10px;
        font-size: 14px;
        color: #0f172a;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .alert-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #0073bd;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 13px;
        line-height: 1.6;
    }

    .komponen-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        box-shadow: 0 1px 8px rgba(0,0,0,0.04);
    }

    .komponen-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .komponen-no {
        font-size: 12px;
        color: #0073bd;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .komponen-text {
        font-size: 14px;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.55;
    }

    .komponen-body {
        padding: 16px 18px 18px;
    }

    .choice-row {
        display: flex;
        gap: 10px;
        margin-bottom: 13px;    
        flex-wrap: wrap;
    }

    .choice-option {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        padding: 10px 12px;
        cursor: pointer;
        min-width: 130px;
        transition: all 0.2s ease;
        background: #fff;
    }

    .choice-option:hover {
        border-color: #0073bd;
        background: #f8fbff;
    }

    .choice-option input {
        accent-color: #0073bd;
    }

    .choice-option span {
        font-size: 14px;
        font-weight: 500;
        line-height: 1.2;
    }

    .choice-option.yes input:checked + span {
        color: #166534;
        font-weight: 700;
    }

    .choice-option.no input:checked + span {
        color: #b91c1c;
        font-weight: 700;
    }

    .catatan-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .catatan-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        min-height: 94px;
        padding: 10px 12px;
        font-size: 14px;
        color: #1e293b;
        resize: vertical;
        font-family: inherit;
        line-height: 1.5;
    }

    .catatan-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.13);
    }

    .required-mark {
        color: #dc2626;
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back {
        background: #f1f5f9;
        color: #334155;
    }

    .btn-submit {
        background: #0073bd;
        color: #fff;
    }

    .btn-save {
        background: #0073bd;
        color: #fff;
    }

    .btn-save:hover {
        box-shadow: 0 8px 18px rgba(0, 97, 165, 0.35);
    }

    .btn-submit:hover {
        box-shadow: 0 8px 18px rgba(0, 97, 165, 0.35);
    }

    .error-list {
        margin-bottom: 14px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        border-radius: 8px;
        padding: 10px 12px;
        color: #991b1b;
        font-size: 13px;
    }

    .empty {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .form-header { padding: 14px; }
        .komponen-header,
        .komponen-body { padding: 12px; }
        .choice-option { width: 100%; justify-content: center; }
        .actions { flex-direction: column-reverse; align-items: stretch; }
        .btn { justify-content: center; width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="form-header">
    <h2>Penilaian Kinerja Asesor</h2>
    <p>Berikan penilaian objektif untuk setiap pernyataan komponen. Pilih Ya/Tidak dan isi catatan komentar pada semua komponen.</p>
    <div class="meta">
        <span><i class="bi bi-patch-check"></i> {{ $skema->nama_skema }}</span>
        <span><i class="bi bi-upc-scan"></i> {{ $skema->nomor_skema }}</span>
        <span><i class="bi bi-ui-checks-grid"></i> {{ $komponenList->count() }} Komponen</span>
    </div>
</div>

@if($komponenList->isEmpty())
<div class="empty">
    <i class="bi bi-info-circle" style="font-size:36px;display:block;margin-bottom:8px;"></i>
    Komponen umpan balik untuk skema ini belum tersedia.
</div>
@else
<div class="alert-box">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Gunakan Simpan Draft untuk menyimpan bertahap. Gunakan Selesaikan Umpan Balik saat semua komponen sudah lengkap.
</div>

@if($errors->any())
<div class="error-list">
    @foreach($errors->all() as $error)
        <div>- {{ $error }}</div>
    @endforeach
</div>
@endif

<form action="{{ route('asesi.umpan-balik.store', $skema->id) }}" method="POST" id="umpanBalikForm">
    @csrf

    @foreach($komponenList as $index => $komponen)
        @php
            $existingJawaban = old("jawaban.$komponen->id.hasil", $existing->get($komponen->id)->jawaban ?? null);
            $existingCatatan = old("jawaban.$komponen->id.catatan", $existing->get($komponen->id)->catatan ?? '');
        @endphp

        <div class="komponen-card">
            <div class="komponen-header">
                <div class="komponen-no">Komponen {{ $index + 1 }}</div>
                <div class="komponen-text">{{ $komponen->pernyataan }}</div>
            </div>

            <div class="komponen-body">
                <div class="choice-row">
                    <label class="choice-option yes">
                        <input type="radio"
                               name="jawaban[{{ $komponen->id }}][hasil]"
                               value="ya"
                               {{ $existingJawaban === 'ya' ? 'checked' : '' }}>
                        <span>Ya</span>
                    </label>
                    <label class="choice-option no">
                        <input type="radio"
                               name="jawaban[{{ $komponen->id }}][hasil]"
                               value="tidak"
                               {{ $existingJawaban === 'tidak' ? 'checked' : '' }}>
                        <span>Tidak</span>
                    </label>
                </div>

                <label class="catatan-label">
                    Catatan / Komentar Asesi <span class="required-mark">*</span>
                </label>
                <textarea class="catatan-input"
                          name="jawaban[{{ $komponen->id }}][catatan]"
                          placeholder="Tulis catatan atau komentar Anda untuk komponen ini">{{ $existingCatatan }}</textarea>
            </div>
        </div>
    @endforeach

    <div class="actions">
        <a href="{{ route('asesi.umpan-balik.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-left:auto;">
            <button type="submit" name="save_draft" class="btn btn-save">
                <i class="bi bi-save"></i> Simpan Draft
            </button>
            <button type="submit" name="submit_final" class="btn btn-submit">
                <i class="bi bi-check-circle"></i> Selesaikan Umpan Balik
            </button>
        </div>
    </div>
</form>
@endif
@endsection

@section('scripts')
<script>
    document.getElementById('umpanBalikForm')?.addEventListener('submit', function (event) {
        const isFinalSubmit = event.submitter && event.submitter.name === 'submit_final';

        if (!isFinalSubmit) {
            return;
        }

        const komponenCards = this.querySelectorAll('.komponen-card');

        for (const card of komponenCards) {
            const selected = card.querySelector('input[type="radio"]:checked');
            const textarea = card.querySelector('textarea');

            if (!selected) {
                event.preventDefault();
                alert('Untuk menyelesaikan, semua komponen wajib memilih Ya/Tidak.');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            if (!textarea || !textarea.value.trim()) {
                event.preventDefault();
                alert('Untuk menyelesaikan, catatan/komentar wajib diisi pada semua komponen.');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                textarea.focus();
                return;
            }
        }
    });
</script>
@endsection
