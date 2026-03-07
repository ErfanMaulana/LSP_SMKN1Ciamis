@extends('admin.layout')

@section('title', 'Detail Berita')
@section('page-title', 'Detail Berita')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-header h2 { font-size: 22px; font-weight: 700; color: #0F172A; margin: 0; }
    .page-header p  { font-size: 13px; color: #64748b; margin: 4px 0 0; }

    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .detail-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }

    .detail-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 12px 0;
    }

    .detail-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        font-size: 14px;
        color: #6b7280;
    }

    .detail-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.published {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.draft {
        background: #fef3c7;
        color: #92400e;
    }

    .detail-image {
        margin: 24px 0;
        text-align: center;
    }

    .detail-image img {
        max-width: 100%;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .detail-content {
        line-height: 1.8;
        color: #374151;
        font-size: 15px;
    }

    .detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 16px 0;
    }

    .detail-content h1,
    .detail-content h2,
    .detail-content h3 {
        margin-top: 24px;
        margin-bottom: 12px;
        color: #0F172A;
    }

    .detail-content ul,
    .detail-content ol {
        margin: 12px 0;
        padding-left: 24px;
    }

    .detail-content li {
        margin: 6px 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-primary {
        background: #0073bd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #003961;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Detail Berita</h2>
        <p>Lihat detail berita</p>
    </div>
    <div class="action-buttons">
        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Edit Berita
        </a>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="detail-card">
    <div class="detail-header">
        <h1>{{ $berita->judul }}</h1>
        <div class="detail-meta">
            <div class="detail-meta-item">
                <i class="bi bi-person-fill"></i>
                <span>{{ $berita->penulis }}</span>
            </div>
            <div class="detail-meta-item">
                <i class="bi bi-calendar-event"></i>
                <span>{{ $berita->tanggal_publikasi->format('d F Y') }}</span>
            </div>
            <div class="detail-meta-item">
                <span class="status-badge {{ $berita->status }}">
                    {{ $berita->status == 'published' ? 'Published' : 'Draft' }}
                </span>
            </div>
        </div>
    </div>

    @if($berita->gambar)
    <div class="detail-image">
        <img src="{{ asset('storage/' . $berita->gambar) }}" 
             alt="{{ $berita->judul }}"
             onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}'">
    </div>
    @endif

    <div class="detail-content">
        {!! $berita->konten !!}
    </div>
</div>
@endsection
