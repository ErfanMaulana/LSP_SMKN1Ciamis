@extends('admin.layout')

@section('title', 'Tambah Asesor')
@section('page-title', 'Tambah Asesor')

@section('content')
<div class="page-header">
    <h2>Tambah Data Asesor</h2>
    <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.asesor.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Informasi Asesor</h3>
                
                <div class="form-group">
                    <label for="nama">Nama Asesor <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required autofocus>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_met">NO MET <span class="required">*</span></label>
                    <input type="text" id="no_met" name="no_met"
                           class="form-control @error('no_met') is-invalid @enderror"
                           value="{{ old('no_met') }}"
                           placeholder="Contoh: ASR001">
                    @error('no_met')
                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Digunakan sebagai username login ke panel asesor. Password awal = NO MET.</small>
                </div>

                <div class="form-group">
                    <label>Skema Kompetensi</label>
                    <div id="skema-hidden-inputs"></div>

                    <div style="position:relative;" id="skema-dropdown-wrap">
                        {{-- Trigger --}}
                        <div id="skema-trigger" onclick="toggleSkemaDropdown()"
                             style="display:flex;justify-content:space-between;align-items:center;
                                    padding:10px 14px;border:1px solid #e2e8f0;border-radius:6px;
                                    background:#f8fafc;cursor:pointer;font-size:14px;user-select:none;
                                    transition:border-color .2s,box-shadow .2s;">
                            <span id="skema-trigger-text" style="color:#94a3b8;">Pilih skema kompetensi...</span>
                            <i class="bi bi-chevron-down" id="skema-chevron"
                               style="font-size:12px;color:#64748b;transition:transform .2s;"></i>
                        </div>

                        {{-- Dropdown panel --}}
                        <div id="skema-dropdown"
                             style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:300;
                                    background:white;border:1px solid #e2e8f0;border-radius:8px;
                                    box-shadow:0 8px 24px rgba(0,0,0,.12);overflow:hidden;">
                            <div style="padding:8px 10px;border-bottom:1px solid #f1f5f9;">
                                <input type="text" id="skema-search" autocomplete="off"
                                       placeholder="&#128269; Cari nama atau kode skema..."
                                       oninput="filterSkema(this.value)"
                                       style="width:100%;padding:7px 10px;border:1px solid #e2e8f0;
                                              border-radius:6px;font-size:13px;outline:none;
                                              font-family:inherit;background:#f8fafc;">
                            </div>
                            <div id="skema-list" style="max-height:220px;overflow-y:auto;"></div>
                        </div>
                    </div>

                    <div id="skema-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>

                    @error('skema_ids')
                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Pilih satu atau lebih skema sertifikasi (opsional)</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.asesor.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const ALL_SKEMAS = @json($skema->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_skema, 'nomor' => $s->nomor_skema]));
let selectedSkemas = new Map();
let skemaOpen = false;

@foreach(old('skema_ids', []) as $oldId)
    (function() {
        const s = ALL_SKEMAS.find(x => x.id == {{ $oldId }});
        if (s) selectedSkemas.set(s.id, s);
    })();
@endforeach

function renderTriggerText() {
    const el = document.getElementById('skema-trigger-text');
    if (selectedSkemas.size === 0) {
        el.textContent = 'Pilih skema kompetensi...';
        el.style.color = '#94a3b8';
    } else {
        el.textContent = selectedSkemas.size + ' skema dipilih';
        el.style.color = '#1e293b';
    }
}

function renderBadges() {
    const badgeWrap  = document.getElementById('skema-badges');
    const hiddenWrap = document.getElementById('skema-hidden-inputs');
    badgeWrap.innerHTML  = '';
    hiddenWrap.innerHTML = '';
    selectedSkemas.forEach(s => {
        const badge = document.createElement('span');
        badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;padding:4px 10px 4px 12px;background:#dbeafe;color:#1d4ed8;border-radius:20px;font-size:12px;font-weight:600;';
        badge.innerHTML = `${s.nama} <button type="button" onclick="removeSkema(${s.id})" style="background:none;border:none;cursor:pointer;color:#1d4ed8;font-size:15px;line-height:1;padding:0;">&times;</button>`;
        badgeWrap.appendChild(badge);
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'skema_ids[]'; inp.value = s.id;
        hiddenWrap.appendChild(inp);
    });
    if (selectedSkemas.size === 0) {
        badgeWrap.innerHTML = '<span style="font-size:12px;color:#94a3b8;">Belum ada skema dipilih.</span>';
    }
    renderTriggerText();
}

function removeSkema(id) {
    selectedSkemas.delete(id);
    renderBadges();
    if (skemaOpen) buildDropdownItems(currentSkemaList());
}

function selectSkema(id) {
    const s = ALL_SKEMAS.find(x => x.id === id);
    if (s) selectedSkemas.set(s.id, s);
    renderBadges();
    buildDropdownItems(currentSkemaList());
}

function currentSkemaList() {
    const q = (document.getElementById('skema-search').value || '').toLowerCase().trim();
    return q ? ALL_SKEMAS.filter(s => s.nama.toLowerCase().includes(q) || s.nomor.toLowerCase().includes(q)) : ALL_SKEMAS;
}

function buildDropdownItems(list) {
    const el = document.getElementById('skema-list');
    el.innerHTML = '';
    if (!list.length) {
        el.innerHTML = '<div style="padding:10px 14px;color:#94a3b8;font-size:13px;">Tidak ada hasil.</div>';
        return;
    }
    list.forEach(s => {
        const item = document.createElement('div');
        const already = selectedSkemas.has(s.id);
        item.style.cssText = `padding:9px 14px;font-size:13px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f8fafc;cursor:${already?'default':'pointer'};${already?'color:#94a3b8;':'color:#1e293b;'}`;
        item.innerHTML = `<span>${s.nama} <span style="font-size:11px;color:#94a3b8;">(${s.nomor})</span></span>${already?'<span style="font-size:11px;color:#16a34a;font-weight:600;">&#10003; Dipilih</span>':''}`;
        if (!already) {
            item.onclick = () => selectSkema(s.id);
            item.onmouseover = () => item.style.background = '#f0f9ff';
            item.onmouseout  = () => item.style.background = '';
        }
        el.appendChild(item);
    });
}

function filterSkema(q) {
    buildDropdownItems(currentSkemaList());
}

function openSkemaDropdown() {
    skemaOpen = true;
    document.getElementById('skema-search').value = '';
    buildDropdownItems(ALL_SKEMAS);
    document.getElementById('skema-dropdown').style.display = '';
    const trigger = document.getElementById('skema-trigger');
    trigger.style.borderColor = '#0073bd';
    trigger.style.boxShadow   = '0 0 0 3px rgba(0,115,189,.1)';
    trigger.style.background  = 'white';
    document.getElementById('skema-chevron').style.transform = 'rotate(180deg)';
}

function closeSkemaDropdown() {
    skemaOpen = false;
    document.getElementById('skema-dropdown').style.display = 'none';
    const trigger = document.getElementById('skema-trigger');
    trigger.style.borderColor = '#e2e8f0';
    trigger.style.boxShadow   = '';
    trigger.style.background  = '#f8fafc';
    document.getElementById('skema-chevron').style.transform = '';
}

function toggleSkemaDropdown() {
    skemaOpen ? closeSkemaDropdown() : openSkemaDropdown();
}

document.addEventListener('click', e => {
    if (!document.getElementById('skema-dropdown-wrap').contains(e.target)) {
        closeSkemaDropdown();
    }
});

document.addEventListener('DOMContentLoaded', renderBadges);
</script>

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

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #0073bd;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
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
