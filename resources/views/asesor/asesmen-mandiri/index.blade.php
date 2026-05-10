@extends('asesor.layout')

@section('title', 'Asesmen Mandiri')
@section('page-title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .filter-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 16px;
    }

    .search-box {
        flex: 1 1 320px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1px solid #dbe4ef;
        border-radius: 10px;
        font-size: 13px;
        background: #ffffff;
    }

    .filter-select {
        padding: 9px 12px;
        border: 1px solid #dbe4ef;
        border-radius: 10px;
        font-size: 13px;
        background: #ffffff;
    }

    .btn-filter {
        background: #0073bd;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
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

    tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-selesai { background: #d1fae5; color: #059669; }
    .badge-sedang  { background: #fef3c7; color: #d97706; }
    .badge-belum   { background: #f1f5f9; color: #6b7280; }

    .badge-rekom-lanjut { background: #d1fae5; color: #059669; }
    .badge-rekom-tidak { background: #fee2e2; color: #dc2626; }
    .badge-rekom-pending { background: #f1f5f9; color: #94a3b8; }

    .btn-review {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #0073bd;
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-review.disabled {
        background: #e2e8f0;
        color: #94a3b8;
        pointer-events: none;
    }

    .action-cell {
        width: 96px;
        text-align: center;
        padding: 12px 16px;
    }

    .action-wrap { display: flex; align-items: center; justify-content: center; }

    .action-menu { position: relative; display: inline-block; }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .2s;
        color: #475569;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .action-dropdown {
        display: none;
        position: fixed;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0,0,0,.15);
        min-width: 160px;
        z-index: 9990;
        overflow: hidden;
    }

    .action-dropdown.show {
        display: block;
    }

    .action-dropdown a,
    .action-dropdown button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        border: none;
        background: none;
        text-align: left;
        font-size: 14px;
        color: #475569;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background: #f8fafc;
        color: #0F172A;
    }

    .action-dropdown.disabled a,
    .action-dropdown.disabled button {
        pointer-events: none;
        opacity: 0.5;
        color: #94a3b8;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2><i class="bi bi-clipboard-check"></i> Asesmen Mandiri</h2>
        <p>Kelola rekomendasi asesmen mandiri untuk asesi.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $summary['total'] ?? 0 }}</div>
        <div class="stat-label">Total Asesi</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $summary['selesai'] ?? 0 }}</div>
        <div class="stat-label">Selesai Asesmen</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $summary['pending_review'] ?? 0 }}</div>
        <div class="stat-label">Menunggu Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $summary['reviewed'] ?? 0 }}</div>
        <div class="stat-label">Sudah Direkomendasi</div>
    </div>
</div>

<form method="GET" action="{{ route('asesor.asesmen-mandiri.index') }}" class="filter-bar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama asesi, NIK, atau skema">
    </div>
    <select name="status" class="filter-select">
        <option value="" {{ $status === '' ? 'selected' : '' }}>Semua Status</option>
        <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="sedang_mengerjakan" {{ $status === 'sedang_mengerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
        <option value="belum_mulai" {{ $status === 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
    </select>
    <button type="submit" class="btn-filter"><i class="bi bi-funnel"></i> Filter</button>
    <a href="{{ route('asesor.asesmen-mandiri.index') }}" class="btn-reset"><i class="bi bi-arrow-clockwise"></i> Reset</a>
</form>

<div class="table-card">
    @if($data->count())
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Asesi</th>
                        <th>NIK</th>
                        <th>Skema</th>
                        <th>Status</th>
                        <th>Jawaban</th>
                        <th>Rekomendasi</th>
                        <th class="action-cell">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        @php
                            $statusClass = match($row->status) {
                                'selesai' => 'badge-selesai',
                                'sedang_mengerjakan' => 'badge-sedang',
                                default => 'badge-belum',
                            };
                            $statusLabel = match($row->status) {
                                'selesai' => 'Selesai',
                                'sedang_mengerjakan' => 'Sedang Dikerjakan',
                                default => 'Belum Mulai',
                            };
                        @endphp
                        <tr>
                            <td>{{ $row->asesi?->nama ?? '-' }}</td>
                            <td>{{ $row->asesi_nik }}</td>
                            <td>{{ $row->skema?->nama_skema ?? '-' }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td>{{ $row->jawaban_count ? $row->jawaban_count . ' elemen' : 'Belum ada' }}</td>
                            <td>
                                @if($row->rekomendasi === 'lanjut')
                                    <span class="badge badge-rekom-lanjut">Lanjut</span>
                                @elseif($row->rekomendasi === 'tidak_lanjut')
                                    <span class="badge badge-rekom-tidak">Tidak Lanjut</span>
                                @else
                                    <span class="badge badge-rekom-pending">Belum Direview</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                <div class="action-wrap">
                                    <div class="action-menu">
                                        <button type="button" class="action-btn" onclick="toggleMenu(this)" aria-label="Aksi data">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div class="action-dropdown">
                                            @if($row->has_asesmen_mandiri)
                                                <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}">
                                                    <i class="bi bi-eye"></i> Review
                                                </a>
                                            @else
                                                <span style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #94a3b8;">
                                                    <i class="bi bi-eye" style="width: 18px; text-align: center;"></i> Review
                                                </span>
                                            @endif
                                        </div>
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
            <p>Belum ada asesmen mandiri untuk ditampilkan.</p>
        </div>
    @endif
</div>

<script>
function toggleMenu(button) {
    const menu = button.nextElementSibling;
    if (!menu) return;

    document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
        if (dropdown !== menu) {
            dropdown.classList.remove('show');
        }
    });

    menu.classList.toggle('show');

    if (menu.classList.contains('show')) {
        const buttonRect = button.getBoundingClientRect();
        const menuHeight = menu.offsetHeight;
        const viewportHeight = window.innerHeight;

        let top = buttonRect.bottom + 8;
        if (top + menuHeight > viewportHeight) {
            top = buttonRect.top - menuHeight - 8;
        }

        menu.style.top = top + 'px';
        menu.style.left = (buttonRect.left - menu.offsetWidth + button.offsetWidth) + 'px';
    }

    event.stopPropagation();
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});
</script>
@endsection
