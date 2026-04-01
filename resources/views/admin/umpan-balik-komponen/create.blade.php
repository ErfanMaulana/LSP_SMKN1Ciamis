@extends('admin.layout')

@section('title', 'Tambah Komponen Umpan Balik')
@section('page-title', 'Tambah Komponen Umpan Balik')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px; }
    .page-header h2 { margin: 0; font-size: 22px; color: #0F172A; }
    .btn { display: inline-flex; align-items: center; gap: 6px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 16px; }
    .btn-primary { background: #0073bd; color: #fff; }
    .btn-secondary { background: #64748b; color: #fff; }
    .btn-outline { background: #eff6ff; color: #1d4ed8; }
    .btn-danger { background: #fee2e2; color: #b91c1c; }
    .btn-primary:hover { background: #005f9a; }
    .btn-secondary:hover { background: #475569; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; }
    .card-body { padding: 24px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #334155; }
    .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-control:focus { outline: none; border-color: #0073bd; box-shadow: 0 0 0 3px rgba(0, 115, 189, .1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 5px; }
    .form-hint { font-size: 12px; color: #64748b; margin-top: 5px; }

    .component-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .component-row {
        display: grid;
        grid-template-columns: 34px minmax(0, 1fr) auto;
        gap: 10px;
        align-items: center;
    }

    .component-index {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #fef3c7;
        color: #b45309;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .component-input {
        width: 100%;
        min-width: 0;
    }

    .remove-btn {
        width: 30px;
        height: 30px;
        border: none;
        background: transparent;
        color: #94a3b8;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s;
    }

    .remove-btn:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border: 1px dashed #cbd5e1;
        background: transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all .2s;
    }

    .add-btn:hover {
        border-color: #0073bd;
        color: #0073bd;
        background: #eff6ff;
    }

    .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Tambah Komponen Umpan Balik</h2>
    <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.umpan-balik-komponen.store') }}">
            @csrf

            <div class="form-group">
                <label for="skema_ids">Skema <span class="required">*</span></label>
                @php
                    $oldSkemaIds = collect(old('skema_ids', []))->map(fn($id) => (string) $id)->all();
                @endphp
                <div id="skema-hidden-inputs"></div>

                <div style="position:relative;" id="skema-dropdown-wrap">
                    <div id="skema-trigger"
                         style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;
                                background:#f8fafc;cursor:pointer;font-size:14px;user-select:none;
                                transition:border-color .2s,box-shadow .2s;"
                         class="@error('skema_ids') is-invalid @enderror @error('skema_ids.*') is-invalid @enderror">
                        <span id="skema-trigger-text" style="color:#94a3b8;">Pilih skema kompetensi...</span>
                        <i class="bi bi-chevron-down" id="skema-chevron"
                           style="font-size:12px;color:#64748b;transition:transform .2s;"></i>
                    </div>

                    <div id="skema-dropdown"
                         style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:300;
                                background:white;border:1px solid #e2e8f0;border-radius:8px;
                                box-shadow:0 8px 24px rgba(0,0,0,.12);overflow:hidden;">
                        <div style="padding:8px 10px;border-bottom:1px solid #f1f5f9;">
                            <input type="text" id="skema-search" autocomplete="off"
                                   placeholder="&#128269; Cari nama atau kode skema..."
                                   style="width:100%;padding:7px 10px;border:1px solid #e2e8f0;
                                          border-radius:6px;font-size:13px;outline:none;
                                          font-family:inherit;background:#f8fafc;">
                        </div>
                        <div id="skema-list" style="max-height:240px;overflow-y:auto;"></div>
                    </div>
                </div>

                <div id="skema-badges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;"></div>
                @error('skema_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('skema_ids.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-hint">Pilih satu atau lebih skema sertifikasi.</div>
            </div>

            <div class="form-group">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:10px;">
                    <label style="margin:0;">Pernyataan Komponen <span class="required">*</span></label>
                </div>

                <div id="statementContainer" class="component-list">
                    @php
                        $oldStatements = old('pernyataan', ['']);
                    @endphp
                    @foreach($oldStatements as $i => $stmt)
                        <div class="component-row" data-statement-item>
                            <span class="component-index" data-component-index>{{ $i + 1 }}</span>
                            <input type="text" name="pernyataan[]" value="{{ $stmt }}" class="form-control component-input" placeholder="Contoh: Asesor memberikan kesempatan untuk mendiskusikan metode, instrumen, dan sumber asesmen." />
                            <button type="button" class="remove-btn" data-remove-statement title="Hapus pernyataan" {{ count($oldStatements) === 1 ? 'style=display:none;' : '' }}>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:10px;">
                    <button type="button" class="add-btn" id="addStatementBtn">
                        <i class="bi bi-plus-lg"></i> Tambah Komponen
                    </button>
                </div>

                @error('pernyataan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('pernyataan.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-hint">Anda bisa menambahkan beberapa pernyataan sekaligus untuk skema yang dipilih.</div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    Aktifkan semua komponen yang ditambahkan
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const ALL_SKEMAS = @json($skemaList->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_skema, 'nomor' => $s->nomor_skema]));
    const PRESELECTED_SKEMAS = @json($oldSkemaIds);

    const skemaWrap = document.getElementById('skema-dropdown-wrap');
    const skemaTrigger = document.getElementById('skema-trigger');
    const skemaTriggerText = document.getElementById('skema-trigger-text');
    const skemaChevron = document.getElementById('skema-chevron');
    const skemaDropdown = document.getElementById('skema-dropdown');
    const skemaSearch = document.getElementById('skema-search');
    const skemaList = document.getElementById('skema-list');
    const skemaBadges = document.getElementById('skema-badges');
    const skemaHiddenInputs = document.getElementById('skema-hidden-inputs');

    let skemaOpen = false;
    const selectedSkemas = new Map();

    PRESELECTED_SKEMAS.forEach((oldId) => {
        const s = ALL_SKEMAS.find((x) => String(x.id) === String(oldId));
        if (s) selectedSkemas.set(s.id, s);
    });

    const renderTriggerText = () => {
        if (selectedSkemas.size === 0) {
            skemaTriggerText.textContent = 'Pilih skema kompetensi...';
            skemaTriggerText.style.color = '#94a3b8';
            return;
        }
        skemaTriggerText.textContent = `${selectedSkemas.size} skema dipilih`;
        skemaTriggerText.style.color = '#1e293b';
    };

    const renderBadgesAndHiddenInputs = () => {
        skemaBadges.innerHTML = '';
        skemaHiddenInputs.innerHTML = '';

        selectedSkemas.forEach((s) => {
            const badge = document.createElement('span');
            badge.style.cssText = 'display:inline-flex;align-items:center;gap:5px;padding:4px 10px 4px 12px;background:#dbeafe;color:#1d4ed8;border-radius:20px;font-size:12px;font-weight:600;';
            badge.innerHTML = `${s.nama} <button type="button" data-remove-skema="${s.id}" style="background:none;border:none;cursor:pointer;color:#1d4ed8;font-size:15px;line-height:1;padding:0;">&times;</button>`;
            skemaBadges.appendChild(badge);

            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'skema_ids[]';
            inp.value = s.id;
            skemaHiddenInputs.appendChild(inp);
        });

        if (selectedSkemas.size === 0) {
            skemaBadges.innerHTML = '<span style="font-size:12px;color:#94a3b8;">Belum ada skema dipilih.</span>';
        }

        renderTriggerText();
    };

    const currentSkemaList = () => {
        const q = (skemaSearch.value || '').toLowerCase().trim();
        if (!q) return ALL_SKEMAS;
        return ALL_SKEMAS.filter((s) => s.nama.toLowerCase().includes(q) || s.nomor.toLowerCase().includes(q));
    };

    const buildDropdownItems = (list) => {
        skemaList.innerHTML = '';
        if (!list.length) {
            skemaList.innerHTML = '<div style="padding:10px 14px;color:#94a3b8;font-size:13px;">Tidak ada hasil.</div>';
            return;
        }

        list.forEach((s) => {
            const already = selectedSkemas.has(s.id);
            const item = document.createElement('div');
            item.style.cssText = `padding:9px 14px;font-size:13px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f8fafc;cursor:${already ? 'default' : 'pointer'};${already ? 'color:#94a3b8;' : 'color:#1e293b;'}`;
            item.innerHTML = `<span>${s.nama} <span style="font-size:11px;color:#94a3b8;">(${s.nomor})</span></span>${already ? '<span style="font-size:11px;color:#16a34a;font-weight:600;">&#10003; Dipilih</span>' : ''}`;

            if (!already) {
                item.addEventListener('click', () => {
                    selectedSkemas.set(s.id, s);
                    renderBadgesAndHiddenInputs();
                    buildDropdownItems(currentSkemaList());
                });
                item.addEventListener('mouseover', () => { item.style.background = '#f0f9ff'; });
                item.addEventListener('mouseout', () => { item.style.background = ''; });
            }

            skemaList.appendChild(item);
        });
    };

    const openSkemaDropdown = () => {
        skemaOpen = true;
        skemaSearch.value = '';
        buildDropdownItems(ALL_SKEMAS);
        skemaDropdown.style.display = '';
        skemaTrigger.style.borderColor = '#0073bd';
        skemaTrigger.style.boxShadow = '0 0 0 3px rgba(0,115,189,.1)';
        skemaTrigger.style.background = '#fff';
        skemaChevron.style.transform = 'rotate(180deg)';
        skemaSearch.focus();
    };

    const closeSkemaDropdown = () => {
        skemaOpen = false;
        skemaDropdown.style.display = 'none';
        skemaTrigger.style.borderColor = '#e2e8f0';
        skemaTrigger.style.boxShadow = '';
        skemaTrigger.style.background = '#f8fafc';
        skemaChevron.style.transform = '';
    };

    skemaTrigger.addEventListener('click', () => {
        if (skemaOpen) {
            closeSkemaDropdown();
        } else {
            openSkemaDropdown();
        }
    });

    skemaSearch.addEventListener('input', () => {
        buildDropdownItems(currentSkemaList());
    });

    skemaBadges.addEventListener('click', (event) => {
        const removeBtn = event.target.closest('[data-remove-skema]');
        if (!removeBtn) return;
        const id = Number(removeBtn.getAttribute('data-remove-skema'));
        selectedSkemas.delete(id);
        renderBadgesAndHiddenInputs();
        if (skemaOpen) buildDropdownItems(currentSkemaList());
    });

    document.addEventListener('click', (event) => {
        if (!skemaWrap.contains(event.target)) {
            closeSkemaDropdown();
        }
    });

    const container = document.getElementById('statementContainer');
    const addBtn = document.getElementById('addStatementBtn');

    const refreshTitles = () => {
        const items = container.querySelectorAll('[data-statement-item]');
        items.forEach((item, index) => {
            const title = item.querySelector('[data-component-index]');
            const removeBtn = item.querySelector('[data-remove-statement]');
            if (title) {
                title.textContent = `${index + 1}`;
            }
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? '' : 'none';
            }
        });
    };

    addBtn.addEventListener('click', () => {
        const item = document.createElement('div');
        item.className = 'component-row';
        item.setAttribute('data-statement-item', '');
        item.innerHTML = `
            <span class="component-index" data-component-index></span>
            <input type="text" name="pernyataan[]" class="form-control component-input" placeholder="Contoh: Asesor memberikan kesempatan untuk mendiskusikan metode, instrumen, dan sumber asesmen." />
            <button type="button" class="remove-btn" data-remove-statement title="Hapus pernyataan">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(item);
        refreshTitles();
    });

    container.addEventListener('click', (event) => {
        const btn = event.target.closest('[data-remove-statement]');
        if (!btn) return;

        const item = btn.closest('[data-statement-item]');
        if (item) {
            item.remove();
            refreshTitles();
        }
    });

    renderBadgesAndHiddenInputs();
    refreshTitles();
})();
</script>
@endsection
