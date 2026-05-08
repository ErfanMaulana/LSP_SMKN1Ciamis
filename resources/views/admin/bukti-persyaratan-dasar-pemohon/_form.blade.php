@php
    $items = old('items', $item->items ?? ['']);
    if (empty($items)) {
        $items = [''];
    }
@endphp

<div class="card">
    <div class="card-body">
        <div class="section-title">
            <div>
                <h3>Master Persyaratan Dasar</h3>
                <p>Setiap skema hanya memiliki satu set persyaratan dasar pemohon.</p>
            </div>
            <button type="button" class="btn-secondary" onclick="addRequirementRow()"><i class="bi bi-plus-circle"></i> Tambah Item</button>
        </div>

        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Skema <span style="color:#ef4444;">*</span></label>
                <select name="skema_id" class="form-control" required>
                    <option value="">-- Pilih Skema --</option>
                    @foreach($skemaList as $skema)
                        @php $existingId = $skema->buktiPersyaratanDasarPemohon->id ?? null; @endphp
                        <option value="{{ $skema->id }}" {{ (string) old('skema_id', $item->skema_id ?? '') === (string) $skema->id ? 'selected' : '' }}>
                            {{ $skema->nama_skema }} - {{ $skema->nomor_skema }}
                            @if($existingId && (string) $existingId !== (string) ($item->id ?? ''))
                                (sudah ada)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('skema_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="requirements-card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
                <div>
                    <h4 style="margin:0;font-size:16px;color:#0f172a;">Item Persyaratan</h4>
                    <p style="margin:6px 0 0;color:#64748b;font-size:13px;">Tambahkan daftar bukti yang harus diperiksa pada verifikasi asesi.</p>
                </div>
            </div>

            <div id="requirements-container">
                @foreach($items as $index => $value)
                    <div class="requirement-row">
                        <div class="requirement-number">{{ $index + 1 }}</div>
                        <input type="text" name="items[]" class="form-control requirement-input" value="{{ $value }}" placeholder="Masukkan item persyaratan..." required>
                        <button type="button" class="remove-btn" onclick="removeRequirementRow(this)" title="Hapus item"><i class="bi bi-trash"></i></button>
                    </div>
                @endforeach
            </div>
            @error('items')<div class="text-danger" style="margin-top:8px;">{{ $message }}</div>@enderror
            @error('items.*')<div class="text-danger" style="margin-top:8px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.bukti-persyaratan-dasar-pemohon.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><i class="bi bi-save"></i> {{ $submitLabel ?? 'Simpan' }}</button>
        </div>
    </div>
</div>

<script>
    function addRequirementRow() {
        const container = document.getElementById('requirements-container');
        const currentCount = container.querySelectorAll('.requirement-row').length;
        const row = document.createElement('div');
        row.className = 'requirement-row';
        row.innerHTML = `
            <div class="requirement-number">${currentCount + 1}</div>
            <input type="text" name="items[]" class="form-control requirement-input" placeholder="Masukkan item persyaratan..." required>
            <button type="button" class="remove-btn" onclick="removeRequirementRow(this)"><i class="bi bi-trash"></i></button>
        `;
        container.appendChild(row);
        refreshRequirementNumbers();
    }

    function removeRequirementRow(button) {
        const container = document.getElementById('requirements-container');
        if (container.querySelectorAll('.requirement-row').length <= 1) {
            return;
        }

        button.closest('.requirement-row').remove();
        refreshRequirementNumbers();
    }

    function refreshRequirementNumbers() {
        document.querySelectorAll('#requirements-container .requirement-row').forEach((row, index) => {
            const number = row.querySelector('.requirement-number');
            if (number) {
                number.textContent = index + 1;
            }
        });
    }
</script>