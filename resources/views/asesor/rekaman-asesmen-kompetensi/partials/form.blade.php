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
    $selectedTuk = (string) $value('tuk', '');

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

@php
    $asesorAsesiList = collect();
    if (isset($asesor) && $asesor) {
        try {
            $skemaIds = $asesor->skemas ? $asesor->skemas->pluck('id')->map(fn($i) => (int)$i)->values()->all() : [];

            // Asesi yang secara langsung ditugaskan ke asesor
            $direct = \App\Models\Asesi::query()
                ->where('ID_asesor', $asesor->ID_asesor)
                ->get(['NIK', 'nama']);

            // Asesi yang terdaftar pada skema yang ditugaskan ke asesor (pivot asesi_skema)
            $bySkema = collect();
            if (count($skemaIds)) {
                $bySkema = \App\Models\Asesi::query()
                    ->whereHas('skemas', function ($q) use ($skemaIds) {
                        $q->whereIn('skemas.id', $skemaIds);
                    })
                    ->get(['NIK', 'nama']);
            }

            // Gabungkan, unik berdasarkan NIK
            $combined = $direct->concat($bySkema)
                ->unique(fn($a) => (string)$a->NIK)
                ->sortBy('nama')
                ->values()
                ->map(fn($a) => ['id' => (string)$a->NIK, 'nama' => $a->nama]);

            $asesorAsesiList = $combined;
        } catch (\Throwable $e) {
            $asesorAsesiList = collect();
        }
    }
@endphp

