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
    $selectedTuk = (string) $value('tuk', 'sewaktu');

    $tukOptions = [
        'sewaktu' => 'TUK Sewaktu',
        'tempat_kerja' => 'TUK Tempat Kerja',
        'mandiri' => 'TUK Mandiri',
    ];

    if (!array_key_exists($selectedTuk, $tukOptions)) {
        $legacyTukMap = [
            'Sewaktu/Tempat Kerja/Mandiri*' => 'sewaktu',
            'TUK Sewaktu' => 'sewaktu',
            'TUK Tempat Kerja' => 'tempat_kerja',
            'TUK Mandiri' => 'mandiri',
        ];

        $selectedTuk = $legacyTukMap[$selectedTuk] ?? 'sewaktu';
    }

    $initialDetailMap = old('detail');
    if (!$initialDetailMap && $record) {
        $initialDetailMap = $record->details->mapWithKeys(function ($detail) {
            return [
                (string) $detail->unit_id => [
                    'unit_id' => $detail->unit_id,
                    'observasi_demonstrasi' => $detail->observasi_demonstrasi,
                    'portofolio' => $detail->portofolio,
                    'pernyataan_pihak_ketiga' => $detail->pernyataan_pihak_ketiga,
                    'pertanyaan_lisan' => $detail->pertanyaan_lisan,
                    'pertanyaan_tertulis' => $detail->pertanyaan_tertulis,
                    'proyek_kerja' => $detail->proyek_kerja,
                    'lainnya' => $detail->lainnya,
                ],
            ];
        })->toArray();
    }

    $tanggalMulai = old('tanggal_mulai', ($record && $record->tanggal_mulai) ? $record->tanggal_mulai->format('Y-m-d') : '');
    $tanggalSelesai = old('tanggal_selesai', ($record && $record->tanggal_selesai) ? $record->tanggal_selesai->format('Y-m-d') : '');
@endphp

