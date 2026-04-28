@php
    $record = $item ?? null;
    $defaults = $defaults ?? [];
    $skemaList = $skemaList ?? collect();

    $value = function (string $field, $fallback = '') use ($record, $defaults) {
        if (old($field) !== null) {
            return old($field);
        }

        if ($record && isset($record->{$field})) {
            return $record->{$field};
        }

        return $defaults[$field] ?? $fallback;
    };

    $selectedSkemaId = (string) $value('skema_id', '');
    $selectedAsesiNik = (string) $value('asesi_nik', '');
    $selectedAsesorId = (string) $value('asesor_id', '');

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
    .card-form {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 22px;
        margin-bottom: 16px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field.full { grid-column: 1 / -1; }

    .field label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .req { color: #dc2626; }

    .field input,
    .field textarea,
    .field select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: inherit;
    }

    .field textarea { min-height: 84px; resize: vertical; }

    .field .invalid {
        border-color: #ef4444;
    }

    .error-text {
        font-size: 12px;
        color: #ef4444;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }

    .btn-outline {
        background: #f1f5f9;
        color: #0f172a;
        border: 1px solid #cbd5e1;
    }

    .checklist-wrapper {
        margin-top: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .checklist-header {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 12px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .checklist-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 14px;
    }

    .checklist-tools {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .unit-block {
        border-top: 1px solid #e2e8f0;
    }

    .unit-head {
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        background: #f8fafc;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .checklist-table {
        width: 100%;
        min-width: 920px;
        border-collapse: collapse;
    }

    .checklist-table th,
    .checklist-table td {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        font-size: 13px;
        vertical-align: top;
    }

    .checklist-table th {
        background: #f8fafc;
        text-align: center;
        color: #334155;
        font-weight: 700;
    }

    .pencapaian-wrap {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .pencapaian-wrap label {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #334155;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .recommendation-box {
        margin-top: 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
    }

    .recommendation-head {
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        background: #f8fafc;
        border-bottom: 1px solid #cbd5e1;
    }

    .recommendation-content {
        padding: 12px;
    }

    .radio-line {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 10px;
    }

    .radio-line label {
        display: inline-flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
        color: #334155;
    }

    .hidden {
        display: none;
    }

    .signature-section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
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

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }

        .checklist-tools {
            width: 100%;
        }

        .checklist-tools .btn {
            flex: 1;
            justify-content: center;
        }

        .signature-canvas {
            height: 180px;
        }
    }
</style>

<div class="card-form">
    <div class="grid-2">
        <div class="field">
            <label>Kode Form <span class="req">*</span></label>
            <input type="text" name="kode_form" value="{{ $value('kode_form', '') }}" class="{{ $errors->has('kode_form') ? 'invalid' : '' }}">
            @error('kode_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Judul Form <span class="req">*</span></label>
            <input type="text" name="judul_form" value="{{ $value('judul_form', '') }}" class="{{ $errors->has('judul_form') ? 'invalid' : '' }}">
            @error('judul_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema Sertifikasi <span class="req">*</span></label>
            <select id="skemaSelect" name="skema_id" class="{{ $errors->has('skema_id') ? 'invalid' : '' }}">
                <option value="">-- Pilih Skema --</option>
                @foreach($skemaList as $skema)
                    <option value="{{ $skema->id }}" data-nomor="{{ $skema->nomor_skema }}" {{ $selectedSkemaId === (string) $skema->id ? 'selected' : '' }}>
                        {{ $skema->nama_skema }} ({{ $skema->nomor_skema }})
                    </option>
                @endforeach
            </select>
            @error('skema_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nomor Skema</label>
            <input id="nomorSkemaDisplay" type="text" value="" readonly>
        </div>

        <div class="field">
            <label>Asesor</label>
            <select id="asesorSelect" name="asesor_id" class="{{ $errors->has('asesor_id') ? 'invalid' : '' }}">
                <option value="">-- Pilih Asesor --</option>
            </select>
            @error('asesor_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>No. Reg Asesor</label>
            <input id="asesorNoRegInput" type="text" name="ttd_asesor_no_reg" value="{{ $value('ttd_asesor_no_reg', '') }}" placeholder="Nomor registrasi asesor">
            @error('ttd_asesor_no_reg')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Asesi <span class="req">*</span></label>
            <select id="asesiSelect" name="asesi_nik" class="{{ $errors->has('asesi_nik') ? 'invalid' : '' }}">
                <option value="">-- Pilih Asesi --</option>
            </select>
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>TUK</label>
            <input type="text" name="tuk" value="{{ $value('tuk', '') }}" placeholder="Sewaktu/Tempat Kerja/Mandiri">
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
            @error('catatan_footer')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="checklist-wrapper">
        <div class="checklist-header">
            <div class="checklist-title">Daftar Ceklis Observasi Per KUK</div>
            <div class="checklist-tools">
                <button type="button" class="btn btn-outline" id="bulkYaBtn">Set Semua Ya</button>
                <button type="button" class="btn btn-outline" id="bulkTidakBtn">Set Semua Tidak</button>
                <button type="button" class="btn btn-outline" id="clearPencapaianBtn">Kosongkan Pencapaian</button>
            </div>
        </div>
        <div id="checklistContainer"></div>
    </div>
    @error('detail')<div class="error-text" style="margin-top:6px;">{{ $message }}</div>@enderror

    <div class="recommendation-box">
        <div class="recommendation-head">Rekomendasi</div>
        <div class="recommendation-content">
            <div class="radio-line">
                <label>
                    <input type="radio" name="rekomendasi" value="kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'kompeten' ? 'checked' : '' }}>
                    Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>KOMPETEN</strong>
                </label>
                <label>
                    <input type="radio" name="rekomendasi" value="belum_kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'belum_kompeten' ? 'checked' : '' }}>
                    Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>BELUM KOMPETEN</strong>
                </label>
            </div>
            @error('rekomendasi')<div class="error-text">{{ $message }}</div>@enderror

            <div id="belumKompetenFields" class="grid-2 {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'belum_kompeten' ? '' : 'hidden' }}">
                <div class="field">
                    <label>Kelompok Pekerjaan</label>
                    <input type="text" name="belum_kompeten_kelompok_pekerjaan" value="{{ $value('belum_kompeten_kelompok_pekerjaan', '') }}">
                </div>
                <div class="field">
                    <label>Unit</label>
                    <input type="text" name="belum_kompeten_unit" value="{{ $value('belum_kompeten_unit', '') }}">
                </div>
                <div class="field">
                    <label>Elemen</label>
                    <input type="text" name="belum_kompeten_elemen" value="{{ $value('belum_kompeten_elemen', '') }}">
                </div>
                <div class="field">
                    <label>KUK</label>
                    <input type="text" name="belum_kompeten_kuk" value="{{ $value('belum_kompeten_kuk', '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="margin-top:16px;">
        <div class="field full">
            <div class="signature-section">
                <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
                <p class="signature-subtitle">Dengan menandatangani, asesi menyatakan bahwa penilaian telah dilakukan sesuai dengan prosedur yang benar.</p>

                <div class="signature-canvas-wrapper" id="signatureWrapperAsesi">
                    <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $value('ttd_asesi_nama', '') }}">
                <input type="hidden" name="ttd_asesi_tanggal" id="ttdAsesiTanggalInput" value="{{ $ttdAsesiTanggal }}">
                @error('ttd_asesi_nama')<div class="error-text">{{ $message }}</div>@enderror
                @error('ttd_asesi_tanggal')<div class="error-text">{{ $message }}</div>@enderror

                <div class="signature-actions">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong id="signatureDateAsesi">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                    <button type="button" class="btn-clear-signature" id="clearSignatureAsesi">
                        <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                    </button>
                </div>
            </div>
        </div>

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
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel }}</button>
        <a href="{{ route('admin.ceklis-observasi-aktivitas-praktik.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skemaSelect = document.getElementById('skemaSelect');
    const nomorSkemaDisplay = document.getElementById('nomorSkemaDisplay');
    const asesorSelect = document.getElementById('asesorSelect');
    const asesiSelect = document.getElementById('asesiSelect');
    const asesorNoRegInput = document.getElementById('asesorNoRegInput');
    const checklistContainer = document.getElementById('checklistContainer');
    const bulkYaBtn = document.getElementById('bulkYaBtn');
    const bulkTidakBtn = document.getElementById('bulkTidakBtn');
    const clearPencapaianBtn = document.getElementById('clearPencapaianBtn');
    const rekomendasiInputs = document.querySelectorAll('input[name="rekomendasi"]');
    const belumKompetenFields = document.getElementById('belumKompetenFields');

    const participantsUrl = '{{ route('admin.ceklis-observasi-aktivitas-praktik.skema-participants') }}';
    const structureUrl = '{{ route('admin.ceklis-observasi-aktivitas-praktik.skema-structure') }}';

    const selectedAsesiNik = @json($selectedAsesiNik);
    const selectedAsesorId = @json($selectedAsesorId);
    const initialDetailMap = @json($initialDetailMap ?? []);
    let firstHydration = true;

    const setNomorSkema = () => {
        const selected = skemaSelect.options[skemaSelect.selectedIndex];
        nomorSkemaDisplay.value = selected ? (selected.getAttribute('data-nomor') || '') : '';
    };

    const resetSelect = (select, placeholder) => {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    };

    const fillAsesi = (items, selectedValue) => {
        resetSelect(asesiSelect, '-- Pilih Asesi --');

        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.nama} (${item.id})`;
            if (selectedValue && selectedValue === item.id) {
                option.selected = true;
            }
            asesiSelect.appendChild(option);
        });
    };

    const fillAsesor = (items, selectedValue) => {
        resetSelect(asesorSelect, '-- Pilih Asesor --');

        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.nama}${item.no_reg ? ' (' + item.no_reg + ')' : ''}`;
            option.dataset.noReg = item.no_reg || '';

            if (selectedValue && selectedValue === item.id) {
                option.selected = true;
                if (!asesorNoRegInput.value) {
                    asesorNoRegInput.value = item.no_reg || '';
                }
            }

            asesorSelect.appendChild(option);
        });
    };

    const createPencapaianCell = (kriteriaId, value) => {
        const wrap = document.createElement('div');
        wrap.className = 'pencapaian-wrap';

        const yesLabel = document.createElement('label');
        const yesRadio = document.createElement('input');
        yesRadio.type = 'radio';
        yesRadio.name = `detail[${kriteriaId}][pencapaian]`;
        yesRadio.value = 'ya';
        if (value === 'ya') {
            yesRadio.checked = true;
        }
        yesLabel.appendChild(yesRadio);
        yesLabel.appendChild(document.createTextNode(' Ya'));

        const noLabel = document.createElement('label');
        const noRadio = document.createElement('input');
        noRadio.type = 'radio';
        noRadio.name = `detail[${kriteriaId}][pencapaian]`;
        noRadio.value = 'tidak';
        if (value === 'tidak') {
            noRadio.checked = true;
        }
        noLabel.appendChild(noRadio);
        noLabel.appendChild(document.createTextNode(' Tidak'));

        wrap.appendChild(yesLabel);
        wrap.appendChild(noLabel);

        return wrap;
    };

    const createHiddenInput = (name, value) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
    };

    const renderChecklist = (units) => {
        checklistContainer.innerHTML = '';

        if (!units || units.length === 0) {
            checklistContainer.innerHTML = '<div style="padding:12px;font-size:13px;color:#64748b;">Skema ini belum memiliki unit/elemen/kriteria.</div>';
            return;
        }

        let noGlobal = 1;

        units.forEach((unit) => {
            const block = document.createElement('div');
            block.className = 'unit-block';

            const head = document.createElement('div');
            head.className = 'unit-head';
            head.textContent = `${unit.kode_unit} - ${unit.judul_unit}`;
            block.appendChild(head);

            const tableWrap = document.createElement('div');
            tableWrap.className = 'table-wrap';

            const table = document.createElement('table');
            table.className = 'checklist-table';

            table.innerHTML = `
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th style="width:220px;">Elemen</th>
                        <th>Kriteria Unjuk Kerja</th>
                        <th style="width:160px;">Pencapaian</th>
                        <th style="width:220px;">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;

            const tbody = table.querySelector('tbody');

            unit.elemens.forEach((elemen) => {
                elemen.kriteria.forEach((kriteria) => {
                    const key = String(kriteria.id);
                    const prefilled = initialDetailMap[key] || {};

                    const tr = document.createElement('tr');

                    const tdNo = document.createElement('td');
                    tdNo.style.textAlign = 'center';
                    tdNo.textContent = String(noGlobal++);

                    const tdElemen = document.createElement('td');
                    tdElemen.textContent = elemen.nama_elemen;

                    const tdKriteria = document.createElement('td');
                    tdKriteria.textContent = kriteria.deskripsi_kriteria;

                    const tdPencapaian = document.createElement('td');
                    tdPencapaian.appendChild(createPencapaianCell(key, prefilled.pencapaian || ''));

                    const tdLanjut = document.createElement('td');
                    const textarea = document.createElement('textarea');
                    textarea.name = `detail[${key}][penilaian_lanjut]`;
                    textarea.placeholder = 'Penilaian lanjut (opsional)';
                    textarea.style.minHeight = '68px';
                    textarea.value = prefilled.penilaian_lanjut || '';
                    tdLanjut.appendChild(textarea);

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

            tableWrap.appendChild(table);
            block.appendChild(tableWrap);
            checklistContainer.appendChild(block);
        });
    };

    const loadParticipantsAndStructure = async () => {
        const skemaId = skemaSelect.value;

        if (!skemaId) {
            resetSelect(asesorSelect, '-- Pilih Asesor --');
            resetSelect(asesiSelect, '-- Pilih Asesi --');
            checklistContainer.innerHTML = '<div style="padding:12px;font-size:13px;color:#64748b;">Pilih skema untuk memuat checklist.</div>';
            return;
        }

        try {
            const [participantsResponse, structureResponse] = await Promise.all([
                fetch(`${participantsUrl}?skema_id=${encodeURIComponent(skemaId)}`),
                fetch(`${structureUrl}?skema_id=${encodeURIComponent(skemaId)}`),
            ]);

            const participants = await participantsResponse.json();
            const structure = await structureResponse.json();

            fillAsesor(participants.asesor || [], firstHydration ? selectedAsesorId : '');
            fillAsesi(participants.asesi || [], firstHydration ? selectedAsesiNik : '');
            renderChecklist(structure.units || []);

            firstHydration = false;
        } catch (error) {
            checklistContainer.innerHTML = '<div style="padding:12px;font-size:13px;color:#b91c1c;">Gagal memuat data skema.</div>';
            resetSelect(asesorSelect, '-- Gagal memuat asesor --');
            resetSelect(asesiSelect, '-- Gagal memuat asesi --');
            firstHydration = false;
        }
    };

    const setBulkPencapaian = (value) => {
        const radios = document.querySelectorAll(`input[type="radio"][name$="[pencapaian]"][value="${value}"]`);
        radios.forEach((radio) => {
            radio.checked = true;
        });
    };

    const clearBulkPencapaian = () => {
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

    skemaSelect.addEventListener('change', () => {
        setNomorSkema();
        loadParticipantsAndStructure();
    });

    asesorSelect.addEventListener('change', () => {
        const selectedOption = asesorSelect.options[asesorSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.noReg && !asesorNoRegInput.value) {
            asesorNoRegInput.value = selectedOption.dataset.noReg;
        }
    });

    bulkYaBtn.addEventListener('click', () => setBulkPencapaian('ya'));
    bulkTidakBtn.addEventListener('click', () => setBulkPencapaian('tidak'));
    clearPencapaianBtn.addEventListener('click', clearBulkPencapaian);

    rekomendasiInputs.forEach((input) => {
        input.addEventListener('change', toggleBelumKompeten);
    });

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

    const signatureCanvasAsesi = document.getElementById('signatureCanvasAsesi');
    const signatureWrapperAsesi = document.getElementById('signatureWrapperAsesi');
    const clearSignatureAsesi = document.getElementById('clearSignatureAsesi');
    const ttdAsesiNamaInput = document.getElementById('ttdAsesiNamaInput');
    const ttdAsesiTanggalInput = document.getElementById('ttdAsesiTanggalInput');
    const signatureCanvasAsesor = document.getElementById('signatureCanvasAsesor');
    const signatureWrapperAsesor = document.getElementById('signatureWrapperAsesor');
    const clearSignatureAsesor = document.getElementById('clearSignatureAsesor');
    const ttdAsesorNamaInput = document.getElementById('ttdAsesorNamaInput');
    const ttdAsesorTanggalInput = document.getElementById('ttdAsesorTanggalInput');

    initSignaturePad({
        canvas: signatureCanvasAsesi,
        wrapper: signatureWrapperAsesi,
        clearButton: clearSignatureAsesi,
        nameInput: ttdAsesiNamaInput,
        dateInput: ttdAsesiTanggalInput,
    });

    initSignaturePad({
        canvas: signatureCanvasAsesor,
        wrapper: signatureWrapperAsesor,
        clearButton: clearSignatureAsesor,
        nameInput: ttdAsesorNamaInput,
        dateInput: ttdAsesorTanggalInput,
    });

    setNomorSkema();
    loadParticipantsAndStructure();
    toggleBelumKompeten();
});
</script>
