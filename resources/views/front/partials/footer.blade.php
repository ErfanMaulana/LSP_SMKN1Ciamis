<footer class="bg-gray-900 text-gray-300 mt-16">
    @php
        $kontak = \App\Models\Kontak::getKontak();
    @endphp
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-12 flex flex-col md:flex-row md:items-start md:justify-between gap-8 md:gap-10">

        {{-- Tentang --}}
        <div class="text-left" style="max-width: 590px; flex-shrink: 0;">
            <h2 class="text-white text-lg font-semibold mb-4 whitespace-nowrap">LSP SMKN 1 Ciamis</h2>
            <p class="text-sm leading-relaxed">
                Lembaga Sertifikasi Profesi P1 SMKN 1 Ciamis yang melaksanakan sertifikasi kompetensi
                bagi siswa sesuai standar industri dan BNSP.
            </p>

            <div id="kontak" class="mt-6">
                <div class="mt-2 w-full rounded-xl overflow-hidden border border-gray-700 shadow-md" style="max-width: 590px; height: 160px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.271937689828!2d108.3269639!3d-7.323321499999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f5eba1b06f52f%3A0xaf882382d9de1508!2sSMK%20Negeri%201%20Ciamis!5e0!3m2!1sid!2sid!4v1771343177921!5m2!1sid!2sid"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full border-0"
                        allowfullscreen="">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Kontak --}}
        <div class="text-left md:min-w-0 md:flex-1">
            <h2 class="text-white text-lg font-semibold mb-4 whitespace-nowrap">Kontak</h2>
            <ul class="space-y-3 text-sm">
                <li style="display: flex; align-items: flex-start; gap: 12px; min-width: 0;">
                    <i class="bi bi-geo-alt text-base text-blue-300 leading-none" style="width: 20px; text-align: center; flex: 0 0 20px; margin-top: 2px;"></i>
                    <span class="leading-relaxed break-words md:overflow-hidden md:[display:-webkit-box] md:[-webkit-line-clamp:2] md:[-webkit-box-orient:vertical]">{{ $kontak->alamat ?: 'Belum diatur' }}</span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 12px; min-width: 0;">
                    <i class="bi bi-telephone text-base text-blue-300 leading-none" style="width: 20px; text-align: center; flex: 0 0 20px; margin-top: 2px;"></i>
                    @if($kontak->telepon)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $kontak->telepon) }}" class="hover:text-white transition leading-relaxed break-words md:overflow-hidden md:[display:-webkit-box] md:[-webkit-line-clamp:2] md:[-webkit-box-orient:vertical]">{{ $kontak->telepon }}</a>
                    @else
                        <span class="leading-relaxed break-words md:overflow-hidden md:[display:-webkit-box] md:[-webkit-line-clamp:2] md:[-webkit-box-orient:vertical]">Belum diatur</span>
                    @endif
                </li>
                <li style="display: flex; align-items: flex-start; gap: 12px; min-width: 0;">
                    <i class="bi bi-envelope text-base text-blue-300 leading-none" style="width: 20px; text-align: center; flex: 0 0 20px; margin-top: 2px;"></i>
                    @if($kontak->email_1)
                        <a href="mailto:{{ $kontak->email_1 }}" class="hover:text-white transition leading-relaxed break-words md:overflow-hidden md:[display:-webkit-box] md:[-webkit-line-clamp:2] md:[-webkit-box-orient:vertical]">{{ $kontak->email_1 }}</a>
                    @else
                        <span class="leading-relaxed break-words md:overflow-hidden md:[display:-webkit-box] md:[-webkit-line-clamp:2] md:[-webkit-box-orient:vertical]">Belum diatur</span>
                    @endif
                </li>
            </ul>
        </div>

        {{-- Sosial Media --}}
        <div class="text-left">
            <h2 class="text-white text-lg font-semibold mb-2 whitespace-nowrap">Navigasi</h2>
            <a href="{{ route('front.panduan.overview') }}" class="inline-flex items-center gap-2 text-sm hover:text-white transition mb-4">
                <i class="bi bi-journal-text text-blue-300"></i>
                Panduan
            </a>

            <h2 class="text-white text-lg font-semibold mb-4 whitespace-nowrap">Sosial Media</h2>
            @php
                $socialMedias = \App\Models\SocialMedia::active()->get();
                $smIcons = [
                    'instagram' => ['icon' => 'bi-instagram',  'color' => '#e1306c'],
                    'youtube'   => ['icon' => 'bi-youtube',    'color' => '#ff0000'],
                    'facebook'  => ['icon' => 'bi-facebook',   'color' => '#1877f2'],
                    'tiktok'    => ['icon' => 'bi-tiktok',     'color' => '#e0e0e0'],
                    'twitter'   => ['icon' => 'bi-twitter-x',  'color' => '#1da1f2'],
                    'whatsapp'  => ['icon' => 'bi-whatsapp',   'color' => '#25d366'],
                    'linkedin'  => ['icon' => 'bi-linkedin',   'color' => '#0a66c2'],
                ];
            @endphp
            @if($socialMedias->count() > 0)
            <ul class="space-y-3 text-sm">
                @foreach($socialMedias as $sm)
                @php
                    $smData = $smIcons[$sm->platform] ?? ['icon' => 'bi-globe', 'color' => '#94a3b8'];
                @endphp
                <li>
                    <a href="{{ $sm->url }}" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-white transition">
                        <i class="bi {{ $smData['icon'] }}" style="color: {{ $smData['color'] }}; font-size: 18px;"></i>
                        {{ $sm->name }}
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">Belum ada sosial media.</p>
            @endif
        </div>

    </div>

    <div class="border-t border-gray-800 text-center px-4 sm:px-6 py-4 text-xs sm:text-sm">
        © {{ date('Y') }} LSP SMKN 1 Ciamis — All rights reserved
    </div>
</footer>
