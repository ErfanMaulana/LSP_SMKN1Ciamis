@extends('admin.layout')

@section('title', 'Edit Skema Sertifikasi')
@section('page-title', 'Edit Skema Sertifikasi')

@section('styles')
<style>
    .form-container { padding: 0; }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .form-header h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin: 0 0 4px 0;
    }

    .subtitle { font-size: 14px; color: #64748b; margin: 0; }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary { background: #0F172A; color: white; }
    .btn-primary:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3); }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .btn-outline { background: white; color: #0F172A; border: 1px solid #e2e8f0; }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-body { padding: 28px; }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .required { color: #ef4444; }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s;
        width: 100%;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 12px; color: #ef4444; }
    .form-text { font-size: 11px; color: #64748b; }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 16px;
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    textarea.form-control { resize: vertical; min-height: 60px; }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .section-label {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-desc {
        font-size: 12px;
        color: #64748b;
        margin: 0 0 16px;
    }

    .unit-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .unit-card:hover { border-color: #cbd5e1; }

    .unit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .unit-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #3b82f6;
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }

    .unit-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .elemen-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        margin-left: 16px;
    }

    .elemen-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .elemen-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #8b5cf6;
        color: white;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .elemen-title {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kriteria-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 8px;
        margin-left: 16px;
    }

    .kriteria-number {
        min-width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef3c7;
        color: #92400e;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 7px;
    }

    .kriteria-item .form-control { flex: 1; }

    .remove-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: transparent;
        color: #94a3b8;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
        font-size: 16px;
    }

    .remove-btn:hover { background: #fee2e2; color: #ef4444; }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: 1px dashed #cbd5e1;
        background: transparent;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 8px;
    }

    .add-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

    .add-btn.add-unit {
        width: 100%;
        justify-content: center;
        padding: 14px;
        font-size: 13px;
        font-weight: 600;
        border-width: 2px;
    }

    .add-btn.add-elemen { margin-left: 16px; }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
    }

    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .info-box {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .info-box i { color: #0284c7; font-size: 18px; flex-shrink: 0; }
    .info-box strong { display: block; font-size: 13px; color: #0c4a6e; margin-bottom: 2px; }
    .info-box p { font-size: 12px; color: #075985; margin: 0; }

    .divider { height: 1px; background: #e2e8f0; margin: 20px 0; }

    @media (max-width: 768px) {
        .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
        .elemen-card { margin-left: 8px; }
        .kriteria-item { margin-left: 8px; }
        .add-btn.add-elemen { margin-left: 8px; }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <div>
            <h2>Edit Skema Sertifikasi</h2>
            <p class="subtitle">Perbarui data skema beserta unit kompetensi, elemen, dan kriteria</p>
        </div>
        <a href="{{ route('admin.skema.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin: 4px 0 0 16px; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.skema.update', $skema->id) }}" method="POST" id="skemaForm">
        @csrf
        @method('PUT')

        <!-- Info Skema -->
        <div class="card">
            <div class="card-body">
                <div class="section-label">
                    <i class="bi bi-patch-check" style="color:#3b82f6;"></i> Informasi Skema
                </div>
                <p class="section-desc">Data dasar skema sertifikasi kompetensi.</p>

                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Informasi</strong>
                        <p>Skema dibuat pada {{ $skema->created_at->format('d M Y, H:i') }} &bull; Terakhir diperbarui {{ $skema->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nomor_skema">Nomor Skema <span class="required">*</span></label>
                    <input type="text" id="nomor_skema" name="nomor_skema" class="form-control" 
                           value="{{ old('nomor_skema', $skema->nomor_skema) }}" placeholder="Contoh: SKM/0072/00024/1/2023/1" required>
                    <small class="form-text">Format: SKM/xxxx/xxxxx/x/xxxx/x</small>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_skema">Nama Skema <span class="required">*</span></label>
                        <input type="text" id="nama_skema" name="nama_skema" class="form-control" 
                               value="{{ old('nama_skema', $skema->nama_skema) }}" placeholder="Contoh: Okupasi Pemrogram Junior" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_skema">Jenis Skema <span class="required">*</span></label>
                        <select id="jenis_skema" name="jenis_skema" class="form-control" required>
                            <option value="">-- Pilih Jenis Skema --</option>
                            <option value="KKNI" {{ old('jenis_skema', $skema->jenis_skema) == 'KKNI' ? 'selected' : '' }}>KKNI</option>
                            <option value="Okupasi" {{ old('jenis_skema', $skema->jenis_skema) == 'Okupasi' ? 'selected' : '' }}>Okupasi</option>
                            <option value="Klaster" {{ old('jenis_skema', $skema->jenis_skema) == 'Klaster' ? 'selected' : '' }}>Klaster</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Kompetensi -->
        <div class="card">
            <div class="card-body">
                <div class="section-label">
                    <i class="bi bi-layers" style="color:#10b981;"></i> Unit Kompetensi
                </div>
                <p class="section-desc">Kelola unit kompetensi beserta elemen dan kriteria unjuk kerja (KUK).</p>

                <div id="units-container">
                    @php $units = old('units', $skema->units->toArray()); @endphp
                    @foreach($units as $uIdx => $unit)
                    <div class="unit-card" data-unit-index="{{ $uIdx }}">
                        <div class="unit-header">
                            <div class="unit-title">
                                <span class="unit-number">{{ $uIdx + 1 }}</span>
                                Unit Kompetensi #{{ $uIdx + 1 }}
                            </div>
                            <button type="button" class="remove-btn remove-unit-btn" title="Hapus unit" style="{{ count($units) <= 1 ? 'display:none;' : '' }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>Kode Unit <span class="required">*</span></label>
                                <input type="text" name="units[{{ $uIdx }}][kode_unit]" class="form-control" 
                                       value="{{ $unit['kode_unit'] ?? '' }}" placeholder="Contoh: J.620100.001.02" required>
                            </div>
                            <div class="form-group">
                                <label>Judul Unit <span class="required">*</span></label>
                                <input type="text" name="units[{{ $uIdx }}][judul_unit]" class="form-control" 
                                       value="{{ $unit['judul_unit'] ?? '' }}" placeholder="Contoh: Menggunakan Struktur Data" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pertanyaan Unit</label>
                            <textarea name="units[{{ $uIdx }}][pertanyaan_unit]" class="form-control" rows="2" placeholder="Contoh: Dapatkah Saya menggunakan Struktur Data?">{{ $unit['pertanyaan_unit'] ?? '' }}</textarea>
                        </div>

                        <div class="divider"></div>

                        <div class="elemens-container">
                            @php $elemens = $unit['elemens'] ?? []; @endphp
                            @foreach($elemens as $eIdx => $elemen)
                            <div class="elemen-card" data-elemen-index="{{ $eIdx }}">
                                <div class="elemen-header">
                                    <div class="elemen-title">
                                        <span class="elemen-number">{{ $eIdx + 1 }}</span>
                                        Elemen #{{ $eIdx + 1 }}
                                    </div>
                                    <button type="button" class="remove-btn remove-elemen-btn" title="Hapus elemen" style="{{ count($elemens) <= 1 ? 'display:none;' : '' }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label>Nama Elemen <span class="required">*</span></label>
                                    <input type="text" name="units[{{ $uIdx }}][elemens][{{ $eIdx }}][nama_elemen]" class="form-control" 
                                           value="{{ $elemen['nama_elemen'] ?? '' }}" placeholder="Contoh: Mengidentifikasi konsep data" required>
                                </div>

                                <div class="kriteria-container">
                                    <label style="font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;display:block;margin-left:16px;">
                                        Kriteria Unjuk Kerja (KUK) <span class="required">*</span>
                                    </label>
                                    @php $kriterias = $elemen['kriteria'] ?? []; @endphp
                                    @foreach($kriterias as $kIdx => $kriteria)
                                    <div class="kriteria-item">
                                        <span class="kriteria-number">{{ $kIdx + 1 }}</span>
                                        <input type="text" name="units[{{ $uIdx }}][elemens][{{ $eIdx }}][kriteria][{{ $kIdx }}][deskripsi_kriteria]" class="form-control" 
                                               value="{{ $kriteria['deskripsi_kriteria'] ?? '' }}" placeholder="Deskripsi kriteria unjuk kerja..." required>
                                        <button type="button" class="remove-btn remove-kriteria-btn" title="Hapus kriteria" style="{{ count($kriterias) <= 1 ? 'display:none;' : '' }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                    @if(count($kriterias) === 0)
                                    <div class="kriteria-item">
                                        <span class="kriteria-number">1</span>
                                        <input type="text" name="units[{{ $uIdx }}][elemens][{{ $eIdx }}][kriteria][0][deskripsi_kriteria]" class="form-control" 
                                               placeholder="Deskripsi kriteria unjuk kerja..." required>
                                        <button type="button" class="remove-btn remove-kriteria-btn" title="Hapus kriteria" style="display:none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>

                                <button type="button" class="add-btn add-kriteria-btn" style="margin-left:16px;">
                                    <i class="bi bi-plus"></i> Tambah KUK
                                </button>
                            </div>
                            @endforeach
                            @if(count($elemens) === 0)
                            <div class="elemen-card" data-elemen-index="0">
                                <div class="elemen-header">
                                    <div class="elemen-title">
                                        <span class="elemen-number">1</span>
                                        Elemen #1
                                    </div>
                                    <button type="button" class="remove-btn remove-elemen-btn" title="Hapus elemen" style="display:none;">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label>Nama Elemen <span class="required">*</span></label>
                                    <input type="text" name="units[{{ $uIdx }}][elemens][0][nama_elemen]" class="form-control" placeholder="Contoh: Mengidentifikasi konsep data" required>
                                </div>
                                <div class="kriteria-container">
                                    <label style="font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;display:block;margin-left:16px;">
                                        Kriteria Unjuk Kerja (KUK) <span class="required">*</span>
                                    </label>
                                    <div class="kriteria-item">
                                        <span class="kriteria-number">1</span>
                                        <input type="text" name="units[{{ $uIdx }}][elemens][0][kriteria][0][deskripsi_kriteria]" class="form-control" placeholder="Deskripsi kriteria unjuk kerja..." required>
                                        <button type="button" class="remove-btn remove-kriteria-btn" title="Hapus kriteria" style="display:none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="add-btn add-kriteria-btn" style="margin-left:16px;">
                                    <i class="bi bi-plus"></i> Tambah KUK
                                </button>
                            </div>
                            @endif
                        </div>

                        <button type="button" class="add-btn add-elemen">
                            <i class="bi bi-plus-circle"></i> Tambah Elemen
                        </button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="add-btn add-unit" id="addUnitBtn">
                    <i class="bi bi-plus-circle"></i> Tambah Unit Kompetensi
                </button>
            </div>
        </div>

        <!-- Submit -->
        <div class="card">
            <div class="card-body">
                <div class="form-actions" style="border-top: none; padding-top: 0;">
                    <a href="{{ route('admin.skema.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Skema
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let unitIndex = {{ count($units ?? []) }};

    // ===== ADD UNIT =====
    document.getElementById('addUnitBtn').addEventListener('click', function() {
        const container = document.getElementById('units-container');
        const unitHtml = createUnitHtml(unitIndex);
        container.insertAdjacentHTML('beforeend', unitHtml);
        unitIndex++;
        updateNumbers();
        updateRemoveButtons();
    });

    // ===== EVENT DELEGATION =====
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-elemen')) {
            const unitCard = e.target.closest('.unit-card');
            const uIdx = getUnitIndex(unitCard);
            const elemensContainer = unitCard.querySelector('.elemens-container');
            const eIdx = elemensContainer.querySelectorAll('.elemen-card').length;
            elemensContainer.insertAdjacentHTML('beforeend', createElemenHtml(uIdx, eIdx));
            updateNumbers();
            updateRemoveButtons();
        }

        if (e.target.closest('.add-kriteria-btn')) {
            const elemenCard = e.target.closest('.elemen-card');
            const unitCard = elemenCard.closest('.unit-card');
            const uIdx = getUnitIndex(unitCard);
            const eIdx = getElemenIndex(elemenCard);
            const kriteriaContainer = elemenCard.querySelector('.kriteria-container');
            const kIdx = kriteriaContainer.querySelectorAll('.kriteria-item').length;
            kriteriaContainer.insertAdjacentHTML('beforeend', createKriteriaHtml(uIdx, eIdx, kIdx));
            updateNumbers();
            updateRemoveButtons();
        }

        if (e.target.closest('.remove-unit-btn')) {
            const unitCard = e.target.closest('.unit-card');
            unitCard.remove();
            reindexAll();
            updateNumbers();
            updateRemoveButtons();
        }

        if (e.target.closest('.remove-elemen-btn')) {
            const elemenCard = e.target.closest('.elemen-card');
            const unitCard = elemenCard.closest('.unit-card');
            elemenCard.remove();
            reindexUnit(unitCard);
            updateNumbers();
            updateRemoveButtons();
        }

        if (e.target.closest('.remove-kriteria-btn')) {
            const kriteriaItem = e.target.closest('.kriteria-item');
            const elemenCard = kriteriaItem.closest('.elemen-card');
            const unitCard = elemenCard.closest('.unit-card');
            kriteriaItem.remove();
            reindexElemen(elemenCard, unitCard);
            updateNumbers();
            updateRemoveButtons();
        }
    });

    // ===== HTML GENERATORS =====
    function createUnitHtml(uIdx) {
        return `
        <div class="unit-card" data-unit-index="${uIdx}">
            <div class="unit-header">
                <div class="unit-title">
                    <span class="unit-number">${uIdx + 1}</span>
                    Unit Kompetensi #${uIdx + 1}
                </div>
                <button type="button" class="remove-btn remove-unit-btn" title="Hapus unit">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Kode Unit <span class="required">*</span></label>
                    <input type="text" name="units[${uIdx}][kode_unit]" class="form-control" placeholder="Contoh: J.620100.001.02" required>
                </div>
                <div class="form-group">
                    <label>Judul Unit <span class="required">*</span></label>
                    <input type="text" name="units[${uIdx}][judul_unit]" class="form-control" placeholder="Contoh: Menggunakan Struktur Data" required>
                </div>
            </div>
            <div class="form-group">
                <label>Pertanyaan Unit</label>
                <textarea name="units[${uIdx}][pertanyaan_unit]" class="form-control" rows="2" placeholder="Contoh: Dapatkah Saya menggunakan Struktur Data?"></textarea>
            </div>
            <div class="divider"></div>
            <div class="elemens-container">
                ${createElemenHtml(uIdx, 0)}
            </div>
            <button type="button" class="add-btn add-elemen">
                <i class="bi bi-plus-circle"></i> Tambah Elemen
            </button>
        </div>`;
    }

    function createElemenHtml(uIdx, eIdx) {
        return `
        <div class="elemen-card" data-elemen-index="${eIdx}">
            <div class="elemen-header">
                <div class="elemen-title">
                    <span class="elemen-number">${eIdx + 1}</span>
                    Elemen #${eIdx + 1}
                </div>
                <button type="button" class="remove-btn remove-elemen-btn" title="Hapus elemen">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="form-group">
                <label>Nama Elemen <span class="required">*</span></label>
                <input type="text" name="units[${uIdx}][elemens][${eIdx}][nama_elemen]" class="form-control" placeholder="Contoh: Mengidentifikasi konsep data" required>
            </div>
            <div class="kriteria-container">
                <label style="font-size:12px;font-weight:600;color:#475569;margin-bottom:6px;display:block;margin-left:16px;">
                    Kriteria Unjuk Kerja (KUK) <span class="required">*</span>
                </label>
                ${createKriteriaHtml(uIdx, eIdx, 0)}
            </div>
            <button type="button" class="add-btn add-kriteria-btn" style="margin-left:16px;">
                <i class="bi bi-plus"></i> Tambah KUK
            </button>
        </div>`;
    }

    function createKriteriaHtml(uIdx, eIdx, kIdx) {
        return `
        <div class="kriteria-item">
            <span class="kriteria-number">${kIdx + 1}</span>
            <input type="text" name="units[${uIdx}][elemens][${eIdx}][kriteria][${kIdx}][deskripsi_kriteria]" class="form-control" placeholder="Deskripsi kriteria unjuk kerja..." required>
            <button type="button" class="remove-btn remove-kriteria-btn" title="Hapus kriteria">
                <i class="bi bi-x"></i>
            </button>
        </div>`;
    }

    // ===== REINDEX =====
    function getUnitIndex(unitCard) {
        const allUnits = document.querySelectorAll('#units-container .unit-card');
        return Array.from(allUnits).indexOf(unitCard);
    }

    function getElemenIndex(elemenCard) {
        const elemens = elemenCard.closest('.elemens-container').querySelectorAll('.elemen-card');
        return Array.from(elemens).indexOf(elemenCard);
    }

    function reindexAll() {
        const units = document.querySelectorAll('#units-container .unit-card');
        units.forEach((unit, uIdx) => {
            unit.setAttribute('data-unit-index', uIdx);
            unit.querySelector('input[name*="[kode_unit]"]').name = `units[${uIdx}][kode_unit]`;
            unit.querySelector('input[name*="[judul_unit]"]').name = `units[${uIdx}][judul_unit]`;
            unit.querySelector('textarea[name*="[pertanyaan_unit]"]').name = `units[${uIdx}][pertanyaan_unit]`;
            reindexUnit(unit);
        });
        unitIndex = units.length;
    }

    function reindexUnit(unitCard) {
        const uIdx = getUnitIndex(unitCard);
        if (uIdx < 0) return;

        const kodeInput = unitCard.querySelector('input[name*="[kode_unit]"]');
        const judulInput = unitCard.querySelector('input[name*="[judul_unit]"]');
        const pertanyaanArea = unitCard.querySelector('textarea[name*="[pertanyaan_unit]"]');
        if (kodeInput) kodeInput.name = `units[${uIdx}][kode_unit]`;
        if (judulInput) judulInput.name = `units[${uIdx}][judul_unit]`;
        if (pertanyaanArea) pertanyaanArea.name = `units[${uIdx}][pertanyaan_unit]`;

        const elemens = unitCard.querySelectorAll('.elemen-card');
        elemens.forEach((elemen, eIdx) => {
            elemen.setAttribute('data-elemen-index', eIdx);
            elemen.querySelector('input[name*="[nama_elemen]"]').name = `units[${uIdx}][elemens][${eIdx}][nama_elemen]`;
            reindexElemen(elemen, unitCard);
        });
    }

    function reindexElemen(elemenCard, unitCard) {
        const uIdx = getUnitIndex(unitCard);
        const eIdx = getElemenIndex(elemenCard);
        if (uIdx < 0 || eIdx < 0) return;

        const namaInput = elemenCard.querySelector('input[name*="[nama_elemen]"]');
        if (namaInput) namaInput.name = `units[${uIdx}][elemens][${eIdx}][nama_elemen]`;

        const kriterias = elemenCard.querySelectorAll('.kriteria-item');
        kriterias.forEach((kriteria, kIdx) => {
            kriteria.querySelector('input').name = `units[${uIdx}][elemens][${eIdx}][kriteria][${kIdx}][deskripsi_kriteria]`;
        });
    }

    // ===== UPDATE NUMBERS =====
    function updateNumbers() {
        const units = document.querySelectorAll('#units-container .unit-card');
        units.forEach((unit, uIdx) => {
            unit.querySelector('.unit-title').innerHTML = `<span class="unit-number">${uIdx + 1}</span> Unit Kompetensi #${uIdx + 1}`;

            const elemens = unit.querySelectorAll('.elemen-card');
            elemens.forEach((elemen, eIdx) => {
                elemen.querySelector('.elemen-title').innerHTML = `<span class="elemen-number">${eIdx + 1}</span> Elemen #${eIdx + 1}`;

                const kriterias = elemen.querySelectorAll('.kriteria-number');
                kriterias.forEach((num, kIdx) => {
                    num.textContent = kIdx + 1;
                });
            });
        });
    }

    // ===== UPDATE REMOVE BUTTONS =====
    function updateRemoveButtons() {
        const units = document.querySelectorAll('#units-container .unit-card');
        units.forEach(unit => {
            const removeBtn = unit.querySelector(':scope > .unit-header .remove-unit-btn');
            if (removeBtn) removeBtn.style.display = units.length > 1 ? 'flex' : 'none';

            const elemens = unit.querySelectorAll('.elemen-card');
            elemens.forEach(elemen => {
                const removeElBtn = elemen.querySelector(':scope > .elemen-header .remove-elemen-btn');
                if (removeElBtn) removeElBtn.style.display = elemens.length > 1 ? 'flex' : 'none';

                const kriterias = elemen.querySelectorAll('.kriteria-item');
                kriterias.forEach(kriteria => {
                    const removeKrBtn = kriteria.querySelector('.remove-kriteria-btn');
                    if (removeKrBtn) removeKrBtn.style.display = kriterias.length > 1 ? 'flex' : 'none';
                });
            });
        });
    }

    // Init
    updateRemoveButtons();
</script>
@endsection
