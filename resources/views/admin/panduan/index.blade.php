@extends('admin.layout')

@section('title', 'Panduan - ' . $sectionMeta['title'])
@section('page-title', 'Panduan')

@section('content')
@php
    $adminUser = Auth::guard('admin')->user();
    $canCreate = $adminUser->hasPermission('panduan.create');
    $canEdit = $adminUser->hasPermission('panduan.edit');
    $canDelete = $adminUser->hasPermission('panduan.delete');
@endphp

<div class="page-header">
    <div>
        <h2>{{ $sectionMeta['title'] }}</h2>
        <p class="subtitle">Kelola isi panduan yang tampil di halaman front.</p>
    </div>
    @if($canCreate)
        <a href="{{ route('admin.panduan.create', $section) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Poin
        </a>
    @endif
</div>

<div class="section-tabs">
    @foreach($sections as $key => $meta)
        <a href="{{ route('admin.panduan.index', $key) }}" class="section-tab {{ $section === $key ? 'active' : '' }}">
            {{ $meta['title'] }}
        </a>
    @endforeach
</div>

@if($canDelete && count($items) > 0)
    <div class="bulk-toolbar">
        <button type="button" class="btn btn-secondary" id="bulk-mode-btn" onclick="toggleBulkMode()">
            <i class="bi bi-ui-checks-grid"></i> Aktifkan Bulk Action
        </button>
        <div class="bulk-actions" id="bulk-actions">
            <button type="button" class="btn btn-secondary" id="bulk-select-btn" onclick="toggleAllSelection()" disabled>
                <i class="bi bi-check2-square"></i> Pilih Semua
            </button>
            <button type="button" class="btn btn-danger" id="bulk-delete-btn" disabled onclick="submitBulkDelete()">
                <i class="bi bi-trash"></i> Hapus Terpilih
            </button>
            <span id="bulk-selection-text" class="bulk-selection-text">0 item dipilih</span>
        </div>
    </div>

    <form id="bulk-delete-form" method="POST" action="{{ route('admin.panduan.bulk-destroy', $section) }}" style="display:none;">
        @csrf
        <div id="bulk-delete-ids"></div>
    </form>
@endif

@if(count($items) === 0)
    <div class="empty-state">
        <i class="bi bi-journal-text"></i>
        <h3>Belum ada poin panduan</h3>
        <p>Tambahkan poin agar halaman panduan front menampilkan konten dinamis.</p>
        @if($canCreate)
            <a href="{{ route('admin.panduan.create', $section) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Poin Pertama
            </a>
        @endif
    </div>
@else
    <div class="card table-wrap">
        <table class="table">
            <thead>
                <tr>
                    @if($canDelete)
                        <th width="44" class="text-center bulk-column">
                            <input type="checkbox" id="select-all-items" title="Pilih semua">
                        </th>
                    @endif
                    <th width="72">Urutan</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Penjelasan</th>
                    <th width="140">Foto</th>
                    <th width="90">Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        @if($canDelete)
                            <td class="text-center bulk-column">
                                <input type="checkbox" class="bulk-item-checkbox" value="{{ $item->id }}" aria-label="Pilih {{ $item->title }}">
                            </td>
                        @endif
                        <td>
                            <span class="order-badge">{{ $item->sort_order }}</span>
                        </td>
                        <td>
                            <strong>{{ $item->title }}</strong>
                        </td>
                        <td>
                            <span class="desc-preview">{{ \Illuminate\Support\Str::limit($item->description, 120) }}</span>
                        </td>
                        <td>
                            <span class="desc-preview">{{ \Illuminate\Support\Str::limit(strip_tags($item->penjelasan ?? ''), 140) ?: '-' }}</span>
                        </td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="thumb" alt="{{ $item->title }}">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($canEdit)
                                <form method="POST" action="{{ route('admin.panduan.toggle', [$section, $item->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-badge {{ $item->is_active ? 'active' : 'inactive' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            @else
                                <span class="status-badge {{ $item->is_active ? 'active' : 'inactive' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($canEdit || $canDelete)
                                <div class="action-menu">
                                    <button class="action-btn" onclick="toggleMenu(this)">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="action-dropdown">
                                        @if($canEdit)
                                            <a href="{{ route('admin.panduan.edit', [$section, $item->id]) }}" class="dropdown-item">
                                                <i class="bi bi-pencil"></i> Ubah
                                            </a>
                                        @endif
                                        @if($canDelete)
                                            <form action="{{ route('admin.panduan.destroy', [$section, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus poin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item danger">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection

