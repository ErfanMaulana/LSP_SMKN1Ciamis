@extends('admin.layout')

@section('title', 'Tambah Kelompok')
@section('page-title', 'Tambah Kelompok')

@section('content')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; }
    .page-header h2 { font-size:24px; font-weight:700; color:#1e293b; }
    .page-header .subtitle { font-size:14px; color:#64748b; margin-top:4px; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .2s; }
    .btn-primary { background:#0061A5; color:white; }
    .btn-primary:hover { background:#00509e; }
    .btn-outline { background:transparent; border:1.5px solid #cbd5e1; color:#64748b; }
    .btn-outline:hover { border-color:#0061A5; color:#0061A5; }
    .card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.07); border:1px solid #e2e8f0; margin-bottom:24px; }
    .card-header { padding:18px 24px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:10px; }
    .card-header h3 { font-size:16px; font-weight:700; color:#1e293b; }
    .card-body { padding:24px; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    @media(max-width:768px){ .form-grid { grid-template-columns:1fr; } }
    .form-group label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
    .form-group label .required { color:#ef4444; }
    .form-control { width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s; box-sizing:border-box; }
    .form-control:focus { border-color:#0061A5; box-shadow:0 0 0 3px rgba(0,97,165,.1); }
    .error-text { font-size:12px; color:#ef4444; margin-top:4px; }
    .form-hint { font-size:12px; color:#94a3b8; margin-top:5px; }
    .alert-danger { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:14px 20px; border-radius:10px; font-size:14px; margin-bottom:20px; }
    .dd-wrapper { position:relative; }
    .dd-trigger { width:100%; padding:10px 40px 10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; background:white; cursor:pointer; text-align:left; color:#374151; outline:none; display:flex; align-items:center; justify-content:space-between; transition:border-color .2s; box-sizing:border-box; }
    .dd-trigger.open { border-color:#0061A5; box-shadow:0 0 0 3px rgba(0,97,165,.1); }
    .dd-trigger .chevron { font-size:12px; color:#94a3b8; transition:transform .2s; flex-shrink:0; }
    .dd-trigger.open .chevron { transform:rotate(180deg); }
    .dd-panel { display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; background:white; border:1px solid #e2e8f0; border-radius:8px; z-index:100; box-shadow:0 8px 24px rgba(0,0,0,.12); max-height:260px; flex-direction:column; }
    .dd-panel.open { display:flex; }
    .dd-search { padding:8px 10px; border-bottom:1px solid #f1f5f9; }
    .dd-search input { width:100%; padding:7px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box; }
    .dd-search input:focus { border-color:#0061A5; }
    .dd-list { overflow-y:auto; flex:1; }
    .dd-item { padding:9px 14px; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; }
    .dd-item:hover { background:#f0f9ff; }
    .dd-item.selected { background:#eff6ff; color:#0061A5; font-weight:600; }
    .dd-empty { padding:16px; text-align:center; color:#94a3b8; font-size:13px; }
    .badge-list { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; min-height:4px; }
    .badge-chip { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#dbeafe; color:#1d4ed8; border-radius:20px; font-size:12px; font-weight:600; }
    .badge-chip.green { background:#d1fae5; color:#065f46; }
    .badge-chip .rm { cursor:pointer; font-size:15px; color:#93c5fd; line-height:1; }
    .badge-chip.green .rm { color:#6ee7b7; }
    .badge-chip .rm:hover { color:#1d4ed8; }
    .badge-chip.green .rm:hover { color:#065f46; }
    .asesi-checklist { border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; margin-top:8px; }
    .asesi-checklist-search { padding:10px; border-bottom:1px solid #f1f5f9; }
    .asesi-checklist-search input { width:100%; padding:8px 12px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box; }
    .asesi-checklist-search input:focus { border-color:#0061A5; }
    .asesi-checklist-body { max-height:240px; overflow-y:auto; }
    .asesi-check-row { display:flex; align-items:center; gap:10px; padding:10px 14px; border-bottom:1px solid #f9f9f9; cursor:pointer; font-size:13px; }
    .asesi-check-row:last-child { border-bottom:none; }
    .asesi-check-row:hover { background:#f0f9ff; }
    .asesi-check-row.checked { background:#eff6ff; }
    .asesi-check-row input[type=checkbox] { width:16px; height:16px; cursor:pointer; flex-shrink:0; accent-color:#0061A5; }
    .asesi-info .name { font-weight:600; color:#1e293b; }
    .asesi-info .meta { font-size:11px; color:#94a3b8; }
    .asesi-empty-msg { padding:24px; text-align:center; color:#94a3b8; font-size:13px; }
    .locked-box { display:flex; align-items:center; gap:10px; padding:14px 16px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; color:#94a3b8; font-size:13px; }
    .locked-box i { font-size:18px; }
    .form-actions { display:flex; gap:12px; padding-top:8px; }
</style>

@if($errors->any())
<div class="alert-danger">
    <strong>Terjadi kesalahan:</strong>
    <ul style="margin:6px 0 0 16px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="page-header">
    <div>
        <h2>Tambah Kelompok Baru</h2>
        <p class="subtitle">Pilih skema terlebih dahulu, lalu pilih asesor dan asesi yang sesuai</p>
    </div>
    <a href="{{ route('admin.kelompok.index') }}" class="btn btn-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.kelompok.store') }}">
    @csrf

    {{-- Informasi Dasar --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-collection" style="color:#0061A5;font-size:18px;"></i>
            <h3>Informasi Kelompok</h3>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Kelompok <span class="required">*</span></label>
                    <input type="text" name="nama_kelompok" class="form-control"
                           value="{{ old('nama_kelompok') }}" placeholder="Contoh: Kelompok 1 RPL" required>
                    @error('nama_kelompok')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Skema Kompetensi <span class="required">*</span></label>
                    <div class="dd-wrapper" id="skemaWrapper">
                        <button type="button" class="dd-trigger" id="skemaTrigger" onclick="toggleDd('skema')">
                            <span id="skemaTriggerText">-- Pilih Skema --</span>
                            <i class="bi bi-chevron-down chevron"></i>
                        </button>
                        <div class="dd-panel" id="skemaPanel">
                            <div class="dd-search"><input type="text" placeholder="Cari skema..." oninput="filterDd('skema',this.value)"></div>
                            <div class="dd-list" id="skemaList">
                                @foreach($skemas as $skema)
                                    <div class="dd-item" data-id="{{ $skema->id }}" data-nama="{{ $skema->nama_skema }}"
                                         onclick="selectSkema({{ $skema->id }},{{ json_encode($skema->nama_skema) }})">
                                        {{ $skema->nama_skema }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="skema_id" id="skemaIdInput" value="{{ old('skema_id') }}">
                    </div>
                    @error('skema_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Asesor --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-person-badge" style="color:#0061A5;font-size:18px;"></i>
            <h3>Asesor <span style="font-weight:400;font-size:13px;color:#94a3b8;">(1 asesor per kelompok)</span></h3>
        </div>
        <div class="card-body">
            <div id="asesorLocked" class="locked-box">
                <i class="bi bi-lock"></i> Pilih skema terlebih dahulu untuk melihat asesor yang tersedia.
            </div>
            <div id="asesorSection" style="display:none;">
                <div class="dd-wrapper" id="asesorWrapper">
                    <button type="button" class="dd-trigger" id="asesorTrigger" onclick="toggleDd('asesor')">
                        <span id="asesorTriggerText">-- Pilih Asesor --</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <div class="dd-panel" id="asesorPanel">
                        <div class="dd-search"><input type="text" placeholder="Cari asesor..." oninput="filterDd('asesor',this.value)"></div>
                        <div class="dd-list" id="asesorList"></div>
                    </div>
                    <input type="hidden" name="asesor_id" id="asesorIdInput" value="{{ old('asesor_id') }}">
                </div>
                <div class="badge-list" id="asesorBadge"></div>
                @error('asesor_id')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Asesi --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-people-fill" style="color:#0061A5;font-size:18px;"></i>
            <h3>Asesi <span style="font-weight:400;font-size:13px;color:#94a3b8;">(hanya yang memiliki skema sesuai)</span></h3>
        </div>
        <div class="card-body">
            <div id="asesiLocked" class="locked-box">
                <i class="bi bi-lock"></i> Pilih skema terlebih dahulu untuk melihat asesi yang tersedia.
            </div>
            <div id="asesiSection" style="display:none;">
                <div class="badge-list" id="asesiBadges" style="margin-bottom:10px;"></div>
                <div class="asesi-checklist">
                    <div class="asesi-checklist-search">
                        <input type="text" placeholder="Cari asesi berdasarkan nama..." oninput="filterAsesiList(this.value)">
                    </div>
                    <div class="asesi-checklist-body" id="asesiChecklist"></div>
                </div>
                <div class="form-hint" style="margin-top:6px;">Hanya asesi yang memiliki skema yang sesuai dan belum ditugaskan ke kelompok lain yang ditampilkan.</div>
                @error('asesi_niks')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Kelompok</button>
        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-outline">Batal</a>
    </div>
    <div id="asesi-hidden-inputs"></div>
</form>

<script>
const ASESORS = @json($asesorsJson);
const ASESIS  = @json($asesisJson);

let curSkemaId     = null;
let selAsesorId    = null;
let selAsesorNama  = '';
let selAsesis      = new Map(); // nik -> {nik, nama, kelas, jurusan}

// ── Generic dropdown helpers ─────────────────────────────────────────────────
function toggleDd(key) {
    if (key === 'asesor' && !curSkemaId) return;
    const panel   = document.getElementById(key+'Panel');
    const trigger = document.getElementById(key+'Trigger');
    const open    = !panel.classList.contains('open');
    panel.classList.toggle('open', open);
    trigger.classList.toggle('open', open);
    if (open) panel.querySelector('.dd-search input').focus();
}

function filterDd(key, q) {
    q = q.toLowerCase();
    document.querySelectorAll('#'+key+'List .dd-item').forEach(el => {
        el.style.display = (el.dataset.nama||'').toLowerCase().includes(q) ? '' : 'none';
    });
}

// ── Skema ────────────────────────────────────────────────────────────────────
function selectSkema(id, nama) {
    curSkemaId = id;
    document.getElementById('skemaIdInput').value = id;
    document.getElementById('skemaTriggerText').textContent = nama;
    document.querySelectorAll('#skemaList .dd-item').forEach(el =>
        el.classList.toggle('selected', parseInt(el.dataset.id) === id));
    closeDd('skema');

    // Validate current asesor against new skema
    if (selAsesorId !== null) {
        const a = ASESORS.find(x => x.id === selAsesorId);
        if (!a || !a.skema_ids.includes(id)) clearAsesor();
    }

    // Validate current asesis against new skema
    selAsesis.forEach((_, nik) => {
        const a = ASESIS.find(x => x.nik === nik);
        if (!a || !a.skema_ids.includes(id)) selAsesis.delete(nik);
    });

    rebuildAsesorList(id);
    rebuildAsesiList(id);
}

// ── Asesor ───────────────────────────────────────────────────────────────────
function rebuildAsesorList(skemaId) {
    const filtered = ASESORS.filter(a => a.skema_ids.includes(skemaId));
    const list = document.getElementById('asesorList');
    list.innerHTML = filtered.length
        ? filtered.map(a => `<div class="dd-item${selAsesorId===a.id?' selected':''}"
              data-id="${a.id}" data-nama="${escHtml(a.nama)}"
              onclick="selectAsesor(${a.id},${escHtml(JSON.stringify(a.nama))},${escHtml(JSON.stringify(a.no_met || '-'))})">
              <div style="line-height:1.3"><span style="font-weight:600">${escHtml(a.nama)}</span><br><span style="font-size:11px;color:#64748b">No MET: ${escHtml(a.no_met || '-')}</span></div>
            </div>`).join('')
        : `<div style="padding:14px;font-size:12px;color:#94a3b8;text-align:center;line-height:1.6">
             <i class="bi bi-exclamation-circle" style="font-size:18px;display:block;margin-bottom:6px;color:#f59e0b"></i>
             Belum ada asesor yang memiliki skema ini.<br>
             <a href="/admin/asesor" target="_blank" style="color:#0061A5;font-weight:600;">Buka halaman Asesor</a>
             dan tambahkan skema ke asesor terlebih dahulu.
           </div>`;
    document.getElementById('asesorLocked').style.display = 'none';
    document.getElementById('asesorSection').style.display = '';
    renderAsesorBadge();
}

let selAsesorNoMet = '';
function selectAsesor(id, nama, noMet) {
    selAsesorId = id; selAsesorNama = nama; selAsesorNoMet = noMet || '-';
    document.getElementById('asesorIdInput').value = id;
    document.getElementById('asesorTriggerText').textContent = nama + ' (No MET: ' + selAsesorNoMet + ')';
    document.querySelectorAll('#asesorList .dd-item').forEach(el =>
        el.classList.toggle('selected', parseInt(el.dataset.id) === id));
    closeDd('asesor');
    renderAsesorBadge();
}

function clearAsesor() {
    selAsesorId = null; selAsesorNama = ''; selAsesorNoMet = '';
    document.getElementById('asesorIdInput').value = '';
    document.getElementById('asesorTriggerText').textContent = '-- Pilih Asesor --';
    document.querySelectorAll('#asesorList .dd-item').forEach(el => el.classList.remove('selected'));
    renderAsesorBadge();
}

function renderAsesorBadge() {
    document.getElementById('asesorBadge').innerHTML = selAsesorId
        ? `<span class="badge-chip green">${escHtml(selAsesorNama)} <span style="font-size:11px;opacity:.75">(${escHtml(selAsesorNoMet)})</span><span class="rm" onclick="clearAsesor()">&times;</span></span>`
        : '';
}

// ── Asesi ────────────────────────────────────────────────────────────────────
function rebuildAsesiList(skemaId) {
    const filtered = ASESIS.filter(a => a.skema_ids.includes(skemaId));
    document.getElementById('asesiLocked').style.display = 'none';
    document.getElementById('asesiSection').style.display = '';
    const body = document.getElementById('asesiChecklist');
    if (!filtered.length) {
        body.innerHTML = '<div class="asesi-empty-msg"><i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px;"></i>Tidak ada asesi dengan skema ini yang tersedia.</div>';
        renderAsesiBadges(); return;
    }
    body.innerHTML = filtered.map(a => {
        const chk = selAsesis.has(a.nik);
        return `<label class="asesi-check-row${chk?' checked':''}" data-nik="${a.nik}">
            <input type="checkbox" value="${a.nik}"${chk?' checked':''}
                   onchange="toggleAsesi('${a.nik}',${escHtml(JSON.stringify(a.nama))},${escHtml(JSON.stringify(a))})">
            <div class="asesi-info">
                <div class="name">${escHtml(a.nama)}</div>
                <div class="meta">${a.kelas?'Kelas '+a.kelas+' &middot; ':''}${escHtml(a.jurusan)}</div>
            </div>
        </label>`;
    }).join('');
    // Sync hidden inputs
    renderAsesiBadges();
}

function toggleAsesi(nik, nama, data) {
    const row = document.querySelector(`.asesi-check-row[data-nik="${nik}"]`);
    if (selAsesis.has(nik)) {
        selAsesis.delete(nik);
        if (row) { row.classList.remove('checked'); row.querySelector('input').checked = false; }
    } else {
        selAsesis.set(nik, data || {nik, nama});
        if (row) { row.classList.add('checked'); row.querySelector('input').checked = true; }
    }
    renderAsesiBadges();
}

function removeAsesi(nik) {
    selAsesis.delete(nik);
    const row = document.querySelector(`.asesi-check-row[data-nik="${nik}"]`);
    if (row) { row.classList.remove('checked'); row.querySelector('input').checked = false; }
    renderAsesiBadges();
}

function renderAsesiBadges() {
    // Update badge display
    let html = '';
    selAsesis.forEach((data, nik) => {
        html += `<span class="badge-chip">${escHtml(data.nama)}<span class="rm" onclick="removeAsesi('${nik}')">&times;</span></span>`;
    });
    document.getElementById('asesiBadges').innerHTML = html;

    // Sync hidden inputs (primary submission mechanism)
    const container = document.getElementById('asesi-hidden-inputs');
    container.innerHTML = '';
    selAsesis.forEach((_, nik) => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'asesi_niks[]';
        inp.value = nik;
        container.appendChild(inp);
    });

    // Clear checkbox names (not used for submission)
    document.querySelectorAll('.asesi-check-row input[type=checkbox]').forEach(cb => {
        cb.name = '';
    });
}

function filterAsesiList(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.asesi-check-row').forEach(row => {
        const name = row.querySelector('.name')?.textContent.toLowerCase() || '';
        row.style.display = name.includes(q) ? '' : 'none';
    });
}

function closeDd(key) {
    document.getElementById(key+'Panel').classList.remove('open');
    document.getElementById(key+'Trigger').classList.remove('open');
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Close on outside click
document.addEventListener('click', e => {
    ['skema','asesor'].forEach(key => {
        const w = document.getElementById(key+'Wrapper');
        if (w && !w.contains(e.target)) closeDd(key);
    });
});

// ── Ensure hidden inputs are populated before form submission ──────────────────
document.querySelector('form').addEventListener('submit', function() {
    const container = document.getElementById('asesi-hidden-inputs');
    if (container) {
        container.innerHTML = '';
        selAsesis.forEach((_, nik) => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'asesi_niks[]';
            inp.value = nik;
            container.appendChild(inp);
        });
    }
});

// Init old() values on validation fail (populate selAsesis BEFORE rebuilding lists)
@foreach(old('asesi_niks', []) as $nik)
(function(){
    const a = ASESIS.find(x => x.nik === {{ json_encode($nik) }});
    if (a) selAsesis.set(a.nik, a);
})();
@endforeach
@if(old('skema_id'))
(function(){
    const el = document.querySelector('#skemaList .dd-item[data-id="{{ old('skema_id') }}"]');
    if (el) selectSkema({{ old('skema_id') }}, el.dataset.nama);
})();
@endif
@if(old('asesor_id'))
(function(){
    const a = ASESORS.find(x => x.id === {{ old('asesor_id') }});
    if (a) {
        selAsesorId = a.id; selAsesorNama = a.nama; selAsesorNoMet = a.no_met || '-';
        document.getElementById('asesorIdInput').value = a.id;
        document.getElementById('asesorTriggerText').textContent = a.nama + ' (No MET: ' + selAsesorNoMet + ')';
        renderAsesorBadge();
    }
})();
@endif
</script>
@endsection
