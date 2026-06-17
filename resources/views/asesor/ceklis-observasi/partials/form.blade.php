@php
    $record = $item ?? null;
    $defaults = $defaults ?? [];
    $activeSkema = $activeSkema ?? null;
    $skemas = $skemas ?? collect();

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
    $selectedSkema = $skemas->firstWhere('id', (int) $selectedSkemaId) ?? $activeSkema;
    $selectedSkemaJenis = $selectedSkema->jenis_skema ?? $activeSkema->jenis_skema ?? '';
    $selectedAsesiNik = (string) $value('asesi_nik', '');
    $selectedAsesiNama = (string) $value('asesi_nama', '');
    $selectedTuk = (string) $value('tuk', '');
    $selectedTipeTuk = (string) $value('tipe_tuk', '');
    $tipeTukLabels = [
        'sewaktu'       => 'Sewaktu',
        'tempat_kerja'  => 'Tempat Kerja',
        'mandiri'       => 'Mandiri',
    ];
    $selectedTipeTukLabel = $tipeTukLabels[$selectedTipeTuk] ?? $selectedTipeTuk;
    $skemaOptions = $skemas->map(function ($skema) {
        return [
            'id' => (string) $skema->id,
            'nama' => $skema->nama_skema,
            'nomor' => $skema->nomor_skema,
            'jenis' => $skema->jenis_skema,
        ];
    })->values();

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

    $parseMultiValue = function ($raw) {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return [];
        }

        return collect(preg_split('/\s*(?:\||,|\r\n|\r|\n)\s*/', $raw))
            ->filter(fn ($item) => trim((string) $item) !== '')
            ->map(fn ($item) => trim((string) $item))
            ->values()
            ->all();
    };

    $belumKompetenDefaults = [
        'kelompok_pekerjaan' => $parseMultiValue($value('belum_kompeten_kelompok_pekerjaan', '')),
        'unit' => $parseMultiValue($value('belum_kompeten_unit', '')),
        'elemen' => $parseMultiValue($value('belum_kompeten_elemen', '')),
        'kuk' => $parseMultiValue($value('belum_kompeten_kuk', '')),
    ];
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

    .search-multiselect {
        position: relative;
    }

    .search-multiselect-toggle {
        width: 100%;
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        padding: 8px 11px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-multiselect-toggle:focus,
    .search-multiselect.open .search-multiselect-toggle {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .search-multiselect-placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    .search-multiselect-values {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        width: 100%;
    }

    .search-multiselect-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .search-multiselect-chevron {
        margin-left: auto;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .search-multiselect-dropdown {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        z-index: 30;
        background: #fff;
        border: 1px solid #dbe4ef;
        border-radius: 10px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        overflow: hidden;
    }

    .search-multiselect.open .search-multiselect-dropdown {
        display: block;
    }

    .search-multiselect-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 13px;
        outline: none;
        border-radius: 0;
    }

    .search-multiselect-options {
        max-height: 220px;
        overflow-y: auto;
    }

    .search-multiselect-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
    }

    .search-multiselect-option:last-child {
        border-bottom: none;
    }

    .search-multiselect-option:hover {
        background: #f8fafc;
    }

    .search-multiselect-option input {
        margin: 0;
        flex-shrink: 0;
    }

    .search-multiselect-empty {
        padding: 12px;
        color: #94a3b8;
        font-size: 13px;
    }

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
        width: min(100%, 320px);
        aspect-ratio: 1 / 1;
        margin-left: auto;
        margin-right: auto;
        cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='4' fill='%230073bd' stroke='white' stroke-width='2'/%3E%3Cpath d='M16 2v8M16 22v8M2 16h8M22 16h8' stroke='%230073bd' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E") 16 16, crosshair;
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
        height: 100%;
        cursor: inherit;
        display: block;
        touch-action: none;
        user-select: none;
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
            max-width: 100%;
        }
    }
</style>

