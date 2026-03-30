@php
    // safety fallback biar gak 500 walau controller lupa kirim
    $totalAsesi  = $totalAsesi  ?? 0;
    $totalAsesor = $totalAsesor ?? 0;
    $totalSkema  = $totalSkema  ?? 0;
    $totalTuk    = $totalTuk    ?? 0;

    $items = [
        [
            'label' => 'TOTAL ASESI',
            'value' => $totalAsesi,
            'suffix' => ''
        ],
        [
            'label' => 'ASESOR BERLISENSI',
            'value' => $totalAsesor,
            'suffix' => ''
        ],
        [
            'label' => 'SKEMA KOMPETENSI',
            'value' => $totalSkema,
            'suffix' => ''
        ],
        [
            'label' => 'TUK',
            'value' => $totalTuk,
            'suffix' => ''
        ],
    ];
@endphp


<section id="kompetensi" class="bg-gray-100 py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-center">

            @foreach($items as $item)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-4 sm:p-6"
                 data-scroll-reveal="zoom"
                 data-reveal-delay="{{ $loop->index * 90 }}">

                <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-600 counter"
                    data-target="{{ $item['value'] }}"
                    data-suffix="{{ $item['suffix'] }}"
                    data-counter-duration="{{ 900 + ($loop->index * 120) }}">
                    0{{ $item['suffix'] }}
                </h3>

                <p class="mt-2 text-[11px] sm:text-sm text-gray-500 tracking-wide leading-snug">
                    {{ $item['label'] }}
                </p>

            </div>
            @endforeach

        </div>

    </div>
</section>
