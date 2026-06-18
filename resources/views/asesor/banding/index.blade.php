@extends('asesor.layout')

@section('title', 'Banding Asesmen')
@section('page-title', 'Banding Asesmen')

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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .stat-card small {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: .4px;
    }
    .stat-card strong {
        display: block;
        margin-top: 5px;
        font-size: 24px;
        color: #0073bd;
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
        grid-template-columns: minmax(0, 1fr) minmax(200px, 240px) minmax(200px, 280px);
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
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 11px 14px;
    }
    td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    .badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-keputusan-lanjut { background: #dcfce7; color: #166534; }
    .badge-keputusan-tidak { background: #fee2e2; color: #991b1b; }

    .badge-status-draft { background: #e2e8f0; color: #475569; }
    .badge-status-diajukan { background: #dbeafe; color: #0073bd; }
    .badge-status-ditinjau { background: #fef3c7; color: #92400e; }
    .badge-status-diterima { background: #dcfce7; color: #166534; }
    .badge-status-ditolak { background: #fee2e2; color: #991b1b; }
    .badge-status-tidak_banding { background: #e5e7eb; color: #374151; }

    .btn-review {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #0073bd;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover { background: #003961; color: white; }
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

    .paginate { padding: 12px 14px; border-top: 1px solid #e2e8f0; }

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
        <h2>Daftar Banding Asesmen</h2>
        <p>Monitoring pengajuan banding FR.AK.04 dari asesi pada skema yang Anda ampuh.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><small>Total Kandidat Banding</small><strong>{{ $stats['total'] ?? 0 }}</strong></div>
    <div class="stat-card"><small>Diajukan</small><strong>{{ $stats['diajukan'] ?? 0 }}</strong></div>
    <div class="stat-card"><small>Ditinjau</small><strong>{{ $stats['ditinjau'] ?? 0 }}</strong></div>
    <div class="stat-card"><small>Diterima</small><strong>{{ $stats['diterima'] ?? 0 }}</strong></div>
    <div class="stat-card"><small>Ditolak</small><strong>{{ $stats['ditolak'] ?? 0 }}</strong></div>
    <div class="stat-card"><small>Tidak Banding</small><strong>{{ $stats['tidak_banding'] ?? 0 }}</strong></div>
</div>

<form method="GET" action="{{ route('asesor.banding.index') }}" class="filter-form" id="bandingFilterForm">
    <div class="filter-row filter-row-top">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                class="search-input"
                id="bandingSearchInput"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari nama asesi, NIK, nama skema, nomor skema..."
                autocomplete="off"
            >
        </div>
        <div class="filter-field">
            <label class="filter-label">Status Banding</label>
            <select name="status" id="bandingStatusFilter" class="filter-select">
                @php $currentStatus = $status ?? 'all'; @endphp
                <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="belum" {{ $currentStatus === 'belum' ? 'selected' : '' }}>Belum Mengajukan</option>
                <option value="diajukan" {{ $currentStatus === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="ditinjau" {{ $currentStatus === 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                <option value="diterima" {{ $currentStatus === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak" {{ $currentStatus === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="tidak_banding" {{ $currentStatus === 'tidak_banding' ? 'selected' : '' }}>Tidak Banding</option>
            </select>
        </div>
        <div class="filter-field">
            <label class="filter-label">Keputusan Asesmen</label>
            <select name="keputusan" id="bandingKeputusanFilter" class="filter-select">
                @php $currentKeputusan = $keputusan ?? ''; @endphp
                <option value="" {{ $currentKeputusan === '' ? 'selected' : '' }}>Semua Keputusan</option>
                <option value="lanjut" {{ $currentKeputusan === 'lanjut' ? 'selected' : '' }}>Asesmen Dapat Dilanjutkan</option>
                <option value="tidak_lanjut" {{ $currentKeputusan === 'tidak_lanjut' ? 'selected' : '' }}>Asesmen Tidak Dapat Dilanjutkan</option>
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
                    <th>Asesi</th>
                    <th>Skema</th>
                    <th>Keputusan Asesmen</th>
                    <th>Status Banding</th>
                    <th>Tgl Pengajuan</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="bandingTableContainer">
                @include('asesor.banding.partials.table-rows')
            </tbody>
        </table>
    </div>

    @if(method_exists($rows, 'links') && $rows->hasPages())
        <div class="paginate">{{ $rows->links() }}</div>
    @endif
</div>

<script>
    let bandingAjaxController = null;
    let bandingSearchTimer = null;

    function ajaxLoadBanding(url) {
        if (bandingAjaxController) {
            bandingAjaxController.abort();
        }

        bandingAjaxController = new AbortController();

        const tableContainer = document.getElementById('bandingTableContainer');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: bandingAjaxController.signal
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data banding asesmen');
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

    function serializeBandingForm() {
        const searchInput = document.getElementById('bandingSearchInput');
        const statusFilter = document.getElementById('bandingStatusFilter');
        const keputusanFilter = document.getElementById('bandingKeputusanFilter');
        const url = new URL('{{ route('asesor.banding.index') }}', window.location.origin);

        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value.trim());
        }

        if (statusFilter && statusFilter.value.trim() !== '' && statusFilter.value.trim() !== 'all') {
            url.searchParams.set('status', statusFilter.value.trim());
        }

        if (keputusanFilter && keputusanFilter.value.trim() !== '') {
            url.searchParams.set('keputusan', keputusanFilter.value.trim());
        }

        return url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bandingSearchInput');
        const statusFilter = document.getElementById('bandingStatusFilter');
        const keputusanFilter = document.getElementById('bandingKeputusanFilter');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(bandingSearchTimer);
                bandingSearchTimer = setTimeout(function() {
                    ajaxLoadBanding(serializeBandingForm());
                }, 400);
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                ajaxLoadBanding(serializeBandingForm());
            });
        }

        if (keputusanFilter) {
            keputusanFilter.addEventListener('change', function() {
                ajaxLoadBanding(serializeBandingForm());
            });
        }
    });
</script>
@endsection
