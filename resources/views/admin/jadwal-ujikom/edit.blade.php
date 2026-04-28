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
    .form-control[multiple] { min-height: 156px; }
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
                @php
                    $kelompokScheduleMap = $kelompokScheduleMap ?? [];
                    $kelompokOptions = $kelompoks->map(function ($k) use ($kelompokScheduleMap) {
                        return [
                            'id' => (int) $k->id,
                            'nama' => $k->nama_kelompok,
                            'skema' => $k->skema?->nama_skema ?? '-',
                            'asesi_count' => $k->asesis->count(),
                            'locked_jadwal' => $kelompokScheduleMap[$k->id] ?? null,
                        ];
                    })->values();
                @endphp
                <div id="kelompok-hidden-inputs"></div>

                <div style="position:relative;" id="kelompok-dropdown-wrap">
                    <div id="kelompok-trigger" onclick="toggleKelompokDropdown()"
                         style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;border:1px solid {{ ($errors->has('kelompok_ids') || $errors->has('kelompok_ids.*')) ? '#ef4444' : '#d1d5db' }};border-radius:8px;
                                background:#f8fafc;cursor:pointer;font-size:13px;user-select:none;
                                transition:border-color .2s,box-shadow .2s;">
                        <span id="kelompok-trigger-text" style="color:#94a3b8;">Pilih kelompok...</span>
                        <i class="bi bi-chevron-down" id="kelompok-chevron"
                           style="font-size:12px;color:#64748b;transition:transform .2s;"></i>
                    </div>

                    <div id="kelompok-dropdown"
                         style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:300;
                                background:white;border:1px solid #e2e8f0;border-radius:8px;
                                box-shadow:0 8px 24px rgba(0,0,0,.12);overflow:hidden;">
                        <div style="padding:8px 10px;border-bottom:1px solid #f1f5f9;">
                            <input type="text" id="kelompok-search" autocomplete="off"
                                   placeholder="Cari nama kelompok atau skema..."
                                   oninput="filterKelompok(this.value)"
                                   style="width:100%;padding:7px 10px;border:1px solid #e2e8f0;
                                          border-radius:6px;font-size:13px;outline:none;
                                          font-family:inherit;background:#f8fafc;">
                        </div>
                        <div id="kelompok-list" style="max-height:240px;overflow-y:auto;"></div>
                    </div>
                </div>

                <div id="kelompok-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>

                @if($errors->has('kelompok_ids') || $errors->has('kelompok_ids.*'))
                    <div class="invalid-feedback">{{ $errors->first('kelompok_ids') ?: $errors->first('kelompok_ids.*') }}</div>
                @endif
                <span class="hint">Bisa pilih lebih dari satu kelompok. Satu kelompok hanya boleh berada pada satu jadwal.</span>

                <div class="kelompok-preview" id="kelompok_preview">
                    <div class="kelompok-preview-row">
                        <span class="kelompok-preview-label"><i class="bi bi-diagram-3"></i> Kelompok</span>
                        <span id="prev_kelompok">—</span>
                    </div>
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

            @php
                $todayDate = now()->toDateString();
                $initialTanggalMulai = old('tanggal_mulai', $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('Y-m-d') : '');
                $initialTanggalSelesai = old('tanggal_selesai', $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('Y-m-d') : '');
                $minTanggalMulai = ($initialTanggalMulai && $initialTanggalMulai < $todayDate) ? $initialTanggalMulai : $todayDate;
                $minTanggalSelesai = ($initialTanggalSelesai && $initialTanggalSelesai < $todayDate) ? $initialTanggalSelesai : $todayDate;
            @endphp

            {{-- ─── Tanggal Mulai ─── --}}
            <div class="form-group">
                <label>Tanggal Mulai <span class="required">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                       value="{{ $initialTanggalMulai }}"
                       min="{{ $minTanggalMulai }}"
                       data-original="{{ $initialTanggalMulai }}"
                       data-today="{{ $todayDate }}"
                       class="form-control {{ $errors->has('tanggal_mulai') ? 'is-invalid' : '' }}">
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ─── Tanggal Selesai ─── --}}
            <div class="form-group" id="tanggal_selesai_group">
                <label>Tanggal Selesai <span class="required">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                       value="{{ $initialTanggalSelesai }}"
                       min="{{ $minTanggalSelesai }}"
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
const KELOMPOK_SOURCE = @json($kelompokOptions);
const PRESELECTED_KELOMPOK_IDS = @json($selectedKelompokIds ?? []);

let selectedKelompoks = new Map();
let kelompokOpen = false;

PRESELECTED_KELOMPOK_IDS.forEach(function(id) {
    const found = KELOMPOK_SOURCE.find(function(k) { return k.id === Number(id); });
    if (found) {
        selectedKelompoks.set(found.id, found);
    }
});

function renderKelompokTriggerText() {
    const el = document.getElementById('kelompok-trigger-text');
    if (selectedKelompoks.size === 0) {
        el.textContent = 'Pilih kelompok...';
        el.style.color = '#94a3b8';
    } else {
        el.textContent = selectedKelompoks.size + ' kelompok dipilih';
        el.style.color = '#1e293b';
    }
}

