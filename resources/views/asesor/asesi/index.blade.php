@extends('asesor.layout')

@section('title', 'Daftar Asesi')
@section('page-title', 'Daftar Asesi')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }

    .page-header {
        background: #0073bd;
        border-radius: 12px;
        padding: 22px 26px;
        color: white;
        margin-bottom: 22px;
    }
    .page-header h2 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .page-header p  { font-size: 14px; opacity: 0.85; margin: 0; }

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
        padding: 11px 14px 11px 38px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .search-input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 12px;
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
        display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;
    }
    .filter-btn {
        padding: 8px 18px; border-radius: 20px; border: 1.5px solid #e2e8f0;
        font-size: 14px; font-weight: 500; text-decoration: none;
        color: #64748b; background: white; cursor: pointer; transition: all 0.2s;
    }
    .filter-btn:hover, .filter-btn.active { background: #0073bd; color: white; border-color: #0073bd; }

    .table-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0; overflow: hidden;
    }
    .table-wrap { overflow-x: auto; }
    .table-card table { width: 100%; border-collapse: collapse; table-layout: auto; }
    .table-card thead th {
        background: #f8fafc; padding: 7px 6px;
        text-align: left; font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-card tbody td {
        padding: 5px 6px; font-size: 13px; color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-card tbody tr:last-child td { border-bottom: none; }
    .table-card tbody tr:hover { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 16px;
        font-size: 11px; font-weight: 600;
        white-space: nowrap;
    }
    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #6b7280; }

    .btn-review {
        display: inline-flex; align-items: center; gap: 4px;
        background: #0073bd; color: white;
        padding: 4px 10px; border-radius: 5px;
        font-size: 11px; font-weight: 600; text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-review:hover { background: #003961; color: white; }
    .btn-review.disabled { background: #94a3b8; pointer-events: none; }

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

    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 13px; }

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
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        margin-bottom: 12px;
        box-shadow: 0 1px 8px rgba(0,0,0,0.04);
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
        gap: 8px;
        margin-bottom: 10px;
        min-width: 0;
    }

    .card-header > div:first-child {
        min-width: 0;
        flex: 1;
    }

    .card-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 13px;
        line-height: 1.3;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-email {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 2px;
        word-break: break-all;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-status-badge {
        padding: 3px 8px;
        border-radius: 14px;
        font-size: 9px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 8px;
        margin-bottom: 10px;
        font-size: 11px;
    }

    .card-field {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .card-label {
        font-size: 8px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 2px;
        letter-spacing: 0.2px;
    }

    .card-value {
        color: #374151;
        font-weight: 500;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .card-value code {
        font-family: monospace;
        font-size: 9px;
        background: #f1f5f9;
        padding: 2px 4px;
        border-radius: 3px;
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
        font-size: 10px;
        line-height: 1.3;
        word-break: break-word;
    }

    .card-rekomendasi {
        grid-column: 1 / -1;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .card-rekomendasi .card-label {
        margin-bottom: 3px;
    }

    .card-badge-rekomendasi {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 3px 8px;
        border-radius: 14px;
        font-size: 9px;
        font-weight: 600;
        white-space: nowrap;
    }

    .card-footer {
        display: flex;
        gap: 6px;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .card-btn {
        flex: 1;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 10px;
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

    .card-btn-primary:active {
        background: #003961;
    }

    .card-btn-disabled {
        background: #f1f5f9;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 16px;
            margin-bottom: 16px;
        }

        .page-header h2 {
            font-size: 16px;
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
            font-size: 13px;
            padding: 7px 14px;
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
    <h2><i class="bi bi-people"></i> Daftar Asesi — {{ $skema?->nama_skema ?? 'Skema tidak ditetapkan' }}</h2>
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
                        <div style="font-weight:600;color:#1e3a5f;font-size:13px;">{{ $asesi?->nama ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8;line-height:1.3;">{{ $asesi?->email ?? '' }}</div>
                    </td>
                    <td><code style="font-size:12px;color:#475569;">{{ $row->asesi_nik }}</code></td>
                    <td><code style="font-size:12px;color:#475569;">{{ $asesi?->jurusan?->kode_jurusan ?? '—' }}</code></td>
                    <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        @if($row->rekomendasi === 'lanjut')
                            <span class="badge" style="background:#d1fae5;color:#059669;justify-content:center;white-space:nowrap;">✓ Dapat Lanjut</span>
                        @elseif($row->rekomendasi === 'tidak_lanjut')
                            <span class="badge" style="background:#fee2e2;color:#dc2626;justify-content:center;white-space:nowrap;">✗ Tidak Lanjut</span>
                        @else
                            <span style="font-size:13px;color:#94a3b8;">— Belum direview</span>
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
        <i class="bi bi-people"></i>
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
                        <span class="card-badge-rekomendasi" style="background:#d1fae5;color:#059669;">
                            <i class="bi bi-check-circle-fill"></i> Dapat Lanjut
                        </span>
                    @elseif($row->rekomendasi === 'tidak_lanjut')
                        <span class="card-badge-rekomendasi" style="background:#fee2e2;color:#dc2626;">
                            <i class="bi bi-x-circle-fill"></i> Tidak Lanjut
                        </span>
                    @else
                        <span style="font-size:11px;color:#94a3b8;">— Belum direview</span>
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
            <i class="bi bi-people"></i>
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
