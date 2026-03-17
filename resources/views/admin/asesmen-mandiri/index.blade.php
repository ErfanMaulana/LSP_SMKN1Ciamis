@extends('admin.layout')

@section('title', 'Asesmen Mandiri')

@section('styles')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.2);
        transform: translateY(-2px);
        border-color: #bfdbfe;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #0073bd, #0073bd); }
    .stat-icon.gray { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .stat-icon.yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 10px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        align-items: baseline;
        gap: 8px;
        flex-wrap: wrap;
    }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .filter-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; }
    .filter-form input, .filter-form select {
        padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; outline: none; transition: border-color .2s;
    }
    .filter-form input[type=text] { flex: 1; min-width: 200px; }
    .filter-form input:focus, .filter-form select:focus { border-color: #0061a5; }
    .btn-search {
        padding: 9px 18px; background: #0061a5; color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    }

    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing:.5px;
        background: #f8fafc; border-bottom: 1px solid #e5e7eb;
    }
    td { padding: 14px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .badge.belum_mulai        { background: #f1f5f9; color: #64748b; }
    .badge.sedang_mengerjakan { background: #fef3c7; color: #92400e; }
    .badge.selesai            { background: #d1fae5; color: #065f46; }

    .badge-rekom { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; }
    .badge-rekom.lanjut       { background: #d1fae5; color: #065f46; }
    .badge-rekom.tidak_lanjut { background: #fee2e2; color: #991b1b; }
    .badge-rekom.draft        { background: #f1f5f9; color: #94a3b8; }

    .btn-detail {
        padding: 6px 14px; background: #f0f7ff; color: #0061a5; border: 1px solid #bfdbfe;
        border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all .2s;
    }
    .btn-detail:hover { background: #0061a5; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }

    .pagination-wrapper { padding: 16px; display: flex; justify-content: center; }
    .pagination-wrapper nav { display: flex; }
    .pagination-wrapper svg { height: 20px; }

    @media (max-width: 640px) {
        .stats-grid {
            gap: 12px;
        }

        .stat-card {
            padding: 14px;
            gap: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
            border-radius: 10px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Asesmen Mandiri</h2>
        <p>Monitoring status dan hasil asesmen mandiri seluruh asesi</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-text"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats->total ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-hourglass"></i></div>
        <div class="stat-content">
            <div class="stat-label">Belum Mulai</div>
            <div class="stat-value">{{ $stats->belum_mulai ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-pencil-square"></i></div>
        <div class="stat-content">
            <div class="stat-label">Sedang Mengerjakan</div>
            <div class="stat-value">{{ $stats->sedang_mengerjakan ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-check-circle"></i></div>
        <div class="stat-content">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats->selesai ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <form class="filter-form" method="GET" action="{{ route('admin.asesmen-mandiri.index') }}">
        <input type="text" name="search" placeholder="Cari nama / NIK / skema..." value="{{ request('search') }}">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="belum_mulai" {{ request('status') == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
            <option value="sedang_mengerjakan" {{ request('status') == 'sedang_mengerjakan' ? 'selected' : '' }}>Sedang Mengerjakan</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <select name="skema_id">
            <option value="">Semua Skema</option>
            @foreach($skemas as $skema)
                <option value="{{ $skema->id }}" {{ request('skema_id') == $skema->id ? 'selected' : '' }}>{{ $skema->nama_skema }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
    </form>
</div>

<!-- Table -->
<div class="card" id="tableContainer">
    @include('admin.asesmen-mandiri._table')
</div>
@endsection
