@extends('asesi.layout')

@section('title', 'Ceklis Observasi')
@section('page-title', 'Ceklis Observasi')

@section('styles')
<style>
    .page-header {
        background: #0061A5;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
        color: #ffffff;
    }

    .page-header h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .page-header p {
        font-size: 14px;
        opacity: 0.92;
        margin: 0;
    }

    .panel-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .data-table thead th {
        text-align: left;
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 16px;
        font-weight: 700;
    }

    .data-table tbody td {
        font-size: 14px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 16px;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-badge.waiting {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.done {
        background: #dcfce7;
        color: #166534;
    }

    .btn-action {
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-action.primary {
        background: #0061A5;
        color: #ffffff;
    }

    .btn-action.secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .empty-state {
        text-align: center;
        padding: 54px 18px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 54px;
        display: block;
        margin-bottom: 12px;
        color: #cbd5e1;
    }
</style>
@endsection

@section('content')
@php $items = collect($items ?? []); @endphp

<div class="page-header">
    <h2><i class="bi bi-check2-square"></i> Ceklis Observasi Aktivitas Praktik</h2>
    <p>Daftar ceklis observasi yang sudah ditandatangani asesor dan menunggu tanda tangan Anda.</p>
</div>

<div class="panel-card">
    @if($items->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum Ada Ceklis Masuk</h3>
            <p>Belum ada ceklis observasi yang siap ditandatangani.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:58px;">No</th>
                        <th>Kode / Judul Form</th>
                        <th>Skema</th>
                        <th style="width:210px;">Status</th>
                        <th style="width:170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $statusText = $item['status'] ?? '';
                            $isDone = \Illuminate\Support\Str::contains(strtolower($statusText), 'sudah');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item['kode_form'] }}</strong><br>
                                <span>{{ $item['judul_form'] }}</span>
                            </td>
                            <td>
                                <strong>{{ $item['skema_nama'] }}</strong><br>
                                <span style="font-family:monospace;">{{ $item['skema_nomor'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $isDone ? 'done' : 'waiting' }}">
                                    <i class="bi {{ $isDone ? 'bi-check-circle' : 'bi-hourglass-split' }}"></i>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                @if(!empty($item['can_sign']))
                                    <a class="btn-action primary" href="{{ route('asesi.ceklis-observasi.show', $item['id']) }}">
                                        <i class="bi bi-pen"></i> Tanda Tangani
                                    </a>
                                @else
                                    <a class="btn-action secondary" href="{{ route('asesi.ceklis-observasi.show', $item['id']) }}">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
