@extends('asesor.layout')

@section('title', 'Asesi')
@section('page-title', 'Asesi')

@section('styles')
<style>
    .page-header {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .page-header h2 i {
        color: #0073bd;
    }
    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .summary-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .summary-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .summary-value {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .summary-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .summary-total .summary-value { color: #0073bd; }
    .summary-selesai .summary-value { color: #059669; }
    .summary-sedang .summary-value { color: #d97706; }
    .summary-belum .summary-value { color: #6b7280; }

    .page-header-actions {
        display: inline-flex;
        gap: 10px;
        flex-wrap: wrap;
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
        font-family: 'Plus Jakarta Sans', sans-serif;
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

    .clear-search {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
        border-radius: 999px;
        display: none;
    }

    .search-input:not(:placeholder-shown) ~ .clear-search {
        display: block;
    }

    .clear-search:hover {
        color: #475569;
    }

    .search-results-info {
        font-size: 12px;
        color: #64748b;
        padding: 8px 14px;
        background: #f8fafc;
        border-radius: 10px;
        margin: -4px 0 12px;
        display: none;
    }

    .search-results-info.show {
        display: block;
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
        grid-template-columns: minmax(0, 1fr) minmax(240px, 280px) auto;
    }

    .filter-row-bottom {
        grid-template-columns: repeat(3, minmax(0, 1fr)) auto;
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

    .filter-select {
        width: 100%;
        min-width: 0;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #dbe4ef;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        color: #0f172a;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        outline: none;
    }

    .filter-select:focus {
        border-color: #0073bd;
        box-shadow: 0 0 0 4px rgba(0, 115, 189, 0.1);
    }

    .filter-reset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .filter-reset:hover {
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .filter-actions {
        display: flex;
        align-items: end;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
           z-index: 1;
    }
    .table-wrap {
        max-width: 100%;
        overflow-x: auto;
        overflow-y: visible;
    }
    .table-card table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
    }
    .table-card thead th {
        background: #f8fafc;
        padding: 8px 10px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-card tbody td {
        padding: 8px 10px;
        font-size: 12px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f9fafb; }

    .asesi-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .asesi-nik {
        margin-top: 4px;
    }

    .asesi-email {
        font-size: 12px;
        color: #94a3b8;
        line-height: 1.3;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: normal;
    }

    .mono-chip {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        font-family: monospace;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .skema-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 6px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #6b7280; }

    .badge-rekomendasi-lanjut {
        background: #d1fae5;
        color: #059669;
    }

    .badge-rekomendasi-tidak {
        background: #fee2e2;
        color: #dc2626;
    }

    .rekomendasi-empty {
        font-size: 13px;
        color: #94a3b8;
    }

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
        min-width: 200px;
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
        width: 220px;
        min-width: 220px;
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

    .menu-entry-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: auto;
        color: #16a34a;
        font-size: 14px;
    }

    .menu-entry-status i {
        font-size: 14px;
        color: #16a34a;
    }

    .menu-entry-status-pending {
        color: #d97706;
    }

    .menu-entry-status-pending i {
        color: #d97706;
    }

    .dropdown-menu .menu-disabled {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        color: #94a3b8;
        background: #f8fafc;
        border-bottom: 1px solid #f3f4f6;
        cursor: not-allowed;
    }

    .dropdown-menu .menu-disabled .menu-entry-status {
        color: #cbd5e1;
    }

    .dropdown-menu .menu-disabled .menu-entry-status i {
        color: #cbd5e1;
    }

    .dropdown-menu .menu-disabled:last-child {
        border-bottom: none;
    }

    .dropdown-menu .menu-disabled i {
        font-size: 14px;
        width: 18px;
        text-align: center;
        color: #94a3b8;
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

    .period-col {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .period-sep {
        color: #94a3b8;
        margin: 0 3px;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 12px;
        display: block;
    }

    .empty-state h4 {
        font-size: 15px;
        color: #6b7280;
        font-weight: 500;
        margin: 0 0 6px;
    }

    .empty-state p {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }

    .loading-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(0, 115, 189, 0.2);
        border-top-color: #0073bd;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Table Column Widths (updated for Kelompok column) */
    .table-card th:nth-child(1), .table-card td:nth-child(1) { width: 30%; }
    .table-card th:nth-child(2), .table-card td:nth-child(2) { width: 12%; }
    .table-card th:nth-child(3), .table-card td:nth-child(3) { width: 26%; }
    .table-card th:nth-child(4), .table-card td:nth-child(4) { width: 14%; }
    .table-card th:nth-child(5), .table-card td:nth-child(5) { width: 12%; }
    .table-card th:nth-child(6), .table-card td:nth-child(6) {
        width: 6%;
        min-width: 56px;
        text-align: center;
        overflow: visible;
    }

    /* Card View (Mobile) */
    .card-view {
        display: none;
    }

    .asesi-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 14px 16px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
        transition: all 0.2s ease;
        overflow: visible;
    }

    .asesi-card:active {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        min-width: 0;
        background-color: #007ac3;
        color: #fff;
        border-radius: 8px 8px 0 0;
        padding: 18px 24px;
    }

    .card-header > div:first-child {
        min-width: 0;
        flex: 1;
        color: #fff;
    }

    .card-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 14px;
        line-height: 1.3;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-email {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 3px;
        word-break: break-all;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-status-badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 10px;
        margin-bottom: 12px;
        font-size: 12px;
    }

    .card-field {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .card-label {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
        letter-spacing: 0.35px;
    }

    .card-value {
        color: #374151;
        font-weight: 500;
        font-size: 12px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .card-value code {
        font-family: monospace;
        font-size: 11px;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
        color: #475569;
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-periode {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .card-periode .card-label {
        margin-bottom: 2px;
    }

    .card-periode-dates {
        color: #475569;
        font-size: 12px;
        line-height: 1.4;
        word-break: break-word;
    }

    .card-rekomendasi {
        grid-column: 1 / -1;
        padding-top: 12px;
        border-top: 1px solid #f3f4f6;
        overflow: hidden;
    }

    .card-rekomendasi .card-label {
        margin-bottom: 6px;
    }

    .card-badge-rekomendasi {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .card-footer {
        display: flex;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid #f3f4f6;
    }

    .card-footer .btn-kebab {
        width: 100%;
        height: auto;
        justify-content: flex-start;
        color: #0073bd;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 0;
        border-radius: 0;
    }

    .card-btn {
        flex: 1;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-btn-primary {
        background: #0073bd;
        color: white;
    }

    .card-btn-primary:hover,
    .card-btn-primary:active {
        background: #003961;
    }

    .card-btn-disabled {
        background: #e2e8f0;
        color: #64748b;
        border: 1px solid #cbd5e1;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 0;
            margin-bottom: 16px;
        }

        .page-header h2 {
            font-size: 19px;
            line-height: 1.35;
        }

        .search-bar {
            margin-bottom: 12px;
        }

        .summary-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .summary-card {
            padding: 12px 14px;
        }

        .summary-value {
            font-size: 20px;
        }

        .search-input {
            padding: 11px 40px 11px 38px;
            font-size: 13px;
        }

        .search-icon {
            left: 12px;
            font-size: 15px;
        }

        .search-results-info {
            font-size: 11px;
            padding: 6px 12px;
            margin-bottom: 10px;
        }

        .filter-select,
        .filter-reset {
            width: 100%;
        }

        .filter-row-top,
        .filter-row-bottom {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            align-items: stretch;
        }

        .table-wrap {
            -webkit-overflow-scrolling: touch;
        }

        /* Hide table, show card */
        .table-view {
            display: none;
        }

        .card-view {
            display: block;
        }

        body {
            overflow-x: hidden;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2><i class="bi bi-people"></i> Asesi</h2>
        <p>
            {{ $skema?->nama_skema ?? ($skemaNames->count() ? $skemaNames->join(', ') : 'Semua skema yang diampu') }}
            &bull; <span id="totalAsesi">{{ $summary['total'] ?? count($data) }}</span> asesi
        </p>
    </div>
</div>

<div class="summary-row">
    <div class="summary-card summary-total">
        <div class="summary-value">{{ $summary['total'] ?? 0 }}</div>
        <div class="summary-label">Total Asesi</div>
    </div>
    <div class="summary-card summary-selesai">
        <div class="summary-value">{{ $summary['selesai'] ?? 0 }}</div>
        <div class="summary-label">Selesai</div>
    </div>
    <div class="summary-card summary-sedang">
        <div class="summary-value">{{ $summary['sedang'] ?? 0 }}</div>
        <div class="summary-label">Sedang Dikerjakan</div>
    </div>
    <div class="summary-card summary-belum">
        <div class="summary-value">{{ $summary['belum'] ?? 0 }}</div>
        <div class="summary-label">Belum Mulai</div>
    </div>
</div>

<div class="search-results-info" id="searchResultsInfo"></div>

{{-- Filter --}}
<div class="filter-row">
    <form method="GET" action="{{ route('asesor.asesi.index') }}" class="filter-form">
        <div class="filter-row filter-row-top">
            <div class="search-input-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    class="search-input"
                    id="asesiSearch"
                    name="search"
                    placeholder="Cari nama atau NIK asesi..."
                    autocomplete="off"
                    value="{{ request('search', '') }}"
                >
                <button class="clear-search" id="clearSearch">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>

            <div class="filter-field">
                <label for="statusFilter" class="filter-label">Status</label>
                <select id="statusFilter" name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="sedang_mengerjakan" {{ request('status') === 'sedang_mengerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="belum_mulai" {{ request('status') === 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                </select>
            </div>

            @if(request('jurusan') || request('skema') || request('kelas') || request('status') || request('search'))
                <div class="filter-actions">
                    <a href="{{ route('asesor.asesi.index') }}" class="filter-reset">
                        <i class="bi bi-x-circle"></i>
                        Reset
                    </a>
                </div>
            @endif
        </div>

        <div class="filter-row filter-row-bottom">
            <div class="filter-field">
                <label for="jurusanFilter" class="filter-label">Jurusan</label>
                <select id="jurusanFilter" name="jurusan" class="filter-select">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans ?? [] as $jurusan)
                        <option value="{{ data_get($jurusan, 'ID_jurusan') }}" {{ request('jurusan') == data_get($jurusan, 'ID_jurusan') ? 'selected' : '' }}>
                            {{ data_get($jurusan, 'nama_jurusan') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label for="skemaFilter" class="filter-label">Skema</label>
                <select id="skemaFilter" name="skema" class="filter-select" onchange="this.form.submit()" {{ empty($selectedJurusan) ? 'disabled' : '' }}>
                    @if(empty($selectedJurusan))
                        <option value="">Pilih jurusan dulu</option>
                    @else
                        <option value="">Semua Skema</option>
                        @foreach($skemaList ?? [] as $s)
                            <option value="{{ $s->id }}" {{ request('skema') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_skema }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="filter-field">
                <label for="kelasFilter" class="filter-label">Kelas</label>
                <select id="kelasFilter" name="kelas" class="filter-select" onchange="this.form.submit()" {{ empty($selectedJurusan) ? 'disabled' : '' }}>
                    @if(empty($selectedJurusan))
                        <option value="">Pilih jurusan dulu</option>
                    @else
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ data_get($k, 'nama_kelas') }}" {{ request('kelas') == data_get($k, 'nama_kelas') ? 'selected' : '' }}>
                                {{ data_get($k, 'nama_kelas') }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

        </div>
    </form>
</div>

<div class="table-card table-view">
    @if(count($data))
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Asesi</th>
                    <th>Kelompok</th>
                    <th>Jurusan / Skema</th>
                    <th>Status Asesmen</th>
                    <th>Rekomendasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                @php
                    $asesi = $row->asesi;
                    $kelompokNama = $asesi?->kelompok?->nama_kelompok ?? '-';
                    $hasAsesmenMandiri = (bool) $row->has_asesmen_mandiri;
                    $mandiriRecommended = in_array(($row->rekomendasi ?? ''), ['lanjut', 'tidak_lanjut'], true);
                    $mandiriPending = $hasAsesmenMandiri && ! $mandiriRecommended;
                    $mandiriCompleted = $mandiriRecommended;
                    $persetujuanSignedByAsesor = $row->persetujuan_signed_by_asesor ?? false;
                    $persetujuanSignedByAsesi  = $row->persetujuan_signed_by_asesi  ?? false;
                    // Ceklis Observasi hanya bisa diakses jika persetujuan sudah ditandatangani oleh KEDUA pihak
                    $persetujuanFullySigned = $persetujuanSignedByAsesor && $persetujuanSignedByAsesi;
                    $rekamanExists = $row->has_rekaman ?? false;
                    $ceklisExists = $row->has_ceklis_observasi ?? false;
                    $penilaianExists = $row->has_penilaian ?? false;
                    $canProceed = ($row->status ?? '') !== 'belum_mulai';
                    $hasPenilaian = (bool) $row->has_penilaian;
                    $hasCeklisObservasi = (bool) $row->has_ceklis_observasi;
                    $hasRekaman = (bool) $row->has_rekaman;
                    // Persetujuan Asesmen hanya bisa diakses jika asesi sudah memiliki jadwal dari admin dan sudah direkomendasikan lanjut
                    $canAccessPersetujuan = ($row->has_jadwal ?? false) && (($row->rekomendasi ?? '') === 'lanjut');
                @endphp
                <tr>
                    <td>
                        <div class="asesi-name">{{ $asesi?->nama ?? '—' }}</div>
                        <div class="asesi-nik"><span class="mono-chip">{{ $row->asesi_nik }}</span></div>
                        <div class="search-keywords" aria-hidden="true" style="display:none;">{{ $kelompokNama }}</div>
                    </td>
                    <td>
                        {{ $kelompokNama }}
                    </td>
                    <td>
                        <span class="mono-chip">{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</span>
                        <span class="period-sep">•</span>
                        <span class="skema-chip">{{ $row->skema?->nama_skema ?? '—' }}</span>
                    </td>
                    <td>@include('components.asesi-status', ['row' => $row])</td>
                    <td>
                        @if($row->rekomendasi === 'lanjut')
                            <span class="badge badge-rekomendasi-lanjut"><i class="bi bi-check-circle-fill"></i> Dapat Lanjut</span>
                        @elseif($row->rekomendasi === 'tidak_lanjut')
                            <span class="badge badge-rekomendasi-tidak"><i class="bi bi-x-circle-fill"></i> Tidak Lanjut</span>
                        @else
                            <span class="rekomendasi-empty">— Belum direview</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-menu-wrapper">
                            <button class="btn-kebab" type="button" data-nik="{{ $row->asesi_nik }}">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Lihat detail asesmen mandiri">
                                    <i class="bi bi-eye"></i>
                                    <span class="menu-entry-label">Lihat Detail</span>
                                </a>

                                @if($hasAsesmenMandiri)
                                    <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Review asesmen mandiri">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="menu-entry-label">Asesmen Mandiri</span>
                                        <span class="menu-entry-status {{ $mandiriPending ? 'menu-entry-status-pending' : '' }}" aria-hidden="true">
                                            <i class="{{ $mandiriPending ? 'bi bi-circle-fill' : 'bi bi-check-circle-fill' }}"></i>
                                        </span>
                                    </a>
                                @else
                                    <span class="menu-disabled">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="menu-entry-label">Asesmen Mandiri</span>
                                    </span>
                                @endif

                                {{-- Persetujuan Asesmen --}}
                                @if($canAccessPersetujuan)
                                    <a href="{{ route('asesor.persetujuan.front.asesor.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Buka persetujuan asesmen">
                                        <i class="bi bi-file-check"></i>
                                        <span class="menu-entry-label">Persetujuan Asesmen</span>
                                        <span class="menu-entry-status {{ $persetujuanSignedByAsesor ? '' : 'menu-entry-status-pending' }}" aria-hidden="true">
                                            <i class="{{ $persetujuanSignedByAsesor ? 'bi bi-check-circle-fill' : 'bi bi-circle-fill' }}"></i>
                                        </span>
                                    </a>
                                @else
                                    <span class="menu-disabled">
                                        <i class="bi bi-file-check"></i>
                                        <span class="menu-entry-label">Persetujuan Asesmen</span>
                                        <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                                    </span>
                                @endif

                                {{-- Ceklis Observasi: hanya aktif jika persetujuan ditandatangani oleh KEDUA pihak (asesor + asesi) --}}
                                @if($persetujuanFullySigned)
                                    @if($hasCeklisObservasi && !empty($row->ceklis_observasi_id))
                                        <a href="{{ route('asesor.ceklis-observasi.show', $row->ceklis_observasi_id) }}" title="Lihat detail ceklis observasi">
                                            <i class="bi bi-check2-square"></i>
                                            <span class="menu-entry-label">Ceklis Observasi</span>
                                            <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                        </a>
                                    @else
                                        <a href="{{ route('asesor.ceklis-observasi.create', ['asesi_nik' => $row->asesi_nik, 'skema_id' => $row->skema_id]) }}" title="Isi ceklis observasi">
                                            <i class="bi bi-check2-square"></i>
                                            <span class="menu-entry-label">Ceklis Observasi</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="menu-disabled" title="Persetujuan asesmen harus ditandatangani oleh asesor dan asesi terlebih dahulu">
                                        <i class="bi bi-check2-square"></i>
                                        <span class="menu-entry-label">Ceklis Observasi</span>
                                        <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                                    </span>
                                @endif

                                {{-- Rekaman Asesmen --}}
                                @if($hasCeklisObservasi)
                                    @if($rekamanExists)
                                        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index', ['asesi_nik' => $row->asesi_nik]) }}" title="Lihat rekaman asesmen">
                                            <i class="bi bi-file-earmark-text"></i>
                                            <span class="menu-entry-label">Rekaman Asesmen</span>
                                            <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                        </a>
                                    @else
                                        <a href="{{ route('asesor.rekaman-asesmen-kompetensi.create', ['asesi_nik' => $row->asesi_nik, 'skema_id' => $row->skema_id]) }}" title="Buat rekaman asesmen">
                                            <i class="bi bi-file-earmark-text"></i>
                                            <span class="menu-entry-label">Rekaman Asesmen</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="menu-disabled">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span class="menu-entry-label">Rekaman Asesmen</span>
                                        <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                                    </span>
                                @endif

                                {{-- Penilaian --}}
                                @if($rekamanExists)
                                    @if($hasPenilaian)
                                        <a href="{{ route('asesor.entry-penilaian.form', $row->asesi_nik) }}" title="Edit penilaian">
                                            <i class="bi bi-clipboard-check"></i>
                                            <span class="menu-entry-label">Penilaian</span>
                                            <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                        </a>
                                    @else
                                        <a href="{{ route('asesor.entry-penilaian.form', $row->asesi_nik) }}" title="Mulai penilaian">
                                            <i class="bi bi-clipboard-check"></i>
                                            <span class="menu-entry-label">Penilaian</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="menu-disabled">
                                        <i class="bi bi-clipboard-check"></i>
                                        <span class="menu-entry-label">Penilaian</span>
                                        <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h4>Tidak ada data asesi ditemukan</h4>
        <p>Belum ada asesi yang terdaftar pada skema ini.</p>
    </div>
    @endif
</div>

<!-- Card View (Mobile) -->
<div class="card-view">
    @if(count($data))
        @foreach($data as $row)
        @php
            $asesi = $row->asesi;
            $kelompokNama = $asesi?->kelompok?->nama_kelompok ?? '-';
            $hasAsesmenMandiri = (bool) $row->has_asesmen_mandiri;
            $mandiriRecommended = in_array(($row->rekomendasi ?? ''), ['lanjut', 'tidak_lanjut'], true);
            $mandiriPending = $hasAsesmenMandiri && ! $mandiriRecommended;
            $mandiriCompleted = $mandiriRecommended;
            $canProceed = ($row->status ?? '') !== 'belum_mulai';
            $persetujuanSignedByAsesor = $row->persetujuan_signed_by_asesor ?? false;
            $persetujuanSignedByAsesi  = $row->persetujuan_signed_by_asesi  ?? false;
            // Ceklis Observasi hanya bisa diakses jika persetujuan sudah ditandatangani oleh KEDUA pihak
            $persetujuanFullySigned = $persetujuanSignedByAsesor && $persetujuanSignedByAsesi;
            $hasPenilaian = (bool) $row->has_penilaian;
            $hasCeklisObservasi = (bool) $row->has_ceklis_observasi;
            $hasRekaman = (bool) $row->has_rekaman;
            // Persetujuan Asesmen hanya bisa diakses jika asesi sudah memiliki jadwal dari admin dan sudah direkomendasikan lanjut
            $canAccessPersetujuan = ($row->has_jadwal ?? false) && (($row->rekomendasi ?? '') === 'lanjut');
        @endphp
        <div class="asesi-card">
            <!-- Card Header -->
            <div class="card-header">
                <div>
                    <div class="card-name">{{ $asesi?->nama ?? '—' }}</div>
                    <div class="card-email">{{ $row->asesi_nik }}</div>
                    <div class="search-keywords" aria-hidden="true" style="display:none;">{{ $kelompokNama }}</div>
                </div>
                @include('components.asesi-status', ['row' => $row])
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Jurusan -->
                <div class="card-field">
                    <div class="card-label">Jurusan</div>
                    <div class="card-value"><code>{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</code></div>
                </div>

                <div class="card-field">
                    <div class="card-label">Skema</div>
                    <div class="card-value"><span class="skema-chip">{{ $row->skema?->nama_skema ?? '—' }}</span></div>
                </div>

                <div class="card-field">
                    <div class="card-label">Kelompok</div>
                    <div class="card-value">{{ $kelompokNama }}</div>
                </div>

                <!-- Periode -->
                <div class="card-field card-periode">
                    <div class="card-label">Periode Asesmen</div>
                    <div class="card-periode-dates">
                        {{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') : '—' }}
                        <br>
                        s/d {{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') : '—' }}
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="card-field card-rekomendasi">
                    <div class="card-label">Rekomendasi</div>
                    @if($row->rekomendasi === 'lanjut')
                        <span class="card-badge-rekomendasi badge-rekomendasi-lanjut">
                            <i class="bi bi-check-circle-fill"></i> Dapat Lanjut
                        </span>
                    @elseif($row->rekomendasi === 'tidak_lanjut')
                        <span class="card-badge-rekomendasi badge-rekomendasi-tidak">
                            <i class="bi bi-x-circle-fill"></i> Tidak Lanjut
                        </span>
                    @else
                        <span class="rekomendasi-empty">— Belum direview</span>
                    @endif
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer">
                <div class="action-menu-wrapper">
                    <button class="btn-kebab" type="button" data-nik="{{ $row->asesi_nik }}" style="width: 100%; border-radius: 8px; gap: 6px; padding: 8px 12px;">
                        <i class="bi bi-three-dots-vertical"></i>
                        <span>Menu Aksi</span>
                    </button>
                    <div class="dropdown-menu" style="right: auto; left: 0; min-width: 180px;">
                        <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Lihat detail asesmen mandiri">
                            <i class="bi bi-eye"></i>
                            <span class="menu-entry-label">Lihat Detail</span>
                        </a>
                                @if($canProceed && ($hasAsesmenMandiri || $mandiriCompleted))
                            <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Review asesmen mandiri">
                                <i class="bi bi-pencil-square"></i>
                                <span class="menu-entry-label">Asesmen Mandiri</span>
                                        <span class="menu-entry-status {{ $mandiriCompleted ? '' : 'menu-entry-status-pending' }}" aria-hidden="true">
                                            <i class="{{ $mandiriCompleted ? 'bi bi-check-circle-fill' : 'bi bi-circle-fill' }}"></i>
                                </span>
                            </a>
                        @else
                            <span class="menu-disabled">
                                <i class="bi bi-pencil-square"></i>
                                <span class="menu-entry-label">Asesmen Mandiri</span>
                                        @if($hasAsesmenMandiri || $mandiriCompleted)
                                            <span class="menu-entry-status {{ $mandiriCompleted ? '' : 'menu-entry-status-pending' }}" aria-hidden="true">
                                                <i class="{{ $mandiriCompleted ? 'bi bi-check-circle-fill' : 'bi bi-circle-fill' }}"></i>
                                    </span>
                                @endif
                            </span>
                        @endif
                        @if($canAccessPersetujuan)
                            <a href="{{ route('asesor.persetujuan.front.asesor.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" title="Buka persetujuan asesmen">
                                <i class="bi bi-file-check"></i>
                                <span class="menu-entry-label">Persetujuan Asesmen</span>
                                <span class="menu-entry-status {{ $persetujuanSignedByAsesor ? '' : 'menu-entry-status-pending' }}" aria-hidden="true">
                                    <i class="{{ $persetujuanSignedByAsesor ? 'bi bi-check-circle-fill' : 'bi bi-circle-fill' }}"></i>
                                </span>
                            </a>
                        @else
                            <span class="menu-disabled">
                                <i class="bi bi-file-check"></i>
                                <span class="menu-entry-label">Persetujuan Asesmen</span>
                                <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                            </span>
                        @endif
                        @if($mandiriPending)
                            <span class="menu-disabled">
                                <i class="bi bi-clipboard-check"></i>
                                <span class="menu-entry-label">Penilaian</span>
                                <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                            </span>
                            <span class="menu-disabled">
                                <i class="bi bi-check2-square"></i>
                                <span class="menu-entry-label">Ceklis Observasi</span>
                                <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                            </span>
                            <span class="menu-disabled">
                                <i class="bi bi-file-earmark-text"></i>
                                <span class="menu-entry-label">Rekaman Asesmen</span>
                                <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                            </span>
                        @else
                            @if($hasPenilaian)
                                <a href="{{ route('asesor.entry-penilaian.form', $row->asesi_nik) }}" title="Edit penilaian">
                                    <i class="bi bi-clipboard-check"></i>
                                    <span class="menu-entry-label">Penilaian</span>
                                    <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                </a>
                            @else
                                <a href="{{ route('asesor.entry-penilaian.form', $row->asesi_nik) }}" title="Mulai penilaian">
                                    <i class="bi bi-clipboard-check"></i>
                                    <span class="menu-entry-label">Penilaian</span>
                                </a>
                            @endif
                            {{-- Ceklis Observasi: hanya aktif jika persetujuan ditandatangani oleh KEDUA pihak (asesor + asesi) --}}
                            @if($persetujuanFullySigned)
                                @if($hasCeklisObservasi && !empty($row->ceklis_observasi_id))
                                    <a href="{{ route('asesor.ceklis-observasi.show', $row->ceklis_observasi_id) }}" title="Lihat detail ceklis observasi">
                                        <i class="bi bi-check2-square"></i>
                                        <span class="menu-entry-label">Ceklis Observasi</span>
                                        <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                    </a>
                                @else
                                    <a href="{{ route('asesor.ceklis-observasi.create', ['asesi_nik' => $row->asesi_nik, 'skema_id' => $row->skema_id]) }}" title="Isi ceklis observasi">
                                        <i class="bi bi-check2-square"></i>
                                        <span class="menu-entry-label">Ceklis Observasi</span>
                                    </a>
                                @endif
                            @else
                                <span class="menu-disabled" title="Persetujuan asesmen harus ditandatangani oleh asesor dan asesi terlebih dahulu">
                                    <i class="bi bi-check2-square"></i>
                                    <span class="menu-entry-label">Ceklis Observasi</span>
                                    <span class="menu-entry-status menu-entry-status-pending" aria-hidden="true"><i class="bi bi-circle-fill"></i></span>
                                </span>
                            @endif
                            @if($hasCeklisObservasi)
                                <a href="{{ route('asesor.rekaman-asesmen-kompetensi.index', ['asesi_nik' => $row->asesi_nik]) }}" title="Lihat rekaman asesmen">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span class="menu-entry-label">Rekaman Asesmen</span>
                                    <span class="menu-entry-status" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                                </a>
                            @else
                                <a href="{{ route('asesor.rekaman-asesmen-kompetensi.create', ['asesi_nik' => $row->asesi_nik, 'skema_id' => $row->skema_id]) }}" title="Buat rekaman asesmen">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span class="menu-entry-label">Rekaman Asesmen</span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
        <h4>Belum ada asesi</h4>
        <p>Belum ada data asesi yang terhubung dengan skema asesor ini.</p>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('asesiSearch');
    const clearBtn = document.getElementById('clearSearch');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    const filterForm = document.querySelector('.filter-form');
    const jurusanFilter = document.getElementById('jurusanFilter');
    const skemaFilter = document.getElementById('skemaFilter');
    const kelasFilter = document.getElementById('kelasFilter');
    const tableBody = document.querySelector('.table-card tbody');
    const cardView = document.querySelector('.card-view');
    const totalAsesiEl = document.getElementById('totalAsesi');
    let allRows = [];
    let allCards = [];

    // Store all original rows and cards
    if (tableBody) {
        allRows = Array.from(tableBody.querySelectorAll('tr'));
    }
    if (cardView) {
        allCards = Array.from(cardView.querySelectorAll('.asesi-card'));
    }

    // Clear search button
    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.value = '';
        searchInput.focus();
        resetSearch();
    });

    // Search input
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length === 0) {
            resetSearch();
            return;
        }

        performSearch(query);
    });

    if (jurusanFilter && filterForm) {
        jurusanFilter.addEventListener('change', function() {
            if (skemaFilter) {
                skemaFilter.value = '';
            }

            if (kelasFilter) {
                kelasFilter.value = '';
            }

            filterForm.submit();
        });
    }

    function performSearch(query) {
        const lowerQuery = query.toLowerCase();
        let matchedCount = 0;

        // Search in table
        if (allRows.length > 0) {
            allRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(lowerQuery);
                row.style.display = matches ? '' : 'none';
                if (matches) matchedCount++;
            });
        }

        // Search in cards
        if (allCards.length > 0) {
            allCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const matches = text.includes(lowerQuery);
                card.style.display = matches ? '' : 'none';
            });
        }

        // Update results info
        const displayedCount = allCards.length > 0 
            ? allCards.filter(c => c.style.display !== 'none').length
            : allRows.filter(r => r.style.display !== 'none').length;

        if (displayedCount === 0) {
            searchResultsInfo.textContent = `Tidak ada data yang cocok dengan "${query}"`;
            searchResultsInfo.classList.add('show');
        } else {
            searchResultsInfo.textContent = `Ditemukan ${displayedCount} asesi`;
            searchResultsInfo.classList.add('show');
        }
    }

    function resetSearch() {
        // Show all rows
        allRows.forEach(row => {
            row.style.display = '';
        });

        // Show all cards
        allCards.forEach(card => {
            card.style.display = '';
        });

        // Hide results info
        searchResultsInfo.classList.remove('show');
        totalAsesiEl.textContent = '{{ count($data) }}';
    }

    // Dropdown Menu Functionality
    const kebabBtns = document.querySelectorAll('.btn-kebab');
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
        const menuWidth = Math.max(menu.offsetWidth, 220);
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
        menu.style.width = '220px';
        menu.style.minWidth = '220px';
        menu.style.maxWidth = '240px';
        menu.style.maxHeight = `calc(100vh - ${margin * 2}px)`;

        if (positionUp) {
            menu.classList.add('show-up');
        } else {
            menu.classList.remove('show-up');
        }

        activeDropdown = { menu, button, wrapper };
    }

    kebabBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            openDropdown(this);
        });
    });

    document.addEventListener('scroll', function() {
        closeActiveDropdown();
    }, true);

    window.addEventListener('resize', function() {
        closeActiveDropdown();
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-menu') && !e.target.closest('.btn-kebab')) {
            closeActiveDropdown();
        }
    });
});
</script>
@endsection