function renderKelompokBadges() {
    const badgeWrap = document.getElementById('kelompok-badges');
    const hiddenWrap = document.getElementById('kelompok-hidden-inputs');

    badgeWrap.innerHTML = '';
    hiddenWrap.innerHTML = '';

    selectedKelompoks.forEach(function(kelompok) {
        const badge = document.createElement('span');
        badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;padding:4px 10px 4px 12px;background:#dbeafe;color:#1d4ed8;border-radius:20px;font-size:12px;font-weight:600;';
        badge.innerHTML = kelompok.nama + ' <button type="button" onclick="removeKelompok(' + kelompok.id + ')" style="background:none;border:none;cursor:pointer;color:#1d4ed8;font-size:15px;line-height:1;padding:0;">&times;</button>';
        badgeWrap.appendChild(badge);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'kelompok_ids[]';
        input.value = String(kelompok.id);
        hiddenWrap.appendChild(input);
    });

    if (selectedKelompoks.size === 0) {
        badgeWrap.innerHTML = '<span style="font-size:12px;color:#94a3b8;">Belum ada kelompok dipilih.</span>';
    }

    renderKelompokTriggerText();
    onKelompokChange();
}

function removeKelompok(id) {
    selectedKelompoks.delete(Number(id));
    renderKelompokBadges();
    if (kelompokOpen) {
        buildKelompokItems(currentKelompokList());
    }
}

function selectKelompok(id) {
    const found = KELOMPOK_SOURCE.find(function(k) { return k.id === Number(id); });
    if (!found) {
        return;
    }

    if (found.locked_jadwal && !selectedKelompoks.has(found.id)) {
        return;
    }

    selectedKelompoks.set(found.id, found);
    renderKelompokBadges();
    buildKelompokItems(currentKelompokList());
}

function currentKelompokList() {
    const q = (document.getElementById('kelompok-search').value || '').toLowerCase().trim();
    if (!q) {
        return KELOMPOK_SOURCE;
    }

    return KELOMPOK_SOURCE.filter(function(k) {
        return (k.nama || '').toLowerCase().includes(q)
            || (k.skema || '').toLowerCase().includes(q);
    });
}

function buildKelompokItems(list) {
    const wrap = document.getElementById('kelompok-list');
    wrap.innerHTML = '';

    if (!list.length) {
        wrap.innerHTML = '<div style="padding:10px 14px;color:#94a3b8;font-size:13px;">Tidak ada hasil.</div>';
        return;
    }

    list.forEach(function(item) {
        const selected = selectedKelompoks.has(item.id);
        const locked = Boolean(item.locked_jadwal) && !selected;

        const row = document.createElement('div');
        row.style.cssText = 'padding:9px 14px;font-size:13px;display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px solid #f8fafc;cursor:' + ((selected || locked) ? 'default' : 'pointer') + ';' + ((selected || locked) ? 'color:#94a3b8;' : 'color:#1e293b;');

        let status = '';
        if (selected) {
            status = '<span style="font-size:11px;color:#16a34a;font-weight:600;white-space:nowrap;">✓ Dipilih</span>';
        } else if (locked) {
            status = '<span style="font-size:11px;color:#b45309;font-weight:600;white-space:nowrap;">Sudah dijadwal</span>';
        }

        row.innerHTML = '<div style="display:flex;flex-direction:column;gap:2px;">'
            + '<span>' + item.nama + '</span>'
            + '<span style="font-size:11px;color:#94a3b8;">' + item.skema + ' • ' + item.asesi_count + ' asesi' + (item.locked_jadwal ? ' • ' + item.locked_jadwal : '') + '</span>'
            + '</div>'
            + status;

        if (!selected && !locked) {
            row.onclick = function() { selectKelompok(item.id); };
            row.onmouseover = function() { row.style.background = '#f0f9ff'; };
            row.onmouseout = function() { row.style.background = ''; };
        }

        wrap.appendChild(row);
    });
}

function filterKelompok() {
    buildKelompokItems(currentKelompokList());
}

function openKelompokDropdown() {
    kelompokOpen = true;
    document.getElementById('kelompok-search').value = '';
    buildKelompokItems(KELOMPOK_SOURCE);
    document.getElementById('kelompok-dropdown').style.display = '';

    const trigger = document.getElementById('kelompok-trigger');
    trigger.style.borderColor = '#0061a5';
    trigger.style.boxShadow = '0 0 0 3px rgba(0,97,165,.1)';
    trigger.style.background = 'white';
    document.getElementById('kelompok-chevron').style.transform = 'rotate(180deg)';
}

function closeKelompokDropdown() {
    kelompokOpen = false;
    document.getElementById('kelompok-dropdown').style.display = 'none';

    const trigger = document.getElementById('kelompok-trigger');
    trigger.style.borderColor = '{{ ($errors->has('kelompok_ids') || $errors->has('kelompok_ids.*')) ? '#ef4444' : '#d1d5db' }}';
    trigger.style.boxShadow = '';
    trigger.style.background = '#f8fafc';
    document.getElementById('kelompok-chevron').style.transform = '';
}

