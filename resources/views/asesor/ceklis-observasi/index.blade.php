@extends('asesor.layout')

@section('title', 'Ceklis Observasi')
@section('page-title', 'Ceklis Observasi Aktivitas Praktik')

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
        background: #005f9a;
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

    .btn-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #e0f2fe;
        color: #0c4a6e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover { background: #bae6fd; color: #0c4a6e; }
    .btn-review.disabled {
        background: #e2e8f0;
        color: #64748b;
        pointer-events: none;
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
        <h2>Ceklis Observasi Aktivitas Praktik</h2>
        <p>Kelola ceklis observasi aktivitas praktik asesi.</p>
    </div>
    <a href="{{ route('asesor.ceklis-observasi.create') }}" class="btn-action">
        <i class="bi bi-plus-circle"></i> Isi Ceklis
    </a>
</div>

<form method="GET" action="{{ route('asesor.ceklis-observasi.index') }}" class="filter-form" id="ceklisObsFilterForm">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                class="search-input"
                id="ceklisObsSearchInput"
                name="search"
                value="{{ $search }}"
                placeholder="Cari asesi atau skema..."
                autocomplete="off"
            >
        </div>
        <div class="filter-field">
            <label class="filter-label">Rekomendasi</label>
            <select name="rekomendasi" id="ceklisObsRekomendasiFilter" class="filter-select">
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
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Rekomendasi</th>
                    <th>Tanggal</th>
                    <th style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="ceklisObsTableContainer">
                @include('asesor.ceklis-observasi.partials.table-rows')
            </tbody>
        </table>
    </div>

    @php $isPaginator = is_object($items) && method_exists($items, 'firstItem'); @endphp
    @if($isPaginator && $items->hasPages())
        <div class="pager">{{ $items->links() }}</div>
    @endif
</div>

<script>
    let ceklisObsAjaxController = null;
    let ceklisObsSearchTimer = null;

    function ajaxLoadCeklisObs(url) {
        if (ceklisObsAjaxController) {
            ceklisObsAjaxController.abort();
        }

        ceklisObsAjaxController = new AbortController();

        const tableContainer = document.getElementById('ceklisObsTableContainer');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: ceklisObsAjaxController.signal
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data ceklis observasi');
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

    function serializeCeklisObsForm() {
        const searchInput = document.getElementById('ceklisObsSearchInput');
        const rekomendasiFilter = document.getElementById('ceklisObsRekomendasiFilter');
        const url = new URL('{{ route('asesor.ceklis-observasi.index') }}', window.location.origin);

        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        if (rekomendasiFilter && rekomendasiFilter.value.trim() !== '') {
            url.searchParams.set('rekomendasi', rekomendasiFilter.value.trim());
        }

        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('ceklisObsSearchInput');
        const rekomendasiFilter = document.getElementById('ceklisObsRekomendasiFilter');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(ceklisObsSearchTimer);
                ceklisObsSearchTimer = setTimeout(function() {
                    ajaxLoadCeklisObs(serializeCeklisObsForm());
                }, 400);
            });
        }

        if (rekomendasiFilter) {
            rekomendasiFilter.addEventListener('change', function() {
                ajaxLoadCeklisObs(serializeCeklisObsForm());
            });
        }
    });
</script>
@endsection