<style>
    .card-form { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.08); padding:22px; margin-bottom:16px; }
    .grid-2 { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .field { display:flex; flex-direction:column; gap:6px; }
    .field.full { grid-column:1 / -1; }
    .field label { font-size:13px; font-weight:600; color:#334155; }
    .req { color:#dc2626; }

    .field input, .field textarea, .field select {
        border:1px solid #d1d5db; border-radius:8px; padding:10px 12px; font-size:13px; font-family:inherit;
    }

    .field textarea { min-height:80px; resize:vertical; }
    .error-text { font-size:12px; color:#ef4444; }

    .table-wrap { overflow-x:auto; border:1px solid #e2e8f0; border-radius:8px; margin-top:12px; }
    .rekaman-table { width:100%; min-width:1100px; border-collapse:collapse; }
    .rekaman-table th, .rekaman-table td { border:1px solid #e2e8f0; padding:8px 10px; font-size:13px; text-align:center; }
    .rekaman-table th { background:#f8fafc; color:#334155; font-weight:700; }
    .rekaman-table td:first-child, .rekaman-table td:nth-child(2) { text-align:left; }

    .section-head {
        margin-top:16px; margin-bottom:8px; font-size:14px; font-weight:700; color:#0f172a;
    }

    .form-actions { display:flex; gap:10px; margin-top:16px; flex-wrap:wrap; }

    .btn { border:none; border-radius:8px; padding:10px 14px; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:6px; cursor:pointer; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-secondary { background:#64748b; color:#fff; }

    @media (max-width:768px) { .grid-2 { grid-template-columns:1fr; } }
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
            <label>Skema Sertifikasi</label>
            <input type="text" name="kategori_skema" value="{{ $value('kategori_skema', '') }}">
            @error('kategori_skema')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema <span class="req">*</span></label>
            <select id="skemaSelect" name="skema_id">
                <option value="">-- Pilih Skema --</option>
                @foreach($skemaList as $skema)
                    <option value="{{ $skema->id }}" data-nomor="{{ $skema->nomor_skema }}" {{ $selectedSkemaId === (string) $skema->id ? 'selected' : '' }}>
                        {{ $skema->nama_skema }}
                    </option>
                @endforeach
            </select>
            @error('skema_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nomor Skema</label>
            <input id="nomorSkemaInput" type="text" readonly>
        </div>

        <div class="field">
            <label>TUK</label>
            <select name="tuk">
                <option value="">-- Pilih TUK --</option>
                @foreach($tukOptions as $tukValue => $tukLabel)
                    <option value="{{ $tukValue }}" {{ $selectedTuk === $tukValue ? 'selected' : '' }}>{{ $tukLabel }}</option>
                @endforeach
            </select>
            @error('tuk')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nama Asesor</label>
            <select id="asesorSelect" name="asesor_id">
                <option value="">-- Pilih Asesor --</option>
            </select>
            @error('asesor_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nama Asesi <span class="req">*</span></label>
            <select id="asesiSelect" name="asesi_nik">
                <option value="">-- Pilih Asesi --</option>
            </select>
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Tanggal Asesmen Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            @error('tanggal_mulai')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Tanggal Asesmen Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
            @error('tanggal_selesai')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="section-head">Bukti per Unit Kompetensi</div>
    <div class="table-wrap">
        <table class="rekaman-table">
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th style="min-width:280px;">Unit Kompetensi</th>
                    <th>Observasi Demonstrasi</th>
                    <th>Portofolio</th>
                    <th>Pernyataan Pihak Ketiga</th>
                    <th>Pertanyaan Lisan</th>
                    <th>Pertanyaan Tertulis</th>
                    <th>Proyek Kerja</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody id="unitRowsContainer">
                <tr>
                    <td colspan="9" style="text-align:center;color:#64748b;">Pilih skema untuk memuat unit kompetensi.</td>
                </tr>
            </tbody>
        </table>
    </div>
    @error('detail')<div class="error-text" style="margin-top:6px;">{{ $message }}</div>@enderror

    <div class="grid-2" style="margin-top:16px;">
        <div class="field full">
            <label>Rekomendasi Hasil Asesmen</label>
            <div style="display:flex;gap:12px;flex-wrap:wrap;border:1px solid #d1d5db;border-radius:8px;padding:10px 12px;">
                <label><input type="radio" name="rekomendasi" value="kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'kompeten' ? 'checked' : '' }}> Kompeten</label>
                <label><input type="radio" name="rekomendasi" value="belum_kompeten" {{ old('rekomendasi', $value('rekomendasi', 'belum_kompeten')) === 'belum_kompeten' ? 'checked' : '' }}> Belum Kompeten</label>
            </div>
            @error('rekomendasi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Tindak Lanjut yang Dibutuhkan</label>
            <textarea name="tindak_lanjut" placeholder="Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi">{{ $value('tindak_lanjut', '') }}</textarea>
            @error('tindak_lanjut')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Komentar / Observasi oleh Asesor</label>
            <textarea name="komentar_observasi">{{ $value('komentar_observasi', '') }}</textarea>
            @error('komentar_observasi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Catatan Footer</label>
            <input type="text" name="catatan_footer" value="{{ $value('catatan_footer', '') }}">
            @error('catatan_footer')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel }}</button>
        <a href="{{ route('admin.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skemaSelect = document.getElementById('skemaSelect');
    const nomorSkemaInput = document.getElementById('nomorSkemaInput');
    const asesorSelect = document.getElementById('asesorSelect');
    const asesiSelect = document.getElementById('asesiSelect');
    const unitRowsContainer = document.getElementById('unitRowsContainer');

    const participantsUrl = '{{ route('admin.rekaman-asesmen-kompetensi.skema-participants') }}';
    const unitsUrl = '{{ route('admin.rekaman-asesmen-kompetensi.skema-units') }}';
    const selectedAsesorId = @json($selectedAsesorId);
    const selectedAsesiNik = @json($selectedAsesiNik);
    const initialDetailMap = @json($initialDetailMap ?? []);
    let applyInitialSelection = true;

    const resetSelect = (select, placeholder) => {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    };

    const fillSelect = (select, placeholder, items, selectedValue, formatter) => {
        resetSelect(select, placeholder);

        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = formatter(item);
            if (selectedValue && String(selectedValue) === String(item.id)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    };

    const syncNomorSkema = () => {
        const selected = skemaSelect.options[skemaSelect.selectedIndex];
        nomorSkemaInput.value = selected ? (selected.getAttribute('data-nomor') || '') : '';
    };

    const checkboxCell = (name, checked) => {
        return `<input type="checkbox" name="${name}" value="1" ${checked ? 'checked' : ''}>`;
    };

    const renderUnits = (units) => {
        if (!units || units.length === 0) {
            unitRowsContainer.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#64748b;">Skema ini belum memiliki unit kompetensi.</td></tr>';
            return;
        }

        unitRowsContainer.innerHTML = units.map((unit, index) => {
            const key = String(unit.id);
            const prefilled = initialDetailMap[key] || {};

            return `
                <tr>
                    <td>${index + 1}<input type="hidden" name="detail[${key}][unit_id]" value="${unit.id}"></td>
                    <td>${unit.judul_unit}</td>
                    <td>${checkboxCell(`detail[${key}][observasi_demonstrasi]`, !!prefilled.observasi_demonstrasi)}</td>
                    <td>${checkboxCell(`detail[${key}][portofolio]`, !!prefilled.portofolio)}</td>
                    <td>${checkboxCell(`detail[${key}][pernyataan_pihak_ketiga]`, !!prefilled.pernyataan_pihak_ketiga)}</td>
                    <td>${checkboxCell(`detail[${key}][pertanyaan_lisan]`, !!prefilled.pertanyaan_lisan)}</td>
                    <td>${checkboxCell(`detail[${key}][pertanyaan_tertulis]`, !!prefilled.pertanyaan_tertulis)}</td>
                    <td>${checkboxCell(`detail[${key}][proyek_kerja]`, !!prefilled.proyek_kerja)}</td>
                    <td>${checkboxCell(`detail[${key}][lainnya]`, !!prefilled.lainnya)}</td>
                </tr>
            `;
        }).join('');
    };

    const loadBySkema = async () => {
        const skemaId = skemaSelect.value;

        if (!skemaId) {
            resetSelect(asesorSelect, '-- Pilih Asesor --');
            resetSelect(asesiSelect, '-- Pilih Asesi --');
            unitRowsContainer.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#64748b;">Pilih skema untuk memuat unit kompetensi.</td></tr>';
            return;
        }

        try {
            const [participantsResponse, unitsResponse] = await Promise.all([
                fetch(`${participantsUrl}?skema_id=${encodeURIComponent(skemaId)}`),
                fetch(`${unitsUrl}?skema_id=${encodeURIComponent(skemaId)}`),
            ]);

            const participants = await participantsResponse.json();
            const unitPayload = await unitsResponse.json();

            fillSelect(
                asesorSelect,
                '-- Pilih Asesor --',
                participants.asesor || [],
                applyInitialSelection ? selectedAsesorId : '',
                (item) => item.no_reg ? `${item.nama} (${item.no_reg})` : item.nama
            );

            fillSelect(
                asesiSelect,
                '-- Pilih Asesi --',
                participants.asesi || [],
                applyInitialSelection ? selectedAsesiNik : '',
                (item) => `${item.nama} (${item.id})`
            );

            renderUnits(unitPayload.units || []);
            applyInitialSelection = false;
        } catch (error) {
            resetSelect(asesorSelect, '-- Gagal memuat asesor --');
            resetSelect(asesiSelect, '-- Gagal memuat asesi --');
            unitRowsContainer.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#b91c1c;">Gagal memuat unit kompetensi.</td></tr>';
            applyInitialSelection = false;
        }
    };

    skemaSelect.addEventListener('change', () => {
        syncNomorSkema();
        loadBySkema();
    });

    syncNomorSkema();
    loadBySkema();
});
</script>
