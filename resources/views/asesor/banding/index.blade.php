@extends('asesor.layout')

@section('title', 'Banding Asesmen')
@section('page-title', 'Daftar Banding Asesmen')

@section('content')
<div class="container-fluid py-4">
    {{-- Status Summary --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Menunggu Review</h6>
                    <h3 class="mb-0 text-warning">{{ $bandings->get('pending', collect())->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Disetujui</h6>
                    <h3 class="mb-0 text-success">{{ $bandings->get('approved', collect())->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-left-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Ditolak</h6>
                    <h3 class="mb-0 text-danger">{{ $bandings->get('rejected', collect())->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h6 class="text-muted mb-0">Nilai Direvisi</h6>
                    <h3 class="mb-0 text-info">{{ $bandings->get('revised', collect())->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Bandings (Priority) --}}
    @if($bandings->has('pending') && $bandings->get('pending')->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-warning bg-opacity-10 border-bottom">
                <h6 class="mb-0 text-warning"><i class="bi bi-hourglass-split"></i> Banding Menunggu Review</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Peserta (NIK)</th>
                                <th>Skema</th>
                                <th>Tanggal Diajukan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bandings->get('pending') as $banding)
                                <tr>
                                    <td>
                                        <small>
                                            <strong>{{ $banding->asesi->nama }}</strong><br>
                                            <code>{{ $banding->asesi_nik }}</code>
                                        </small>
                                    </td>
                                    <td>{{ $banding->skema->nama_skema }}</td>
                                    <td><small>{{ $banding->diajukan_at->format('d/m/Y H:i') }}</small></td>
                                    <td class="text-center">
                                        <a href="{{ route('asesor.banding.form', [$banding->asesi_nik, $banding->skema_id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Other Bandings --}}
    @foreach(['approved' => 'success', 'revised' => 'info', 'rejected' => 'danger'] as $status => $color)
        @if($bandings->has($status) && $bandings->get($status)->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header" style="background-color: rgba({{ $color === 'success' ? '16, 185, 129' : ($color === 'info' ? '59, 130, 246' : '239, 68, 68') }}, 0.1)">
                    <h6 class="mb-0" style="color: {{ $color === 'success' ? '#10b981' : ($color === 'info' ? '#3b82f6' : '#ef4444') }}">
                        <i class="bi bi-check-circle"></i> Banding {{ ucfirst($status) }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Peserta</th>
                                    <th>Skema</th>
                                    <th>Diajukan</th>
                                    <th>Direview</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bandings->get($status) as $banding)
                                    <tr>
                                        <td><small>{{ $banding->asesi->nama }}</small></td>
                                        <td><small>{{ $banding->skema->nama_skema }}</small></td>
                                        <td><small>{{ $banding->diajukan_at->format('d/m/y') }}</small></td>
                                        <td><small>{{ $banding->direview_at ? $banding->direview_at->format('d/m/y') : '-' }}</small></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-outline-secondary" data-bs-toggle="modal" 
                                                    data-bs-target="#bandingModal{{ $banding->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Detail --}}
                                    <div class="modal fade" id="bandingModal{{ $banding->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Detail Banding: {{ $banding->asesi->nama }}</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Alasan Banding:</strong></p>
                                                    <p class="text-muted">{{ $banding->alasan_banding }}</p>

                                                    <p class="mt-3"><strong>Catatan Asesor:</strong></p>
                                                    <p class="text-muted">{{ $banding->catatan_asesor ?? '-' }}</p>

                                                    <div class="row mt-3">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Nilai Sebelum</small>
                                                            <span class="badge bg-success">{{ $banding->total_k_sebelum }} K</span>
                                                            <span class="badge bg-danger">{{ $banding->total_bk_sebelum }} BK</span>
                                                        </div>
                                                        @if($banding->status === 'revised')
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">Nilai Sesudah</small>
                                                                <span class="badge bg-success">{{ $banding->total_k_sesudah }} K</span>
                                                                <span class="badge bg-danger">{{ $banding->total_bk_sesudah }} BK</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if($bandings->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> Belum ada banding asesmen yang diajukan kepada Anda.
        </div>
    @endif
</div>

<style>
    .border-left-warning {
        border-left: 4px solid #f59e0b !important;
    }
    .border-left-success {
        border-left: 4px solid #10b981 !important;
    }
    .border-left-danger {
        border-left: 4px solid #ef4444 !important;
    }
    .border-left-info {
        border-left: 4px solid #3b82f6 !important;
    }
    .btn-xs {
        padding: 2px 8px;
        font-size: 11px;
    }
</style>
@endsection
