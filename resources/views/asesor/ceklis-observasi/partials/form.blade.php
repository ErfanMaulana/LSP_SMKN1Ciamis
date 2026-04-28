@php
    $record = $item ?? null;
    $defaults = $defaults ?? [];
    $activeSkema = $activeSkema ?? null;

    $value = function (string $field, $fallback = '') use ($record, $defaults) {
        if (old($field) !== null) {
            return old($field);
        }

        if ($record && isset($record->{$field})) {
            return $record->{$field};
        }

        return $defaults[$field] ?? $fallback;
    };

    $selectedSkemaId = (string) $value('skema_id', (string) ($activeSkema->id ?? ''));
    if (!$activeSkema && $record && isset($record->skema)) {
        $activeSkema = $record->skema;
    }
    $activeSkemaNama = $activeSkema->nama_skema ?? '-';
    $activeSkemaNomor = $activeSkema->nomor_skema ?? '';
    $selectedAsesiNik = (string) $value('asesi_nik', '');

    $initialDetailMap = old('detail');
    if (!$initialDetailMap && $record) {
        $initialDetailMap = $record->details->mapWithKeys(function ($detail) {
            return [
                (string) $detail->kriteria_id => [
                    'unit_id' => $detail->unit_id,
                    'elemen_id' => $detail->elemen_id,
                    'kriteria_id' => $detail->kriteria_id,
                    'pencapaian' => $detail->pencapaian,
                    'penilaian_lanjut' => $detail->penilaian_lanjut,
                ],
            ];
        })->toArray();
    }

    $selectedTanggal = old('tanggal', ($record && $record->tanggal) ? $record->tanggal->format('Y-m-d') : '');
    $ttdAsesiTanggal = old('ttd_asesi_tanggal', ($record && $record->ttd_asesi_tanggal) ? $record->ttd_asesi_tanggal->format('Y-m-d') : '');
    $ttdAsesorTanggal = old('ttd_asesor_tanggal', ($record && $record->ttd_asesor_tanggal) ? $record->ttd_asesor_tanggal->format('Y-m-d') : '');
@endphp

