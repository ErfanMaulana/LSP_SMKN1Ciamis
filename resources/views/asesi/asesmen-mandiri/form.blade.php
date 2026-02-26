@extends('asesi.layout')

@section('title', 'Asesmen Mandiri - ' . $skema->nama_skema)
@section('page-title', 'Form Asesmen Mandiri')

@section('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #16a34a;
    }

    .skema-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .skema-header-top {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skema-header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }

    .skema-header-info h2 {
        font-size: 18px;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .skema-header-info .skema-number {
        font-size: 13px;
        color: #64748b;
        font-family: monospace;
    }

    .skema-header-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .meta-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
    }

    .meta-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    .instructions-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .instructions-box h3 {
        font-size: 15px;
        color: #166534;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instructions-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .instructions-box li {
        font-size: 13px;
        color: #15803d;
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .instructions-box li:last-child {
        margin-bottom: 0;
    }

    .unit-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .unit-header {
        background: linear-gradient(135deg, #14532d 0%, #166534 100%);
        padding: 20px 24px;
        color: white;
    }

    .unit-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .unit-number {
        background: rgba(255,255,255,0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .unit-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .unit-meta {
        display: flex;
        gap: 24px;
        font-size: 13px;
        opacity: 0.9;
    }

    .unit-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .unit-question {
        background: #fef3c7;
        padding: 14px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        border-bottom: 1px solid #e5e7eb;
    }

    .unit-body {
        padding: 0;
    }

    .elemen-item {
        border-bottom: 1px solid #f1f5f9;
    }

    .elemen-item:last-child {
        border-bottom: none;
    }

    .elemen-header {
        background: #f8fafc;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .elemen-info {
        flex: 1;
    }

    .elemen-number {
        font-size: 12px;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 4px;
    }

    .elemen-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
    }

    .elemen-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 200px;
    }

    .radio-group {
        display: flex;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #16a34a;
    }

    .radio-option.kompeten span {
        font-size: 13px;
        font-weight: 600;
        color: #16a34a;
    }

    .radio-option.belum span {
        font-size: 13px;
        font-weight: 600;
        color: #dc2626;
    }

    .kriteria-list {
        padding: 16px 24px 16px 48px;
    }

    .kriteria-title {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kriteria-item {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .kriteria-item:last-child {
        margin-bottom: 0;
    }

    .kriteria-item .num {
        color: #94a3b8;
        font-weight: 500;
        flex-shrink: 0;
    }

    .bukti-section {
        padding: 0 24px 20px 24px;
    }

    .bukti-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .bukti-input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
        transition: all 0.2s;
    }

    .bukti-input:focus {
        outline: none;
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .bukti-input::placeholder {
        color: #9ca3af;
    }

    .form-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        position: sticky;
        bottom: 20px;
        margin-top: 24px;
    }

    .progress-info {
        font-size: 14px;
        color: #64748b;
    }

    .progress-info strong {
        color: #16a34a;
    }

    .btn-group {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-save {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-save:hover {
        background: #e2e8f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
    }

    @media (max-width: 768px) {
        .elemen-header {
            flex-direction: column;
        }

        .elemen-controls {
            width: 100%;
            min-width: auto;
        }

        .form-actions {
            flex-direction: column;
            position: relative;
            bottom: auto;
        }

        .btn-group {
            width: 100%;
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<a href="{{ route('asesi.asesmen-mandiri.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Skema
</a>

<!-- Skema Header -->
<div class="skema-header">
    <div class="skema-header-top">
        <div class="skema-header-icon">
            <i class="bi bi-patch-check"></i>
        </div>
        <div class="skema-header-info">
            <h2>{{ $skema->nama_skema }}</h2>
            <div class="skema-number">{{ $skema->nomor_skema }}</div>
        </div>
    </div>
    <div class="skema-header-meta">
        <div class="meta-item">
            <span class="meta-label">Jenis Skema</span>
            <span class="meta-value">{{ $skema->jenis_skema ?? 'KKNI/Okupasi/Klaster' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Jumlah Unit</span>
            <span class="meta-value">{{ $skema->units->count() }} Unit Kompetensi</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total Elemen</span>
            <span class="meta-value">{{ $skema->units->sum(fn($u) => $u->elemens->count()) }} Elemen</span>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="instructions-box">
    <h3><i class="bi bi-info-circle"></i> Panduan Asesmen Mandiri</h3>
    <ul>
        <li>Baca setiap pertanyaan/elemen di kolom sebelah kiri dengan teliti.</li>
        <li>Pilih <strong>K (Kompeten)</strong> jika Anda yakin dapat melakukan tugas yang dijelaskan.</li>
        <li>Pilih <strong>BK (Belum Kompeten)</strong> jika Anda merasa belum menguasai kompetensi tersebut.</li>
        <li>Isi kolom <strong>Bukti yang Relevan</strong> dengan menuliskan bukti yang Anda miliki untuk menunjukkan bahwa Anda melakukan pekerjaan tersebut.</li>
        <li>Anda dapat menyimpan jawaban sementara dan melanjutkan di lain waktu.</li>
    </ul>
</div>

<form action="{{ route('asesi.asesmen-mandiri.store', $skema->id) }}" method="POST" id="asesmenForm">
    @csrf

    @foreach($skema->units as $unitIndex => $unit)
    <div class="unit-card">
        <div class="unit-header">
            <div class="unit-title-row">
                <span class="unit-number">Unit Kompetensi {{ $unitIndex + 1 }}</span>
            </div>
            <h3>{{ $unit->judul_unit }}</h3>
            <div class="unit-meta">
                <span><i class="bi bi-tag"></i> {{ $unit->kode_unit }}</span>
                <span><i class="bi bi-layers"></i> {{ $unit->elemens->count() }} Elemen</span>
            </div>
        </div>

        @if($unit->pertanyaan_unit)
        <div class="unit-question">
            <i class="bi bi-question-circle"></i> {{ $unit->pertanyaan_unit }}
        </div>
        @endif

        <div class="unit-body">
            @foreach($unit->elemens as $elemenIndex => $elemen)
            @php
                $existingAnswer = $existingAnswers->get($elemen->id);
            @endphp
            <div class="elemen-item">
                <div class="elemen-header">
                    <div class="elemen-info">
                        <div class="elemen-number">Elemen {{ $elemenIndex + 1 }}</div>
                        <div class="elemen-title">{{ $elemen->nama_elemen }}</div>
                    </div>
                    <div class="elemen-controls">
                        <div class="radio-group">
                            <label class="radio-option kompeten">
                                <input type="radio" 
                                       name="jawaban[{{ $elemen->id }}][status]" 
                                       value="K" 
                                       {{ ($existingAnswer && $existingAnswer->status === 'K') ? 'checked' : '' }}
                                       required>
                                <span>K (Kompeten)</span>
                            </label>
                            <label class="radio-option belum">
                                <input type="radio" 
                                       name="jawaban[{{ $elemen->id }}][status]" 
                                       value="BK"
                                       {{ ($existingAnswer && $existingAnswer->status === 'BK') ? 'checked' : '' }}>
                                <span>BK (Belum Kompeten)</span>
                            </label>
                        </div>
                    </div>
                </div>

                @if($elemen->kriteria->count() > 0)
                <div class="kriteria-list">
                    <div class="kriteria-title">Kriteria Unjuk Kerja:</div>
                    @foreach($elemen->kriteria->sortBy('urutan') as $kriteria)
                    <div class="kriteria-item">
                        <span class="num">{{ $elemenIndex + 1 }}.{{ $kriteria->urutan ?? $loop->iteration }}</span>
                        <span>{{ $kriteria->deskripsi_kriteria }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="bukti-section">
                    <label class="bukti-label">Bukti yang Relevan</label>
                    <textarea name="jawaban[{{ $elemen->id }}][bukti]" 
                              class="bukti-input" 
                              placeholder="Tuliskan bukti yang menunjukkan bahwa Anda dapat melakukan kompetensi ini (contoh: sertifikat, pengalaman kerja, portofolio, dll)">{{ $existingAnswer->bukti ?? '' }}</textarea>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="form-actions">
        <div class="progress-info">
            <i class="bi bi-info-circle"></i>
            Total: <strong>{{ $skema->units->sum(fn($u) => $u->elemens->count()) }} elemen</strong> dari {{ $skema->units->count() }} unit kompetensi
        </div>
        <div class="btn-group">
            <button type="submit" name="save_draft" class="btn btn-save">
                <i class="bi bi-save"></i> Simpan Sementara
            </button>
            <button type="submit" name="submit_final" class="btn btn-submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan asesmen mandiri ini? Pastikan semua jawaban sudah benar.')">
                <i class="bi bi-check-circle"></i> Selesaikan Asesmen
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // Auto-save functionality
    let saveTimeout;
    const form = document.getElementById('asesmenForm');
    
    form.addEventListener('change', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function() {
            // Visual feedback
            const saveBtn = document.querySelector('.btn-save');
            saveBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Menyimpan...';
        }, 2000);
    });
</script>
@endsection
