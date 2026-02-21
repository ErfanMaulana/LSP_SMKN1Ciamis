@php
    // safety fallback biar gak 500 walau controller lupa kirim
    $totalMurid  = $totalMurid  ?? 1200;
    $totalAsesor = $totalAsesor ?? 45;
    $totalSkema  = $totalSkema  ?? 12;
    $totalTuk    = $totalTuk    ?? 8;

    $items = [
        [
            'label' => 'TOTAL MURID',
            'value' => $totalMurid,
            'suffix' => '+'
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
            'label' => 'LOKASI TUK',
            'value' => $totalTuk,
            'suffix' => ''
        ],
    ];
@endphp


<section class="bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-4">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

            @foreach($items as $item)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-6">

                <h3 class="text-3xl md:text-4xl font-bold text-blue-600 counter"
                    data-target="{{ $item['value'] }}">
                    0{{ $item['suffix'] }}
                </h3>

                <p class="mt-2 text-gray-500 text-sm tracking-wide">
                    {{ $item['label'] }}
                </p>

            </div>
            @endforeach

        </div>

    </div>
</section>


{{-- Counter Animation --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        const target = +counter.dataset.target;
        let count = 0;
        const speed = 40;

        const update = () => {
            const increment = Math.ceil(target / speed);

            if (count < target) {
                count += increment;
                counter.innerText = count + (counter.innerText.includes('+') ? '+' : '');
                requestAnimationFrame(update);
            } else {
                counter.innerText = target + (counter.innerText.includes('+') ? '+' : '');
            }
        };

        update();
    });
});
</script>
