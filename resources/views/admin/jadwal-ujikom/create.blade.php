@extends('admin.layout')

@section('title', 'Tambah Jadwal Ujikom')
@section('page-title', 'Tambah Jadwal Ujikom')

@section('styles')
<style>
    .form-card {
        background: white; border-radius: 12px; padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08); max-width: 860px;
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

    .tuk-preview {
        background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px;
        padding: 12px 16px; font-size: 13px; color: #0369a1; display: none;
    }
    .tuk-preview.show { display: block; }

    /* Peserta Section */
    .peserta-section { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-top:8px; }
    .peserta-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:#f8fafc; border-bottom:1px solid #e5e7eb; }
    .peserta-header-left { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#374151; }
    .peserta-counter { font-size:12px; font-weight:700; padding:3px 10px; border-radius:20px; background:#dbeafe; color:#1e40af; }
    .peserta-counter.over { background:#fee2e2; color:#991b1b; }
    .peserta-counter.full { background:#d1fae5; color:#065f46; }
    .peserta-search { padding:8px 12px; width:100%; border:none; border-bottom:1px solid #f1f5f9; font-size:13px; outline:none; font-family:inherit; background:white; color:#374151; }
    .peserta-search::placeholder { color:#94a3b8; }
    .peserta-list { max-height:300px; overflow-y:auto; }
    .peserta-item { display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid #f8fafc; cursor:pointer; transition:background .15s; }
    .peserta-item:last-child { border-bottom:none; }
    .peserta-item:hover { background:#f0f9ff; }
    .peserta-item.selected { background:#eff6ff; }
    .peserta-item.disabled { opacity:.45; cursor:not-allowed; }
    .peserta-item.disabled:hover { background:transparent; }
    .peserta-item input[type=checkbox] { accent-color:#0061a5; width:16px; height:16px; flex-shrink:0; cursor:pointer; }
    .peserta-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#0061a5,#0073bd); color:white; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
    .peserta-info { flex:1; min-width:0; }
    .peserta-nama { font-size:13px; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .peserta-meta { font-size:11px; color:#64748b; margin-top:2px; }
    .peserta-badge { font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; background:#d1fae5; color:#065f46; flex-shrink:0; }
    .peserta-empty { padding:28px 16px; text-align:center; color:#94a3b8; font-size:13px; }
    .peserta-empty i { font-size:28px; display:block; margin-bottom:8px; }
    .peserta-loading { padding:20px; text-align:center; color:#94a3b8; font-size:13px; }
    .select-all-bar { display:flex; align-items:center; gap:8px; padding:7px 16px; background:#f8fafc; border-bottom:1px solid #e5e7eb; font-size:12px; color:#475569; }
    .select-all-bar label { cursor:pointer; display:flex; align-items:center; gap:6px; }
    .select-all-bar input { accent-color:#0061a5; width:14px; height:14px; cursor:pointer; }
    .kuota-warning { background:#fef3c7; border:1px solid #fcd34d; border-radius:8px; padding:8px 14px; font-size:12px; color:#92400e; margin-top:8px; display:none; }
    .kuota-warning.show { display:block; }

    .validation-error { font-size: 12px; color: #ef4444; margin-top: 4px; display: none; align-items: center; gap: 6px; }
    .validation-error.show { display: flex; }
    .validation-error i { font-size: 14px; }
    .form-group.has-error { position: relative; }
    .form-group.has-error .form-control { border-color: #ef4444; }

    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-submit {
        padding: 10px 24px; background: #0061a5; color: #fff; border: none;
        border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #003961; }
    .btn-cancel {
        padding: 10px 20px; background: #f1f5f9; color: #475569; border: none;
        border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-cancel:hover { background: #e2e8f0; }
    @media(max-width:640px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.jadwal-ujikom.index') }}" style="color:#64748b;text-decoration:none;font-size:14px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Jadwal
    </a>
</div>

<div class="form-card">
    <h3><i class="bi bi-calendar-plus" style="color:#0061a5;"></i> Tambah Jadwal Uji Kompetensi</h3>

    <form method="POST" action="{{ route('admin.jadwal-ujikom.store') }}">
        @csrf

        <div class="form-grid">
            <!-- Judul -->
            <div class="form-group form-full">
                <label>Judul Jadwal <span class="required">*</span></label>
                <input type="text" name="judul_jadwal" value="{{ old('judul_jadwal') }}"
                       class="form-control {{ $errors->has('judul_jadwal') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: Ujikom RPL Gelombang I - 2026">
                @error('judul_jadwal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title"><i class="bi bi-clipboard-data"></i> Skema & Lokasi</div>

            <!-- Skema -->
            <div class="form-group">
                <label>Skema Kompetensi <span class="required">*</span></label>
                <select name="skema_id" id="skema_select"
                        class="form-control {{ $errors->has('skema_id') ? 'is-invalid' : '' }}"
                        onchange="loadAsesiRekomendasi(this.value)">
                    <option value="">-- Pilih Skema --</option>
                    @foreach($skemas as $skema)
                    <option value="{{ $skema->id }}" {{ old('skema_id') == $skema->id ? 'selected' : '' }}>
                        {{ $skema->nama_skema }}
                    </option>
                    @endforeach
                </select>
                @error('skema_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- TUK -->
            <div class="form-group">
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
                            {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
                        {{ $tuk->nama_tuk }} — {{ $tuk->kota ?? 'N/A' }}
                    </option>
                    @endforeach
                </select>
                @error('tuk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="tuk_preview" class="tuk-preview">
                    <i class="bi bi-building"></i>
                    <span id="tuk_info"></span>
                </div>
            </div>

            <div class="form-section-title"><i class="bi bi-clock"></i> Tanggal & Waktu</div>

            <!-- Tanggal Mulai -->
            <div class="form-group" id="tanggal_mulai_group">
                <label>Tanggal Mulai <span class="required">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                       class="form-control {{ $errors->has('tanggal_mulai') ? 'is-invalid' : '' }}">
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Tanggal Selesai -->
            <div class="form-group" id="tanggal_selesai_group">
                <label>Tanggal Selesai <span class="required">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                       class="form-control {{ $errors->has('tanggal_selesai') ? 'is-invalid' : '' }}">
                <span class="hint">Bisa sama dengan tanggal mulai jika ujikom hanya 1 hari</span>
                <div class="validation-error" id="tanggal_error">
                    <i class="bi bi-exclamation-circle"></i>
                    <span id="tanggal_error_message"></span>
                </div>
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Kuota -->
            <div class="form-group form-full">
                <label>Kuota Peserta <span class="required">*</span></label>
                <input type="number" name="kuota" value="{{ old('kuota', 30) }}" min="1"
                       class="form-control {{ $errors->has('kuota') ? 'is-invalid' : '' }}">
                @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Waktu Mulai -->
            <div class="form-group">
                <label>Waktu Mulai <span class="required">*</span></label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}"
                       class="form-control {{ $errors->has('waktu_mulai') ? 'is-invalid' : '' }}">
                @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Waktu Selesai -->
            <div class="form-group">
                <label>Waktu Selesai <span class="required">*</span></label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', '12:00') }}"
                       class="form-control {{ $errors->has('waktu_selesai') ? 'is-invalid' : '' }}">
                @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title"><i class="bi bi-info-circle"></i> Status & Keterangan</div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="dijadwalkan" {{ old('status', 'dijadwalkan') === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ old('status') === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai"     {{ old('status') === 'selesai'     ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan"  {{ old('status') === 'dibatalkan'  ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="form-group form-full">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"
                          placeholder="Catatan tambahan, instruksi peserta, dll...">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        {{-- ─── Peserta Section ─── --}}
        <div style="margin-top: 24px;">
            <div class="form-section-title" style="margin-top:0; grid-column:unset;">
                <i class="bi bi-people-fill"></i> Peserta Terdaftar
                <span style="font-size:11px;font-weight:400;color:#94a3b8;text-transform:none;letter-spacing:0;margin-left:8px;">
                    — Pilih asesi yang akan mengikuti jadwal ini
                </span>
            </div>

            <div id="peserta-placeholder" style="padding:16px 0; color:#94a3b8; font-size:13px;">
                <i class="bi bi-arrow-up-circle" style="margin-right:6px;"></i>
                Pilih Skema Kompetensi untuk melihat daftar asesi yang direkomendasikan.
            </div>

            <div id="peserta-wrapper" style="display:none;">
                <div class="peserta-section">
                    <div class="peserta-header">
                        <div class="peserta-header-left">
                            <i class="bi bi-person-check-fill" style="color:#16a34a;"></i>
                            <span id="peserta-skema-label">Asesi Direkomendasikan</span>
                        </div>
                        <span class="peserta-counter" id="peserta-counter">0 / 30 dipilih</span>
                    </div>
                    <input type="text" class="peserta-search" id="peserta-search"
                           placeholder="🔍 Cari nama atau no. registrasi..." oninput="filterPeserta(this.value)">
                    <div class="select-all-bar">
                        <label>
                            <input type="checkbox" id="select-all-cb" onchange="toggleSelectAll(this.checked)">
                            Pilih semua
                        </label>
                        <span id="peserta-total-info" style="margin-left:auto;"></span>
                    </div>
                    <div class="peserta-list" id="peserta-list">
                        <div class="peserta-loading"><i class="bi bi-arrow-repeat"></i> Memuat daftar peserta...</div>
                    </div>
                </div>
                <div class="kuota-warning" id="kuota-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="kuota-warning-text">Kuota peserta sudah penuh.</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="bi bi-calendar-plus"></i> Simpan Jadwal
            </button>
            <a href="{{ route('admin.jadwal-ujikom.index') }}" class="btn-cancel">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const AJAX_URL    = "{{ route('admin.jadwal-ujikom.asesi-rekomendasi') }}";
let allAsesi      = [];
let selectedNiks  = new Set();

function getKuota() { return parseInt(document.querySelector('[name=kuota]')?.value) || 0; }

/* ── TUK Preview ── */
function showTukPreview(sel) {
    const opt     = sel.options[sel.selectedIndex];
    const preview = document.getElementById('tuk_preview');
    const info    = document.getElementById('tuk_info');
    if (sel.value) {
        info.textContent = opt.dataset.tipe + ' | Kota: ' + (opt.dataset.kota || 'N/A') + ' | Kapasitas: ' + opt.dataset.kapasitas + ' peserta';
        preview.classList.add('show');
    } else {
        preview.classList.remove('show');
    }
}

/* ── Load Asesi by Skema ── */
async function loadAsesiRekomendasi(skemaId) {
    const list  = document.getElementById('peserta-list');
    const label = document.getElementById('peserta-skema-label');
    const sel   = document.getElementById('skema_select');

    selectedNiks.clear();
    updateCounter();

    if (!skemaId) {
        document.getElementById('peserta-wrapper').style.display = 'none';
        document.getElementById('peserta-placeholder').style.display = '';
        return;
    }

    label.textContent = 'Asesi Direkomendasikan — ' + (sel.options[sel.selectedIndex]?.text || '');
    document.getElementById('peserta-wrapper').style.display = '';
    document.getElementById('peserta-placeholder').style.display = 'none';
    list.innerHTML = '<div class="peserta-loading"><i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;"></i> Memuat...</div>';

    try {
        const resp = await fetch(AJAX_URL + '?skema_id=' + skemaId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        allAsesi = await resp.json();
        renderList(allAsesi);
    } catch (e) {
        list.innerHTML = '<div class="peserta-empty"><i class="bi bi-exclamation-circle"></i>Gagal memuat data. Coba refresh halaman.</div>';
    }
}

function renderList(items) {
    const list      = document.getElementById('peserta-list');
    const totalInfo = document.getElementById('peserta-total-info');
    document.getElementById('select-all-cb').checked = false;
    totalInfo.textContent = items.length + ' asesi tersedia';

    if (items.length === 0) {
        list.innerHTML = '<div class="peserta-empty"><i class="bi bi-person-x"></i>Belum ada asesi dengan rekomendasi "Lanjut" untuk skema ini.</div>';
        return;
    }

    const k = getKuota();
    list.innerHTML = items.map(a => {
        const initials = (a.nama || '?').split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase();
        const isSelected = selectedNiks.has(a.NIK);
        const isFull     = !isSelected && selectedNiks.size >= k && k > 0;
        const checked    = isSelected ? 'checked' : '';
        const disabled   = isFull ? 'disabled' : '';
        const selCls     = isSelected ? 'selected' : (isFull ? 'disabled' : '');
        const catatan    = a.catatan_asesor
            ? `<span title="${esc(a.catatan_asesor)}"> · ${esc(a.catatan_asesor.substring(0,45))}${a.catatan_asesor.length>45?'…':''}</span>` : '';
        return `<div class="peserta-item ${selCls}" onclick="togglePeserta('${esc(a.NIK)}',this)">
            <input type="checkbox" name="peserta_niks[]" value="${esc(a.NIK)}" ${checked} ${disabled}
                   onchange="onCheckChange('${esc(a.NIK)}',this)" onclick="event.stopPropagation()">
            <div class="peserta-avatar">${initials}</div>
            <div class="peserta-info">
                <div class="peserta-nama">${esc(a.nama||'-')}</div>
                <div class="peserta-meta">No. Reg: <strong>${esc(a.no_reg||'-')}</strong>${catatan}</div>
            </div>
            <span class="peserta-badge"><i class="bi bi-check-circle-fill"></i> Direkomendasikan</span>
        </div>`;
    }).join('');
    syncSelectAll();
    updateCounter();
}

function togglePeserta(nik, row) {
    const cb = row.querySelector('input[type=checkbox]');
    if (cb.disabled) return;
    const k = getKuota();
    if (!cb.checked && selectedNiks.size >= k && k > 0) return; // kuota penuh
    cb.checked = !cb.checked;
    cb.checked ? selectedNiks.add(nik) : selectedNiks.delete(nik);
    row.classList.toggle('selected', cb.checked);
    updateCounter(); syncSelectAll(); refreshDisabled();
}

function onCheckChange(nik, cb) {
    const k = getKuota();
    if (cb.checked && selectedNiks.size >= k && k > 0) { cb.checked = false; return; }
    cb.checked ? selectedNiks.add(nik) : selectedNiks.delete(nik);
    cb.closest('.peserta-item').classList.toggle('selected', cb.checked);
    updateCounter(); syncSelectAll(); refreshDisabled();
}

function toggleSelectAll(checked) {
    const k = getKuota();
    let count = 0;
    document.querySelectorAll('#peserta-list .peserta-item').forEach(row => {
        const cb = row.querySelector('input[type=checkbox]');
        if (checked && count >= k && k > 0) {
            cb.checked = false; cb.disabled = true;
            selectedNiks.delete(cb.value);
            row.classList.remove('selected'); row.classList.add('disabled');
            return;
        }
        cb.checked = checked; cb.disabled = false;
        checked ? selectedNiks.add(cb.value) : selectedNiks.delete(cb.value);
        row.classList.toggle('selected', checked);
        row.classList.remove('disabled');
        if (checked) count++;
    });
    updateCounter(); refreshDisabled();
}

function syncSelectAll() {
    const cbs = [...document.querySelectorAll('#peserta-list input[type=checkbox]:not([disabled])')];
    document.getElementById('select-all-cb').checked = cbs.length > 0 && cbs.every(c => c.checked);
}

function updateCounter() {
    const k = getKuota();
    const c = document.getElementById('peserta-counter');
    const n = selectedNiks.size;
    c.textContent = n + ' / ' + k + ' dipilih';
    c.className   = 'peserta-counter' + (n > k && k > 0 ? ' over' : (n === k && k > 0 ? ' full' : ''));

    const warn = document.getElementById('kuota-warning');
    if (n >= k && k > 0) {
        document.getElementById('kuota-warning-text').textContent = n > k
            ? `Peserta melebihi kuota! Maksimal ${k} peserta.`
            : `Kuota peserta sudah penuh (${k}/${k}).`;
        warn.classList.add('show');
    } else { warn.classList.remove('show'); }
}

function refreshDisabled() {
    const k    = getKuota();
    const full = selectedNiks.size >= k && k > 0;
    document.querySelectorAll('#peserta-list .peserta-item').forEach(row => {
        const cb = row.querySelector('input[type=checkbox]');
        if (!cb.checked && full) {
            cb.disabled = true; row.classList.add('disabled');
        } else {
            cb.disabled = false; row.classList.remove('disabled');
        }
    });
}

function filterPeserta(q) {
    const kw = q.toLowerCase().trim();
    renderList(kw ? allAsesi.filter(a => (a.nama||'').toLowerCase().includes(kw) || (a.no_reg||'').toLowerCase().includes(kw)) : allAsesi);
}

function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

// Validasi tanggal real-time
function validateTanggal() {
    const tanggalMulai = document.getElementById('tanggal_mulai').value;
    const tanggalSelesai = document.getElementById('tanggal_selesai').value;
    const errorMsg = document.getElementById('tanggal_error');
    const errorText = document.getElementById('tanggal_error_message');
    const submitBtn = document.querySelector('[name="submit"]') || document.querySelector('button[type="submit"]');
    const tanggalGroup = document.getElementById('tanggal_selesai_group');
    
    if (tanggalMulai && tanggalSelesai) {
        if (new Date(tanggalSelesai) < new Date(tanggalMulai)) {
            errorText.textContent = 'Tanggal selesai harus sama atau lebih besar dari tanggal mulai';
            errorMsg.classList.add('show');
            tanggalGroup.classList.add('has-error');
            if (submitBtn) submitBtn.disabled = true;
            return false;
        } else {
            errorMsg.classList.remove('show');
            tanggalGroup.classList.remove('has-error');
            if (submitBtn) submitBtn.disabled = false;
            return true;
        }
    }
    errorMsg.classList.remove('show');
    tanggalGroup.classList.remove('has-error');
    if (submitBtn) submitBtn.disabled = false;
    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    const tukSel = document.getElementById('tuk_select');
    if (tukSel.value) showTukPreview(tukSel);

    const skemaSel = document.getElementById('skema_select');
    if (skemaSel.value) loadAsesiRekomendasi(skemaSel.value);

    document.querySelector('[name=kuota]')?.addEventListener('input', () => { updateCounter(); refreshDisabled(); });
    
    // Event listeners untuk validasi tanggal real-time
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
    
    if (tanggalMulaiInput && tanggalSelesaiInput) {
        tanggalMulaiInput.addEventListener('change', validateTanggal);
        tanggalMulaiInput.addEventListener('input', validateTanggal);
        tanggalSelesaiInput.addEventListener('change', validateTanggal);
        tanggalSelesaiInput.addEventListener('input', validateTanggal);
        
        // Validasi awal saat loading page
        validateTanggal();
    }
});
</script>
<style>@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }</style>
@endsection
