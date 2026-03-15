@extends('admin.layout')

@section('title', 'Nilai Asesor')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-value {
        margin-top: 6px;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }

    .toolbar {
        margin-bottom: 14px;
    }

    .filter-form {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-form input,
    .filter-form select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 12px;
        outline: none;
    }

    .filter-form input[type=text] {
        min-width: 200px;
        flex: 1;
    }

    .filter-form input:focus,
    .filter-form select:focus {
        border-color: #0061a5;
    }

    .btn-search {
        padding: 8px 14px;
        background: #0061a5;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    th {
        padding: 10px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    td {
        padding: 10px 12px;
        font-size: 12px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    tr:hover td {
        background: #f8fafc;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge.kompeten {
        background: #d1fae5;
        color: #065f46;
    }

    .badge.belum {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-detail {
        padding: 5px 10px;
        background: #f0f7ff;
        color: #0061a5;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .btn-detail:hover {
        background: #0061a5;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 8px;
        display: block;
    }

    .pagination-wrapper {
        padding: 16px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper svg {
        height: 20px;
    }

    .btn-kkm-setting {
        padding: 10px 16px;
        background: #0061a5;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-kkm-setting:hover {
        background: #004a7a;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        width: 90%;
        max-width: 500px;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: #0f172a;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .kkm-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .kkm-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .kkm-item-label {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
    }

    .kkm-item-number {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .kkm-input-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .kkm-input {
        width: 80px;
        padding: 6px 8px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        text-align: center;
        color: #0f172a;
        background-color: #fff;
    }

    .kkm-input:focus {
        outline: none;
        border-color: #0061a5;
        box-shadow: 0 0 0 3px rgba(0, 97, 165, 0.1);
    }

    /* Chrome, Safari, Edge, Opera */
    .kkm-input::-webkit-outer-spin-button,
    .kkm-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    .kkm-input[type=number] {
        -moz-appearance: textfield;
    }

    .btn-save-kkm {
        padding: 6px 12px;
        background: #10b981;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-save-kkm:hover {
        background: #059669;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        text-align: right;
    }

    .btn-modal-close {
        padding: 8px 16px;
        background: #f1f5f9;
        color: #0f172a;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-modal-close:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Tabel Nilai Asesor</h2>
        <p>Monitoring nilai hasil input asesor untuk setiap asesi dan skema</p>
    </div>
    <button type="button" class="btn-kkm-setting" onclick="openKkmModal()" title="Atur KKM">
        <i class="bi bi-gear"></i> Atur KKM
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Form Dinilai</div>
        <div class="stat-value">{{ $stats->total_form ?? 0 }}</div>
    </div>
    <!-- <div class="stat-card">
        <div class="stat-label">Total Elemen Dinilai</div>
        <div class="stat-value">{{ $stats->total_elemen_dinilai ?? 0 }}</div>
    </div> -->
    <div class="stat-card">
        <div class="stat-label">Rata-rata Global Nilai</div>
        <div class="stat-value">{{ number_format((float) ($stats->rata_global ?? 0), 2) }}</div>
    </div>
</div>

<div class="toolbar">
    <form id="nilaiAsesorFilterForm" class="filter-form" method="GET" action="{{ route('admin.nilai-asesor.index') }}">
        <input type="text" id="nilaiAsesorSearchInput" name="search" placeholder="Cari nama / NIK / skema / asesor..." value="{{ request('search') }}">
        <select id="nilaiAsesorSkemaFilter" name="skema_id">
            <option value="">Semua Skema</option>
            @foreach($skemas as $skema)
                <option value="{{ $skema->id }}" {{ request('skema_id') == $skema->id ? 'selected' : '' }}>{{ $skema->nama_skema }}</option>
            @endforeach
        </select>
        <!-- <button type="submit" id="nilaiAsesorSearchButton" class="btn-search"><i class="bi bi-search"></i> Cari</button> -->
    </form>
</div>

<div id="nilaiAsesorTableContainer" class="card">
    <div class="table-wrap">
        @if($data->count())
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:140px;">Asesi</th>
                        <th style="width:100px;">NIK</th>
                        <th style="width:150px;">Skema</th>
                        <th style="width:100px;">Asesor</th>
                        <!-- <th style="width:90px;">Elemen</th> -->
                        <th style="width:80px;">Rata-rata</th>
                        <th style="width:100px;">Hasil</th>
                        <th style="width:140px;">Terakhir Dinilai</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                        <tr>
                            <td>{{ $data->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $row->nama_asesi }}</strong>
                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;overflow:hidden;text-overflow:ellipsis;max-width:130px;">{{ $row->email_asesi }}</div>
                            </td>
                            <td style="font-family:monospace;">{{ $row->asesi_nik }}</td>
                            <td>
                                <strong style="font-size:12px;">{{ $row->nama_skema }}</strong>
                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $row->nomor_skema }}</div>
                            </td>
                            <td>{{ $row->nama_asesor ?? '-' }}</td>
                            <!-- <td>{{ $row->total_elemen }}</td> -->
                            <td>{{ number_format((float) $row->rata_rata, 2) }}</td>
                            <td>
                                @if((float) $row->rata_rata >= (float) $row->kkm)
                                    <span class="badge kompeten"><i class="bi bi-check-circle"></i> Kompeten</span>
                                @else
                                    <span class="badge belum"><i class="bi bi-x-circle"></i> Belum Kompeten</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.nilai-asesor.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" class="btn-detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">{{ $data->links() }}</div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada data nilai asesor.</p>
            </div>
        @endif
    </div>
</div>

<!-- KKM Setting Modal -->
<div id="kkmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Pengaturan KKM (Kriteria Ketuntasan Minimal)</h3>
            <button type="button" class="modal-close" onclick="closeKkmModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="kkm-list">
                @forelse($skemas as $skema)
                    <form method="POST" action="{{ route('admin.nilai-asesor.update-kkm', $skema->id) }}" onsubmit="handleKkmSubmit(event, this)">
                        @csrf
                        <div class="kkm-item">
                            <div>
                                <div class="kkm-item-label">{{ $skema->nama_skema }}</div>
                                <div class="kkm-item-number">No. {{ $skema->nomor_skema }}</div>
                            </div>
                            <div class="kkm-input-group">
                                <input type="number" name="kkm" class="kkm-input" value="{{ $skema->kkm !== null ? $skema->kkm : 75 }}" min="0" max="100" step="0.01" required>
                                <span style="font-size: 12px; color: #64748b;">%</span>
                                <button type="submit" class="btn-save-kkm">Simpan</button>
                            </div>
                        </div>
                    </form>
                @empty
                    <p style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada skema yang tersedia.</p>
                @endforelse
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-close" onclick="closeKkmModal()">Tutup</button>
        </div>
    </div>
</div>

<script>
let nilaiAsesorAjaxController = null;
let nilaiAsesorSearchTimer = null;

function openKkmModal() {
    document.getElementById('kkmModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeKkmModal() {
    document.getElementById('kkmModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

let kkmSubmitting = false;

function handleKkmSubmit(event, form) {
    event.preventDefault();
    
    // Prevent duplicate submissions
    if (kkmSubmitting) {
        return;
    }
    
    // Validate the input
    const kkmInput = form.querySelector('input[name="kkm"]');
    const kkmValue = parseFloat(kkmInput.value);
    
    if (kkmValue < 0 || kkmValue > 100) {
        alert('KKM harus berada antara 0 dan 100');
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    kkmSubmitting = true;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyimpan...';
    
    const formData = new FormData(form);
    const url = form.action;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menyimpan KKM');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            kkmSubmitting = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan KKM');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        kkmSubmitting = false;
    });
}

function setSearchButtonLoading(isLoading) {
    const btn = document.getElementById('nilaiAsesorSearchButton');
    if (!btn) {
        return;
    }

    if (isLoading) {
        btn.disabled = true;
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memuat...';
    } else {
        btn.disabled = false;
        if (btn.dataset.originalText) {
            btn.innerHTML = btn.dataset.originalText;
        }
    }
}

function ajaxLoadNilaiAsesor(url) {
    if (nilaiAsesorAjaxController) {
        nilaiAsesorAjaxController.abort();
    }

    nilaiAsesorAjaxController = new AbortController();
    setSearchButtonLoading(true);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        signal: nilaiAsesorAjaxController.signal
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Gagal memuat data nilai asesor');
        }
        return response.text();
    })
    .then(function(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTable = doc.getElementById('nilaiAsesorTableContainer');
        const currentTable = document.getElementById('nilaiAsesorTableContainer');

        if (newTable && currentTable) {
            currentTable.innerHTML = newTable.innerHTML;
        }

        window.history.replaceState({}, '', url);
    })
    .catch(function(error) {
        if (error.name !== 'AbortError') {
            console.error(error);
            alert('Gagal memuat data. Silakan coba lagi.');
        }
    })
    .finally(function() {
        setSearchButtonLoading(false);
    });
}

function serializeFilterForm() {
    const form = document.getElementById('nilaiAsesorFilterForm');
    const url = new URL(form.action, window.location.origin);
    const params = new URLSearchParams(new FormData(form));

    params.forEach(function(value, key) {
        if (value !== null && String(value).trim() !== '') {
            url.searchParams.set(key, value);
        }
    });

    return url.toString();
}

function bindNilaiAsesorAjaxSearch() {
    const form = document.getElementById('nilaiAsesorFilterForm');
    const searchInput = document.getElementById('nilaiAsesorSearchInput');
    const skemaFilter = document.getElementById('nilaiAsesorSkemaFilter');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        ajaxLoadNilaiAsesor(serializeFilterForm());
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(nilaiAsesorSearchTimer);
            nilaiAsesorSearchTimer = setTimeout(function() {
                ajaxLoadNilaiAsesor(serializeFilterForm());
            }, 350);
        });
    }

    if (skemaFilter) {
        skemaFilter.addEventListener('change', function() {
            ajaxLoadNilaiAsesor(serializeFilterForm());
        });
    }
}

function bindNilaiAsesorAjaxPagination() {
    document.addEventListener('click', function(event) {
        const link = event.target.closest('#nilaiAsesorTableContainer .pagination a');
        if (!link) {
            return;
        }

        event.preventDefault();
        ajaxLoadNilaiAsesor(link.href);
    });
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('kkmModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeKkmModal();
            }
        });
    }

    bindNilaiAsesorAjaxSearch();
    bindNilaiAsesorAjaxPagination();
});
</script>
@endsection
