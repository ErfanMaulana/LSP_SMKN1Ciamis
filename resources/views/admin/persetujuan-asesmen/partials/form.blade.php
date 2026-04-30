@php
    $record = $item ?? null;
    $defaults = $defaults ?? [];
    $skemaList = $skemaList ?? collect();
    $tukList = $tukList ?? collect();
    $ttdAsesorTanggal = old('ttd_asesor_tanggal', ($record && $record->ttd_asesor_tanggal) ? $record->ttd_asesor_tanggal->format('Y-m-d') : '');
    $ttdAsesiTanggal = old('ttd_asesi_tanggal', ($record && $record->ttd_asesi_tanggal) ? $record->ttd_asesi_tanggal->format('Y-m-d') : '');

    $value = function (string $field, $fallback = '') use ($record, $defaults) {
        if (old($field) !== null) {
            return old($field);
        }

        if ($record && isset($record->{$field})) {
            return $record->{$field};
        }

        return $defaults[$field] ?? $fallback;
    };

    $checked = function (string $field) use ($record) {
        if (old($field) !== null) {
            return old($field) == '1';
        }

        return (bool) ($record->{$field} ?? false);
    };

    $selectedAsesor = $value('nama_asesor');
    $selectedAsesi = $value('nama_asesi');
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

    .field textarea { min-height: 92px; resize: vertical; }

    .field .invalid {
        border-color: #ef4444;
    }

    .error-text {
        font-size: 12px;
        color: #ef4444;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 14px;
        margin-top: 6px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #334155;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 14px;
        flex-wrap: wrap;
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
        .grid-2, .checkbox-grid {
            grid-template-columns: 1fr;
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
            <input type="text" name="kode_form" value="{{ $value('kode_form') }}" class="{{ $errors->has('kode_form') ? 'invalid' : '' }}">
            @error('kode_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Judul Form <span class="req">*</span></label>
            <input type="text" name="judul_form" value="{{ $value('judul_form') }}" class="{{ $errors->has('judul_form') ? 'invalid' : '' }}">
            @error('judul_form')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Pengantar <span class="req">*</span></label>
            <textarea name="pengantar" class="{{ $errors->has('pengantar') ? 'invalid' : '' }}">{{ $value('pengantar') }}</textarea>
            @error('pengantar')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Skema Sertifikasi (Kategori)</label>
            @php $selectedKategoriSkema = $value('kategori_skema'); @endphp
            <select name="kategori_skema">
                <option value="">-- Pilih Kategori Skema --</option>
                <option value="KKNI" {{ $selectedKategoriSkema === 'KKNI' ? 'selected' : '' }}>KKNI</option>
                <option value="Okupasi" {{ $selectedKategoriSkema === 'Okupasi' ? 'selected' : '' }}>Okupasi</option>
                <option value="Klaster" {{ $selectedKategoriSkema === 'Klaster' ? 'selected' : '' }}>Klaster</option>
               
            </select>
            @error('kategori_skema')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Judul Skema <span class="req">*</span></label>
            <select id="judulSkemaSelect" name="judul_skema" class="{{ $errors->has('judul_skema') ? 'invalid' : '' }}">
                <option value="">-- Pilih Judul Skema --</option>
                @foreach($skemaList as $skema)
                    <option value="{{ $skema->nama_skema }}" data-id="{{ $skema->id }}" data-nomor="{{ $skema->nomor_skema }}" {{ $value('judul_skema') === $skema->nama_skema ? 'selected' : '' }}>
                        {{ $skema->nama_skema }}
                    </option>
                @endforeach
            </select>
            @error('judul_skema')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nomor Skema <span class="req">*</span></label>
            <input id="nomorSkemaInput" type="text" name="nomor_skema" value="{{ $value('nomor_skema') }}" class="{{ $errors->has('nomor_skema') ? 'invalid' : '' }}" readonly>
            @error('nomor_skema')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>TUK</label>
            @php $selectedTuk = $value('tuk'); @endphp
            <select name="tuk">
                <option value="">-- Pilih TUK --</option>
                <option value="Sewaktu" {{ $selectedTuk === 'Sewaktu' ? 'selected' : '' }}>Sewaktu</option>
                <option value="Tempat Kerja" {{ $selectedTuk === 'Tempat Kerja' ? 'selected' : '' }}>Tempat Kerja</option>
                <option value="Mandiri" {{ $selectedTuk === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                
            </select>
            @error('tuk')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nama Asesor <span class="req">*</span></label>
            <select id="namaAsesorSelect" name="nama_asesor" class="{{ $errors->has('nama_asesor') ? 'invalid' : '' }}">
                <option value="">-- Pilih Asesor --</option>
            </select>
            @error('nama_asesor')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Nama Asesi <span class="req">*</span></label>
            <select id="namaAsesiSelect" name="nama_asesi" class="{{ $errors->has('nama_asesi') ? 'invalid' : '' }}">
                <option value="">-- Pilih Asesi --</option>
            </select>
            @error('nama_asesi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Bukti yang akan dikumpulkan</label>
            <div class="checkbox-grid">
                <label class="checkbox-item"><input type="checkbox" name="bukti_verifikasi_portofolio" value="1" {{ $checked('bukti_verifikasi_portofolio') ? 'checked' : '' }}> Hasil Verifikasi Portofolio</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_reviu_produk" value="1" {{ $checked('bukti_reviu_produk') ? 'checked' : '' }}> Hasil Reviu Produk</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_observasi_langsung" value="1" {{ $checked('bukti_observasi_langsung') ? 'checked' : '' }}> Hasil Observasi Langsung</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_kegiatan_terstruktur" value="1" {{ $checked('bukti_kegiatan_terstruktur') ? 'checked' : '' }}> Hasil Kegiatan Terstruktur</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_pertanyaan_lisan" value="1" {{ $checked('bukti_pertanyaan_lisan') ? 'checked' : '' }}> Hasil Pertanyaan Lisan</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_pertanyaan_tertulis" value="1" {{ $checked('bukti_pertanyaan_tertulis') ? 'checked' : '' }}> Hasil Pertanyaan Tertulis</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_pertanyaan_wawancara" value="1" {{ $checked('bukti_pertanyaan_wawancara') ? 'checked' : '' }}> Hasil Pertanyaan Wawancara</label>
                <label class="checkbox-item"><input type="checkbox" name="bukti_lainnya" value="1" {{ $checked('bukti_lainnya') ? 'checked' : '' }}> Lainnya</label>
            </div>
            <input style="margin-top:8px;" type="text" name="bukti_lainnya_keterangan" value="{{ $value('bukti_lainnya_keterangan') }}" placeholder="Keterangan bukti lainnya (opsional)">
            @error('bukti_lainnya_keterangan')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Hari / Tanggal</label>
            <input type="text" name="hari_tanggal" value="{{ $value('hari_tanggal') }}" placeholder="Contoh: Senin, 11 April 2026">
            @error('hari_tanggal')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Waktu</label>
            <input type="text" name="waktu" value="{{ $value('waktu') }}" placeholder="Contoh: 08.00 - 10.00 WIB">
            @error('waktu')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>TUK Pelaksanaan</label>
            <select name="tuk_pelaksanaan">
                <option value="">-- Pilih TUK Pelaksanaan --</option>
                @foreach($tukList as $tuk)
                    @php
                        $tipeLabelPelaksanaan = match($tuk->tipe_tuk) {
                            'sewaktu' => 'TUK Sewaktu',
                            'tempat_kerja' => 'TUK Tempat Kerja',
                            'mandiri' => 'TUK Mandiri',
                            default => $tuk->tipe_tuk,
                        };
                        $displayLabelPelaksanaan = $tuk->nama_tuk . ' (' . $tipeLabelPelaksanaan . ($tuk->kota ? ' - ' . $tuk->kota : '') . ')';
                        $selectedTukPelaksanaan = $value('tuk_pelaksanaan');
                    @endphp
                    <option value="{{ $tuk->nama_tuk }}" {{ $selectedTukPelaksanaan === $tuk->nama_tuk ? 'selected' : '' }}>
                        {{ $displayLabelPelaksanaan }}
                    </option>
                @endforeach
                @if($value('tuk_pelaksanaan') && $tukList->where('nama_tuk', $value('tuk_pelaksanaan'))->isEmpty())
                    <option value="{{ $value('tuk_pelaksanaan') }}" selected>{{ $value('tuk_pelaksanaan') }}</option>
                @endif
            </select>
            @error('tuk_pelaksanaan')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Pernyataan Asesi (Bagian 1) <span class="req">*</span></label>
            <textarea name="pernyataan_asesi_1" class="{{ $errors->has('pernyataan_asesi_1') ? 'invalid' : '' }}">{{ $value('pernyataan_asesi_1') }}</textarea>
            @error('pernyataan_asesi_1')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Pernyataan Asesor <span class="req">*</span></label>
            <textarea name="pernyataan_asesor" class="{{ $errors->has('pernyataan_asesor') ? 'invalid' : '' }}">{{ $value('pernyataan_asesor') }}</textarea>
            @error('pernyataan_asesor')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <label>Pernyataan Asesi (Bagian 2) <span class="req">*</span></label>
            <textarea name="pernyataan_asesi_2" class="{{ $errors->has('pernyataan_asesi_2') ? 'invalid' : '' }}">{{ $value('pernyataan_asesi_2') }}</textarea>
            @error('pernyataan_asesi_2')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field full">
            <div class="signature-section">
                <h3><i class="bi bi-pen"></i> Tanda Tangan Asesor</h3>
                <p class="signature-subtitle">Dengan menandatangani, asesor menyatakan pernyataan asesor di atas telah diisi dengan benar.</p>

                <div class="signature-canvas-wrapper" id="signatureWrapperAsesor">
                    <canvas class="signature-canvas" id="signatureCanvasAsesor"></canvas>
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesor_nama" id="ttdAsesorNamaInput" value="{{ $value('ttd_asesor_nama') }}">
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

        <div class="field full">
            <div class="signature-section">
                <h3><i class="bi bi-pen"></i> Tanda Tangan Asesi</h3>
                <p class="signature-subtitle">Dengan menandatangani, saya menyatakan bahwa semua jawaban di atas adalah benar dan sesuai dengan kompetensi yang saya miliki.</p>

                <div class="signature-canvas-wrapper" id="signatureWrapperAsesi">
                    <canvas class="signature-canvas" id="signatureCanvasAsesi"></canvas>
                    <div class="signature-placeholder">
                        <i class="bi bi-pen"></i>
                        <span>Tanda tangan di sini</span>
                    </div>
                </div>

                <input type="hidden" name="ttd_asesi_nama" id="ttdAsesiNamaInput" value="{{ $value('ttd_asesi_nama') }}">
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
            <label>Catatan Footer</label>
            <input type="text" name="catatan_footer" value="{{ $value('catatan_footer') }}">
            @error('catatan_footer')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel }}</button>
        <a href="{{ route('admin.persetujuan-asesmen.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const judulSkemaSelect = document.getElementById('judulSkemaSelect');
    const nomorSkemaInput = document.getElementById('nomorSkemaInput');
    const namaAsesorSelect = document.getElementById('namaAsesorSelect');
    const namaAsesiSelect = document.getElementById('namaAsesiSelect');
    const signatureCanvasAsesor = document.getElementById('signatureCanvasAsesor');
    const signatureWrapperAsesor = document.getElementById('signatureWrapperAsesor');
    const clearSignatureAsesor = document.getElementById('clearSignatureAsesor');
    const ttdAsesorNamaInput = document.getElementById('ttdAsesorNamaInput');
    const ttdAsesorTanggalInput = document.getElementById('ttdAsesorTanggalInput');
    const signatureCanvasAsesi = document.getElementById('signatureCanvasAsesi');
    const signatureWrapperAsesi = document.getElementById('signatureWrapperAsesi');
    const clearSignatureAsesi = document.getElementById('clearSignatureAsesi');
    const ttdAsesiNamaInput = document.getElementById('ttdAsesiNamaInput');
    const ttdAsesiTanggalInput = document.getElementById('ttdAsesiTanggalInput');
    const endpointUrl = '{{ route('admin.persetujuan-asesmen.skema-participants') }}';
    const selectedAsesorName = @json($selectedAsesor);
    const selectedAsesiName = @json($selectedAsesi);
    let applyInitialSelection = true;

    if (!judulSkemaSelect || !nomorSkemaInput || !namaAsesorSelect || !namaAsesiSelect) {
        return;
    }

    const resetParticipantSelect = (select, placeholder) => {
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    };

    const fillParticipantSelect = (select, placeholder, items, selectedValue) => {
        resetParticipantSelect(select, placeholder);

        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.nama;
            option.textContent = item.nama;
            if (selectedValue && selectedValue === item.nama) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        if (selectedValue && !items.some((item) => item.nama === selectedValue)) {
            const fallback = document.createElement('option');
            fallback.value = selectedValue;
            fallback.textContent = selectedValue;
            fallback.selected = true;
            select.appendChild(fallback);
        }
    };

    const syncNomorSkema = () => {
        const selected = judulSkemaSelect.options[judulSkemaSelect.selectedIndex];
        const nomorSkema = selected ? (selected.getAttribute('data-nomor') || '') : '';
        nomorSkemaInput.value = nomorSkema;
    };

    const syncParticipantsBySkema = async () => {
        const selected = judulSkemaSelect.options[judulSkemaSelect.selectedIndex];
        const skemaId = selected ? selected.getAttribute('data-id') : '';

        if (!skemaId) {
            resetParticipantSelect(namaAsesorSelect, '-- Pilih Asesor --');
            resetParticipantSelect(namaAsesiSelect, '-- Pilih Asesi --');
            return;
        }

        try {
            const response = await fetch(`${endpointUrl}?skema_id=${encodeURIComponent(skemaId)}`);
            const payload = await response.json();

            const asesorSelection = applyInitialSelection ? selectedAsesorName : '';
            const asesiSelection = applyInitialSelection ? selectedAsesiName : '';

            fillParticipantSelect(namaAsesorSelect, '-- Pilih Asesor --', payload.asesor || [], asesorSelection);
            fillParticipantSelect(namaAsesiSelect, '-- Pilih Asesi --', payload.asesi || [], asesiSelection);
            applyInitialSelection = false;
        } catch (error) {
            resetParticipantSelect(namaAsesorSelect, '-- Gagal memuat asesor --');
            resetParticipantSelect(namaAsesiSelect, '-- Gagal memuat asesi --');
            applyInitialSelection = false;
        }
    };

    const initSignaturePad = (config) => {
        const {
            canvas,
            wrapper,
            clearButton,
            nameInput,
            dateInput,
            getSignerName,
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
                const signerName = typeof getSignerName === 'function' ? getSignerName() : '';
                nameInput.value = signerName || 'Ditandatangani secara digital';
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

    judulSkemaSelect.addEventListener('change', () => {
        syncNomorSkema();
        syncParticipantsBySkema();
    });

    if (judulSkemaSelect.value) {
        syncNomorSkema();
        syncParticipantsBySkema();
    } else {
        resetParticipantSelect(namaAsesorSelect, '-- Pilih Asesor --');
        resetParticipantSelect(namaAsesiSelect, '-- Pilih Asesi --');
    }

    initSignaturePad({
        canvas: signatureCanvasAsesor,
        wrapper: signatureWrapperAsesor,
        clearButton: clearSignatureAsesor,
        nameInput: ttdAsesorNamaInput,
        dateInput: ttdAsesorTanggalInput,
        getSignerName: () => (namaAsesorSelect && namaAsesorSelect.value) ? namaAsesorSelect.value : '',
    });

    initSignaturePad({
        canvas: signatureCanvasAsesi,
        wrapper: signatureWrapperAsesi,
        clearButton: clearSignatureAsesi,
        nameInput: ttdAsesiNamaInput,
        dateInput: ttdAsesiTanggalInput,
        getSignerName: () => (namaAsesiSelect && namaAsesiSelect.value) ? namaAsesiSelect.value : '',
    });
});
</script>