<style>
    .card-form { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.08); padding:20px; }
    .grid-2 { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .field { display:flex; flex-direction:column; gap:6px; }
    .field.full { grid-column:1 / -1; }
    .field label { font-size:13px; font-weight:600; color:#334155; }
    .field input, .field textarea, .field select { border:1px solid #d1d5db; border-radius:8px; padding:9px 11px; font-size:13px; }
    .field textarea { min-height:76px; resize:vertical; }
    .error-text { font-size:12px; color:#dc2626; }
    .req { color:#dc2626; }

    .checklist-wrapper { margin-top:16px; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; }
    .checklist-head { background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:10px 12px; display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap; }
    .checklist-tools { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-outline { border:1px solid #cbd5e1; border-radius:8px; background:#fff; color:#0f172a; padding:7px 10px; font-size:12px; cursor:pointer; }

    .table-wrap { overflow-x:auto; }
    table { width:100%; min-width:900px; border-collapse:collapse; }
    th, td { border:1px solid #e2e8f0; padding:8px 10px; font-size:13px; vertical-align:top; }
    th { background:#f8fafc; text-align:center; }

    .pencapaian-wrap { display:flex; justify-content:center; gap:10px; }
    .pencapaian-wrap label { font-size:12px; display:inline-flex; align-items:center; gap:4px; }

    .rekom-box { margin-top:14px; border:1px solid #cbd5e1; border-radius:8px; }
    .rekom-head { padding:10px 12px; background:#f8fafc; border-bottom:1px solid #cbd5e1; font-weight:700; font-size:13px; }
    .rekom-content { padding:12px; }

    .form-actions { margin-top:16px; display:flex; gap:8px; flex-wrap:wrap; }
    .btn-primary { background:#0073bd; color:#fff; border:none; border-radius:8px; padding:9px 14px; font-size:13px; cursor:pointer; }
    .btn-secondary { background:#64748b; color:#fff; border:none; border-radius:8px; padding:9px 14px; font-size:13px; text-decoration:none; }

    .hidden { display:none; }

    @media (max-width:768px) {
        .grid-2 { grid-template-columns:1fr; }
    }
</style>

<div class="card-form">
    <div class="grid-2">
        <div class="field">
            <label>Kode Form <span class="req">*</span></label>
            <input type="text" name="kode_form" value="{{ $value('kode_form', '') }}">
            @error('kode_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Judul Form <span class="req">*</span></label>
            <input type="text" name="judul_form" value="{{ $value('judul_form', '') }}">
            @error('judul_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema <span class="req">*</span></label>
            <input type="hidden" id="skemaIdInput" name="skema_id" value="{{ $selectedSkemaId }}" data-nomor="{{ $activeSkemaNomor }}">
            <input type="text" value="{{ $activeSkemaNama }}" readonly>
            @error('skema_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nomor Skema</label>
            <input id="nomorSkemaDisplay" type="text" value="" readonly>
        </div>

        <div class="field">
            <label>Asesi <span class="req">*</span></label>
            <select id="asesiSelect" name="asesi_nik">
                <option value="">-- Pilih Asesi --</option>
            </select>
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>TUK</label>
            <input type="text" name="tuk" value="{{ $value('tuk', '') }}">
            @error('tuk')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ $selectedTanggal }}">
            @error('tanggal')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Catatan Footer</label>
            <input type="text" name="catatan_footer" value="{{ $value('catatan_footer', '') }}">
        </div>
    </div>

    <div class="checklist-wrapper">
        <div class="checklist-head">
            <strong>Daftar Ceklis Per KUK</strong>
            <div class="checklist-tools">
                <button type="button" class="btn-outline" id="bulkYaBtn">Set Semua Ya</button>
                <button type="button" class="btn-outline" id="bulkTidakBtn">Set Semua Tidak</button>
                <button type="button" class="btn-outline" id="clearBtn">Kosongkan</button>
            </div>
        </div>
        <div id="checklistContainer"></div>
    </div>

    <div class="rekom-box">
        <div class="rekom-head">Rekomendasi</div>
        <div class="rekom-content">
            <div style="display:flex;flex-direction:column;gap:6px;">
                <label><input type="radio" name="rekomendasi" value="kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'kompeten' ? 'checked' : '' }}> Asesi direkomendasikan <strong>KOMPETEN</strong></label>
                <label><input type="radio" name="rekomendasi" value="belum_kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'belum_kompeten' ? 'checked' : '' }}> Asesi direkomendasikan <strong>BELUM KOMPETEN</strong></label>
            </div>
            @error('rekomendasi')<div class="error-text">{{ $message }}</div>@enderror

            <div id="belumKompetenFields" class="grid-2 {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'belum_kompeten' ? '' : 'hidden' }}" style="margin-top:10px;">
                <div class="field"><label>Kelompok Pekerjaan</label><input type="text" name="belum_kompeten_kelompok_pekerjaan" value="{{ $value('belum_kompeten_kelompok_pekerjaan', '') }}"></div>
                <div class="field"><label>Unit</label><input type="text" name="belum_kompeten_unit" value="{{ $value('belum_kompeten_unit', '') }}"></div>
                <div class="field"><label>Elemen</label><input type="text" name="belum_kompeten_elemen" value="{{ $value('belum_kompeten_elemen', '') }}"></div>
                <div class="field"><label>KUK</label><input type="text" name="belum_kompeten_kuk" value="{{ $value('belum_kompeten_kuk', '') }}"></div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="margin-top:14px;">
        <div class="field"><label>Nama Asesi</label><input type="text" name="ttd_asesi_nama" value="{{ $value('ttd_asesi_nama', '') }}"></div>
        <div class="field"><label>Tanggal TTD Asesi</label><input type="date" name="ttd_asesi_tanggal" value="{{ $ttdAsesiTanggal }}"></div>
        <div class="field"><label>Nama Asesor</label><input type="text" name="ttd_asesor_nama" value="{{ $value('ttd_asesor_nama', '') }}"></div>
        <div class="field"><label>No Reg Asesor</label><input type="text" name="ttd_asesor_no_reg" value="{{ $value('ttd_asesor_no_reg', '') }}"></div>
        <div class="field"><label>Tanggal TTD Asesor</label><input type="date" name="ttd_asesor_tanggal" value="{{ $ttdAsesorTanggal }}"></div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary"><i class="bi bi-save"></i> {{ $submitLabel }}</button>
        <a href="{{ route('asesor.ceklis-observasi.index') }}" class="btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skemaIdInput = document.getElementById('skemaIdInput');
    const nomorSkemaDisplay = document.getElementById('nomorSkemaDisplay');
    const asesiSelect = document.getElementById('asesiSelect');
    const checklistContainer = document.getElementById('checklistContainer');
    const bulkYaBtn = document.getElementById('bulkYaBtn');
    const bulkTidakBtn = document.getElementById('bulkTidakBtn');
    const clearBtn = document.getElementById('clearBtn');
    const rekomendasiInputs = document.querySelectorAll('input[name="rekomendasi"]');
    const belumKompetenFields = document.getElementById('belumKompetenFields');

    const participantsUrl = '{{ route('asesor.ceklis-observasi.skema-participants') }}';
    const structureUrl = '{{ route('asesor.ceklis-observasi.skema-structure') }}';
    const selectedAsesiNik = @json($selectedAsesiNik);
    const initialDetailMap = @json($initialDetailMap ?? []);
    let firstHydration = true;

    const setNomorSkema = () => {
        nomorSkemaDisplay.value = skemaIdInput ? (skemaIdInput.getAttribute('data-nomor') || '') : '';
    };

    const resetAsesi = (placeholder) => {
        asesiSelect.innerHTML = '';
        const op = document.createElement('option');
        op.value = '';
        op.textContent = placeholder;
        asesiSelect.appendChild(op);
    };

    const fillAsesi = (items, selectedValue) => {
        resetAsesi('-- Pilih Asesi --');
        items.forEach((item) => {
            const op = document.createElement('option');
            op.value = item.id;
            op.textContent = `${item.nama} (${item.id})`;
            if (selectedValue && selectedValue === item.id) {
                op.selected = true;
            }
            asesiSelect.appendChild(op);
        });
    };

    const createHiddenInput = (name, value) => {
        const i = document.createElement('input');
        i.type = 'hidden';
        i.name = name;
        i.value = value;
        return i;
    };

    const createPencapaianCell = (kriteriaId, value) => {
        const wrap = document.createElement('div');
        wrap.className = 'pencapaian-wrap';

        const yes = document.createElement('label');
        const yesRadio = document.createElement('input');
        yesRadio.type = 'radio';
        yesRadio.name = `detail[${kriteriaId}][pencapaian]`;
        yesRadio.value = 'ya';
        if (value === 'ya') {
            yesRadio.checked = true;
        }
        yes.appendChild(yesRadio);
        yes.appendChild(document.createTextNode(' Ya'));

        const no = document.createElement('label');
        const noRadio = document.createElement('input');
        noRadio.type = 'radio';
        noRadio.name = `detail[${kriteriaId}][pencapaian]`;
        noRadio.value = 'tidak';
        if (value === 'tidak') {
            noRadio.checked = true;
        }
        no.appendChild(noRadio);
        no.appendChild(document.createTextNode(' Tidak'));

        wrap.appendChild(yes);
        wrap.appendChild(no);
        return wrap;
    };

    const renderChecklist = (units) => {
        checklistContainer.innerHTML = '';

        if (!units || units.length === 0) {
            checklistContainer.innerHTML = '<div style="padding:12px;color:#64748b;font-size:13px;">Skema belum memiliki data unit/elemen/kriteria.</div>';
            return;
        }

        let noGlobal = 1;

        units.forEach((unit) => {
            const section = document.createElement('div');
            section.style.borderTop = '1px solid #e2e8f0';

            const head = document.createElement('div');
            head.style.padding = '9px 12px';
            head.style.background = '#f8fafc';
            head.style.fontSize = '13px';
            head.style.fontWeight = '700';
            head.textContent = `${unit.kode_unit} - ${unit.judul_unit}`;
            section.appendChild(head);

            const wrap = document.createElement('div');
            wrap.className = 'table-wrap';

            const table = document.createElement('table');
            table.innerHTML = `
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th style="width:220px;">Elemen</th>
                        <th>Kriteria Unjuk Kerja</th>
                        <th style="width:170px;">Pencapaian</th>
                        <th style="width:220px;">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;

            const tbody = table.querySelector('tbody');

            unit.elemens.forEach((elemen) => {
                elemen.kriteria.forEach((kriteria) => {
                    const key = String(kriteria.id);
                    const pre = initialDetailMap[key] || {};

                    const tr = document.createElement('tr');

                    const tdNo = document.createElement('td');
                    tdNo.style.textAlign = 'center';
                    tdNo.textContent = String(noGlobal++);

                    const tdElemen = document.createElement('td');
                    tdElemen.textContent = elemen.nama_elemen;

                    const tdKriteria = document.createElement('td');
                    tdKriteria.textContent = kriteria.deskripsi_kriteria;

                    const tdPencapaian = document.createElement('td');
                    tdPencapaian.appendChild(createPencapaianCell(key, pre.pencapaian || ''));

                    const tdLanjut = document.createElement('td');
                    const ta = document.createElement('textarea');
                    ta.name = `detail[${key}][penilaian_lanjut]`;
                    ta.placeholder = 'Penilaian lanjut (opsional)';
                    ta.style.minHeight = '64px';
                    ta.value = pre.penilaian_lanjut || '';
                    tdLanjut.appendChild(ta);

                    tdLanjut.appendChild(createHiddenInput(`detail[${key}][unit_id]`, String(unit.id)));
                    tdLanjut.appendChild(createHiddenInput(`detail[${key}][elemen_id]`, String(elemen.id)));
                    tdLanjut.appendChild(createHiddenInput(`detail[${key}][kriteria_id]`, String(kriteria.id)));

                    tr.appendChild(tdNo);
                    tr.appendChild(tdElemen);
                    tr.appendChild(tdKriteria);
                    tr.appendChild(tdPencapaian);
                    tr.appendChild(tdLanjut);
                    tbody.appendChild(tr);
                });
            });

            wrap.appendChild(table);
            section.appendChild(wrap);
            checklistContainer.appendChild(section);
        });
    };

    const loadData = async () => {
        const skemaId = skemaIdInput ? skemaIdInput.value : '';

        if (!skemaId) {
            resetAsesi('-- Pilih Asesi --');
            checklistContainer.innerHTML = '<div style="padding:12px;color:#64748b;font-size:13px;">Pilih skema untuk memuat ceklis.</div>';
            return;
        }

        try {
            const [participantsResponse, structureResponse] = await Promise.all([
                fetch(`${participantsUrl}?skema_id=${encodeURIComponent(skemaId)}`),
                fetch(`${structureUrl}?skema_id=${encodeURIComponent(skemaId)}`),
            ]);

            const participants = await participantsResponse.json();
            const structure = await structureResponse.json();

            fillAsesi(participants.asesi || [], firstHydration ? selectedAsesiNik : '');
            renderChecklist(structure.units || []);
            firstHydration = false;
        } catch (error) {
            resetAsesi('-- Gagal memuat asesi --');
            checklistContainer.innerHTML = '<div style="padding:12px;color:#b91c1c;font-size:13px;">Gagal memuat data skema.</div>';
            firstHydration = false;
        }
    };

    const setBulk = (value) => {
        const radios = document.querySelectorAll(`input[type="radio"][name$="[pencapaian]"][value="${value}"]`);
        radios.forEach((radio) => {
            radio.checked = true;
        });
    };

    const clearBulk = () => {
        const radios = document.querySelectorAll('input[type="radio"][name$="[pencapaian]"]');
        radios.forEach((radio) => {
            radio.checked = false;
        });
    };

    const toggleBelumKompeten = () => {
        const selected = document.querySelector('input[name="rekomendasi"]:checked');
        if (selected && selected.value === 'belum_kompeten') {
            belumKompetenFields.classList.remove('hidden');
        } else {
            belumKompetenFields.classList.add('hidden');
        }
    };

    bulkYaBtn.addEventListener('click', () => setBulk('ya'));
    bulkTidakBtn.addEventListener('click', () => setBulk('tidak'));
    clearBtn.addEventListener('click', clearBulk);

    rekomendasiInputs.forEach((input) => input.addEventListener('change', toggleBelumKompeten));

    setNomorSkema();
    loadData();
    toggleBelumKompeten();
});
</script>