<div class="card-form">
    @if ($errors->any())
        <div style="margin-bottom:14px;padding:12px 14px;border:1px solid #fecaca;background:#fef2f2;color:#b91c1c;border-radius:10px;font-size:13px;">
            <strong>Gagal menyimpan ceklis.</strong>
            <div style="margin-top:6px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <input type="hidden" name="kode_form" value="{{ $value('kode_form', 'FR.IA.01.') }}">
    <input type="hidden" name="judul_form" value="{{ $value('judul_form', 'CEKLIS OBSERVASI AKTIVITAS PRAKTIK') }}">

    <div class="grid-2">
        <div class="field full">
            <label>Asesi <span class="req">*</span></label>
            @if($selectedAsesiNik !== '' && $selectedAsesiNama !== '')
                {{-- Asesi sudah diketahui dari URL (dibuka dari action tabel) – tampilkan readonly --}}
                <input type="text"
                    value="{{ $selectedAsesiNama }} ({{ $selectedAsesiNik }})"
                    readonly
                    style="background:#f8fafc;color:#334155;font-weight:600;">
                <input type="hidden" id="asesiNikHidden" name="asesi_nik" value="{{ $selectedAsesiNik }}">
                {{-- Select tersembunyi supaya JS tidak error saat mencari #asesiSelect --}}
                <select id="asesiSelect" style="display:none;">
                    <option value="{{ $selectedAsesiNik }}" selected>{{ $selectedAsesiNama }}</option>
                </select>
            @else
                {{-- Asesi belum dipilih – tampilkan select AJAX biasa --}}
                <select id="asesiSelect" name="asesi_nik">
                    <option value="">-- Pilih Asesi --</option>
                </select>
            @endif
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema Sertifikasi <span class="req">*</span></label>
            <input id="skemaJenisInput" type="text" value="{{ $selectedSkemaJenis }}" readonly>
            <input type="hidden" id="skemaJenisHidden" value="{{ $selectedSkemaJenis }}">
        </div>

        <div class="field">
            <label>Nama Skema <span class="req">*</span></label>
            <input id="skemaNamaInput" type="text" value="{{ $activeSkemaNama }}" readonly>
            <input type="hidden" id="skemaIdInput" name="skema_id" value="{{ $selectedSkemaId }}">
        </div>

        <div class="field">
            <label>Nomor Skema</label>
            <input id="nomorSkemaDisplay" type="text" value="{{ $activeSkemaNomor }}" readonly>
        </div>

        <div class="field">
            <label>Tipe TUK</label>
            <input id="tipeTukDisplay" type="text"
                value="{{ $selectedTipeTukLabel }}"
                placeholder="Otomatis terisi dari jadwal" readonly
                style="background:#f8fafc;color:#64748b;">
            {{-- Nama TUK disimpan sebagai nilai field tuk --}}
            <input id="tukInput" type="hidden" name="tuk" value="{{ $selectedTuk }}">
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
                <div class="field">
                    <label>Kelompok Pekerjaan</label>
                    <div class="search-multiselect" data-field="kelompok_pekerjaan" data-placeholder="Cari dan pilih kelompok pekerjaan">
                        <input type="hidden" name="belum_kompeten_kelompok_pekerjaan" value="{{ $value('belum_kompeten_kelompok_pekerjaan', '') }}">
                        <button type="button" class="search-multiselect-toggle" aria-haspopup="listbox" aria-expanded="false">
                            <span class="search-multiselect-values"></span>
                            <span class="search-multiselect-placeholder">Cari dan pilih kelompok pekerjaan</span>
                            <i class="bi bi-chevron-down search-multiselect-chevron"></i>
                        </button>
                        <div class="search-multiselect-dropdown" role="listbox">
                            <input type="text" class="search-multiselect-search" placeholder="Ketik untuk mencari...">
                            <div class="search-multiselect-options"></div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Unit</label>
                    <div class="search-multiselect" data-field="unit" data-placeholder="Cari dan pilih unit">
                        <input type="hidden" name="belum_kompeten_unit" value="{{ $value('belum_kompeten_unit', '') }}">
                        <button type="button" class="search-multiselect-toggle" aria-haspopup="listbox" aria-expanded="false">
                            <span class="search-multiselect-values"></span>
                            <span class="search-multiselect-placeholder">Cari dan pilih unit</span>
                            <i class="bi bi-chevron-down search-multiselect-chevron"></i>
                        </button>
                        <div class="search-multiselect-dropdown" role="listbox">
                            <input type="text" class="search-multiselect-search" placeholder="Ketik untuk mencari...">
                            <div class="search-multiselect-options"></div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Elemen</label>
                    <div class="search-multiselect" data-field="elemen" data-placeholder="Cari dan pilih elemen">
                        <input type="hidden" name="belum_kompeten_elemen" value="{{ $value('belum_kompeten_elemen', '') }}">
                        <button type="button" class="search-multiselect-toggle" aria-haspopup="listbox" aria-expanded="false">
                            <span class="search-multiselect-values"></span>
                            <span class="search-multiselect-placeholder">Cari dan pilih elemen</span>
                            <i class="bi bi-chevron-down search-multiselect-chevron"></i>
                        </button>
                        <div class="search-multiselect-dropdown" role="listbox">
                            <input type="text" class="search-multiselect-search" placeholder="Ketik untuk mencari...">
                            <div class="search-multiselect-options"></div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>KUK</label>
                    <div class="search-multiselect" data-field="kuk" data-placeholder="Cari dan pilih KUK">
                        <input type="hidden" name="belum_kompeten_kuk" value="{{ $value('belum_kompeten_kuk', '') }}">
                        <button type="button" class="search-multiselect-toggle" aria-haspopup="listbox" aria-expanded="false">
                            <span class="search-multiselect-values"></span>
                            <span class="search-multiselect-placeholder">Cari dan pilih KUK</span>
                            <i class="bi bi-chevron-down search-multiselect-chevron"></i>
                        </button>
                        <div class="search-multiselect-dropdown" role="listbox">
                            <input type="text" class="search-multiselect-search" placeholder="Ketik untuk mencari...">
                            <div class="search-multiselect-options"></div>
                        </div>
                    </div>
                </div>
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
                    @if($value('ttd_asesor_file'))
                        <img src="{{ asset('storage/' . ltrim($value('ttd_asesor_file'), '/')) }}" class="signature-saved-img" id="savedSignatureImgAsesor" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; background:#fff; pointer-events:none;">
                    @endif
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesor_nama" id="ttdAsesorNamaInput" value="{{ $value('ttd_asesor_nama', '') }}">
                <input type="hidden" name="ttd_asesor_tanggal" id="ttdAsesorTanggalInput" value="{{ $ttdAsesorTanggal }}">
                <input type="hidden" name="ttd_asesor_file" id="ttdAsesorFileInput" value="{{ $value('ttd_asesor_file', '') }}">
                <input type="hidden" name="ttd_asesi_file" id="ttdAsesiFileInput" value="{{ $value('ttd_asesi_file', '') }}">
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
        <a href="{{ route('asesor.ceklis-observasi.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skemaJenisInput = document.getElementById('skemaJenisInput');
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
    const selectedAsesiNama = @json($selectedAsesiNama);
    const urlParams = new URLSearchParams(window.location.search);
    const initialAsesiNik = selectedAsesiNik || urlParams.get('asesi_nik') || '';
    const initialAsesiNama = selectedAsesiNama || '';
    const initialSkemaId = skemaIdInput ? (skemaIdInput.value || urlParams.get('skema_id') || '') : (urlParams.get('skema_id') || '');
    const skemaOptions = @json($skemaOptions);
    const initialDetailMap = @json($initialDetailMap ?? []);
    const belumKompetenDefaults = @json($belumKompetenDefaults ?? []);
    let firstHydration = true;
    let availableKelompokPekerjaan = [];
    let currentBelumKompetenOptions = {
        kelompok_pekerjaan: [],
        unit: [],
        elemen: [],
        kuk: [],
    };

    const tukInput = document.getElementById('tukInput');
    const tanggalInput = document.getElementById('tanggalInput');
    const kelompokField = document.querySelector('.search-multiselect[data-field="kelompok_pekerjaan"]')?.closest('.field');

    const updateKelompokFieldVisibility = (kelompokOptions, selectedValues) => {
        const hasSingleGroup = (kelompokOptions || []).length === 1;
        availableKelompokPekerjaan = kelompokOptions || [];

        if (kelompokField) {
            kelompokField.classList.toggle('hidden', hasSingleGroup);
        }

        if (hasSingleGroup && (!selectedValues || selectedValues.length === 0)) {
            const hiddenInput = document.querySelector('.search-multiselect[data-field="kelompok_pekerjaan"] input[type="hidden"]');
            if (hiddenInput) {
                hiddenInput.value = kelompokOptions[0].value;
            }
        }
    };

    const syncSkemaReadonlyFields = () => {
        if (!skemaJenisInput || !skemaIdInput || !nomorSkemaDisplay) {
            return;
        }

        const selectedSkema = skemaOptions.find((skema) => skema.id === String(skemaIdInput.value || '')) || null;

        if (selectedSkema) {
            skemaJenisInput.value = selectedSkema.jenis || '';
            nomorSkemaDisplay.value = selectedSkema.nomor || '';
        } else {
            skemaJenisInput.value = '';
            nomorSkemaDisplay.value = '';
        }
    };

    const resetAsesi = (placeholder) => {
        asesiSelect.innerHTML = '';
        const op = document.createElement('option');
        op.value = '';
        op.textContent = placeholder;
        asesiSelect.appendChild(op);
    };

    const fillAsesi = (items, selectedValue, selectedLabel = '') => {
        resetAsesi('-- Pilih Asesi --');
        let matched = false;
        items.forEach((item) => {
            const op = document.createElement('option');
            op.value = item.id;
            op.textContent = `${item.nama} (${item.id})`;
            if (selectedValue && selectedValue === item.id) {
                op.selected = true;
                matched = true;
            }
            asesiSelect.appendChild(op);
        });

        if (selectedValue && !matched && selectedLabel) {
            const op = document.createElement('option');
            op.value = selectedValue;
            op.textContent = `${selectedLabel} (${selectedValue})`;
            op.selected = true;
            asesiSelect.appendChild(op);
        }
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

    const parseMultiValue = (value) => {
        if (!value) {
            return [];
        }

        return String(value)
            .split(/\s*(?:\||,|\r\n|\r|\n)\s*/)
            .map((item) => item.trim())
            .filter(Boolean);
    };

    const formatMultiValue = (values) => values.join(' | ');

    let structureUnits = [];

    const buildBelumKompetenOptions = (units) => {
        structureUnits = units || [];

        const kelompokSet = new Set();
        const unitOptions = [];
        const elemenOptions = [];
        const kukOptions = [];

        (structureUnits || []).forEach((unit) => {
            const kp = unit.kelompok_pekerjaan || '(Tanpa Kelompok)';
            kelompokSet.add(kp);

            const unitLabel = `${unit.kode_unit} - ${unit.judul_unit}`;
            unitOptions.push({ value: unitLabel, label: unitLabel, meta: { unitId: unit.id, kelompok: kp } });

            (unit.elemens || []).forEach((elemen) => {
                elemenOptions.push({ value: elemen.nama_elemen, label: elemen.nama_elemen, meta: { elemenId: elemen.id, unitId: unit.id } });

                (elemen.kriteria || []).forEach((kriteria) => {
                    kukOptions.push({ value: kriteria.deskripsi_kriteria, label: kriteria.deskripsi_kriteria, meta: { kriteriaId: kriteria.id, elemenId: elemen.id } });
                });
            });
        });

        const kelompokOptions = Array.from(kelompokSet).map((v) => ({ value: v, label: v }));

        return {
            kelompok_pekerjaan: kelompokOptions,
            unit: unitOptions,
            elemen: elemenOptions,
            kuk: kukOptions,
        };
    };

    const closeMultiSelect = (container) => {
        if (!container) {
            return;
        }

        container.classList.remove('open');
        const toggle = container.querySelector('.search-multiselect-toggle');
        if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
        }
    };

    const renderMultiSelect = (container, options, selectedValues, onChange) => {
        const hiddenInput = container.querySelector('input[type="hidden"]');
        const toggle = container.querySelector('.search-multiselect-toggle');
        const valuesWrap = container.querySelector('.search-multiselect-values');
        const placeholder = container.querySelector('.search-multiselect-placeholder');
        const searchInput = container.querySelector('.search-multiselect-search');
        const optionsWrap = container.querySelector('.search-multiselect-options');

        if (!hiddenInput || !toggle || !valuesWrap || !placeholder || !searchInput || !optionsWrap) {
            return;
        }

        const selectedSet = new Set(selectedValues || []);

        const syncValue = () => {
            hiddenInput.value = formatMultiValue(Array.from(selectedSet));
            valuesWrap.innerHTML = '';

            if (selectedSet.size === 0) {
                placeholder.style.display = 'inline';
                return;
            }

            placeholder.style.display = 'none';
            Array.from(selectedSet).forEach((value) => {
                const chip = document.createElement('span');
                chip.className = 'search-multiselect-chip';
                chip.textContent = value;
                valuesWrap.appendChild(chip);
            });
        };

        const drawOptions = () => {
            const query = searchInput.value.trim().toLowerCase();
            optionsWrap.innerHTML = '';

            const filtered = (options || []).filter((option) => {
                const haystack = `${option.label} ${option.value}`.toLowerCase();
                return haystack.includes(query);
            });

            if (filtered.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'search-multiselect-empty';
                empty.textContent = 'Tidak ada opsi yang cocok.';
                optionsWrap.appendChild(empty);
                return;
            }

            filtered.forEach((option) => {
                const item = document.createElement('label');
                item.className = 'search-multiselect-option';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = selectedSet.has(option.value);

                const text = document.createElement('span');
                text.textContent = option.label;

                item.appendChild(checkbox);
                item.appendChild(text);
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (selectedSet.has(option.value)) {
                        selectedSet.delete(option.value);
                    } else {
                        selectedSet.add(option.value);
                    }
                    syncValue();
                    drawOptions();
                        if (typeof onChange === 'function') {
                            onChange(Array.from(selectedSet));
                        }
                });

                optionsWrap.appendChild(item);
            });
        };

        syncValue();
        drawOptions();

        toggle.onclick = function (event) {
            event.preventDefault();
            const isOpen = container.classList.contains('open');
            document.querySelectorAll('.search-multiselect.open').forEach((openContainer) => {
                if (openContainer !== container) {
                    closeMultiSelect(openContainer);
                }
            });
            container.classList.toggle('open', !isOpen);
            toggle.setAttribute('aria-expanded', String(!isOpen));
            if (!isOpen) {
                searchInput.focus();
            }
        };

        searchInput.oninput = drawOptions;

        container.onclick = (event) => {
            event.stopPropagation();
        };

        if (!container.dataset.multiSelectDocBound) {
            document.addEventListener('click', (event) => {
                if (!container.contains(event.target)) {
                    closeMultiSelect(container);
                }
            });
            container.dataset.multiSelectDocBound = '1';
        }
    };

    const updateDependentOptions = (changedField) => {
        // read current selected values
        const getSelected = (field) => {
            const c = document.querySelector(`.search-multiselect[data-field="${field}"]`);
            if (!c) return [];
            const hidden = c.querySelector('input[type="hidden"]');
            return parseMultiValue(hidden?.value || '');
        };

        const selectedKelompok = getSelected('kelompok_pekerjaan');
        const selectedUnits = getSelected('unit');
        const selectedElemen = getSelected('elemen');

        // compute unit options filtered by kelompok
        const allOptions = buildBelumKompetenOptions(structureUnits);
        updateKelompokFieldVisibility(allOptions.kelompok_pekerjaan, selectedKelompok);

        const effectiveSelectedKelompok = (selectedKelompok.length > 0)
            ? selectedKelompok
            : (allOptions.kelompok_pekerjaan.length === 1 ? [allOptions.kelompok_pekerjaan[0].value] : []);

        const unitOptionsFiltered = allOptions.unit.filter((opt) => {
            if (effectiveSelectedKelompok.length === 0) return true;
            return effectiveSelectedKelompok.includes(opt.meta.kelompok);
        });

        // auto-fill unit if only one option and none selected
        const unitHidden = document.querySelector('.search-multiselect[data-field="unit"] input[type="hidden"]');
        const unitSelectedNow = parseMultiValue(unitHidden?.value || '');
        if (unitOptionsFiltered.length === 1 && unitSelectedNow.length === 0) {
            if (unitHidden) unitHidden.value = unitOptionsFiltered[0].value;
        }

        // compute elemen options filtered by selected units
        const selectedUnitVals = parseMultiValue(unitHidden?.value || '');
        const selectedUnitIds = new Set(unitOptionsFiltered.filter(u => selectedUnitVals.includes(u.value)).map(u => u.meta.unitId));
        const elemenOptionsFiltered = allOptions.elemen.filter((opt) => {
            if (selectedUnitVals.length === 0) return true;
            return selectedUnitIds.has(opt.meta.unitId);
        });

        // auto-fill elemen if only one option and none selected
        const elemenHidden = document.querySelector('.search-multiselect[data-field="elemen"] input[type="hidden"]');
        const elemenSelectedNow = parseMultiValue(elemenHidden?.value || '');
        if (elemenOptionsFiltered.length === 1 && elemenSelectedNow.length === 0) {
            if (elemenHidden) elemenHidden.value = elemenOptionsFiltered[0].value;
        }

        // compute kuk options filtered by selected elemen
        const selectedElemenVals = parseMultiValue(elemenHidden?.value || '');
        const selectedElemenIds = new Set(allOptions.elemen.filter(e => selectedElemenVals.includes(e.value)).map(e => e.meta.elemenId));
        const kukOptionsFiltered = allOptions.kuk.filter((opt) => {
            if (selectedElemenVals.length === 0) return true;
            return selectedElemenIds.has(opt.meta.elemenId);
        });

        // auto-fill kuk if only one option and none selected
        const kukHidden = document.querySelector('.search-multiselect[data-field="kuk"] input[type="hidden"]');
        const kukSelectedNow = parseMultiValue(kukHidden?.value || '');
        if (kukOptionsFiltered.length === 1 && kukSelectedNow.length === 0) {
            if (kukHidden) kukHidden.value = kukOptionsFiltered[0].value;
        }

        currentBelumKompetenOptions = {
            kelompok_pekerjaan: allOptions.kelompok_pekerjaan,
            unit: unitOptionsFiltered,
            elemen: elemenOptionsFiltered,
            kuk: kukOptionsFiltered,
        };

        // render with preserved selections
        document.querySelectorAll('.search-multiselect').forEach((container) => {
            const field = container.getAttribute('data-field');
            const options = currentBelumKompetenOptions[field] || [];
            const selected = parseMultiValue(container.querySelector('input[type="hidden"]')?.value || '');
            renderMultiSelect(container, options, selected, function (newSelected) {
                // when a field changes, update dependents
                updateDependentOptions(field);
            });
        });
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

        // Isi nama TUK ke hidden input (disimpan ke DB)
        if (tukInput) {
            tukInput.value = payload.tuk || '';
        }

        // Isi Tipe TUK ke display input (readonly, informatif)
        const tipeTukDisplay = document.getElementById('tipeTukDisplay');
        if (tipeTukDisplay) {
            const tipeTukMap = {
                'sewaktu': 'Sewaktu',
                'tempat_kerja': 'Tempat Kerja',
                'mandiri': 'Mandiri',
            };
            const rawTipe = payload.tipe_tuk || '';
            tipeTukDisplay.value = rawTipe ? (tipeTukMap[rawTipe] || rawTipe) : '';
            tipeTukDisplay.placeholder = rawTipe ? '' : 'Otomatis terisi dari jadwal';
        }

        if (tanggalInput) {
            tanggalInput.value = payload.tanggal || '';
        }
    };

    const fetchAsesiData = async (asesiNik) => {
        const skemaId = skemaIdInput ? skemaIdInput.value : initialSkemaId;
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
        const skemaId = skemaIdInput ? skemaIdInput.value : initialSkemaId;

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

            fillAsesi(participants.asesi || [], firstHydration ? initialAsesiNik : '', firstHydration ? initialAsesiNama : '');
            renderChecklist(structure.units || []);
            // initialize hierarchical belum kompeten options
            buildBelumKompetenOptions(structure.units || []);
            updateDependentOptions();
            document.querySelectorAll('.penilaian-lanjut-textarea').forEach((textarea) => autosizeTextarea(textarea));
            if (firstHydration && initialAsesiNik) {
                asesiSelect.value = initialAsesiNik;
                await fetchAsesiData(initialAsesiNik);
            }
            firstHydration = false;
        } catch (error) {
            console.error('Ceklis load error:', error);
            resetAsesi('-- Gagal memuat asesi --');
            checklistContainer.innerHTML = '<div style="padding:12px;color:#b91c1c;font-size:13px;">Gagal memuat data skema. Pastikan Anda memiliki akses ke skema ini.</div>';
            firstHydration = false;
        }
    };

    syncSkemaReadonlyFields();

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

            const fileInput = document.getElementById(canvas.id === 'signatureCanvasAsesor' ? 'ttdAsesorFileInput' : 'ttdAsesiFileInput');
            if (fileInput) {
                fileInput.value = '';
            }
            const savedImg = document.getElementById(canvas.id === 'signatureCanvasAsesor' ? 'savedSignatureImgAsesor' : 'savedSignatureImgAsesi');
            if (savedImg) {
                savedImg.remove();
            }
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
            hasSignature = true;
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

    loadData();
    toggleBelumKompeten();

    const form = signatureCanvasAsesor ? signatureCanvasAsesor.closest('form') : null;
    if (form) {
        form.addEventListener('submit', function (e) {
            const fileInput = document.getElementById('ttdAsesorFileInput');
            if (fileInput && fileInput.value === '' && signatureCanvasAsesor && signatureWrapperAsesor && signatureWrapperAsesor.classList.contains('has-signature')) {
                fileInput.value = signatureCanvasAsesor.toDataURL('image/png');
            }
        });
    }
});
</script>
