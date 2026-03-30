<!-- PROFIL LSP CONTENT MOVED TO HOME -->
<section class="hero-section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <div class="lg:col-span-12">
        <div class="card-hero h-full" data-scroll-reveal data-reveal-duration="760">
          <div class="p-6 text-left">
            <div class="flex items-center justify-center gap-2 mb-1">
              <h5 class="card-title-h">Sejarah Singkat</h5>
            </div>
            @forelse($sejarah as $item)
              <p class="hero-text">{{ $item->content }}</p>
            @empty
              <p class="hero-text text-muted">Konten sejarah singkat tidak tersedia.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="vm-section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="section-title" data-scroll-reveal>Visi & Misi</h2>
    <div class="section-underline" data-scroll-reveal data-reveal-delay="70"></div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <div class="lg:col-span-5">
        <div class="card-visi" data-scroll-reveal="left" data-reveal-delay="120">
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
        <div class="card-misi" data-scroll-reveal="right" data-reveal-delay="180">
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

<section class="km-section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      <div class="lg:col-span-5 km-left" data-scroll-reveal="left">
        <h4 class="flex items-center gap-2">Kebijakan Mutu</h4>
        <p>LSP P1 SMKN 1 Ciamis berkomitmen memberikan pelayanan sertifikasi yang mengutamakan kepuasan pelanggan, profesionalisme, dan konsistensi dalam menerapkan standar BNSP.</p>
        <p class="prinsip-header">Prinsip Utama</p>
        <span class="prinsip-tag">Kualitas</span>
        <span class="prinsip-tag">Integritas</span>
        <span class="prinsip-tag">Profesionalisme</span>
        <span class="prinsip-tag">Akuntabilitas</span>
      </div>

      <div class="lg:col-span-7" data-scroll-reveal="right" data-reveal-delay="120">
        <h4 class="km-sasaran-title">Sasaran Mutu</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="stat-card" data-scroll-reveal="zoom" data-reveal-delay="160">
            <div class="stat-number">100%</div>
            <div class="stat-label">Kelulusan Pelanggan</div>
            <div class="stat-desc">Target tingkat kepuasan peserta terhadap layanan uji kompetensi.</div>
          </div>
          <div class="stat-card" data-scroll-reveal="zoom" data-reveal-delay="220">
            <div class="stat-number">0%</div>
            <div class="stat-label">Banding Hasil Uji</div>
            <div class="stat-desc">Menjamin penilaian proses hasil penilaian melalui objektivitas tinggi.</div>
          </div>
          <div class="stat-card" data-scroll-reveal="zoom" data-reveal-delay="280">
            <div class="stat-number">24 Jam</div>
            <div class="stat-label">Waktu Respon</div>
            <div class="stat-desc">Maksimal penanganan keluhan dan pertanyaan dari calon asesi.</div>
          </div>
          <div class="stat-card" data-scroll-reveal="zoom" data-reveal-delay="340">
            <div class="stat-number">100%</div>
            <div class="stat-label">Lisensi Aktif</div>
            <div class="stat-desc">Seluruh tenaga penguji memiliki sertifikat kompetensi yang valid.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="rl-section">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="section-title" data-scroll-reveal>Ruang Lingkup Sertifikasi</h2>
    <p class="section-subtitle" data-scroll-reveal data-reveal-delay="70">Berbagai bidang keahlian yang telah terlisensi untuk dilakukan pengujian.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      @forelse($jurusanList ?? [] as $jurusan)
        <div class="scope-card" data-scroll-reveal="zoom" data-reveal-delay="{{ 100 + ($loop->index * 70) }}">
          <div class="scope-icon {{ $jurusan['color'] }}"><i class="bi {{ $jurusan['icon'] }}"></i></div>
          <h6>{{ $jurusan['nama'] }}</h6>
          <p>{{ Str::limit($jurusan['visi'] ?? 'Sertifikasi kompetensi untuk Program Keahlian ' . $jurusan['nama'], 80) }}</p>
          <span class="skema-badge">{{ $jurusan['skema_count'] }} Skema</span>
        </div>
      @empty
        <div class="col-span-full text-center py-8 text-gray-500">
          <i class="bi bi-inbox" style="font-size: 2rem;"></i>
          <p class="mt-2">Belum ada skema sertifikasi tersedia.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
