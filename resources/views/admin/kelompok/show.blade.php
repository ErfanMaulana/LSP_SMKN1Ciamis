@extends('admin.layout')

@section('title', 'Kelola Kelompok – ' . $kelompok->nama_kelompok)
@section('page-title', 'Kelola Kelompok')

@section('content')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
    .page-header h2 { font-size:24px; font-weight:700; color:#1e293b; }
    .page-header .subtitle { font-size:14px; color:#64748b; margin-top:4px; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .2s; }
    .btn-primary { background:#0061A5; color:white; }
    .btn-primary:hover { background:#003961; color:white; }
    .btn-danger { background:#ef4444; color:white; }
    .btn-danger:hover { background:#dc2626; color:white; }
    .btn-secondary { background:#64748b; color:white; }
    .btn-secondary:hover { background:#475569; color:white; }
    .btn-success { background:#16a34a; color:white; }
    .btn-success:hover { background:#15803d; color:white; }
    .btn-sm { padding:6px 14px; font-size:13px; }
    .btn-outline { background:transparent; border:1.5px solid #0061A5; color:#0061A5; }
    .btn-outline:hover { background:#0061A5; color:white; }
    .card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.07); border:1px solid #e2e8f0; overflow:hidden; margin-bottom:24px; }
    .card-header { padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .card-header h3 { font-size:16px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; }
    .card-body { padding:20px; }
    .profile-section { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .profile-avatar { width:64px; height:64px; border-radius:16px; background:#0061A5; color:white; font-size:24px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .profile-info h3 { font-size:20px; font-weight:700; color:#1e293b; }
    .profile-meta { display:flex; gap:16px; flex-wrap:wrap; margin-top:6px; }
    .profile-meta span { font-size:13px; color:#64748b; display:flex; align-items:center; gap:5px; }
    .badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
    .badge-blue { background:#dbeafe; color:#1d4ed8; }
    .badge-green { background:#dcfce7; color:#15803d; }
    .badge-gray { background:#f1f5f9; color:#64748b; }
    .badge-orange { background:#ffedd5; color:#c2410c; }
    .table-wrapper { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; font-size:14px; }
    thead th { background:#f8fafc; padding:12px 16px; text-align:left; font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid #e2e8f0; }
    thead th.check-col { width:44px; }
    tbody td { padding:14px 16px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align:middle; }
    tbody tr:hover { background:#f8fafc; }
    tbody tr:last-child td { border-bottom:none; }
    .empty-state { text-align:center; padding:48px 20px; color:#94a3b8; }
    .empty-state i { font-size:40px; margin-bottom:10px; display:block; }
    .empty-state p { font-size:14px; }
    .bulk-bar { display:none; align-items:center; gap:12px; padding:12px 20px; background:#eff6ff; border-bottom:1px solid #bfdbfe; font-size:14px; color:#1d4ed8; font-weight:600; }
    .bulk-bar.active { display:flex; }
    .search-box { position:relative; flex:1; min-width:200px; }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px; }
    .search-box input { width:100%; padding:10px 12px 10px 38px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s; font-family:inherit; }
    .search-box input:focus { border-color:#0061A5; }
    .filter-bar { display:flex; gap:12px; padding:16px 20px; border-bottom:1px solid #e2e8f0; flex-wrap:wrap; align-items:center; }
    .asesi-row { cursor:pointer; user-select:none; transition:background .15s; }
    .asesi-row.row-selected { background:#eff6ff!important; }
    .asesi-row.row-selected td { color:#1e40af; }
    .alert { padding:14px 20px; border-radius:10px; font-size:14px; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
    .alert-success { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
</style>

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelompok
    </a>
</div>

{{-- Kelompok Profile Card --}}
<div class="card">
    <div class="card-body">
        <div class="profile-section">
            <div class="profile-avatar">
                <i class="bi bi-collection"></i>
            </div>
            <div class="profile-info">
                <h3>{{ $kelompok->nama_kelompok }}</h3>
                <div class="profile-meta">
                    @if($kelompok->skema)
                        <span><i class="bi bi-patch-check"></i> Skema: <strong>{{ $kelompok->skema->nama_skema }}</strong></span>
                    @endif
                    <span>
                        <i class="bi bi-person-badge"></i> Asesor:
                        @if($kelompok->asesors->count())
                            <strong>{{ $kelompok->asesors->pluck('nama')->join(', ') }}</strong>
                        @else
                            <em style="color:#94a3b8;">Belum ada</em>
                        @endif
                    </span>
                    <span>
                        <i class="bi bi-people"></i>
                        <strong>{{ $asesiDitugaskan->count() }}</strong> asesi ditugaskan
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

    {{-- Asesi Ditugaskan --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-people-fill" style="color:#0061A5;"></i> Asesi Ditugaskan <span class="badge badge-blue">{{ $asesiDitugaskan->count() }}</span></h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Asesi</th>
                        <th>NIK</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asesiDitugaskan as $asesi)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#1e293b;">{{ $asesi->nama }}</div>
                                @if($asesi->kelas)
                                    <div style="font-size:12px;color:#94a3b8;">Kelas {{ $asesi->kelas }}</div>
                                @endif
                            </td>
                            <td style="font-size:13px;color:#64748b;">{{ $asesi->NIK }}</td>
                            <td>
                                @if($asesi->jurusan)
                                    <span class="badge badge-gray">{{ $asesi->jurusan->nama_jurusan ?? $asesi->jurusan->nama ?? '-' }}</span>
                                @else
                                    <span class="badge badge-gray">—</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.kelompok.unassign', [$kelompok->id, $asesi->NIK]) }}"
                                      onsubmit="return confirm('Lepas asesi {{ $asesi->nama }} dari kelompok ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> Lepas
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada asesi yang ditugaskan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Asesi Tersedia --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-person-plus-fill" style="color:#0061A5;"></i> Tambah Asesi <span class="badge badge-blue">{{ $asesiTersedia->count() }}</span></h3>
        </div>

        @if($asesiTersedia->isNotEmpty())
            <form method="POST" action="{{ route('admin.kelompok.assign-bulk', $kelompok->id) }}" id="bulkForm">
                @csrf

                <div class="filter-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchAsesi" placeholder="Cari asesi tersedia...">
                    </div>
                </div>

                <div id="bulkBar" class="bulk-bar">
                    <i class="bi bi-check-square"></i>
                    <span id="bulkCount">0 asesi dipilih</span>
                    <button type="submit" class="btn btn-success btn-sm" style="margin-left:auto;">
                        <i class="bi bi-check-lg"></i> Tugaskan Semua yang Dipilih
                    </button>
                </div>

                <div class="table-wrapper">
                    <table id="asesiTable">
                        <thead>
                            <tr>
                                <th class="check-col">
                                    <input type="checkbox" id="selectAll" style="cursor:pointer;width:16px;height:16px;">
                                </th>
                                <th>Asesi</th>
                                <th>NIK</th>
                                <th>Jurusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asesiTersedia as $asesi)
                                <tr class="asesi-row" onclick="toggleRow(this, event)">
                                    <td>
                                        <input type="checkbox" name="niks[]" value="{{ $asesi->NIK }}"
                                               class="asesi-check" style="cursor:pointer;width:16px;height:16px;">
                                    </td>
                                    <td>
                                        <div style="font-weight:600;color:#1e293b;" class="asesi-name">{{ $asesi->nama }}</div>
                                        @if($asesi->kelas)
                                            <div style="font-size:12px;color:#94a3b8;">Kelas {{ $asesi->kelas }}</div>
                                        @endif
                                    </td>
                                    <td style="font-size:13px;color:#64748b;">{{ $asesi->NIK }}</td>
                                    <td>
                                        @if($asesi->jurusan)
                                            <span class="badge badge-gray">{{ $asesi->jurusan->nama_jurusan ?? $asesi->jurusan->nama ?? '-' }}</span>
                                        @else
                                            <span class="badge badge-gray">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                                onclick="tugaskanSatu('{{ $asesi->NIK }}')">
                                            <i class="bi bi-plus-circle"></i> Tugaskan
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- Hidden single assign form --}}
            <form method="POST" action="{{ route('admin.kelompok.assign', $kelompok->id) }}" id="singleForm">
                @csrf
                <input type="hidden" name="NIK" id="singleNIK">
            </form>
        @else
            <div class="card-body">
                <div class="empty-state">
                    <i class="bi bi-person-check-fill" style="color:#0061A5;"></i>
                    <p>Semua asesi yang telah diverifikasi sudah ditugaskan.</p>
                </div>
            </div>
        @endif
    </div>

</div>

<script>
    function tugaskanSatu(nik) {
        if (!confirm('Tugaskan asesi ini ke kelompok?')) return;
        document.getElementById('singleNIK').value = nik;
        document.getElementById('singleForm').submit();
    }

    function toggleRow(row, event) {
        if (event.target.closest('button') || event.target.type === 'checkbox') return;
        const cb = row.querySelector('.asesi-check');
        cb.checked = !cb.checked;
        row.classList.toggle('row-selected', cb.checked);
        updateBulkBar();
    }

    const selectAll = document.getElementById('selectAll');
    const bulkBar   = document.getElementById('bulkBar');
    const bulkCount = document.getElementById('bulkCount');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.asesi-check:checked').length;
        if (checked > 0) {
            bulkBar.classList.add('active');
            bulkCount.textContent = checked + ' asesi dipilih';
        } else {
            bulkBar.classList.remove('active');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.asesi-check').forEach(cb => {
                cb.checked = this.checked;
                cb.closest('tr').classList.toggle('row-selected', this.checked);
            });
            updateBulkBar();
        });
    }

    document.querySelectorAll('.asesi-check').forEach(cb => {
        cb.addEventListener('change', function() {
            this.closest('tr').classList.toggle('row-selected', this.checked);
            updateBulkBar();
        });
    });

    const searchInput = document.getElementById('searchAsesi');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.asesi-row').forEach(row => {
                const name = row.querySelector('.asesi-name')?.textContent.toLowerCase() || '';
                row.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }
</script>
@endsection
