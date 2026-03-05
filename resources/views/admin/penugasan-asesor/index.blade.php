@extends('admin.layout')

@section('title', 'Kelompok Asesor')
@section('page-title', 'Kelompok Asesor')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .page-header .subtitle {
        font-size: 14px;
        color: #64748b;
        margin-top: 4px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #0061A5;
        color: white;
    }

    .btn-primary:hover {
        background: #00509e;
        color: white;
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 13px;
    }

    .btn-outline {
        background: transparent;
        border: 1.5px solid #0061A5;
        color: #0061A5;
    }

    .btn-outline:hover {
        background: #0061A5;
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
        border: 1px solid #e2e8f0;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.green { background: #dcfce7; color: #16a34a; }
    .stat-icon.orange { background: #ffedd5; color: #ea580c; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }

    .stat-content .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
        margin-top: 2px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-body { padding: 20px; }

    .filter-section {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    .search-box input:focus { border-color: #0061A5; }

    .btn-search {
        background: #0061A5;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }

    .btn-search:hover { background: #00509e; }

    .table-wrapper { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }

    tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    tbody tr:hover { background: #f8fafc; }
    tbody tr:last-child td { border-bottom: none; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-green { background: #dcfce7; color: #15803d; }
    .badge-gray { background: #f1f5f9; color: #64748b; }
    .badge-orange { background: #ffedd5; color: #c2410c; }

    .action-buttons { display: flex; gap: 8px; align-items: center; }

    .progress-bar-wrap {
        width: 100%;
        background: #f1f5f9;
        border-radius: 6px;
        height: 8px;
        margin-top: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 6px;
        background: #0061A5;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 15px; }
</style>

<div class="penugasan-asesor">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2>Kelompok Asesor</h2>
            <p class="subtitle">Kelola kelompok asesor dan asesi. Satu asesor dapat membimbing banyak asesi.</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-badge"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Asesor</div>
                <div class="stat-value">{{ $stats['total_asesor'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-person-badge-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Asesor Aktif Bertugas</div>
                <div class="stat-value">{{ $stats['asesor_aktif'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Asesi Ditugaskan</div>
                <div class="stat-value">{{ $stats['total_asesi'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-person-exclamation"></i></div>
            <div class="stat-content">
                <div class="stat-label">Asesi Belum Ditugaskan</div>
                <div class="stat-value">{{ $stats['belum_ditugaskan'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.penugasan-asesor.index') }}" class="filter-section">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari asesor berdasarkan nama atau no. met...">
                </div>
                <button type="submit" class="btn-search">
                    <i class="bi bi-search"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.penugasan-asesor.index') }}" class="btn btn-outline btn-sm">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Asesor Table -->
    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asesor</th>
                        <th>NO MET</th>
                        <th>Skema</th>
                        <th>Jumlah Asesi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asesors as $index => $asesor)
                        <tr>
                            <td>{{ $asesors->firstItem() + $index }}</td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b;">{{ $asesor->nama }}</div>
                                <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">ID: {{ $asesor->ID_asesor }}</div>
                            </td>
                            <td>
                                @if($asesor->no_met)
                                    <span class="badge badge-blue">{{ $asesor->no_met }}</span>
                                @else
                                    <span class="badge badge-gray">—</span>
                                @endif
                            </td>
                            <td>
                                @if($asesor->skemas->count())
                                    @foreach($asesor->skemas as $sk)
                                        <span class="badge badge-green">{{ $sk->nama_skema }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-gray">Tidak ada skema</span>
                                @endif
                            </td>
                            <td>
                                @php $jumlahAsesi = $asesor->asesis->count(); @endphp
                                <div style="display: flex; align-items: center; gap: 10px; min-width: 120px;">
                                    <span style="font-weight: 700; color: #0061A5; font-size: 16px;">{{ $jumlahAsesi }}</span>
                                    <span style="font-size: 12px; color: #94a3b8;">asesi</span>
                                </div>
                                @if($jumlahAsesi > 0)
                                    <div class="progress-bar-wrap">
                                        @php $maxAsesi = max(1, $asesors->max(fn($a) => $a->asesis->count())); @endphp
                                        <div class="progress-bar-fill" style="width: {{ min(100, ($jumlahAsesi / $maxAsesi) * 100) }}%"></div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.penugasan-asesor.show', $asesor->ID_asesor) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-people"></i> Kelola Asesi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-person-badge"></i>
                                    <p>Tidak ada asesor ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asesors->hasPages())
            <div class="pagination-wrapper">
                {{ $asesors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
