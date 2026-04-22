@extends('asesi.layout')

@section('title', 'Status Asesmen')
@section('page-title', 'Status Asesmen')

@section('styles')
<style>
    .status-card {
        border-left: 5px solid #0073bd;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .status-card:hover {
        box-shadow: 0 4px 12px rgba(0, 115, 189, 0.15);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.dinilai {
        background: #d1fae5;
        color: #065f46;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')
@if($hasilUjikom->count())
    <div class="row">
        @foreach($hasilUjikom as $row)
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card status-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">{{ $row->nama_skema }}</h6>
                                <small class="text-muted">{{ $row->nomor_skema }}</small>
                            </div>
                            <span class="status-badge {{ $row->status_penilaian === 'sudah_dinilai' ? 'dinilai' : 'pending' }}">
                                {{ $row->status_penilaian === 'sudah_dinilai' ? 'Sudah Dinilai' : 'Menunggu Penilaian' }}
                            </span>
                        </div>

                        @if($row->status_penilaian === 'sudah_dinilai')
                            <div class="alert alert-info py-2 mb-3" role="alert">
                                <small>
                                    <strong>
                                        @if($row->hasil_ujikom === 'kompeten')
                                            <i class="bi bi-check-circle-fill text-success"></i> Anda Dinyatakan Kompeten
                                        @else
                                            <i class="bi bi-exclamation-circle-fill text-danger"></i> Anda Belum Kompeten
                                        @endif
                                    </strong>
                                </small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Asesor</small>
                                    <small>{{ $row->asesor_nama ?? '-' }}</small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Tanggal Penilaian</small>
                                    <small>
                                        @if($row->terakhir_dinilai)
                                            {{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </div>
                            </div>

                            @if($row->hasil_ujikom !== 'kompeten')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('asesi.banding.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Ajukan Banding
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-muted small mb-0 py-3">
                                <i class="bi bi-hourglass-split"></i> Asesmen Anda sedang diproses oleh asesor. Silakan periksa kembali halaman ini untuk melihat hasil.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>
                    <strong>Belum Ada Data Asesmen</strong><br>
                    <small>Anda belum menyelesaikan asesmen. Kerjakan asesmen mandiri terlebih dahulu.</small>
                </p>
            </div>
        </div>
    </div>
@endif
@endsection
