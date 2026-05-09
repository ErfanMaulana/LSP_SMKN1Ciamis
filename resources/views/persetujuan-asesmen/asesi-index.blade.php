@extends('asesi.layout')

@section('title', 'Persetujuan Asesmen')
@section('page-title', 'Persetujuan Asesmen')

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

    .notice {
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 14px;
        font-size: 13px;
        border: 1px solid transparent;
    }

    .notice.error {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .notice.success {
        background: #ecfdf5;
        color: #166534;
        border-color: #bbf7d0;
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

    .skema-title {
        font-weight: 600;
        color: #0f172a;
        line-height: 1.4;
    }

    .skema-code {
        font-family: monospace;
        font-size: 13px;
        color: #475569;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
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
        justify-content: center;
        gap: 7px;
        transition: all 0.2s ease;
    }

    .btn-action.primary {
        background: #0061A5;
        color: #ffffff;
    }

    .btn-action.primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0, 97, 165, 0.3);
    }

    .btn-action.secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .btn-action.secondary:hover {
        background: #cbd5e1;
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

    .empty-state h3 {
        font-size: 18px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 8px;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 16px;
            margin-bottom: 16px;
        }

        .page-header h2 {
            font-size: 17px;
            line-height: 1.35;
        }

        .data-table thead th,
        .data-table tbody td {
            padding: 12px;
            font-size: 13px;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php $items = collect($items ?? []); @endphp

@if(session('error'))
    <div class="notice error">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="notice success">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2><i class="bi bi-file-earmark-text"></i> Persetujuan Asesmen</h2>
    <p>Daftar form persetujuan asesmen yang sudah dikirim asesor untuk ditandatangani.</p>
</div>

<div class="panel-card">
    @if($items->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum Ada Form Masuk</h3>
            <p>Belum ada form yang dikirim oleh asesor untuk Anda tandatangani.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:58px;">No</th>
                        <th>Skema</th>
                        <th style="width:260px;">Nomor Skema</th>
                        <th style="width:220px;">Status</th>
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
                                <div class="skema-title">{{ $item['skema_nama'] }}</div>
                            </td>
                            <td><span class="skema-code">{{ $item['skema_nomor'] }}</span></td>
                            <td>
                                <span class="status-badge {{ $isDone ? 'done' : 'waiting' }}">
                                    <i class="bi {{ $isDone ? 'bi-check-circle' : 'bi-hourglass-split' }}"></i>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                @if(!empty($item['can_sign']))
                                    <a class="btn-action primary" href="{{ route('asesi.persetujuan.front.asesi.show', $item['skema_id']) }}">
                                        <i class="bi bi-pen"></i> Tanda Tangani
                                    </a>
                                @else
                                    <a class="btn-action secondary" href="{{ route('asesi.persetujuan.front.asesi.show', $item['skema_id']) }}">
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