@php
    $selectedAsesiInfo = null;
    if ($record?->asesi) {
        $selectedAsesiInfo = [
            'nik' => (string) $record->asesi->NIK,
            'email' => $record->asesi->email,
            'jurusan' => $record->asesi->jurusan?->kode_jurusan
                ? trim($record->asesi->jurusan->kode_jurusan . ' - ' . $record->asesi->jurusan->nama_jurusan)
                : ($record->asesi->jurusan?->nama_jurusan ?? '-'),
            'telepon' => $record->asesi->telepon_hp,
        ];
    }
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

    .field input[readonly] {
        color: #111827;
        opacity: 1;
        background: #fff;
    }

    .field textarea { min-height:80px; resize:vertical; }
    .error-text { font-size:12px; color:#ef4444; }

    .section-note {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-top: 12px;
    }

    .info-item {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        background: #f8fafc;
        min-height: 76px;
    }

    .info-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .info-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 600;
        word-break: break-word;
    }

    /* locked select: hide native dropdown arrow and pointer */
    select.locked {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
        color: #111827;
        opacity: 1;
        background: #fff;
        pointer-events: none;
    }

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
            <label>Skema Sertifikasi</label>
            <input id="kategoriSkemaInput" type="text" name="kategori_skema" value="{{ $value('kategori_skema', '') }}" readonly>
            <div class="section-note">Diisi otomatis dari skema yang dipilih.</div>
            @error('kategori_skema')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nama Asesi <span class="req">*</span></label>
            <select id="asesiSelect" name="asesi_nik">
                <option value="">-- Pilih Asesi --</option>
                @foreach($asesorAsesiList as $a)
                    <option value="{{ $a['id'] }}" {{ $selectedAsesiNik === (string)$a['id'] ? 'selected' : '' }}>{{ $a['nama'] }} ({{ $a['id'] }})</option>
                @endforeach
            </select>
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema <span class="req">*</span></label>
            <select id="skemaSelect" name="skema_id">
                <option value="">-- Pilih Skema --</option>
                @foreach($skemaList as $skema)
                    <option value="{{ $skema->id }}" data-nomor="{{ $skema->nomor_skema }}" data-jenis="{{ $skema->jenis_skema ?? '' }}" {{ $selectedSkemaId === (string) $skema->id ? 'selected' : '' }}>
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
                <option value="Sewaktu" {{ $selectedTuk === 'Sewaktu' ? 'selected' : '' }}>Sewaktu</option>
                <option value="Tempat Kerja" {{ $selectedTuk === 'Tempat Kerja' ? 'selected' : '' }}>Tempat Kerja</option>
                <option value="Mandiri" {{ $selectedTuk === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
            </select>
            @error('tuk')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Asesor</label>
            <input type="text" value="{{ trim(($asesor?->nama ?? '-') . ($asesor?->no_met ? ' (' . $asesor->no_met . ')' : '')) }}" readonly>
        </div>



        <div class="field full">
            <label>Ringkasan Data Asesi</label>
            <div class="info-grid" id="asesiInfoGrid">
                <div class="info-item"><span class="info-label">NIK</span><div class="info-value" data-info="nik">-</div></div>
                <div class="info-item"><span class="info-label">Email</span><div class="info-value" data-info="email">-</div></div>
                <div class="info-item"><span class="info-label">Jurusan</span><div class="info-value" data-info="jurusan">-</div></div>
                <div class="info-item"><span class="info-label">Telepon</span><div class="info-value" data-info="telepon">-</div></div>
            </div>
            <div class="section-note">Data ini diambil otomatis dari profil asesi untuk mengurangi input manual.</div>
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
            <label>Komentar atau Observasi Asesor</label>
            <textarea name="komentar_observasi">{{ $value('komentar_observasi', '') }}</textarea>
            @error('komentar_observasi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel }}</button>
        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skemaSelect = document.getElementById('skemaSelect');
    const nomorSkemaInput = document.getElementById('nomorSkemaInput');
    const asesiSelect = document.getElementById('asesiSelect');
    const unitRowsContainer = document.getElementById('unitRowsContainer');
    const kategoriSkemaInput = document.getElementById('kategoriSkemaInput');
    const asesiInfoGrid = document.getElementById('asesiInfoGrid');
    const tukSelect = document.querySelector('select[name="tuk"]');

    const participantsUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.skema-participants') }}';
    const unitsUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.skema-units') }}';
        const getAsesiDataUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.get-asesi-data') }}';
    const selectedAsesiNik = @json($selectedAsesiNik);
    const initialDetailMap = @json($initialDetailMap ?? []);
    const selectedAsesiInfo = @json($selectedAsesiInfo);
    const selectedKategoriSkema = @json($value('kategori_skema', ''));
    let applyInitialSelection = true;
    let asesiOptions = @json($asesorAsesiList ?? []);

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

    const syncAsesiInfo = (selectedValue) => {
        if (!selectedValue) {
            renderAsesiInfo(selectedAsesiInfo);
            return;
        }

        // If an object is passed, render it directly
        if (typeof selectedValue === 'object') {
            renderAsesiInfo(selectedValue);
            return;
        }

        const selectedItem = asesiOptions.find((item) => String(item.id) === String(selectedValue));

        if (selectedItem) {
            renderAsesiInfo(selectedItem);
            return;
        }

        renderAsesiInfo(selectedAsesiInfo);
    };

    const syncNomorSkema = () => {
        const selected = skemaSelect.options[skemaSelect.selectedIndex];
        nomorSkemaInput.value = selected ? (selected.getAttribute('data-nomor') || '') : '';
        if (kategoriSkemaInput) {
            kategoriSkemaInput.value = selected ? (selected.getAttribute('data-jenis') || '') : '';
        }
    };

    const renderAsesiInfo = (item) => {
        const fields = {
            nik: '-',
            email: '-',
            jurusan: '-',
            telepon: '-',
        };

        if (item) {
            fields.nik = item.id || item.nik || '-';
            fields.email = item.email || '-';
            fields.jurusan = item.jurusan || '-';
            fields.telepon = item.telepon_hp || item.telepon || '-';
        }

        if (asesiInfoGrid) {
            asesiInfoGrid.querySelector('[data-info="nik"]').textContent = fields.nik;
            asesiInfoGrid.querySelector('[data-info="email"]').textContent = fields.email;
            asesiInfoGrid.querySelector('[data-info="jurusan"]').textContent = fields.jurusan;
            asesiInfoGrid.querySelector('[data-info="telepon"]').textContent = fields.telepon;
        }
    };

    const normalizeTukValue = (value) => {
        const text = String(value || '').toLowerCase();

        if (text.includes('sewaktu')) {
            return 'Sewaktu';
        }

        if (text.includes('tempat kerja')) {
            return 'Tempat Kerja';
        }

        if (text.includes('mandiri')) {
            return 'Mandiri';
        }

        return '';
    };

    const lockAsesiOnly = () => {
        asesiSelect.classList.add('locked');
    };

    const lockDependentFields = () => {
        if (tukSelect) tukSelect.classList.add('locked');
        const mulaiInput = document.querySelector('input[name="tanggal_mulai"]');
        const selesaiInput = document.querySelector('input[name="tanggal_selesai"]');
        if (mulaiInput) mulaiInput.setAttribute('readonly', 'readonly');
        if (selesaiInput) selesaiInput.setAttribute('readonly', 'readonly');
    };

    const applyAsesiDetail = (data) => {
        if (!data || !data.asesi) {
            return;
        }

        renderAsesiInfo(data.asesi);

        if (tukSelect) {
            const normalizedTuk = normalizeTukValue(data.asesi.tuk || data.asesi.tuk_pelaksanaan);
            if (normalizedTuk) {
                tukSelect.value = normalizedTuk;
            }
        }

        if (data.asesi.jadwal) {
            const mulaiInput = document.querySelector('input[name="tanggal_mulai"]');
            const selesaiInput = document.querySelector('input[name="tanggal_selesai"]');

            if (mulaiInput && data.asesi.jadwal.tanggal_mulai) {
                mulaiInput.value = data.asesi.jadwal.tanggal_mulai;
            }

            if (selesaiInput && data.asesi.jadwal.tanggal_selesai) {
                selesaiInput.value = data.asesi.jadwal.tanggal_selesai;
            }
        }

        lockAsesiOnly();
        lockDependentFields();
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
            resetSelect(asesiSelect, '-- Pilih Asesi --');
            renderAsesiInfo(null);
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
            asesiOptions = participants.asesi || [];

            fillSelect(
                asesiSelect,
                '-- Pilih Asesi --',
                asesiOptions,
                applyInitialSelection ? selectedAsesiNik : '',
                (item) => `${item.nama} (${item.id})`
            );

            // Handle pre-selection of asesi
            if (applyInitialSelection && selectedAsesiNik) {
                const detailResponse = await fetch(`${getAsesiDataUrl}?asesi_nik=${encodeURIComponent(selectedAsesiNik)}&skema_id=${encodeURIComponent(skemaId)}`);
                const detailData = await detailResponse.json();

                // Find the asesi in the options
                const selectedAsesiOption = asesiOptions.find(a => String(a.id).trim() === String(selectedAsesiNik).trim());
                
                if (selectedAsesiOption) {
                    // Asesi exists in list, select it
                    asesiSelect.value = String(selectedAsesiOption.id);
                    syncAsesiInfo(selectedAsesiOption);
                } else {
                    // Asesi not in list - fetch it separately as fallback
                    if (detailData.asesi) {
                        const option = document.createElement('option');
                        option.value = String(detailData.asesi.id);
                        option.textContent = `${detailData.asesi.nama} (${detailData.asesi.id})`;
                        asesiSelect.appendChild(option);
                        asesiSelect.value = String(detailData.asesi.id);
                        syncAsesiInfo(detailData.asesi);
                    } else {
                        asesiSelect.value = '';
                        syncAsesiInfo(null);
                    }
                }

                if (detailData.asesi) {
                    applyAsesiDetail(detailData);
                    if (detailData.asesi.skema_ids && detailData.asesi.skema_ids.length > 0) {
                        skemaSelect.value = String(detailData.asesi.skema_ids[0]);
                        syncNomorSkema();
                    }
                } else {
                    console.error('Asesi detail not found for selected URL param');
                }
            } else {
                syncAsesiInfo(asesiSelect.value);
            }

            if (kategoriSkemaInput) {
                const selectedOption = skemaSelect.options[skemaSelect.selectedIndex];
                kategoriSkemaInput.value = (selectedOption && selectedOption.getAttribute('data-jenis')) || selectedKategoriSkema || '';
            }

            renderUnits(unitPayload.units || []);
            applyInitialSelection = false;
        } catch (error) {
                        console.error('Error loading skema data:', error);
            resetSelect(asesiSelect, '-- Gagal memuat asesi --');
            renderAsesiInfo(null);
            unitRowsContainer.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#b91c1c;">Gagal memuat unit kompetensi.</td></tr>';
            applyInitialSelection = false;
        }
    };

    if (skemaSelect) {
        skemaSelect.addEventListener('change', () => {
            syncNomorSkema();
            loadBySkema();
        });

        asesiSelect.addEventListener('change', async () => {
            syncAsesiInfo(asesiSelect.value);

            const asesiNik = asesiSelect.value;
            if (!asesiNik) {
                // clear dependent fields
                renderAsesiInfo(null);
                if (tukSelect) tukSelect.value = '';
                const mulaiInput = document.querySelector('input[name="tanggal_mulai"]');
                const selesaiInput = document.querySelector('input[name="tanggal_selesai"]');
                if (mulaiInput) mulaiInput.value = '';
                if (selesaiInput) selesaiInput.value = '';
                asesiSelect.classList.remove('locked');
                tukSelect.classList.remove('locked');
                if (mulaiInput) mulaiInput.removeAttribute('readonly');
                if (selesaiInput) selesaiInput.removeAttribute('readonly');
                return;
            }

            try {
                const skemaId = skemaSelect.value || '';
                const url = `${getAsesiDataUrl}?asesi_nik=${encodeURIComponent(asesiNik)}&skema_id=${encodeURIComponent(skemaId)}`;
                const res = await fetch(url);
                if (!res.ok) {
                    console.error('Gagal memuat data asesi');
                    return;
                }
                const detailData = await res.json();

                if (detailData.asesi) {
                    applyAsesiDetail(detailData);

                    // If skema not chosen yet but asesi has skema_ids, pick first and load units
                    if (!skemaSelect.value && detailData.asesi.skema_ids && detailData.asesi.skema_ids.length > 0) {
                        skemaSelect.value = String(detailData.asesi.skema_ids[0]);
                        syncNomorSkema();
                        await loadBySkema();
                    } else if (skemaSelect.value) {
                        // refresh units for selected skema so units list matches
                        await loadBySkema();
                    }
                }
            } catch (err) {
                console.error('Error fetching asesi detail:', err);
            }
        });

        syncNomorSkema();
        loadBySkema();
    }
});
</script>
