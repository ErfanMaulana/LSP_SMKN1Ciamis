{{-- REWRITTEN: kelompok-based jadwal edit form --}}
@extends('admin.layout')

@section('title', 'Edit Jadwal Ujikom')
@section('page-title', 'Edit Jadwal Ujikom')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h2 {
        font-size: 22px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .form-card {
        background: white; border-radius: 12px; padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .form-card h3 {
        font-size: 16px; font-weight: 700; color: #0F172A;
        margin-bottom: 20px; padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;
    }
    .form-section-title {
        font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;
        letter-spacing: .5px; margin: 20px 0 12px; padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9; grid-column: 1/-1;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-full { grid-column: 1 / -1; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-group .hint  { font-size: 11px; color: #94a3b8; }
    .form-control {
        padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; font-family: inherit; transition: border-color .2s; outline: none;
    }
    .form-control:focus { border-color: #0061a5; box-shadow: 0 0 0 3px rgba(0,97,165,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    textarea.form-control { resize: vertical; min-height: 80px; }
    .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 2px; }
    .required { color: #ef4444; margin-left: 2px; }

    .kelompok-preview {
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
        padding: 14px 16px; font-size: 13px; color: #15803d; display: none; margin-top: 6px;
    }
    .kelompok-preview.show { display: block; }
    .kelompok-preview-row { display: flex; gap: 8px; align-items: flex-start; margin-bottom: 6px; }
    .kelompok-preview-row:last-child { margin-bottom: 0; }
    .kelompok-preview-label { font-weight: 600; min-width: 80px; color: #166534; }

    .tuk-preview {
        background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px;
        padding: 12px 16px; font-size: 13px; color: #0369a1; display: none; margin-top: 4px;
    }
    .tuk-preview.show { display: block; }

    .validation-error { font-size: 12px; color: #ef4444; margin-top: 4px; display: none; align-items: center; gap: 6px; }
    .validation-error.show { display: flex; }
    .form-group.has-error .form-control { border-color: #ef4444; }

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
    .btn-submit {
        background: #0073bd;
        color: white;
        padding: 10px 20px;
    }
    .btn-submit:hover {
        background: #0073bd;
        transform: translateY(-1px);
    }
    .btn-cancel {
        background: #64748b;
        color: white;
        padding: 10px 20px;
    }
    .btn-cancel:hover {
        background: #475569;
    }
    @media(max-width:640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Edit Jadwal Uji Kompetensi</h2>
    <a href="{{ route('admin.jadwal-ujikom.index') }}" class="btn btn-cancel">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="form-card">
    @if($errors->any())
    <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#9f1239;">
        <strong><i class="bi bi-exclamation-triangle"></i> Terdapat kesalahan:</strong>
        <ul style="margin:6px 0 0 16px;padding:0;">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.jadwal-ujikom.update', $jadwal->id) }}">
        @csrf @method('PUT')

        <div class="form-grid">

            {{-- ─── Judul ─── --}}
            <div class="form-group form-full">
                <label>Judul Jadwal <span class="required">*</span></label>
                <input type="text" name="judul_jadwal" value="{{ old('judul_jadwal', $jadwal->judul_jadwal) }}"
                       class="form-control {{ $errors->has('judul_jadwal') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: Ujikom RPL Gelombang I - 2026">
                @error('judul_jadwal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title"><i class="bi bi-people-fill"></i> Kelompok & Lokasi</div>

            {{-- ─── Kelompok ─── --}}
            <div class="form-group form-full">
                <label>Kelompok <span class="required">*</span></label>
                <select name="kelompok_id" id="kelompok_select"
                        class="form-control {{ $errors->has('kelompok_id') ? 'is-invalid' : '' }}"
                        onchange="onKelompokChange(this.value)" required>
                    <option value="">-- Pilih Kelompok --</option>
                    @foreach($kelompoks as $k)
                    <option value="{{ $k->id }}" {{ old('kelompok_id', $jadwal->kelompok_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelompok }}
                        @if($k->skema) — {{ $k->skema->nama_skema }} @endif
                        ({{ $k->asesis->count() }} asesi)
                    </option>
                    @endforeach
                </select>
                @error('kelompok_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <span class="hint">Skema, asesor, dan peserta diisi otomatis dari kelompok yang dipilih.</span>

                <div class="kelompok-preview" id="kelompok_preview">
                    <div class="kelompok-preview-row">
                        <span class="kelompok-preview-label"><i class="bi bi-award"></i> Skema</span>
                        <span id="prev_skema">—</span>
                    </div>
                    <div class="kelompok-preview-row">
                        <span class="kelompok-preview-label"><i class="bi bi-person-badge"></i> Asesor</span>
                        <span id="prev_asesor">—</span>
                    </div>
                    <div class="kelompok-preview-row">
                        <span class="kelompok-preview-label"><i class="bi bi-people"></i> Peserta</span>
                        <span id="prev_asesi">—</span>
                    </div>
                </div>
            </div>

            {{-- ─── TUK ─── --}}
            <div class="form-group form-full">
                <label>TUK (Tempat Uji Kompetensi) <span class="required">*</span></label>
                <select name="tuk_id" id="tuk_select"
                        class="form-control {{ $errors->has('tuk_id') ? 'is-invalid' : '' }}"
                        onchange="showTukPreview(this)">
                    <option value="">-- Pilih TUK --</option>
                    @foreach($tuks as $tuk)
                    <option value="{{ $tuk->id }}"
                            data-kapasitas="{{ $tuk->kapasitas }}"
                            data-kota="{{ $tuk->kota }}"
                            data-tipe="{{ $tuk->tipe_label }}"
                            {{ old('tuk_id', $jadwal->tuk_id) == $tuk->id ? 'selected' : '' }}>
                        {{ $tuk->nama_tuk }}@if($tuk->kota) — {{ $tuk->kota }}@endif
                    </option>
                    @endforeach
                </select>
                @error('tuk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="tuk_preview" class="tuk-preview">
                    <i class="bi bi-building"></i> <span id="tuk_info"></span>
                </div>
            </div>

            <div class="form-section-title"><i class="bi bi-clock"></i> Tanggal & Waktu</div>

            {{-- ─── Tanggal Mulai ─── --}}
            <div class="form-group">
                <label>Tanggal Mulai <span class="required">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('Y-m-d') : '') }}"
                       class="form-control {{ $errors->has('tanggal_mulai') ? 'is-invalid' : '' }}">
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ─── Tanggal Selesai ─── --}}
            <div class="form-group" id="tanggal_selesai_group">
                <label>Tanggal Selesai <span class="required">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                       value="{{ old('tanggal_selesai', $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('Y-m-d') : '') }}"
                       class="form-control {{ $errors->has('tanggal_selesai') ? 'is-invalid' : '' }}">
                <span class="hint">Bisa sama dengan tanggal mulai jika ujikom hanya 1 hari.</span>
                <div class="validation-error" id="tanggal_error">
                    <i class="bi bi-exclamation-circle"></i>
                    <span id="tanggal_error_message"></span>
                </div>
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ─── Waktu Mulai ─── --}}
            <div class="form-group">
                <label>Waktu Mulai <span class="required">*</span></label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', substr($jadwal->waktu_mulai, 0, 5)) }}"
                       class="form-control {{ $errors->has('waktu_mulai') ? 'is-invalid' : '' }}">
                @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ─── Waktu Selesai ─── --}}
            <div class="form-group">
                <label>Waktu Selesai <span class="required">*</span></label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', substr($jadwal->waktu_selesai, 0, 5)) }}"
                       class="form-control {{ $errors->has('waktu_selesai') ? 'is-invalid' : '' }}">
                @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title"><i class="bi bi-info-circle"></i> Status & Keterangan</div>

            {{-- ─── Status ─── --}}
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="dijadwalkan" {{ old('status', $jadwal->status) === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ old('status', $jadwal->status) === 'berlangsung'  ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai"     {{ old('status', $jadwal->status) === 'selesai'      ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan"  {{ old('status', $jadwal->status) === 'dibatalkan'   ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- ─── Keterangan ─── --}}
            <div class="form-group form-full">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"
                          placeholder="Catatan tambahan, instruksi peserta, dll...">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="bi bi-save"></i> Update
            </button>
            <a href="{{ route('admin.jadwal-ujikom.index') }}" class="btn btn-cancel">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const KELOMPOK_DATA = @json($kelompokData);

function onKelompokChange(id) {
    const preview = document.getElementById('kelompok_preview');
    if (!id || !KELOMPOK_DATA[id]) {
        preview.classList.remove('show');
        return;
    }
    const d = KELOMPOK_DATA[id];
    document.getElementById('prev_skema').textContent  = d.nama_skema  || '—';
    document.getElementById('prev_asesor').textContent = d.asesor_nama || '(belum ada asesor)';
    document.getElementById('prev_asesi').textContent  = d.asesi_count + ' peserta';
    preview.classList.add('show');
}

function showTukPreview(sel) {
    const opt     = sel.options[sel.selectedIndex];
    const preview = document.getElementById('tuk_preview');
    const info    = document.getElementById('tuk_info');
    if (sel.value) {
        info.textContent = (opt.dataset.tipe || 'TUK') + ' | Kota: ' + (opt.dataset.kota || 'N/A') + ' | Kapasitas: ' + (opt.dataset.kapasitas || '?') + ' peserta';
        preview.classList.add('show');
    } else {
        preview.classList.remove('show');
    }
}

function validateTanggal() {
    const tMulai   = document.getElementById('tanggal_mulai').value;
    const tSelesai = document.getElementById('tanggal_selesai').value;
    const errDiv   = document.getElementById('tanggal_error');
    const errMsg   = document.getElementById('tanggal_error_message');
    const grp      = document.getElementById('tanggal_selesai_group');
    const btn      = document.querySelector('button[type="submit"]');
    if (tMulai && tSelesai && new Date(tSelesai) < new Date(tMulai)) {
        errMsg.textContent = 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai';
        errDiv.classList.add('show');
        grp.classList.add('has-error');
        if (btn) btn.disabled = true;
    } else {
        errDiv.classList.remove('show');
        grp.classList.remove('has-error');
        if (btn) btn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('kelompok_select');
    if (sel?.value) onKelompokChange(sel.value);

    const tukSel = document.getElementById('tuk_select');
    if (tukSel?.value) showTukPreview(tukSel);

    document.getElementById('tanggal_mulai')?.addEventListener('change', validateTanggal);
    document.getElementById('tanggal_selesai')?.addEventListener('change', validateTanggal);
    validateTanggal();
});
</script>
@endsection