@section('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:16px; flex-wrap:wrap; }
    .page-header h2 { font-size:22px; color:#0f172a; font-weight:700; }
    .subtitle { font-size:13px; color:#64748b; margin-top:4px; }

    .section-tabs { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px; }
    .section-tab { text-decoration:none; padding:8px 12px; border-radius:8px; border:1px solid #dbeafe; color:#1d4ed8; background:#eff6ff; font-size:13px; font-weight:600; }
    .section-tab.active { background:#0073bd; border-color:#0073bd; color:#fff; }

    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:8px; font-size:14px; text-decoration:none; border:none; cursor:pointer; }
    .btn-primary { background:#0073bd; color:#fff; }
    .btn-primary:hover { background:#003961; }
    .btn-secondary { background:#e2e8f0; color:#334155; }
    .btn-secondary:hover { background:#cbd5e1; }
    .btn-secondary:disabled { background:#f1f5f9; color:#94a3b8; cursor:not-allowed; }
    .btn-danger { background:#dc2626; color:#fff; }
    .btn-danger:hover { background:#b91c1c; }
    .btn-danger:disabled { background:#fca5a5; cursor:not-allowed; }

    .bulk-toolbar {
        display:flex;
        align-items:center;
        gap:12px;
        margin-bottom:14px;
        flex-wrap:wrap;
    }

    .bulk-actions {
        display: none;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        opacity: 0;
        transform: translateY(-4px);
    }

    .bulk-actions.show {
        display: flex;
        animation: bulkToolbarReveal .22s ease-out forwards;
    }

    .bulk-selection-text { font-size:13px; color:#64748b; font-weight:600; }
    .bulk-selection-text.bump { animation: selectionBump .18s ease-out; }

    .card { background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); }
    .table-wrap { overflow-x:auto; }
    .table { width:100%; border-collapse:collapse; min-width: 980px; }
    .bulk-column { display:none; }
    .table.bulk-enabled .bulk-column { display:table-cell; }
    .table.bulk-enabled .bulk-column input { animation: bulkCheckFade .2s ease-out; }

    #bulk-mode-btn {
        transition: transform .16s ease, box-shadow .2s ease, background-color .2s ease;
    }

    #bulk-mode-btn.bulk-on {
        background: #dbeafe;
        color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
    }

    #bulk-mode-btn:active {
        transform: translateY(1px) scale(0.99);
    }
    .table th { text-align:left; padding:12px 14px; border-bottom:2px solid #e2e8f0; font-size:12px; text-transform:uppercase; color:#64748b; }
    .table td { padding:12px 14px; border-bottom:1px solid #f1f5f9; font-size:14px; color:#334155; vertical-align:middle; }

    .order-badge { background:#e2e8f0; color:#475569; border-radius:6px; padding:4px 10px; font-size:12px; font-weight:600; }
    .desc-preview { font-size:13px; color:#64748b; line-height:1.5; }
    .thumb { width:110px; height:62px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; display:block; }
    .text-muted { color:#94a3b8; font-size:12px; }

    .status-badge { border:none; border-radius:999px; padding:5px 10px; font-size:11px; font-weight:700; cursor:pointer; }
    .status-badge.active { background:#dcfce7; color:#166534; }
    .status-badge.inactive { background:#fee2e2; color:#991b1b; }

    .action-menu { position:relative; display:inline-block; }
    .action-btn { border:none; background:#f1f5f9; width:34px; height:34px; border-radius:8px; cursor:pointer; }
    .action-dropdown {
        position:absolute; right:0; top:40px; min-width:140px; background:#fff; border:1px solid #e2e8f0;
        border-radius:10px; box-shadow:0 8px 20px rgba(15,23,42,.14); display:none; z-index:10;
    }
    .action-menu.open .action-dropdown { display:block; }
    .dropdown-item { display:flex; align-items:center; gap:8px; width:100%; border:none; background:none; text-decoration:none; color:#334155; font-size:13px; padding:10px 12px; cursor:pointer; text-align:left; }
    .dropdown-item:hover { background:#f8fafc; }
    .dropdown-item.danger { color:#b91c1c; }

    .empty-state { background:#fff; border-radius:12px; padding:48px 20px; text-align:center; }
    .empty-state i { font-size:42px; color:#94a3b8; display:block; margin-bottom:10px; }
    .empty-state h3 { color:#1e293b; margin-bottom:8px; }
    .empty-state p { color:#64748b; margin-bottom:16px; }

    @keyframes bulkToolbarReveal {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bulkCheckFade {
        from {
            opacity: 0;
            transform: scale(0.92);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes selectionBump {
        0% { transform: scale(1); }
        50% { transform: scale(1.06); }
        100% { transform: scale(1); }
    }

    @media (max-width: 768px) {
        .page-header h2 { font-size:18px; }
        .section-tab { font-size:12px; }
        .bulk-toolbar { align-items: stretch; }
        .bulk-toolbar .btn { width: 100%; justify-content: center; }
        .bulk-actions { width: 100%; }
        .bulk-selection-text { width: 100%; text-align: center; }
    }
</style>
@endsection

@section('scripts')
<script>
    function toggleMenu(button) {
        document.querySelectorAll('.action-menu').forEach(function (menu) {
            if (!menu.contains(button)) menu.classList.remove('open');
        });
        button.closest('.action-menu').classList.toggle('open');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-menu').forEach(function (menu) {
                menu.classList.remove('open');
            });
        }
    });

    function getSelectedItems() {
        return Array.from(document.querySelectorAll('.bulk-item-checkbox:checked')).map(function (checkbox) {
            return checkbox.value;
        });
    }

    var bulkModeEnabled = false;
    var displayedSelectedCount = 0;
    var selectionAnimFrame = null;

    function setSelectionText(textEl, count) {
        textEl.textContent = count + ' item dipilih';
    }

    function animateSelectionCount(textEl, targetCount) {
        if (selectionAnimFrame) {
            cancelAnimationFrame(selectionAnimFrame);
        }

        var startCount = displayedSelectedCount;
        var startTime = null;
        var duration = 180;

        function step(timestamp) {
            if (!startTime) {
                startTime = timestamp;
            }

            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.round(startCount + (targetCount - startCount) * eased);

            setSelectionText(textEl, current);

            if (progress < 1) {
                selectionAnimFrame = requestAnimationFrame(step);
                return;
            }

            displayedSelectedCount = targetCount;
            textEl.classList.remove('bump');
            void textEl.offsetWidth;
            textEl.classList.add('bump');
        }

        selectionAnimFrame = requestAnimationFrame(step);
    }

    function clearBulkSelection() {
        document.querySelectorAll('.bulk-item-checkbox').forEach(function (checkbox) {
            checkbox.checked = false;
        });

        var selectAll = document.getElementById('select-all-items');
        if (selectAll) {
            selectAll.checked = false;
        }
    }

    function toggleBulkMode() {
        setBulkMode(!bulkModeEnabled);
    }

    function setBulkMode(enabled) {
        var table = document.querySelector('.table');
        var modeBtn = document.getElementById('bulk-mode-btn');
        var actions = document.getElementById('bulk-actions');

        bulkModeEnabled = enabled;

        if (table) {
            table.classList.toggle('bulk-enabled', enabled);
        }

        if (actions) {
            actions.classList.toggle('show', enabled);
        }

        if (modeBtn) {
            modeBtn.innerHTML = enabled
                ? '<i class="bi bi-x-circle"></i> Nonaktifkan Bulk Action'
                : '<i class="bi bi-ui-checks-grid"></i> Aktifkan Bulk Action';
            modeBtn.classList.toggle('bulk-on', enabled);
        }

        if (!enabled) {
            clearBulkSelection();
        }

        updateBulkState();
    }

    function updateBulkState() {
        var checkboxes = Array.from(document.querySelectorAll('.bulk-item-checkbox'));
        var selectedIds = getSelectedItems();
        var selectAll = document.getElementById('select-all-items');
        var deleteBtn = document.getElementById('bulk-delete-btn');
        var selectBtn = document.getElementById('bulk-select-btn');
        var text = document.getElementById('bulk-selection-text');

        if (!checkboxes.length || !selectAll || !deleteBtn || !text) {
            return;
        }

        selectAll.checked = selectedIds.length === checkboxes.length;
        deleteBtn.disabled = !bulkModeEnabled || selectedIds.length === 0;

        if (selectBtn) {
            selectBtn.disabled = !bulkModeEnabled || checkboxes.length === 0;
            selectBtn.innerHTML = selectedIds.length === checkboxes.length
                ? '<i class="bi bi-x-square"></i> Batal Pilih'
                : '<i class="bi bi-check2-square"></i> Pilih Semua';
        }

        animateSelectionCount(text, selectedIds.length);
    }

    function toggleAllSelection() {
        if (!bulkModeEnabled) {
            return;
        }

        var checkboxes = Array.from(document.querySelectorAll('.bulk-item-checkbox'));
        if (!checkboxes.length) {
            return;
        }

        var selectedCount = getSelectedItems().length;
        var shouldSelectAll = selectedCount !== checkboxes.length;

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = shouldSelectAll;
        });

        var selectAll = document.getElementById('select-all-items');
        if (selectAll) {
            selectAll.checked = shouldSelectAll;
        }

        updateBulkState();
    }

    function submitBulkDelete() {
        if (!bulkModeEnabled) {
            return;
        }

        var selectedIds = getSelectedItems();
        if (!selectedIds.length) {
            return;
        }

        if (!confirm('Hapus ' + selectedIds.length + ' poin terpilih?')) {
            return;
        }

        var container = document.getElementById('bulk-delete-ids');
        var form = document.getElementById('bulk-delete-form');
        if (!container || !form) {
            return;
        }

        container.innerHTML = '';
        selectedIds.forEach(function (id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            container.appendChild(input);
        });

        form.submit();
    }

    var selectAllCheckbox = document.getElementById('select-all-items');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function (e) {
            if (!bulkModeEnabled) {
                e.target.checked = false;
                return;
            }

            var checked = e.target.checked;
            document.querySelectorAll('.bulk-item-checkbox').forEach(function (checkbox) {
                checkbox.checked = checked;
            });
            updateBulkState();
        });
    }

    document.querySelectorAll('.bulk-item-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', updateBulkState);
    });

    setBulkMode(false);
    updateBulkState();
</script>
@endsection
