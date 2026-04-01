@extends('admin.layout')

@section('title', 'Hasil Umpan Balik Asesi')
@section('page-title', 'Hasil Umpan Balik Asesi')

@section('styles')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0F172A;
        margin: 0;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #0c4a6e;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 13px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
    }

    .info-box i {
        font-size: 16px;
        margin-top: 1px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 42px;
        display: block;
        margin-bottom: 10px;
        color: #94a3b8;
    }

    .empty-state h4 {
        color: #475569;
        margin: 0 0 6px;
        font-size: 16px;
    }

    .empty-state p {
        margin: 0;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Hasil Umpan Balik Asesi</h2>
    <p>Ringkasan umpan balik asesi terhadap asesor setelah jadwal ujikom selesai.</p>
</div>

<div class="card">
    <div class="info-box">
        <i class="bi bi-info-circle"></i>
        <div>Halaman ini disiapkan untuk menampilkan hasil jawaban umpan balik dari asesi. Data akan muncul setelah fitur pengisian umpan balik asesi diaktifkan.</div>
    </div>

    <div class="empty-state">
        <i class="bi bi-clipboard-x"></i>
        <h4>Belum ada data hasil umpan balik</h4>
        <p>Silakan lanjutkan integrasi fitur submit umpan balik dari sisi asesi.</p>
    </div>
</div>
@endsection
