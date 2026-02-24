<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-6xl mx-auto px-6 py-12 flex flex-row justify-between gap-10">

        {{-- Tentang --}}
        <div>
            <h2 class="text-white text-lg font-semibold mb-4 whitespace-nowrap">LSP SMKN 1 Ciamis</h2>
            <p class="text-sm leading-relaxed">
                Lembaga Sertifikasi Profesi P1 SMKN 1 Ciamis yang melaksanakan sertifikasi kompetensi
                bagi siswa sesuai standar industri dan BNSP.
            </p>
        </div>

        {{-- Kontak --}}
        <div>
            <h2 class="text-white text-lg font-semibold mb-4 whitespace-nowrap">Kontak</h2>
            <ul class="space-y-2 text-sm">
                <li>📍 Jl. Jenderal Sudirman No.269, Sindangrasa</li>
                <li>📞 (0265) 771204</li>
                <li>✉️ lsp@smkn1ciamis.sch.id</li>
            </ul>
        </div>

        {{-- Sosial Media --}}
        <div>
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

    <div class="border-t border-gray-800 text-center py-4 text-sm">
        © {{ date('Y') }} LSP SMKN 1 Ciamis — All rights reserved
    </div>
</footer>
