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
    $lockSkema = !empty($selectedAsesiNik) && !empty($selectedSkemaId);
    $lockTuk = !empty($selectedAsesiNik) && !empty($selectedSkemaId);

    $selectedTipeTuk = '';
    if (!empty($selectedTuk)) {
        $tukRecord = \App\Models\Tuk::where('nama_tuk', $selectedTuk)->first();
        if ($tukRecord) {
            $selectedTipeTuk = $tukRecord->tipe_tuk;
        }
    }
    if (empty($selectedTipeTuk) && !empty($defaults['tipe_tuk'])) {
        $selectedTipeTuk = $defaults['tipe_tuk'];
    }
    $tipeTukLabels = [
        'sewaktu'       => 'Sewaktu',
        'tempat_kerja'  => 'Tempat Kerja',
        'mandiri'       => 'Mandiri',
    ];
    $selectedTipeTukLabel = $tipeTukLabels[$selectedTipeTuk] ?? $selectedTipeTuk;

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

    .search-select {
        position: relative;
    }

    .search-select-toggle {
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

    .search-select-toggle:focus,
    .search-select.open .search-select-toggle {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .search-select-value {
        font-size: 13px;
        color: #334155;
    }

    .search-select-placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    .search-select-chevron {
        margin-left: auto;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .search-select-dropdown {
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

    .search-select.open .search-select-dropdown {
        display: block;
    }

    .search-select-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 13px;
        outline: none;
        border-radius: 0;
    }

    .search-select-options {
        max-height: 220px;
        overflow-y: auto;
    }

    .search-select-option {
        display: block;
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
    }

    .search-select-option:last-child {
        border-bottom: none;
    }

    .search-select-option:hover {
        background: #f8fafc;
    }

    .search-select-option.selected {
        background: #f0f9ff;
        color: #0073bd;
        font-weight: 600;
    }

    .search-select-empty {
        font-size: 13px;
    }

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
        cursor: crosshair;
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
            <div class="search-select" id="asesiSearchSelect">
                <input type="hidden" id="asesiSelect" name="asesi_nik" value="{{ $selectedAsesiNik }}">
                <button type="button" class="search-select-toggle" aria-haspopup="listbox" aria-expanded="false">
                    <span class="search-select-value">-- Pilih Asesi --</span>
                    <i class="bi bi-chevron-down search-select-chevron"></i>
                </button>
                <div class="search-select-dropdown" role="listbox">
                    <input type="text" class="search-select-search" placeholder="Ketik untuk mencari...">
                    <div class="search-select-options"></div>
                </div>
            </div>
            @error('asesi_nik')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema <span class="req">*</span></label>
            <select id="skemaSelect" class="{{ $lockSkema ? 'locked' : '' }}" {{ $lockSkema ? 'disabled' : '' }}>
                <option value="">-- Pilih Skema --</option>
                @foreach($skemaList as $skema)
                    <option value="{{ $skema->id }}" data-nomor="{{ $skema->nomor_skema }}" data-jenis="{{ $skema->jenis_skema ?? '' }}" {{ $selectedSkemaId === (string) $skema->id ? 'selected' : '' }}>
                        {{ $skema->nama_skema }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="skema_id" id="skemaIdInput" value="{{ $selectedSkemaId }}">
            @error('skema_id')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nomor Skema</label>
            <input id="nomorSkemaInput" type="text" readonly>
        </div>

        <div class="field">
            <label>Tipe TUK</label>
            <input id="tipeTukDisplay" type="text"
                value="{{ $selectedTipeTukLabel }}"
                placeholder="Otomatis terisi dari jadwal" readonly
                style="background:#f8fafc;color:#64748b;">
            <input type="hidden" name="tuk" id="tukInput" value="{{ $selectedTuk }}">
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
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="observasi_demonstrasi">
                            <span>Observasi Demonstrasi</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="portofolio">
                            <span>Portofolio</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="pernyataan_pihak_ketiga">
                            <span>Pernyataan Pihak Ketiga</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="pertanyaan_lisan">
                            <span>Pertanyaan Lisan</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="pertanyaan_tertulis">
                            <span>Pertanyaan Tertulis</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="proyek_kerja">
                            <span>Proyek Kerja</span>
                        </div>
                    </th>
                    <th>
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="checkbox" class="col-select-all" data-column="lainnya">
                            <span>Lainnya</span>
                        </div>
                    </th>
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

    @php
        $savedSignature = $asesor->saved_tanda_tangan ?? null;
        $ttdAsesorTanggal = old('ttd_asesor_tanggal', ($record && $record->ttd_asesor_tanggal) ? $record->ttd_asesor_tanggal->format('Y-m-d') : '');
    @endphp

    <div class="grid-2" style="margin-top:16px;">
        <div class="field full">
            <div class="signature-section">
                <h3><i class="bi bi-pen"></i> Tanda Tangan Asesor</h3>
                <p class="signature-subtitle">Dengan menandatangani, asesor menyatakan pernyataan asesor telah diisi dengan benar.</p>

                @if($savedSignature)
                    {{-- Opsi Tanda Tangan --}}
                    <div id="sigChoiceWrapAsesor" style="margin-bottom:14px; text-align: left; display: grid; gap: 8px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;" id="optSavedAsesorLabel">
                            <input type="radio" name="sig_choice_asesor" value="saved" checked id="optSavedAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#10b981;">
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#166534;"><i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Gunakan tanda tangan tersimpan</div>
                                <div style="font-size:12px;color:#64748b;">Menggunakan TTD yang sudah disimpan di profil Anda</div>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;background:#f8fafc;" id="optNewAsesorLabel">
                            <input type="radio" name="sig_choice_asesor" value="new" id="optNewAsesor" onchange="toggleAsesorSigChoice()" style="accent-color:#0073bd;">
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#0f172a;"><i class="bi bi-pen" style="color:#0073bd;"></i> Tanda tangan baru</div>
                                <div style="font-size:12px;color:#64748b;">Gambar tanda tangan baru untuk rekaman asesmen ini</div>
                            </div>
                        </label>
                    </div>

                    {{-- Preview TTD tersimpan --}}
                    <div id="savedAsesorSigPreview" style="margin-bottom: 12px; text-align: center;">
                        <div style="display:inline-block;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:8px;margin-bottom:8px;">
                            <img src="{{ str_starts_with($savedSignature, 'data:image') ? $savedSignature : asset('storage/' . ltrim($savedSignature, '/')) }}" alt="TTD Tersimpan" style="max-width:260px;height:auto;display:block;">
                        </div>
                        <div style="font-size:11px;color:#94a3b8;">Tanda tangan tersimpan dari profil Anda</div>
                    </div>

                    {{-- Canvas tanda tangan baru (tersembunyi) --}}
                    <div id="newAsesorSigDraw" style="display:none;">
                        <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                            <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                            <div class="signature-placeholder">
                                <i class="bi bi-pen"></i>
                                <span>Tanda tangan di sini</span>
                            </div>
                        </div>
                        <div class="signature-actions">
                            <div style="display:flex;align-items:center;gap:8px;justify-content:space-between;width:100%;margin-top:12px;">
                                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#475569;cursor:pointer;margin:0;">
                                    <input type="checkbox" name="simpan_tanda_tangan" value="1" id="saveAsesorSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                                    <span>Simpan sebagai tanda tangan saya</span>
                                </label>
                                <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                                    <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Langsung Canvas --}}
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
                    <div class="signature-actions">
                        <div style="display:flex;align-items:center;gap:8px;justify-content:space-between;width:100%;margin-top:12px;">
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#475569;cursor:pointer;margin:0;">
                                <input type="checkbox" name="simpan_tanda_tangan" value="1" id="saveAsesorSigCheck" style="accent-color:#0073bd;width:15px;height:15px;cursor:pointer;">
                                <span>Simpan sebagai tanda tangan saya</span>
                            </label>
                            <button type="button" class="btn-clear-signature" id="clearSignatureAsesor">
                                    <i class="bi bi-eraser"></i> Hapus Tanda Tangan
                            </button>
                        </div>
                    </div>
                @endif

                <input type="hidden" name="ttd_asesor_nama" id="ttdAsesorNamaInput" value="{{ $value('ttd_asesor_nama', '') }}">
                <input type="hidden" name="ttd_asesor_tanggal" id="ttdAsesorTanggalInput" value="{{ $ttdAsesorTanggal }}">
                <input type="hidden" name="ttd_asesor_file" id="ttdAsesorFileInput" value="{{ $value('ttd_asesor_file', '') }}">
                @error('ttd_asesor_nama')<div class="error-text">{{ $message }}</div>@enderror
                @error('ttd_asesor_tanggal')<div class="error-text">{{ $message }}</div>@enderror

                <div class="signature-actions" style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="signature-date">
                        <i class="bi bi-calendar3"></i>
                        Tanggal: <strong id="signatureDateAsesor">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</strong>
                    </div>
                </div>
            </div>
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
    const skemaIdInput = document.getElementById('skemaIdInput');
    const nomorSkemaInput = document.getElementById('nomorSkemaInput');
    const asesiSelect = document.getElementById('asesiSelect');
    const unitRowsContainer = document.getElementById('unitRowsContainer');
    const kategoriSkemaInput = document.getElementById('kategoriSkemaInput');
    const asesiInfoGrid = document.getElementById('asesiInfoGrid');
    const tipeTukDisplay = document.getElementById('tipeTukDisplay');
    const tukInput = document.getElementById('tukInput');

    const participantsUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.skema-participants') }}';
    const unitsUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.skema-units') }}';
        const getAsesiDataUrl = '{{ route('asesor.rekaman-asesmen-kompetensi.get-asesi-data') }}';
    const selectedAsesiNik = @json($selectedAsesiNik);
    const initialDetailMap = @json($initialDetailMap ?? []);
    const selectedAsesiInfo = @json($selectedAsesiInfo);
    const selectedKategoriSkema = @json($value('kategori_skema', ''));
    let applyInitialSelection = true;
    let asesiOptions = @json($asesorAsesiList ?? []);
    let asesiList = [];
    const asesiSearchSelect = document.getElementById('asesiSearchSelect');

    const renderAsesiSelect = () => {
        if (!asesiSearchSelect) return;

        const hiddenInput = document.getElementById('asesiSelect');
        const toggle = asesiSearchSelect.querySelector('.search-select-toggle');
        const valueWrap = asesiSearchSelect.querySelector('.search-select-value');
        const searchInput = asesiSearchSelect.querySelector('.search-select-search');
        const optionsWrap = asesiSearchSelect.querySelector('.search-select-options');

        if (!hiddenInput || !toggle || !valueWrap || !searchInput || !optionsWrap) {
            return;
        }

        const syncValue = () => {
            const val = hiddenInput.value;
            const matched = asesiList.find(item => String(item.id) === String(val));
            if (matched) {
                valueWrap.textContent = `${matched.nama} (${matched.id})`;
                valueWrap.style.color = '#334155';
                valueWrap.style.fontWeight = '600';
            } else {
                valueWrap.textContent = '-- Pilih Asesi --';
                valueWrap.style.color = '#94a3b8';
                valueWrap.style.fontWeight = '400';
            }
        };

        const drawOptions = () => {
            const query = searchInput.value.trim().toLowerCase();
            optionsWrap.innerHTML = '';

            const filtered = asesiList.filter(item => {
                const haystack = `${item.nama} ${item.id}`.toLowerCase();
                return haystack.includes(query);
            });

            if (filtered.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'search-select-empty';
                empty.textContent = 'Tidak ada opsi yang cocok.';
                optionsWrap.appendChild(empty);
                return;
            }

            filtered.forEach(item => {
                const option = document.createElement('div');
                option.className = 'search-select-option';
                if (String(hiddenInput.value) === String(item.id)) {
                    option.classList.add('selected');
                }
                option.textContent = `${item.nama} (${item.id})`;
                option.addEventListener('click', (event) => {
                    event.preventDefault();
                    hiddenInput.value = item.id;
                    syncValue();
                    closeAsesiSelect();
                    hiddenInput.dispatchEvent(new Event('change'));
                });
                optionsWrap.appendChild(option);
            });
        };

        const closeAsesiSelect = () => {
            asesiSearchSelect.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        };

        syncValue();
        drawOptions();

        toggle.onclick = function (event) {
            event.preventDefault();
            const isOpen = asesiSearchSelect.classList.contains('open');
            document.querySelectorAll('.search-select.open').forEach(c => {
                if (c !== asesiSearchSelect) {
                    c.classList.remove('open');
                }
            });
            asesiSearchSelect.classList.toggle('open', !isOpen);
            toggle.setAttribute('aria-expanded', String(!isOpen));
            if (!isOpen) {
                searchInput.value = '';
                drawOptions();
                searchInput.focus();
            }
        };

        searchInput.oninput = drawOptions;

        asesiSearchSelect.onclick = (event) => {
            event.stopPropagation();
        };

        if (!asesiSearchSelect.dataset.selectDocBound) {
            document.addEventListener('click', (event) => {
                if (!asesiSearchSelect.contains(event.target)) {
                    closeAsesiSelect();
                }
            });
            asesiSearchSelect.dataset.selectDocBound = '1';
        }
    };

    const resetAsesi = (placeholder) => {
        asesiList = [];
        if (asesiSelect) {
            asesiSelect.value = '';
        }
        renderAsesiSelect();
    };

    const fillAsesi = (items, selectedValue, selectedLabel = '') => {
        asesiList = items.map(item => ({
            id: String(item.id),
            nama: item.nama
        }));

        if (asesiSelect) {
            let matched = asesiList.some(item => String(item.id) === String(selectedValue));
            if (selectedValue && !matched && selectedLabel) {
                asesiList.push({ id: String(selectedValue), nama: selectedLabel });
            }
            if (selectedValue) {
                asesiSelect.value = String(selectedValue);
            }
        }
        renderAsesiSelect();
    };

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
        const skemaValue = skemaSelect.value || '';
        if (skemaIdInput) {
            skemaIdInput.value = skemaValue;
        }
        nomorSkemaInput.value = selected ? (selected.getAttribute('data-nomor') || '') : '';
        if (kategoriSkemaInput) {
            kategoriSkemaInput.value = selected ? (selected.getAttribute('data-jenis') || '') : '';
        }
    };

    const syncTukValue = (value, tipeTuk = '') => {
        if (tukInput) {
            tukInput.value = value || '';
        }
        if (tipeTukDisplay) {
            const tipeTukMap = {
                'sewaktu': 'Sewaktu',
                'tempat_kerja': 'Tempat Kerja',
                'mandiri': 'Mandiri',
            };
            const rawTipe = tipeTuk || '';
            tipeTukDisplay.value = rawTipe ? (tipeTukMap[rawTipe] || rawTipe) : '';
            tipeTukDisplay.placeholder = rawTipe ? '' : 'Otomatis terisi dari jadwal';
        }
    };

    const setFieldLocked = (select, locked) => {
        if (!select) return;
        select.disabled = !!locked;
        select.classList.toggle('locked', !!locked);
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

    const lockDependentFields = () => {
        const mulaiInput = document.querySelector('input[name="tanggal_mulai"]');
        const selesaiInput = document.querySelector('input[name="tanggal_selesai"]');
    };

    const applyAsesiDetail = (data) => {
        if (!data || !data.asesi) {
            return;
        }

        renderAsesiInfo(data.asesi);

        syncTukValue(data.asesi.tuk || data.asesi.tuk_pelaksanaan || '', data.asesi.tipe_tuk || '');

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

        setFieldLocked(skemaSelect, true);
        lockDependentFields();
    };

    function updateHeaderCheckboxes() {
        const columns = [
            'observasi_demonstrasi',
            'portofolio',
            'pernyataan_pihak_ketiga',
            'pertanyaan_lisan',
            'pertanyaan_tertulis',
            'proyek_kerja',
            'lainnya'
        ];
        columns.forEach((column) => {
            const headerCheckbox = document.querySelector(`.col-select-all[data-column="${column}"]`);
            if (!headerCheckbox) return;
            const checkboxes = document.querySelectorAll(`input[type="checkbox"][name$="[${column}]"]`);
            if (checkboxes.length === 0) {
                headerCheckbox.checked = false;
                headerCheckbox.indeterminate = false;
                return;
            }
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            headerCheckbox.checked = (checkedCount === checkboxes.length);
            headerCheckbox.indeterminate = (checkedCount > 0 && checkedCount < checkboxes.length);
        });
    }

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

        updateHeaderCheckboxes();
    };

    const loadBySkema = async () => {
        const skemaId = skemaSelect.value;
        const preservedAsesiNik = applyInitialSelection ? selectedAsesiNik : (asesiSelect.value || '');

        syncNomorSkema();

        if (!skemaId) {
            resetAsesi('-- Pilih Asesi --');
            renderAsesiInfo(null);
            unitRowsContainer.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#64748b;">Pilih skema untuk memuat unit kompetensi.</td></tr>';
            setFieldLocked(skemaSelect, false);
            syncTukValue('');
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

            fillAsesi(
                asesiOptions,
                preservedAsesiNik,
                preservedAsesiNik ? asesiOptions.find(o => String(o.id) === String(preservedAsesiNik))?.nama : ''
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
                    renderAsesiSelect();
                    syncAsesiInfo(selectedAsesiOption);
                } else {
                    // Asesi not in list - fetch it separately as fallback
                    if (detailData.asesi) {
                        asesiList.push({
                            id: String(detailData.asesi.id),
                            nama: detailData.asesi.nama
                        });
                        asesiSelect.value = String(detailData.asesi.id);
                        renderAsesiSelect();
                        syncAsesiInfo(detailData.asesi);
                    } else {
                        asesiSelect.value = '';
                        renderAsesiSelect();
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
            resetAsesi('-- Gagal memuat asesi --');
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
                setFieldLocked(skemaSelect, false);
                syncTukValue('');
                const mulaiInput = document.querySelector('input[name="tanggal_mulai"]');
                const selesaiInput = document.querySelector('input[name="tanggal_selesai"]');
                if (mulaiInput) mulaiInput.value = '';
                if (selesaiInput) selesaiInput.value = '';
                asesiSelect.classList.remove('locked');
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

        // Select all feature

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('col-select-all')) {
                const column = e.target.getAttribute('data-column');
                const checked = e.target.checked;
                const checkboxes = document.querySelectorAll(`input[type="checkbox"][name$="[${column}]"]`);
                checkboxes.forEach((cb) => {
                    cb.checked = checked;
                });
            }
        });

        if (unitRowsContainer) {
            unitRowsContainer.addEventListener('change', (e) => {
                if (e.target && e.target.type === 'checkbox') {
                    updateHeaderCheckboxes();
                }
            });
        }

        function initSignatureCanvas(config) {
            const canvas = document.getElementById(config.canvasId);
            if (!canvas) return;

            const clearBtn = document.getElementById(config.clearBtnId);
            const hiddenInput = document.getElementById(config.hiddenInputId);
            const dateInput = config.dateInputId ? document.getElementById(config.dateInputId) : null;
            const nameInput = config.nameInputId ? document.getElementById(config.nameInputId) : null;
            const form = document.getElementById(config.formId) || canvas.closest('form');
            const ctx = canvas.getContext('2d');
            let drawing = false;
            let lastX = 0;
            let lastY = 0;
            const wrapper = config.wrapperId ? document.getElementById(config.wrapperId) : canvas.parentElement;
            const placeholder = wrapper ? wrapper.querySelector('.signature-placeholder') : null;
            let hasSignature = false;

            const resize = () => {
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

            const pos = (event) => {
                const rect = canvas.getBoundingClientRect();
                const point = event.touches && event.touches[0] ? event.touches[0] : event;
                return { x: point.clientX - rect.left, y: point.clientY - rect.top };
            };

            const start = (event) => {
                event.preventDefault();
                // If the canvas has 0 dimensions (e.g. was hidden when initialized), resize now
                if (canvas.width === 0 || canvas.height === 0) {
                    resize();
                }
                drawing = true;
                const p = pos(event);
                lastX = p.x;
                lastY = p.y;
                if (wrapper) wrapper.classList.add('active');
            };

            const move = (event) => {
                event.preventDefault();
                if (!drawing) return;
                const p = pos(event);
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
                ctx.lineTo(p.x, p.y);
                ctx.stroke();
                lastX = p.x;
                lastY = p.y;

                if (!hasSignature) {
                    hasSignature = true;
                    if (wrapper) wrapper.classList.add('has-signature');
                }

                if (nameInput && !nameInput.value) {
                    nameInput.value = 'Ditandatangani secara digital';
                }
                if (dateInput && !dateInput.value) {
                    const now = new Date();
                    const yyyy = now.getFullYear();
                    const mm = String(now.getMonth() + 1).padStart(2, '0');
                    const dd = String(now.getDate()).padStart(2, '0');
                    dateInput.value = `${yyyy}-${mm}-${dd}`;
                }
            };

            const stop = () => {
                drawing = false;
                if (wrapper) wrapper.classList.remove('active');
            };

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    hasSignature = false;
                    if (hiddenInput) hiddenInput.value = '';
                    if (dateInput) dateInput.value = '';
                    if (nameInput) nameInput.value = '';
                    if (wrapper) wrapper.classList.remove('has-signature');
                    const savedImg = document.getElementById('savedSignatureImgAsesor');
                    if (savedImg) savedImg.remove();
                });
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    const optSaved = document.getElementById('optSavedAsesor');
                    if (optSaved && optSaved.checked) {
                        return;
                    }

                    if (hiddenInput && hasSignature) {
                        hiddenInput.value = canvas.toDataURL('image/png');
                    }
                });
            }

            canvas.addEventListener('mousedown', start);
            canvas.addEventListener('mousemove', move);
            canvas.addEventListener('mouseup', stop);
            canvas.addEventListener('mouseleave', stop);
            canvas.addEventListener('touchstart', start, { passive: false });
            canvas.addEventListener('touchmove', move, { passive: false });
            canvas.addEventListener('touchend', stop);
            window.addEventListener('resize', resize);
            resize();

            if (hiddenInput && hiddenInput.value) {
                hasSignature = true;
                if (wrapper) wrapper.classList.add('has-signature');
            }
        }

        initSignatureCanvas({
            canvasId: 'signatureCanvasAsesor',
            clearBtnId: 'clearSignatureAsesor',
            hiddenInputId: 'ttdAsesorFileInput',
            wrapperId: 'signatureWrapperAsesor',
            dateInputId: 'ttdAsesorTanggalInput',
            nameInputId: 'ttdAsesorNamaInput',
        });

        const savedSignature = @json($savedSignature ?? null);
        window.toggleAsesorSigChoice = function() {
            const optSaved = document.getElementById('optSavedAsesor');
            const savedPreview = document.getElementById('savedAsesorSigPreview');
            const newDraw = document.getElementById('newAsesorSigDraw');
            const optSavedLabel = document.getElementById('optSavedAsesorLabel');
            const optNewAsesorLabel = document.getElementById('optNewAsesorLabel');
            const hiddenInput = document.getElementById('ttdAsesorFileInput');

            if (!optSaved) return;

            if (optSaved.checked) {
                if (savedPreview) savedPreview.style.display = '';
                if (newDraw) newDraw.style.display = 'none';
                if (optSavedLabel) {
                    optSavedLabel.style.borderColor = '#d1fae5'; optSavedLabel.style.background = '#f0fdf4';
                }
                if (optNewAsesorLabel) {
                    optNewAsesorLabel.style.borderColor = '#e2e8f0'; optNewAsesorLabel.style.background = '#f8fafc';
                }
                if (hiddenInput && savedSignature) hiddenInput.value = savedSignature;
            } else {
                if (savedPreview) savedPreview.style.display = 'none';
                if (newDraw) newDraw.style.display = 'block';
                if (optSavedLabel) {
                    optSavedLabel.style.borderColor = '#e2e8f0'; optSavedLabel.style.background = '#f8fafc';
                }
                if (optNewAsesorLabel) {
                    optNewAsesorLabel.style.borderColor = '#bfdbfe'; optNewAsesorLabel.style.background = '#eff6ff';
                }
                if (hiddenInput) hiddenInput.value = '';
                // Trigger canvas resize now that the container is visible
                setTimeout(function() { window.dispatchEvent(new Event('resize')); }, 50);
            }
        };

        if (document.getElementById('optSavedAsesor')) {
            toggleAsesorSigChoice();
        }

        syncNomorSkema();
        loadBySkema();
    }
});
</script>
