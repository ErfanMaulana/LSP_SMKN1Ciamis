@extends('admin.layout')

@section('title', 'Detail Komponen Umpan Balik')
@section('page-title', 'Detail Komponen Umpan Balik')

@section('content')
<div class="page-header">
    <h2>Detail Komponen Umpan Balik</h2>
    <div class="header-actions">
        <a href="{{ route('admin.umpan-balik-komponen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Komponen
        </a>
        <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="detail-section">
            <h3>Informasi Skema</h3>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Nomor Skema</label>
                    <div class="detail-value">
                        <span class="badge badge-code">{{ $skema->nomor_skema }}</span>
                    </div>
                </div>

                <div class="detail-item full-width">
                    <label>Nama Skema</label>
                    <div class="detail-value">{{ $skema->nama_skema }}</div>
                </div>

                <div class="detail-item">
                    <label>Jurusan</label>
                    <div class="detail-value">
                        @if($skema->jurusan)
                            <div class="jurusan-info">
                                <div class="jurusan-name">{{ $skema->jurusan->nama_jurusan }}</div>
                                <div class="jurusan-code">Kode: {{ $skema->jurusan->kode_jurusan }}</div>
                            </div>
                        @else
                            <span class="text-muted">Tidak terkait jurusan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Statistik Komponen</h3>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Komponen</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: #065f46;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['active'] }}</div>
                        <div class="stat-label">Komponen Aktif</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #fee2e2; color: #991b1b;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['inactive'] }}</div>
                        <div class="stat-label">Komponen Nonaktif</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3>Daftar Pernyataan Komponen</h3>

            <div class="table-wrap">
                @if($komponen->count())
                    <table>
                        <thead>
                            <tr>
                                <th width="70">Urutan</th>
                                <th>Pernyataan Komponen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($komponen as $item)
                                <tr>
                                    <td>{{ $item->urutan }}</td>
                                    <td>{{ $item->pernyataan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-wrap">
                        <div class="pagination-info">
                            Menampilkan {{ $komponen->firstItem() ?? 0 }} sampai {{ $komponen->lastItem() ?? 0 }} dari {{ $komponen->total() }} data
                        </div>
                        <div>{{ $komponen->links() }}</div>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-journal-x"></i>
                        <h4>Belum ada komponen pada skema ini</h4>
                        <p>Tambahkan komponen untuk skema {{ $skema->nama_skema }}.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="detail-section">
            <h3>Informasi Sistem</h3>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dibuat pada</label>
                    <div class="detail-value">
                        @if($skema->created_at)
                            {{ \Carbon\Carbon::parse($skema->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir diupdate</label>
                    <div class="detail-value">
                        @if($skema->updated_at)
                            {{ \Carbon\Carbon::parse($skema->updated_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.umpan-balik-komponen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Komponen
            </a>
            <a href="{{ route('admin.umpan-balik-komponen.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        font-size: 24px;
        color: #0F172A;
        font-weight: 700;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #0073bd;
        color: white;
    }

    .btn-primary:hover {
        background: #005a94;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .card-body {
        padding: 30px;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section:last-of-type {
        margin-bottom: 0;
    }

    .detail-section h3 {
        font-size: 18px;
        color: #0F172A;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-section h3:before {
        content: '';
        width: 4px;
        height: 20px;
        background: #0073bd;
        border-radius: 2px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-item label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 15px;
        color: #0F172A;
        font-weight: 500;
    }

    .text-muted {
        color: #94a3b8;
        font-style: italic;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        width: fit-content;
    }

    .badge-code {
        background: #f3f4f6;
        color: #374151;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    .jurusan-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .jurusan-name {
        font-size: 15px;
        color: #0F172A;
        font-weight: 600;
    }

    .jurusan-code {
        font-size: 13px;
        color: #64748b;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .toolbar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 14px;
    }

    .toolbar form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input,
    .filter-select {
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }

    .search-input {
        flex: 1;
        min-width: 260px;
    }

    .filter-select {
        min-width: 170px;
    }

    .search-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, .1);
    }

    .table-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
        font-size: 13px;
        color: #334155;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .row-actions {
        display: flex;
        gap: 8px;
    }

    .btn-xs {
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 6px;
    }

    .btn-edit {
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: none;
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border: none;
        cursor: pointer;
    }

    .pagination-wrap {
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: 13px;
        color: #64748b;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 42px;
        display: block;
        margin-bottom: 10px;
    }

    .empty-state h4 {
        margin: 0 0 8px;
        color: #0F172A;
    }

    .form-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 18px;
        }

        .header-actions,
        .form-actions {
            width: 100%;
        }

        .header-actions .btn,
        .form-actions .btn,
        .toolbar .btn,
        .search-input,
        .filter-select {
            width: 100%;
        }

        .toolbar form {
            flex-direction: column;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            min-width: 760px;
        }

        .row-actions {
            flex-direction: column;
        }
    }
</style>
@endsection
