@extends('asesor.layout')

@section('title', 'Asesmen Mandiri')
@section('page-title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        margin-bottom: 20px;
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

    .search-bar {
        margin-bottom: 18px;
        display: flex;
        gap: 10px;
    }

    .search-input-wrapper {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #fff;
    }

    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
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
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
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
        border-radius: 6px;
        margin-bottom: 12px;
        display: none;
    }

    .search-results-info.show {
        display: block;
    }

    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 8px 16px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-btn:hover, .filter-btn.active {
        background: #0073bd;
        color: white;
        border-color: #0073bd;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .table-wrap { overflow-x: auto; }
    .table-card table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }
    .table-card thead th {
        background: #f8fafc;
        padding: 11px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .table-card tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f9fafb; }

    .asesi-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.3;
    }

    .asesi-email {
        font-size: 12px;
        color: #94a3b8;
        line-height: 1.3;
        margin-top: 2px;
        word-break: break-all;
    }

    .mono-chip {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        font-family: monospace;
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

    /* Table Column Widths */
    .table-card th:nth-child(1), .table-card td:nth-child(1) { width: 22%; }
    .table-card th:nth-child(2), .table-card td:nth-child(2) { width: 12%; }
    .table-card th:nth-child(3), .table-card td:nth-child(3) { width: 10%; }
    .table-card th:nth-child(4), .table-card td:nth-child(4) { width: 11%; }
    .table-card th:nth-child(5), .table-card td:nth-child(5) { width: 12%; }
    .table-card th:nth-child(6), .table-card td:nth-child(6) { width: 18%; }
    .table-card th:nth-child(7), .table-card td:nth-child(7) { width: 15%; text-align: center; }

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
        overflow: hidden;
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
            margin-bottom: 14px;
        }

        .search-input {
            padding: 9px 12px 9px 34px;
            font-size: 13px;
        }

        .search-icon {
            left: 10px;
            font-size: 15px;
        }

        .search-results-info {
            font-size: 11px;
            padding: 6px 12px;
            margin-bottom: 10px;
        }

        .filter-bar {
            gap: 8px;
            margin-bottom: 14px;
            overflow-x: auto;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .filter-bar::-webkit-scrollbar {
            height: 3px;
        }

        .filter-bar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .filter-bar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .filter-btn {
            font-size: 12px;
            padding: 7px 13px;
            flex-shrink: 0;
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
    <h2><i class="bi bi-people"></i> Asesmen Mandiri — {{ $skema?->nama_skema ?? 'Skema tidak ditetapkan' }}</h2>
    <p>{{ $skema?->nomor_skema }} &bull; <span id="totalAsesi">{{ $data->count() }}</span> asesi terdaftar</p>
</div>

{{-- Search Bar --}}
<div class="search-bar">
    <div class="search-input-wrapper">
        <i class="bi bi-search search-icon"></i>
        <input 
            type="text" 
            class="search-input" 
            id="asesiSearch" 
            placeholder="Cari nama asesi, NIK, atau email..."
            autocomplete="off"
        >
        <button class="clear-search" id="clearSearch">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    </div>
</div>

<div class="search-results-info" id="searchResultsInfo"></div>

{{-- Filter --}}
<div class="filter-bar">
    <a href="{{ route('asesor.asesi.index') }}"
       class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'selesai']) }}"
       class="filter-btn {{ request('status') === 'selesai' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'sedang_mengerjakan']) }}"
       class="filter-btn {{ request('status') === 'sedang_mengerjakan' ? 'active' : '' }}">Sedang Dikerjakan</a>
    <a href="{{ route('asesor.asesi.index', ['status' => 'belum_mulai']) }}"
       class="filter-btn {{ request('status') === 'belum_mulai' ? 'active' : '' }}">Belum Mulai</a>
</div>

<div class="table-card table-view">
    @if($data->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Asesi</th>
                    <th>NIK</th>
                    <th>Jurusan</th>
                    <th>Status Asesmen</th>
                    <th>Rekomendasi</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                @php
                    $asesi = $row->asesi;
                    $statusClass = match($row->status) {
                        'selesai'            => 'badge-selesai',
                        'sedang_mengerjakan' => 'badge-sedang',
                        default              => 'badge-belum',
                    };
                    $statusLabel = match($row->status) {
                        'selesai'            => 'Selesai',
                        'sedang_mengerjakan' => 'Sedang Dikerjakan',
                        default              => 'Belum Mulai',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="asesi-name">{{ $asesi?->nama ?? '—' }}</div>
                        <div class="asesi-email">{{ $asesi?->email ?? '' }}</div>
                    </td>
                    <td><span class="mono-chip">{{ $row->asesi_nik }}</span></td>
                    <td><span class="mono-chip">{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</span></td>
                    <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        @if($row->rekomendasi === 'lanjut')
                            <span class="badge badge-rekomendasi-lanjut"><i class="bi bi-check-circle-fill"></i> Dapat Lanjut</span>
                        @elseif($row->rekomendasi === 'tidak_lanjut')
                            <span class="badge badge-rekomendasi-tidak"><i class="bi bi-x-circle-fill"></i> Tidak Lanjut</span>
                        @else
                            <span class="rekomendasi-empty">— Belum direview</span>
                        @endif
                    </td>
                    <td class="period-col">
                        <span>{{ $row->tanggal_mulai ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') : '—' }}</span>
                        <span class="period-sep">s/d</span>
                        <span>{{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') : '—' }}</span>
                    </td>
                    <td>
                        @if($row->status === 'selesai')
                            <a href="{{ route('asesor.asesi.review', $row->asesi_nik) }}" class="btn-review">
                                <i class="bi bi-eye"></i> Review
                            </a>
                        @else
                            <span class="btn-review disabled">
                                <i class="bi bi-eye-slash"></i> Belum Selesai
                            </span>
                        @endif
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
    @if($data->count())
        @foreach($data as $row)
        @php
            $asesi = $row->asesi;
            $statusClass = match($row->status) {
                'selesai'            => 'badge-selesai',
                'sedang_mengerjakan' => 'badge-sedang',
                default              => 'badge-belum',
            };
            $statusLabel = match($row->status) {
                'selesai'            => 'Selesai',
                'sedang_mengerjakan' => 'Sedang Dikerjakan',
                default              => 'Belum Mulai',
            };
        @endphp
        <div class="asesi-card">
            <!-- Card Header -->
            <div class="card-header">
                <div>
                    <div class="card-name">{{ $asesi?->nama ?? '—' }}</div>
                    <div class="card-email">{{ $asesi?->email ?? '' }}</div>
                </div>
                <span class="card-status-badge badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- NIK -->
                <div class="card-field">
                    <div class="card-label">NIK</div>
                    <div class="card-value"><code>{{ $row->asesi_nik }}</code></div>
                </div>

                <!-- Jurusan -->
                <div class="card-field">
                    <div class="card-label">Jurusan</div>
                    <div class="card-value"><code>{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</code></div>
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
                @if($row->status === 'selesai')
                    <a href="{{ route('asesor.asesi.review', $row->asesi_nik) }}" class="card-btn card-btn-primary">
                        <i class="bi bi-eye"></i> Review
                    </a>
                @else
                    <button class="card-btn card-btn-disabled" disabled>
                        <i class="bi bi-eye-slash"></i> Belum Selesai
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Tidak ada data asesi ditemukan</h4>
            <p>Belum ada asesi yang terdaftar pada skema ini.</p>
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
            searchResultsInfo.textContent = `Tidak ada asesi yang cocok dengan "${query}"`;
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
        totalAsesiEl.textContent = '{{ $data->count() }}';
    }
});
</script>
@endsection
