@extends('asesor.layout')

@section('title', 'Rekaman Asesmen Kompetensi')
@section('page-title', 'Rekaman Asesmen Kompetensi')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .page-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }
    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0073bd;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s ease;
        white-space: nowrap;
    }
    .btn-action:hover {
        background: #003961;
        color: #ffffff;
    }

    .filter-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .filter-row {
        display: grid;
        gap: 10px;
        align-items: end;
    }

    .filter-row-top {
        grid-template-columns: minmax(0, 1fr) minmax(240px, 280px);
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .search-input-wrapper {
        flex: 1 1 360px;
        position: relative;
        min-width: 0;
    }

    .search-input {
        width: 100%;
        padding: 12px 44px 12px 42px;
        border: 1px solid #dbe4ef;
        border-radius: 14px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }

    .filter-select {
        width: 100%;
        min-width: 0;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #dbe4ef;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        color: #0f172a;
        font-size: 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        outline: none;
    }

    .filter-select:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .3px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 12px;
    }
    td {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-rekomendasi-kompeten { background: #dcfce7; color: #15803d; }
    .badge-rekomendasi-belum { background: #fef3c7; color: #92400e; }

    /* Kebab Menu Styles */
    .action-menu-wrapper {
        position: relative;
        display: inline-block;
        z-index: 999;
    }

    .btn-kebab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: #64748b;
        padding: 4px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 28px;
        height: 28px;
    }

    .btn-kebab:hover {
        color: #0f172a;
    }

    .btn-kebab.active {
        background: transparent;
        color: #0073bd;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        min-width: 180px;
        z-index: 10000;
        display: none;
        margin-top: 4px;
        overflow: visible;
        pointer-events: auto;
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .dropdown-menu.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .dropdown-menu.show-up {
        transform: translateY(0);
    }

    .dropdown-menu.dropdown-floating {
        position: fixed;
        top: 0;
        left: 0;
        right: auto;
        margin: 0;
        width: 180px;
        min-width: 180px;
        max-width: 240px;
        max-height: calc(100vh - 24px);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 20000;
        opacity: 0;
        transform: translateY(6px);
    }

    .dropdown-menu.dropdown-floating.show {
        opacity: 1;
        transform: translateY(0);
    }

    body.dropdown-open {
        overflow: hidden;
    }

    .dropdown-menu a,
    .dropdown-menu button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 14px;
        border: none;
        background: none;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        border-bottom: 1px solid #f3f4f6;
    }

    .menu-entry-label {
        flex: 1;
        min-width: 0;
    }

    .dropdown-menu a:last-child,
    .dropdown-menu button:last-child {
        border-bottom: none;
    }

    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
        background: #f8fafc;
        color: #0073bd;
    }

    .dropdown-menu a i,
    .dropdown-menu button i {
        font-size: 14px;
        width: 18px;
        text-align: center;
    }

    .dropdown-menu .menu-danger:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .dropdown-menu .menu-danger i {
        color: #dc2626;
    }

    .dropdown-menu .menu-danger:hover i {
        color: #dc2626;
    }

    table th:nth-child(6), table td:nth-child(6) {
        text-align: center;
        overflow: visible;
        width: 60px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    .empty-state i {
        font-size: 36px;
        color: #d1d5db;
        display: block;
        margin-bottom: 8px;
    }

    .pager {
        padding: 12px;
    }

    @media (max-width: 900px) {
        .filter-row-top {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Rekaman Asesmen Kompetensi</h2>
        <p>Kelola rekaman asesmen kompetensi asesi.</p>
    </div>
    <a href="{{ route('asesor.rekaman-asesmen-kompetensi.create') }}" class="btn-action">
        <i class="bi bi-plus-circle"></i> Tambah Rekaman
    </a>
</div>

<form method="GET" action="{{ route('asesor.rekaman-asesmen-kompetensi.index') }}" class="filter-form" id="rekamanFilterForm">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                class="search-input"
                id="rekamanSearchInput"
                name="search"
                value="{{ $search }}"
                placeholder="Cari skema atau asesi..."
                autocomplete="off"
            >
        </div>
        <div class="filter-field">
            <label class="filter-label">Rekomendasi</label>
            <select name="rekomendasi" id="rekamanRekomendasiFilter" class="filter-select">
                <option value="">Semua Rekomendasi</option>
                <option value="kompeten" {{ ($rekomendasi ?? '') === 'kompeten' ? 'selected' : '' }}>Kompeten</option>
                <option value="belum_kompeten" {{ ($rekomendasi ?? '') === 'belum_kompeten' ? 'selected' : '' }}>Belum Kompeten</option>
            </select>
        </div>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:46px;">No</th>
                    <th>Skema</th>
                    <th>Asesi</th>
                    <th>Rekomendasi</th>
                    <th>Periode</th>
                    <th style="width:60px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="rekamanTableContainer">
                @include('asesor.rekaman-asesmen-kompetensi.partials.table-rows')
            </tbody>
        </table>
    </div>

    @php $isPaginator = is_object($items) && method_exists($items, 'firstItem'); @endphp
    @if($isPaginator && $items->hasPages())
        <div class="pager">{{ $items->links() }}</div>
    @endif
</div>

<script>
    let rekamanAjaxController = null;
    let rekamanSearchTimer = null;

    // Dropdown Menu Functionality
    let activeDropdown = null;

    function restoreDropdown(menu, wrapper) {
        const originalStyle = menu.dataset.originalStyle || '';

        menu.classList.remove('show', 'show-up', 'dropdown-floating');
        menu.setAttribute('style', originalStyle);

        if (wrapper && !wrapper.contains(menu)) {
            wrapper.appendChild(menu);
        }

        delete menu.dataset.originalStyle;
    }

    function closeActiveDropdown() {
        if (!activeDropdown) {
            return;
        }

        const { menu, button, wrapper } = activeDropdown;
        restoreDropdown(menu, wrapper);
        button.classList.remove('active');
        activeDropdown = null;
        document.body.classList.remove('dropdown-open');
    }

    function openDropdown(button) {
        const wrapper = button.closest('.action-menu-wrapper');
        const menu = wrapper ? wrapper.querySelector('.dropdown-menu') : null;

        if (!menu) {
            return;
        }

        if (activeDropdown && activeDropdown.menu === menu) {
            closeActiveDropdown();
            return;
        }

        closeActiveDropdown();

        menu.dataset.originalStyle = menu.getAttribute('style') || '';
        document.body.appendChild(menu);
        document.body.classList.add('dropdown-open');

        menu.classList.add('dropdown-floating', 'show');
        button.classList.add('active');

        const buttonRect = button.getBoundingClientRect();
        const menuWidth = 180;
        const menuHeight = menu.offsetHeight;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const margin = 12;
        const gap = 8;
        const mainContent = document.querySelector('.main-content');
        const topbar = document.querySelector('.topbar');

        const contentRect = mainContent
            ? mainContent.getBoundingClientRect()
            : { left: margin };
        const topbarRect = topbar
            ? topbar.getBoundingClientRect()
            : { bottom: margin };

        const minLeft = Math.max(margin, Math.floor(contentRect.left) + 8);
        const minTop = Math.max(margin, Math.floor(topbarRect.bottom) + 6);

        let left = buttonRect.right - menuWidth;
        left = Math.max(minLeft, Math.min(left, viewportWidth - menuWidth - margin));

        let top = buttonRect.bottom + gap;
        let positionUp = false;

        if (top + menuHeight > viewportHeight - margin) {
            const aboveTop = buttonRect.top - menuHeight - gap;
            if (aboveTop >= minTop) {
                top = aboveTop;
                positionUp = true;
            } else {
                top = Math.max(minTop, viewportHeight - menuHeight - margin);
            }
        }

        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;
        menu.style.right = 'auto';
        menu.style.width = '180px';
        menu.style.minWidth = '180px';
        menu.style.maxWidth = '240px';
        menu.style.maxHeight = `calc(100vh - ${margin * 2}px)`;

        if (positionUp) {
            menu.classList.add('show-up');
        } else {
            menu.classList.remove('show-up');
        }

        activeDropdown = { menu, button, wrapper };
    }

    function ajaxLoadRekaman(url) {
        closeActiveDropdown();

        if (rekamanAjaxController) {
            rekamanAjaxController.abort();
        }

        rekamanAjaxController = new AbortController();

        const tableContainer = document.getElementById('rekamanTableContainer');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: rekamanAjaxController.signal
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data rekaman');
            }
            return response.text();
        })
        .then(function(html) {
            if (tableContainer) {
                tableContainer.innerHTML = html;
                tableContainer.style.opacity = '1';
            }

            window.history.replaceState({}, '', url);
        })
        .catch(function(error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                if (tableContainer) {
                    tableContainer.style.opacity = '1';
                }
            }
        });
    }

    function serializeRekamanForm() {
        const searchInput = document.getElementById('rekamanSearchInput');
        const rekomendasiFilter = document.getElementById('rekamanRekomendasiFilter');
        const url = new URL('{{ route('asesor.rekaman-asesmen-kompetensi.index') }}', window.location.origin);

        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        if (rekomendasiFilter && rekomendasiFilter.value.trim() !== '') {
            url.searchParams.set('rekomendasi', rekomendasiFilter.value.trim());
        }

        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('rekamanSearchInput');
        const rekomendasiFilter = document.getElementById('rekamanRekomendasiFilter');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(rekamanSearchTimer);
                rekamanSearchTimer = setTimeout(function() {
                    ajaxLoadRekaman(serializeRekamanForm());
                }, 400);
            });
        }

        if (rekomendasiFilter) {
            rekomendasiFilter.addEventListener('change', function() {
                ajaxLoadRekaman(serializeRekamanForm());
            });
        }

        // Use event delegation for dynamically loaded .btn-kebab buttons
        document.addEventListener('click', function(e) {
            const kebabBtn = e.target.closest('.btn-kebab');
            if (kebabBtn) {
                e.stopPropagation();
                openDropdown(kebabBtn);
                return;
            }

            if (!e.target.closest('.dropdown-menu') && !e.target.closest('.btn-kebab')) {
                closeActiveDropdown();
            }
        });

        document.addEventListener('scroll', function() {
            closeActiveDropdown();
        }, true);

        window.addEventListener('resize', function() {
            closeActiveDropdown();
        });
    });
</script>
@endsection
