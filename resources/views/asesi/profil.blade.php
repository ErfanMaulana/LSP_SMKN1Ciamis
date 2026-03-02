@extends('front.layout.app')

@section('title', 'Profil LSP – LSP SMKN1 Ciamis')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')

<!-- HERO -->
<section class="hero-section">
  <div class="max-w-6xl mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

      <!-- Sejarah Singkat -->
      <div class="lg:col-span-12">
        <div class="card-hero h-full">
          <div class="p-6 text-left">
            <div class="flex items-center justify-center gap-2 mb-1">
              
              <h5 class="card-title-h">Sejarah Singkat</h5>
            </div>
            @forelse($sejarah as $item)
            <p class="hero-text">
              {{ $item->content }}
            </p>
            @empty
            <p class="hero-text text-muted">
              Konten sejarah singkat tidak tersedia.
            </p>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- VISI MISI -->
<section class="vm-section">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="section-title">Visi & Misi</h2>
    <div class="section-underline"></div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

      <div class="lg:col-span-5">
        <div class="card-visi">
          <h5>Visi Kami</h5>
          @if($visions->count() > 0)
            @foreach($visions as $vision)
              <p>{{ $vision->content }}</p>
            @endforeach
          @else
            <p>Visi belum tersedia.</p>
          @endif
        </div>
      </div>

      <div class="lg:col-span-7">
        <div class="card-misi">
          <h5 class="flex items-center gap-2">Misi Kami</h5>
          @forelse($missions as $mission)
            <div class="misi-item"><i class="bi bi-check-circle-fill misi-icon"></i><span>{{ $mission->content }}</span></div>
          @empty
            <p>Misi belum tersedia.</p>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</section>

<!-- KEBIJAKAN MUTU -->
<section class="km-section">
  <div class="max-w-6xl mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

      <div class="lg:col-span-5 km-left">
        <h4 class="flex items-center gap-2">Kebijakan Mutu</h4>
        <p>LSP P1 SMKN 1 Ciamis berkomitmen memberikan pelayanan sertifikasi yang mengutamakan kepuasan pelanggan, profesionalisme, dan konsistensi dalam menerapkan standar BNSP.</p>
        <p class="prinsip-header">Prinsip Utama</p>
        <span class="prinsip-tag">Kualitas</span>
        <span class="prinsip-tag">Integritas</span>
        <span class="prinsip-tag">Profesionalisme</span>
        <span class="prinsip-tag">Akuntabilitas</span>
      </div>

      <div class="lg:col-span-7">
        <h4 class="km-sasaran-title">Sasaran Mutu</h4>
        <div class="grid grid-cols-2 gap-3">
          <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Kelulusan Pelanggan</div>
            <div class="stat-desc">Target tingkat kepuasan peserta terhadap layanan uji kompetensi.</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">0%</div>
            <div class="stat-label">Banding Hasil Uji</div>
            <div class="stat-desc">Menjamin penilaian proses hasil penilaian melalui objektivitas tinggi.</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">24 Jam</div>
            <div class="stat-label">Waktu Respon</div>
            <div class="stat-desc">Maksimal penanganan keluhan dan pertanyaan dari calon asesi.</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Lisensi Aktif</div>
            <div class="stat-desc">Seluruh tenaga penguji memiliki sertifikat kompetensi yang valid.</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- RUANG LINGKUP -->
<section class="rl-section">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="section-title">Ruang Lingkup Sertifikasi</h2>
    <p class="section-subtitle">Berbagai bidang keahlian yang telah terlisensi untuk dilakukan pengujian.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

      <div class="scope-card">
        <div class="scope-icon ic-blue"><i class="bi bi-pc-display-horizontal"></i></div>
        <h6>TIK & Rekayasa Perangkat Lunak</h6>
        <p>Pemrograman Web, Mobile, dan Manajemen Basis Data.</p>
        <span class="skema-badge">3 Skema</span>
      </div>
      <div class="scope-card">
        <div class="scope-icon ic-blue"><i class="bi bi-calculator-fill"></i></div>
        <h6>Akuntansi & Keuangan</h6>
        <p>Akuntansi Junior, Pengelolaan Kas, dan Teknis Akuntansi.</p>
        <span class="skema-badge">2 Skema</span>
      </div>
      <div class="scope-card">
        <div class="scope-icon ic-blue"><i class="bi bi-graph-up-arrow"></i></div>
        <h6>Bisnis & Pemasaran</h6>
        <p>Administrasi, Bisnis, dan Digital Marketing Level 3.</p>
        <span class="skema-badge">2 Skema</span>
      </div>
      <div class="scope-card">
        <div class="scope-icon ic-blue"><i class="bi bi-briefcase-fill"></i></div>
        <h6>Manajemen Perkantoran</h6>
        <p>Sekretaris Junior dan Staf Administrasi Perkantoran.</p>
        <span class="skema-badge">3 Skema</span>
      </div>

    </div>
  </div>
</section>

@endsection
