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

    .filter-section {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
    }

    .filter-controls {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 36px 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s;
        min-width: 160px;
    }

    .filter-select.is-placeholder {
        color: inherit;
    }

    .filter-select:hover {
        border-color: #cbd5e1;
    }

    .filter-select:focus {
        outline: none;
        border-color: #0073bd;
        box-shadow: 0 0 0 3px rgba(0, 115, 189, 0.1);
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

    .spinner-border {
        display: inline-block;
        width: 3rem;
        height: 3rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    .text-primary {
        color: #0073bd;
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
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

<div class="card" style="padding: 24px; margin-bottom: 14px;">
    <div class="filter-section">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="nilaiAsesorSearchInput" name="search" placeholder="Cari nama / NIK / skema / asesor..." value="{{ request('search') }}" autocomplete="off">
        </div>
        <div class="filter-controls">
            <select id="nilaiAsesorSkemaFilter" name="skema_id" class="filter-select">
                <option value="">Semua Skema</option>
                @foreach($skemas as $skema)
                    <option value="{{ $skema->id }}" {{ request('skema_id') == $skema->id ? 'selected' : '' }}>{{ $skema->nama_skema }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div id="nilaiAsesorTableContainer" class="card">
    @include('admin.nilai-asesor.partials.table-content')
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

function syncNilaiAsesorSkemaPlaceholderColor() {
    const skemaFilter = document.getElementById('nilaiAsesorSkemaFilter');
    if (!skemaFilter) {
        return;
    }

    if (String(skemaFilter.value).trim() === '') {
        skemaFilter.classList.add('is-placeholder');
    } else {
        skemaFilter.classList.remove('is-placeholder');
    }
}

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

function ajaxLoadNilaiAsesor(url) {
    const tableContainer = document.getElementById('nilaiAsesorTableContainer');

    if (nilaiAsesorAjaxController) {
        nilaiAsesorAjaxController.abort();
    }

    nilaiAsesorAjaxController = new AbortController();

    if (tableContainer) {
        tableContainer.innerHTML = `
            <div class="table-wrap">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px 20px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p style="color: #64748b; margin: 12px 0 0;">Mencari data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }

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
        const currentTable = document.getElementById('nilaiAsesorTableContainer');
        if (currentTable) {
            currentTable.innerHTML = html;
        }

        window.history.replaceState({}, '', url);
    })
    .catch(function(error) {
        if (error.name !== 'AbortError') {
            console.error(error);
            if (tableContainer) {
                tableContainer.innerHTML = `
                    <div class="table-wrap">
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 40px 20px;">
                                        <i class="bi bi-exclamation-triangle" style="font-size: 40px; color: #ef4444; display: block; margin-bottom: 12px;"></i>
                                        <p style="color: #64748b; margin: 0;">Gagal memuat data. Silakan coba lagi.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
            }
        }
    });
}

function serializeFilterForm() {
    const searchInput = document.getElementById('nilaiAsesorSearchInput');
    const skemaFilter = document.getElementById('nilaiAsesorSkemaFilter');
    const url = new URL('{{ route('admin.nilai-asesor.index') }}', window.location.origin);

    if (searchInput && searchInput.value.trim() !== '') {
        url.searchParams.set('search', searchInput.value.trim());
    }

    if (skemaFilter && skemaFilter.value.trim() !== '') {
        url.searchParams.set('skema_id', skemaFilter.value.trim());
    }

    return url.toString();
}

function bindNilaiAsesorAjaxSearch() {
    const searchInput = document.getElementById('nilaiAsesorSearchInput');
    const skemaFilter = document.getElementById('nilaiAsesorSkemaFilter');

    syncNilaiAsesorSkemaPlaceholderColor();

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(nilaiAsesorSearchTimer);
            nilaiAsesorSearchTimer = setTimeout(function() {
                ajaxLoadNilaiAsesor(serializeFilterForm());
            }, 500);
        });
    }

    if (skemaFilter) {
        skemaFilter.addEventListener('change', function() {
            syncNilaiAsesorSkemaPlaceholderColor();
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
