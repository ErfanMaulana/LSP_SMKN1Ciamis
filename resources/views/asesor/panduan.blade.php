@extends('asesor.layout')

@section('title', 'Panduan Asesor')
@section('page-title', 'Panduan Asesor')

@section('styles')
<style>
    .guide-hero {
        background: #0073bd;
        color: white;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 18px;
    }

    .guide-hero h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .guide-hero p {
        margin: 0;
        font-size: 14px;
        opacity: 0.92;
        max-width: 760px;
    }

    .guide-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 14px;
    }

    .guide-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .guide-media {
        width: 100%;
        aspect-ratio: 16 / 9;
        background: #e2e8f0;
        overflow: hidden;
    }

    .guide-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .guide-body {
        padding: 16px;
    }

    .guide-card h3 {
        margin: 0 0 10px;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .guide-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.7;
        margin: 0;
    }

    .guide-desc.truncate {
        display: -webkit-box;
        -webkit-line-clamp: 7;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .detail-btn {
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .detail-btn:hover {
        background: #dbeafe;
    }

    .step-list {
        margin: 0;
        padding-left: 18px;
    }

    .step-list li {
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 6px;
    }

    .quick-links {
        margin-top: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .quick-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #0f4c81;
        text-decoration: none;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        padding: 6px 10px;
    }

    .quick-link:hover {
        background: #dbeafe;
    }

    .notes-card {
        margin-top: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
    }

    .notes-card h4 {
        margin: 0 0 8px;
        font-size: 14px;
        color: #0f172a;
    }

    .notes-card p {
        margin: 0;
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
    }

    .guide-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 3000;
        background: rgba(15, 23, 42, 0.55);
        padding: 24px;
        align-items: center;
        justify-content: center;
    }

    .guide-modal.show {
        display: flex;
    }

    .guide-modal-card {
        width: min(820px, 100%);
        max-height: calc(100vh - 48px);
        overflow: auto;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 18px 50px rgba(2, 6, 23, 0.28);
    }

    .guide-modal-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 1;
    }

    .guide-modal-head h3 {
        margin: 0;
        font-size: 18px;
        color: #0f172a;
    }

    .guide-modal-close {
        border: none;
        background: #f1f5f9;
        color: #475569;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 17px;
        flex-shrink: 0;
    }

    .guide-modal-media {
        width: 100%;
        max-height: 360px;
        object-fit: cover;
        display: none;
    }

    .guide-modal-body {
        padding: 18px;
        font-size: 14px;
        color: #334155;
        line-height: 1.8;
        white-space: pre-line;
    }

    @media (max-width: 768px) {
        .guide-hero {
            padding: 16px;
        }

        .guide-hero h2 {
            font-size: 18px;
        }

        .guide-grid { grid-template-columns: 1fr; }
        .guide-body { padding: 14px; }
    }
</style>
@endsection

@section('content')
<div class="guide-hero">
    <h2><i class="bi bi-journal-check"></i> Panduan Penggunaan Panel Asesor</h2>
    <p>
        Halaman ini membantu Anda menjalankan alur kerja asesmen mulai dari melihat asesmen mandiri,
        mengisi nilai elemen, hingga memberikan rekomendasi hasil asesmen.
    </p>
</div>

<div class="guide-grid">
    @forelse($guideItems as $index => $item)
        <article class="guide-card">
            @if(!empty($item->image))
                <figure class="guide-media">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                </figure>
            @endif
            <div class="guide-body">
                <h3>
                    <i class="bi bi-bookmark-check"></i>
                    {{ $index + 1 }}. {{ $item->title }}
                </h3>
                @php
                    $descText = trim((string) $item->description);
                    $isLongDesc = \Illuminate\Support\Str::length($descText) > 260;
                @endphp
                <p class="guide-desc {{ $isLongDesc ? 'truncate' : '' }}">{{ $descText }}</p>
                @if($isLongDesc)
                    <button type="button" class="detail-btn" onclick='openGuideDetail(@json([
                        "title" => ($index + 1) . ". " . $item->title,
                        "description" => $descText,
                        "image" => !empty($item->image) ? asset("storage/" . $item->image) : null
                    ]))'>
                        <i class="bi bi-arrows-angle-expand"></i> View Detail
                    </button>
                @endif
            </div>
        </article>
    @empty
        <article class="guide-card">
            <div class="guide-body">
                <h3><i class="bi bi-info-circle"></i> Panduan belum tersedia</h3>
                <p style="font-size:13px;color:#475569;line-height:1.7;margin:0;">
                    Konten panduan untuk peran asesor belum diatur. Silakan tambahkan melalui panel admin.
                </p>
            </div>
        </article>
    @endforelse
</div>

<div class="guide-modal" id="guideDetailModal" onclick="closeGuideDetail(event)">
    <div class="guide-modal-card">
        <div class="guide-modal-head">
            <h3 id="guideDetailTitle">Detail Panduan</h3>
            <button type="button" class="guide-modal-close" onclick="closeGuideDetail(event)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <img id="guideDetailImage" class="guide-modal-media" alt="Detail panduan">
        <div class="guide-modal-body" id="guideDetailBody"></div>
    </div>
</div>

<div class="quick-links">
    <a href="{{ route('asesor.asesi.index') }}" class="quick-link"><i class="bi bi-arrow-right"></i> Asesmen Mandiri</a>
    <a href="{{ route('asesor.entry-penilaian') }}" class="quick-link"><i class="bi bi-arrow-right"></i> Entry Penilaian</a>
    <a href="{{ route('asesor.jadwal.index') }}" class="quick-link"><i class="bi bi-arrow-right"></i> Jadwal</a>
    <a href="{{ route('asesor.kelompok.index') }}" class="quick-link"><i class="bi bi-arrow-right"></i> Kelompok</a>
</div>

<div class="notes-card">
    <h4><i class="bi bi-lightbulb"></i> Tips</h4>
    <p>
        Prioritaskan penilaian asesi yang sudah selesai asesmen mandiri terlebih dahulu, kemudian lakukan
        review rekomendasi di hari yang sama agar progres sertifikasi tetap cepat dan konsisten.
    </p>
</div>

<script>
    function openGuideDetail(data) {
        const modal = document.getElementById('guideDetailModal');
        const titleEl = document.getElementById('guideDetailTitle');
        const bodyEl = document.getElementById('guideDetailBody');
        const imageEl = document.getElementById('guideDetailImage');

        titleEl.textContent = data.title || 'Detail Panduan';
        bodyEl.textContent = data.description || '';

        if (data.image) {
            imageEl.src = data.image;
            imageEl.style.display = 'block';
        } else {
            imageEl.removeAttribute('src');
            imageEl.style.display = 'none';
        }

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeGuideDetail(event) {
        if (event && event.target && !event.target.classList.contains('guide-modal') && !event.target.closest('.guide-modal-close')) {
            return;
        }

        const modal = document.getElementById('guideDetailModal');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('guideDetailModal');
            if (modal && modal.classList.contains('show')) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
    });
</script>
@endsection
