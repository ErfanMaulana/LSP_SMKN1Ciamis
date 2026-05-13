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
    td.penilaian-lanjut-cell {
        padding: 6px 8px;
        vertical-align: top;
    }
    .penilaian-lanjut-textarea {
        width: 100%;
        display: block;
        border: none;
        background: transparent;
        padding: 0;
        margin: 0;
        resize: none;
        overflow: hidden;
        min-height: 1.45em;
        line-height: 1.45;
        font: inherit;
        color: #334155;
        box-shadow: none;
        outline: none;
    }
    .penilaian-lanjut-textarea::placeholder {
        color: #94a3b8;
    }
    .penilaian-lanjut-textarea:focus {
        outline: none;
        box-shadow: none;
    }

    .pencapaian-wrap { display:flex; justify-content:center; gap:10px; }
    .pencapaian-wrap label { font-size:12px; display:inline-flex; align-items:center; gap:4px; }

    .rekom-box { margin-top:14px; border:1px solid #cbd5e1; border-radius:8px; }
    .rekom-head { padding:10px 12px; background:#f8fafc; border-bottom:1px solid #cbd5e1; font-weight:700; font-size:13px; }
    .rekom-content { padding:12px; }

    .form-actions { margin-top:16px; display:flex; gap:8px; flex-wrap:wrap; }
    .btn-primary { background:#0073bd; color:#fff; border:none; border-radius:8px; padding:9px 14px; font-size:13px; cursor:pointer; }
    .btn-secondary { background:#64748b; color:#fff; border:none; border-radius:8px; padding:9px 14px; font-size:13px; text-decoration:none; }

    .hidden { display:none; }

    .signature-section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        margin-top: 14px;
    }

    .signature-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .signature-section .signature-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 14px;
    }

    .signature-canvas-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
    }

    .signature-canvas-wrapper.active {
        border-color: #0073bd;
        background: #fff;
    }

    .signature-canvas-wrapper.has-signature {
        border-style: solid;
        border-color: #0073bd;
    }

    .signature-canvas {
        width: 100%;
        height: 240px;
        cursor: crosshair;
        display: block;
    }

    .signature-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
        color: #9ca3af;
        transition: opacity 0.2s;
    }

    .signature-placeholder i {
        font-size: 28px;
        display: block;
        margin-bottom: 6px;
    }

    .signature-placeholder span {
        font-size: 13px;
    }

    .signature-canvas-wrapper.has-signature .signature-placeholder {
        opacity: 0;
    }

    .signature-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        gap: 8px;
        flex-wrap: wrap;
    }

    .signature-date {
        font-size: 13px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .signature-date strong {
        color: #1e293b;
    }

    .btn-clear-signature {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-clear-signature:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    @media (max-width:768px) {
        .grid-2 { grid-template-columns:1fr; }
        .signature-canvas-wrapper {
            max-width: 180px;
        }
    }
</style>

<div class="card-form">
    <div class="grid-2">
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
            <input type="text" id="tukInput" name="tuk" value="{{ $value('tuk', '') }}" readonly>
            @error('tuk')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Tanggal</label>
            <input type="date" id="tanggalInput" name="tanggal" value="{{ $selectedTanggal }}">
            @error('tanggal')<div class="error-text">{{ $message }}</div>@enderror
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

    <div class="grid-2" style="margin-top:16px;">
        <div class="field full">
            <div class="signature-section">
                <h3><i class="bi bi-pen"></i> Tanda Tangan Asesor</h3>
                <p class="signature-subtitle">Dengan menandatangani, asesor menyatakan pernyataan asesor telah diisi dengan benar.</p>

                <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                    <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesor_nama" id="ttdAsesorNamaInput" value="{{ $value('ttd_asesor_nama', '') }}">
                <input type="hidden" name="ttd_asesor_tanggal" id="ttdAsesorTanggalInput" value="{{ $ttdAsesorTanggal }}">
                @error('ttd_asesor_nama')<div class="error-text">{{ $message }}</div>@enderror
                @error('ttd_asesor_tanggal')<div class="error-text">{{ $message }}</div>@enderror

                <div class="signature-actions">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong id="signatureDateAsesor">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                    <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                    </button>
                </div>
            </div>
        </div>
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
    const asesiDataUrl = '{{ route('asesor.ceklis-observasi.get-asesi-data') }}';
    const selectedAsesiNik = @json($selectedAsesiNik);
    const initialDetailMap = @json($initialDetailMap ?? []);
    let firstHydration = true;

    const tukInput = document.getElementById('tukInput');
    const tanggalInput = document.getElementById('tanggalInput');

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

    const autosizeTextarea = (textarea) => {
        textarea.style.height = 'auto';
        textarea.style.height = `${textarea.scrollHeight}px`;
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
                    tdLanjut.className = 'penilaian-lanjut-cell';
                    const ta = document.createElement('textarea');
                    ta.name = `detail[${key}][penilaian_lanjut]`;
                    ta.placeholder = 'Penilaian lanjut (opsional)';
                    ta.className = 'penilaian-lanjut-textarea';
                    ta.rows = 1;
                    ta.value = pre.penilaian_lanjut || '';
                    tdLanjut.appendChild(ta);
                    autosizeTextarea(ta);
                    ta.addEventListener('input', function () {
                        autosizeTextarea(this);
                    });

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

    const applyAsesiData = (payload) => {
        if (!payload) {
            return;
        }

        if (tukInput) {
            tukInput.value = payload.tuk || '';
        }

        if (tanggalInput) {
            tanggalInput.value = payload.tanggal || '';
        }
    };

    const fetchAsesiData = async (asesiNik) => {
        const skemaId = skemaIdInput ? skemaIdInput.value : '';
        if (!asesiNik || !skemaId) {
            applyAsesiData({ tuk: '', tanggal: '', asesi: null });
            return;
        }

        try {
            const response = await fetch(
                `${asesiDataUrl}?asesi_nik=${encodeURIComponent(asesiNik)}&skema_id=${encodeURIComponent(skemaId)}`
            );

            if (!response.ok) {
                applyAsesiData({ tuk: '', tanggal: '', asesi: null });
                return;
            }

            const payload = await response.json();
            applyAsesiData(payload);
        } catch (error) {
            applyAsesiData({ tuk: '', tanggal: '', asesi: null });
        }
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

            if (!participantsResponse.ok || !structureResponse.ok) {
                throw new Error(`HTTP ${participantsResponse.status} / ${structureResponse.status}`);
            }

            const participants = await participantsResponse.json();
            const structure = await structureResponse.json();

            fillAsesi(participants.asesi || [], firstHydration ? selectedAsesiNik : '');
            renderChecklist(structure.units || []);
            document.querySelectorAll('.penilaian-lanjut-textarea').forEach((textarea) => autosizeTextarea(textarea));
            if (firstHydration && selectedAsesiNik) {
                await fetchAsesiData(selectedAsesiNik);
            }
            firstHydration = false;
        } catch (error) {
            console.error('Ceklis load error:', error);
            resetAsesi('-- Gagal memuat asesi --');
            checklistContainer.innerHTML = '<div style="padding:12px;color:#b91c1c;font-size:13px;">Gagal memuat data skema. Pastikan Anda memiliki akses ke skema ini.</div>';
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

    const initSignaturePad = (config) => {
        const {
            canvas,
            wrapper,
            clearButton,
            nameInput,
            dateInput,
        } = config;

        if (!canvas || !wrapper || !clearButton || !nameInput || !dateInput) {
            return;
        }

        const ctx = canvas.getContext('2d');
        if (!ctx) {
            return;
        }

        let isDrawing = false;
        let hasSignature = false;
        let lastX = 0;
        let lastY = 0;

        const updateCanvasSize = () => {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(ratio, ratio);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 2;
        };

        const getPos = (event) => {
            const rect = canvas.getBoundingClientRect();
            const point = event.touches && event.touches[0] ? event.touches[0] : event;
            return {
                x: point.clientX - rect.left,
                y: point.clientY - rect.top,
            };
        };

        const fillSignatureMeta = () => {
            if (!nameInput.value) {
                nameInput.value = 'Ditandatangani secara digital';
            }

            if (!dateInput.value) {
                const now = new Date();
                const yyyy = now.getFullYear();
                const mm = String(now.getMonth() + 1).padStart(2, '0');
                const dd = String(now.getDate()).padStart(2, '0');
                dateInput.value = `${yyyy}-${mm}-${dd}`;
            }
        };

        const startDrawing = (event) => {
            event.preventDefault();
            isDrawing = true;
            const pos = getPos(event);
            lastX = pos.x;
            lastY = pos.y;
            wrapper.classList.add('active');
        };

        const draw = (event) => {
            event.preventDefault();
            if (!isDrawing) {
                return;
            }

            const pos = getPos(event);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;

            if (!hasSignature) {
                hasSignature = true;
                wrapper.classList.add('has-signature');
            }

            fillSignatureMeta();
        };

        const stopDrawing = () => {
            isDrawing = false;
            wrapper.classList.remove('active');
        };

        clearButton.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSignature = false;
            nameInput.value = '';
            dateInput.value = '';
            wrapper.classList.remove('has-signature');
        });

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        window.addEventListener('resize', updateCanvasSize);
        updateCanvasSize();

        if (nameInput.value || dateInput.value) {
            wrapper.classList.add('has-signature');
        }
    };

    const signatureCanvasAsesor = document.getElementById('signatureCanvasAsesor');
    const signatureWrapperAsesor = document.getElementById('signatureWrapperAsesor');
    const clearSignatureAsesor = document.getElementById('clearSignatureAsesor');
    const ttdAsesorNamaInput = document.getElementById('ttdAsesorNamaInput');
    const ttdAsesorTanggalInput = document.getElementById('ttdAsesorTanggalInput');

    initSignaturePad({
        canvas: signatureCanvasAsesor,
        wrapper: signatureWrapperAsesor,
        clearButton: clearSignatureAsesor,
        nameInput: ttdAsesorNamaInput,
        dateInput: ttdAsesorTanggalInput,
    });

    bulkYaBtn.addEventListener('click', () => setBulk('ya'));
    bulkTidakBtn.addEventListener('click', () => setBulk('tidak'));
    clearBtn.addEventListener('click', clearBulk);

    rekomendasiInputs.forEach((input) => input.addEventListener('change', toggleBelumKompeten));
    asesiSelect.addEventListener('change', () => fetchAsesiData(asesiSelect.value));

    setNomorSkema();
    loadData();
    toggleBelumKompeten();
});
</script>
