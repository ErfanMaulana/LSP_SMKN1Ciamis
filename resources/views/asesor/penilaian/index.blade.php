@extends('asesor.layout')

@section('title', 'Entry Penilaian')
@section('page-title', 'Entry Penilaian')

@section('styles')
<style>
    .panel-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .panel-head {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .panel-head h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }
    .panel-head p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
    }
    .tabs-wrapper {
        display: flex;
        gap: 0;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        overflow-x: auto;
    }
    .tab {
        padding: 12px 16px;
        cursor: pointer;
        border: none;
        background: none;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        top: 1px;
    }
    .tab:hover {
        color: #1e293b;
        background: #ffffff;
    }
    .tab.active {
        color: #0073bd;
        border-bottom-color: #0073bd;
        background: #ffffff;
    }
    .tab-content {
        display: none;
        padding: 0;
    }
    .tab-content.active {
        display: block;
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0073bd;
        color: white;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 14px;
        cursor: pointer;
    }
    .btn-primary:hover { background: #005e9b; color: white; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 980px; }
    thead th {
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }
    tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }
    tbody tr:hover { background: #f8fafc; }
    .status-k { color: #059669; font-weight: 700; }
    .status-bk { color: #dc2626; font-weight: 700; }
    .badge-score {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-status {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-status.done {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-status.pending {
        background: #fef3c7;
        color: #92400e;
    }
    .btn-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 7px;
        padding: 6px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-link:hover {
        background: #dbeafe;
        color: #1e40af;
    }
    .empty {
        text-align: center;
        padding: 52px 20px;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .panel-head {
            padding: 14px;
        }

        .panel-head h3 {
            font-size: 15px;
        }

        .tabs-wrapper {
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        .tab {
            flex: 0 0 auto;
            padding: 11px 14px;
            font-size: 12px;
        }

        .table-wrap {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection

@section('content')
<div class="panel-card">
    <div class="panel-head">
        <div>
            <h3><i class="bi bi-list-check"></i> Data Asesi Ujikom</h3>
            <p>Total asesi ujikom: {{ $asesiData->count() }} orang</p>
        </div>
    </div>

    <div class="tabs-wrapper">
        <button class="tab active" onclick="switchTab(event, 'belum')">
            <i class="bi bi-clock-history"></i> Belum Dinilai ({{ $belumDinilai->count() }})
        </button>
        <button class="tab" onclick="switchTab(event, 'sudah')">
            <i class="bi bi-check-circle"></i> Sudah Dinilai ({{ $sudahDinilai->count() }})
        </button>
    </div>

    {{-- Tab: Belum Dinilai --}}
    <div id="belum" class="tab-content active">
        <div class="table-wrap">
            @if($belumDinilai->count())
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Asesi</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Skema</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($belumDinilai as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $row->asesi->nama ?? '-' }}</strong></td>
                                <td style="font-family:monospace; font-size:12px;">{{ $row->asesi_nik }}</td>
                                <td>{{ $row->asesi->email ?? '-' }}</td>
                                <td>{{ $row->skema->nama_skema ?? '-' }}</td>
                                <td><span class="badge-status pending"><i class="bi bi-hourglass-split"></i> Belum Dinilai</span></td>
                                <td>
                                    @if(!empty($row->asesi_nik))
                                        <a href="{{ route('asesor.entry-penilaian.form', ['asesiNik' => $row->asesi_nik]) }}" class="btn-link">
                                            <i class="bi bi-pencil-square"></i> Input Nilai
                                        </a>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">
                    <i class="bi bi-check-circle-fill" style="font-size:42px;color:#059669;"></i>
                    <p style="margin-top:10px;color:#059669;"><strong>Sempurna!</strong> Semua asesi sudah dinilai.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Tab: Sudah Dinilai --}}
    <div id="sudah" class="tab-content">
        <div class="table-wrap">
            @if($sudahDinilai->count())
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Asesi</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Skema</th>
                            <th style="width:80px;">Elemen</th>
                            <th style="width:100px;">Rata-rata</th>
                            <th style="width:110px;">Kompeten</th>
                            <th style="width:150px;">Terakhir Dinilai</th>
                            <th style="width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sudahDinilai as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $row->asesi->nama ?? '-' }}</strong></td>
                                <td style="font-family:monospace; font-size:12px;">{{ $row->asesi_nik }}</td>
                                <td>{{ $row->asesi->email ?? '-' }}</td>
                                <td>{{ $row->skema->nama_skema ?? '-' }}</td>
                                <td>{{ $row->total_elemen ?? 0 }}</td>
                                <td><span class="badge-score">{{ number_format((float) ($row->rata_rata ?? 0), 1) }}</span></td>
                                <td>
                                    @if($row->total_k === $row->total_elemen)
                                        <span class="status-k"><i class="bi bi-check-circle"></i> {{ $row->total_k }}/{{ $row->total_elemen }} K</span>
                                    @else
                                        <span class="status-k">{{ $row->total_k }}/{{ $row->total_elemen }} K</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->terakhir_dinilai)
                                        {{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($row->asesi_nik))
                                        <a href="{{ route('asesor.entry-penilaian.form', ['asesiNik' => $row->asesi_nik]) }}" class="btn-link">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">
                    <i class="bi bi-inbox" style="font-size:42px;"></i>
                    <p style="margin-top:10px;">Belum ada asesi yang dinilai.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(event, tabName) {
        event.preventDefault();
        
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
        
        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        event.target.closest('.tab').classList.add('active');
    }
</script>
@endsection
