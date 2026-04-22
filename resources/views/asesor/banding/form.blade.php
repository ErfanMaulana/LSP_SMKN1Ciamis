@extends('asesor.layout')

@section('title', 'Review Banding Asesmen')
@section('page-title', 'Proses Banding Asesmen')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            {{-- Info Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Peserta</small></p>
                            <h6>{{ $asesi->nama }}</h6>
                            <small class="text-muted">NIK: {{ $asesi->NIK }}</small>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Skema</small></p>
                            <h6>{{ $skema->nama_skema }}</h6>
                            <small class="text-muted">{{ $skema->nomor_skema }}</small>
                        </div>
                    </div>

                    <hr class="my-3">

                    <p class="mb-2"><strong>Alasan Banding:</strong></p>
                    <div class="alert alert-info py-3">
                        <small>{{ $banding->alasan_banding }}</small>
                    </div>

                    <p class="mb-2 mt-3"><strong>Tanggal Ajukan:</strong></p>
                    <small>{{ $banding->diajukan_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            {{-- Nilai Details --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">Detail Penilaian Sebelumnya</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Total Elemen</small>
                            <h6>{{ $banding->total_elemen }}</h6>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Kompeten (K)</small>
                            <h6><span class="badge bg-success">{{ $banding->total_k_sebelum }}</span></h6>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Belum Kompeten (BK)</small>
                            <h6><span class="badge bg-danger">{{ $banding->total_bk_sebelum }}</span></h6>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Unit Kompetensi</th>
                                    <th>Elemen Kompetensi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiDetails as $item)
                                    <tr>
                                        <td><small>{{ $item->nama_unit }}</small></td>
                                        <td><small>{{ $item->kode_elemen }} - {{ $item->nama_elemen }}</small></td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $item->status === 'K' ? 'success' : 'danger' }}">
                                                {{ $item->status === 'K' ? 'K' : 'BK' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Process Form --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">Proses Banding</h6>
                </div>
                <div class="card-body">
                    @if($banding->status === 'pending')
                        <form action="{{ route('asesor.banding.store', [$asesi->NIK, $skema->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="action" class="form-label">Keputusan <span class="text-danger">*</span></label>
                                <select class="form-select @error('action') is-invalid @enderror" id="action" name="action" required onchange="toggleRevisionFields()">
                                    <option value="">-- Pilih Keputusan --</option>
                                    <option value="approve">Setujui Banding</option>
                                    <option value="revise">Revisi Nilai</option>
                                    <option value="reject">Tolak Banding</option>
                                </select>
                                @error('action')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="revisionFields" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label for="total_k_sesudah" class="form-label">Total K</label>
                                        <input type="number" class="form-control" id="total_k_sesudah" name="total_k_sesudah" min="0" placeholder="0">
                                    </div>
                                    <div class="col-6">
                                        <label for="total_bk_sesudah" class="form-label">Total BK</label>
                                        <input type="number" class="form-control" id="total_bk_sesudah" name="total_bk_sesudah" min="0" placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="catatan_asesor" class="form-label">Catatan Asesor <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('catatan_asesor') is-invalid @enderror" 
                                          id="catatan_asesor" name="catatan_asesor" rows="4" 
                                          placeholder="Berikan penjelasan terkait keputusan Anda..." required></textarea>
                                @error('catatan_asesor')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Proses Banding
                                </button>
                                <a href="{{ route('asesor.banding.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info mb-3">
                            <small>
                                <strong>Status:</strong> {{ $banding->status_label }}<br>
                                <strong>Direview:</strong> {{ $banding->direview_at->format('d/m/Y H:i') }}<br>
                                <strong>Oleh:</strong> {{ $banding->direview_oleh ?? 'System' }}
                            </small>
                        </div>
                        <p class="text-muted small mb-2"><strong>Catatan:</strong></p>
                        <div class="alert alert-success py-2 mb-3">
                            <small>{{ $banding->catatan_asesor }}</small>
                        </div>
                        <a href="{{ route('asesor.banding.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            Kembali
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRevisionFields() {
    const action = document.getElementById('action').value;
    const revisionFields = document.getElementById('revisionFields');
    revisionFields.style.display = (action === 'revise') ? 'block' : 'none';
}
</script>
@endsection
