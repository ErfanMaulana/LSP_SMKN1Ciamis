@php
    $oldItems = old('items');
    if ($oldItems !== null) {
        $items = $oldItems;
    } elseif (isset($item) && !empty($item->items)) {
        $items = $item->items;
    } else {
        $items = [''];
    }
@endphp

<div class="form-card">
    <div class="form-card-header">
        <div class="header-icon">
            <i class="bi bi-card-checklist"></i>
        </div>
        <div>
            <h3>Master Persyaratan Dasar</h3>
            <p>Setiap skema sertifikasi memiliki satu set persyaratan dasar pemohon yang akan divalidasi saat pendaftaran.</p>
        </div>
    </div>

    <div class="form-card-body">
        <div class="form-group">
            <label class="form-label" for="skema_id">
                Skema Sertifikasi <span class="required">*</span>
            </label>
            <div class="select-wrapper">
                <select name="skema_id" id="skema_id" class="form-select @error('skema_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Skema Sertifikasi --</option>
                    @foreach($skemaList as $skema)
                        @php $existingId = $skema->buktiPersyaratanDasarPemohon->id ?? null; @endphp
                        <option value="{{ $skema->id }}" {{ (string) old('skema_id', $item->skema_id ?? '') === (string) $skema->id ? 'selected' : '' }}>
                            {{ $skema->nama_skema }} ({{ $skema->nomor_skema }})
                            @if($existingId && (string) $existingId !== (string) ($item->id ?? ''))
                                &mdash; [Sudah Terdaftar]
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            @error('skema_id')
                <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="requirements-section">
            <div class="requirements-header">
                <div>
                    <h4><i class="bi bi-list-check"></i> Poin Persyaratan</h4>
                    <p>Tambahkan butir-butir poin persyaratan dokumen/bukti yang harus dipenuhi oleh asesi.</p>
                </div>
                <button type="button" class="btn-add-item" onclick="addRequirementRow()">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Poin</span>
                </button>
            </div>

            <div id="requirements-container" class="requirements-list">
                @foreach($items as $index => $value)
                    <div class="requirement-item">
                        <div class="requirement-badge">{{ $index + 1 }}</div>
                        <div class="input-wrap">
                            <input type="text" 
                                   name="items[]" 
                                   class="form-input requirement-input @error('items.'.$index) is-invalid @enderror" 
                                   value="{{ $value }}" 
                                   placeholder="Contoh: Copy Sertifikat Pelatihan / Surat Keterangan Pengalaman Kerja..." 
                                   required>
                        </div>
                        <button type="button" 
                                class="btn-remove-item" 
                                onclick="removeRequirementRow(this)" 
                                title="Hapus Poin Ini">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                @endforeach
            </div>

            @error('items')
                <div class="invalid-feedback mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
            @error('items.*')
                <div class="invalid-feedback mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="btn-cancel">
                <i class="bi bi-x-lg"></i>
                <span>Batal</span>
            </a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i>
                <span>{{ $submitLabel ?? 'Simpan Data' }}</span>
            </button>
        </div>
    </div>
</div>

<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .form-card-header {
        padding: 24px 28px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .form-card-header .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #e0f2fe;
        color: #0073bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
    }

    .form-card-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.4;
    }

    .form-card-body {
        padding: 28px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .select-wrapper {
        position: relative;
    }

    .form-select {
        width: 100%;
        padding: 12px 40px 12px 16px;
        font-size: 14px;
        color: #1e293b;
        background-color: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .form-select:hover {
        border-color: #94a3b8;
    }

    .form-select:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3.5px rgba(0, 115, 189, 0.12);
        background-color: #ffffff;
    }

    .requirements-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 28px;
    }

    .requirements-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .requirements-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .requirements-header h4 i {
        color: #0073bd;
    }

    .requirements-header p {
        margin: 4px 0 0;
        font-size: 12.5px;
        color: #64748b;
    }

    .btn-add-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #0073bd;
        background: #ffffff;
        border: 1.5px solid #bfdbfe;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .btn-add-item:hover {
        background: #0073bd;
        color: #ffffff;
        border-color: #0073bd;
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.2);
    }

    .requirements-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeIn 0.25s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .requirement-badge {
        width: 38px;
        height: 42px;
        min-width: 38px;
        background: #e2e8f0;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
    }

    .input-wrap {
        flex: 1;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #1e293b;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .form-input:hover {
        border-color: #94a3b8;
    }

    .form-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3.5px rgba(0, 115, 189, 0.12);
    }

    .btn-remove-item {
        width: 40px;
        height: 42px;
        min-width: 40px;
        background: #ffffff;
        color: #ef4444;
        border: 1.5px solid #fecaca;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-remove-item:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 13.5px;
        font-weight: 600;
        color: #64748b;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #334155;
        border-color: #94a3b8;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        font-size: 13.5px;
        font-weight: 600;
        color: #ffffff;
        background: #0073bd;
        border: 1.5px solid #0073bd;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 115, 189, 0.25);
    }

    .btn-submit:hover {
        background: #005a94;
        border-color: #005a94;
        box-shadow: 0 4px 14px rgba(0, 115, 189, 0.35);
        transform: translateY(-1px);
    }

    .invalid-feedback {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #dc2626;
        margin-top: 6px;
    }

    .mt-2 {
        margin-top: 8px;
    }

    @media (max-width: 640px) {
        .form-card-body {
            padding: 18px;
        }

        .requirements-section {
            padding: 14px;
        }

        .form-actions {
            flex-direction: column-reverse;
            width: 100%;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    function addRequirementRow() {
        const container = document.getElementById('requirements-container');
        const currentCount = container.querySelectorAll('.requirement-item').length;
        const row = document.createElement('div');
        row.className = 'requirement-item';
        row.innerHTML = `
            <div class="requirement-badge">${currentCount + 1}</div>
            <div class="input-wrap">
                <input type="text" 
                       name="items[]" 
                       class="form-input requirement-input" 
                       placeholder="Contoh: Copy Sertifikat Pelatihan / Surat Keterangan..." 
                       required>
            </div>
            <button type="button" 
                    class="btn-remove-item" 
                    onclick="removeRequirementRow(this)" 
                    title="Hapus Poin Ini">
                <i class="bi bi-trash3"></i>
            </button>
        `;
        container.appendChild(row);
        refreshRequirementNumbers();
        
        // Auto focus input yang baru ditambahkan
        const newInput = row.querySelector('input');
        if (newInput) newInput.focus();
    }

    function removeRequirementRow(button) {
        const container = document.getElementById('requirements-container');
        if (container.querySelectorAll('.requirement-item').length <= 1) {
            alert('Minimal harus ada 1 poin persyaratan.');
            return;
        }

        button.closest('.requirement-item').remove();
        refreshRequirementNumbers();
    }

    function refreshRequirementNumbers() {
        document.querySelectorAll('#requirements-container .requirement-item').forEach((row, index) => {
            const number = row.querySelector('.requirement-badge');
            if (number) {
                number.textContent = index + 1;
            }
        });
    }
</script>