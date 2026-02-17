<section class="bg-gray-100 py-16">
    <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

        @php
            $items = [
                [$totalMurid,'TOTAL MURID'],
                [$asesor,'ASESOR BERLISENSI'],
                [$skema,'SKEMA KOMPETENSI'],
                [$tuk,'LOKASI TUK']
            ];
        @endphp

        @foreach($items as $i)
        <div class="bg-white p-6 rounded-2xl shadow-sm">
            <h3 class="text-3xl font-bold">{{ $i[0] }}</h3>
            <p class="text-gray-500 text-sm mt-2">{{ $i[1] }}</p>
        </div>
        @endforeach

    </div>
</section>