function toggleKelompokDropdown() {
    if (kelompokOpen) {
        closeKelompokDropdown();
    } else {
        openKelompokDropdown();
    }
}

document.addEventListener('click', function(e) {
    const wrap = document.getElementById('kelompok-dropdown-wrap');
    if (wrap && !wrap.contains(e.target)) {
        closeKelompokDropdown();
    }
});

function onKelompokChange() {
    const preview = document.getElementById('kelompok_preview');
    const selectedIds = Array.from(selectedKelompoks.keys());

    if (!selectedIds.length) {
        preview.classList.remove('show');
        return;
    }

    const selectedData = selectedIds.map(function(id) { return KELOMPOK_DATA[id]; }).filter(Boolean);
    const skemaSet = new Set(selectedData.map(d => d.nama_skema || '—'));
    const asesorSet = new Set(selectedData.map(d => d.asesor_nama || '(belum ada asesor)'));
    const pesertaSet = new Set();

    selectedData.forEach(function(d) {
        (d.asesi_niks || []).forEach(function(nik) {
            pesertaSet.add(String(nik));
        });
    });

    document.getElementById('prev_kelompok').textContent = selectedData.map(d => d.nama_kelompok).join(', ');
    document.getElementById('prev_skema').textContent = skemaSet.size === 1
        ? Array.from(skemaSet)[0]
        : 'Beragam (' + skemaSet.size + ' skema)';
    document.getElementById('prev_asesor').textContent = asesorSet.size === 1
        ? Array.from(asesorSet)[0]
        : 'Beragam (' + asesorSet.size + ' asesor)';
    document.getElementById('prev_asesi').textContent = pesertaSet.size + ' peserta dari ' + selectedIds.length + ' kelompok';
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

async function validateTanggal() {
    const tMulaiEl = document.getElementById('tanggal_mulai');
    const tMulai   = tMulaiEl?.value;
    const tSelesaiEl = document.getElementById('tanggal_selesai');
    const tSelesai = tSelesaiEl?.value;
    const today    = tMulaiEl?.dataset.today || '';
    const original = tMulaiEl?.dataset.original || '';
    const grp      = document.getElementById('tanggal_selesai_group');
    const btn      = document.querySelector('button[type="submit"]');

    if (tMulaiEl && tSelesaiEl) {
        // enforce two-way constraints: selesai.min = mulai (or original/today), mulai.max = selesai
        const minForSelesai = tMulai || original || today || '{{ now()->toDateString() }}';
        tSelesaiEl.min = minForSelesai;
        tMulaiEl.max = tSelesai || '';

        if (tMulai && tSelesai && tSelesai < tMulai) {
            tSelesaiEl.value = '';
        }
        if (tMulai && tSelesai && tMulai > tSelesai) {
            tMulaiEl.value = '';
        }
    }

    if (!tMulai || !tSelesai) {
        if (btn) btn.disabled = true;
        grp.classList.remove('has-error');
        return;
    }

    // if tanggal_mulai equals original, allow past date (skip today check)
    const skipToday = (original && tMulai === original) ? true : false;

    const token = document.querySelector('input[name="_token"]')?.value || '';

    try {
        const res = await fetch("{{ route('admin.jadwal-ujikom.validate-dates') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ tanggal_mulai: tMulai, tanggal_selesai: tSelesai, skip_today: skipToday })
        });

        const json = await res.json();
        const valid = json.valid === true;
        if (btn) btn.disabled = !valid;
        grp.classList.toggle('has-error', !valid);
    } catch (e) {
        if (btn) btn.disabled = false;
        grp.classList.remove('has-error');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderKelompokBadges();

    const tukSel = document.getElementById('tuk_select');
    if (tukSel?.value) showTukPreview(tukSel);

    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    if (tanggalMulai && tanggalSelesai) {
        const originalTanggal = tanggalMulai.dataset.original || '';
        const todayTanggal = tanggalMulai.dataset.today || '';
        tanggalSelesai.min = tanggalMulai.value || originalTanggal || todayTanggal || '{{ now()->toDateString() }}';
        tanggalMulai.max = tanggalSelesai.value || '';

        tanggalMulai.addEventListener('change', validateTanggal);
        tanggalSelesai.addEventListener('change', validateTanggal);

        tanggalMulai.addEventListener('change', function() {
            tanggalSelesai.min = this.value || originalTanggal || todayTanggal || '{{ now()->toDateString() }}';
            if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                tanggalSelesai.value = '';
            }
            tanggalMulai.max = tanggalSelesai.value || '';
        });

        tanggalSelesai.addEventListener('change', function() {
            tanggalMulai.max = this.value || '';
            if (tanggalMulai.value && tanggalMulai.value > this.value) {
                tanggalMulai.value = '';
            }
            tanggalSelesai.min = tanggalMulai.value || originalTanggal || todayTanggal || '{{ now()->toDateString() }}';
        });
    }
    validateTanggal();
});
</script>
@endsection
