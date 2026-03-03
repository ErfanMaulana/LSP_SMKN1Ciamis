@extends('admin.layout')

@section('title', 'Detail Skema')
@section('page-title', 'Detail Skema')

@section('content')
<div class="page-header">
    <h2>Detail Skema Sertifikasi</h2>
    <div class="header-actions">
        <a href="{{ route('admin.skema.edit', $skema->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.skema.index') }}" class="btn btn-secondary">
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

                <div class="detail-item">
                    <label>Jenis Skema</label>
                    <div class="detail-value">
                        <span class="badge badge-jenis" style="background: {{ $skema->jenis_skema == 'KKNI' ? '#dbeafe' : ($skema->jenis_skema == 'Okupasi' ? '#d1fae5' : '#fef3c7') }}; color: {{ $skema->jenis_skema == 'KKNI' ? '#1e40af' : ($skema->jenis_skema == 'Okupasi' ? '#065f46' : '#92400e') }};">
                            {{ $skema->jenis_skema }}
                        </span>
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
            <h3>Statistik Skema</h3>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $skema->units_count }}</div>
                        <div class="stat-label">Unit Kompetensi</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: #065f46;">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $elemensCount }}</div>
                        <div class="stat-label">Total Elemen</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
                        <i class="bi bi-check2-square"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $kriteriaCount }}</div>
                        <div class="stat-label">Total Kriteria</div>
                    </div>
                </div>
            </div>
        </div>

        @if($skema->units_count > 0)
        <div class="detail-section">
            <h3>Unit Kompetensi</h3>
            
            <div class="units-container">
                @foreach($skema->units as $index => $unit)
                <div class="unit-card">
                    <div class="unit-header">
                        <div class="unit-number">{{ $index + 1 }}</div>
                        <div class="unit-info">
                            <div class="unit-title">{{ $unit->judul_unit }}</div>
                            <div class="unit-code">{{ $unit->kode_unit }}</div>
                        </div>
                        <button type="button" class="toggle-btn" onclick="toggleUnit(this)">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="unit-content">
                        @if($unit->pertanyaan_unit)
                        <div class="unit-question">
                            <div class="question-label">
                                <i class="bi bi-question-circle"></i> Pertanyaan Unit
                            </div>
                            <div class="question-text">{{ $unit->pertanyaan_unit }}</div>
                        </div>
                        @endif

                        @if($unit->elemens->count() > 0)
                        <div class="elemens-container">
                            <div class="section-title">
                                <i class="bi bi-list-check"></i> Elemen Kompetensi ({{ $unit->elemens->count() }})
                            </div>
                            
                            @foreach($unit->elemens as $elIndex => $elemen)
                            <div class="elemen-item">
                                <div class="elemen-header">
                                    <div class="elemen-number">{{ $elIndex + 1 }}</div>
                                    <div class="elemen-name">{{ $elemen->nama_elemen }}</div>
                                    <button type="button" class="toggle-btn small" onclick="toggleElemen(this)">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                                
                                @if($elemen->kriteria->count() > 0)
                                <div class="elemen-content">
                                    <div class="kriteria-title">
                                        <i class="bi bi-check-circle"></i> Kriteria Unjuk Kerja ({{ $elemen->kriteria->count() }})
                                    </div>
                                    <ul class="kriteria-list">
                                        @foreach($elemen->kriteria as $krIndex => $kriteria)
                                        <li class="kriteria-item">
                                            <span class="kriteria-number">{{ $krIndex + 1 }}</span>
                                            <span class="kriteria-text">{{ $kriteria->deskripsi_kriteria }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="detail-section">
            <h3>Informasi Sistem</h3>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Dibuat pada</label>
                    <div class="detail-value">
                        @if($skema->created_at)
                            {{ $skema->created_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Terakhir diupdate</label>
                    <div class="detail-value">
                        @if($skema->updated_at)
                            {{ $skema->updated_at->format('d F Y, H:i') }} WIB
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.skema.edit', $skema->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Data
            </a>
            <a href="{{ route('admin.skema.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
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
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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

    .badge-jenis {
        text-transform: uppercase;
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

    .units-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .unit-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .unit-header {
        background: white;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        user-select: none;
    }

    .unit-header:hover {
        background: #f8fafc;
    }

    .unit-number {
        width: 40px;
        height: 40px;
        background: #0073bd;
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .unit-info {
        flex: 1;
    }

    .unit-title {
        font-size: 15px;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 4px;
    }

    .unit-code {
        font-size: 13px;
        color: #64748b;
        font-family: 'Courier New', monospace;
    }

    .toggle-btn {
        width: 32px;
        height: 32px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .toggle-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .toggle-btn.small {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }

    .toggle-btn i {
        transition: transform 0.3s;
    }

    .unit-card.collapsed .toggle-btn i {
        transform: rotate(-90deg);
    }

    .unit-content {
        padding: 20px;
        display: none;
    }

    .unit-card:not(.collapsed) .unit-content {
        display: block;
    }

    .unit-question {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .question-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #059669;
        margin-bottom: 10px;
    }

    .question-text {
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
    }

    .elemens-container {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #0F172A;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
    }

    .elemen-item {
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .elemen-item:last-child {
        margin-bottom: 0;
    }

    .elemen-header {
        background: #f8fafc;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
    }

    .elemen-header:hover {
        background: #f1f5f9;
    }

    .elemen-number {
        width: 32px;
        height: 32px;
        background: #8b5cf6;
        color: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .elemen-name {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .elemen-item.collapsed .toggle-btn i {
        transform: rotate(-90deg);
    }

    .elemen-content {
        padding: 16px;
        background: white;
        display: none;
    }

    .elemen-item:not(.collapsed) .elemen-content {
        display: block;
    }

    .kriteria-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 12px;
    }

    .kriteria-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .kriteria-item {
        display: flex;
        gap: 12px;
        padding: 10px;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 8px;
        align-items: flex-start;
    }

    .kriteria-item:last-child {
        margin-bottom: 0;
    }

    .kriteria-number {
        min-width: 24px;
        height: 24px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .kriteria-text {
        flex: 1;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f1f5f9;
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

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .unit-content {
            padding: 16px;
        }

        .elemens-container {
            padding: 12px;
        }
    }
</style>

<script>
    function toggleUnit(element) {
        const unitCard = element.closest('.unit-card');
        unitCard.classList.toggle('collapsed');
    }

    function toggleElemen(element) {
        const elemenItem = element.closest('.elemen-item');
        elemenItem.classList.toggle('collapsed');
    }

    // Initialize all collapsed
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.unit-card').forEach(card => {
            card.classList.add('collapsed');
        });
        document.querySelectorAll('.elemen-item').forEach(item => {
            item.classList.add('collapsed');
        });
    });
</script>
@endsection
