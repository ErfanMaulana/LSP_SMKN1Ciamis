@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp
@forelse($items as $item)
    <tr>
        <td>{{ $isPaginator ? $items->firstItem() + $loop->index : $loop->iteration }}</td>
        <td>{{ $item->skema?->nama_skema }}<br><small style="color:#64748b;">{{ $item->skema?->nomor_skema }}</small></td>
        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
        <td>
            @if($item->rekomendasi === 'kompeten')
                <span class="badge badge-rekomendasi-kompeten">Kompeten</span>
            @else
                <span class="badge badge-rekomendasi-belum">Belum Kompeten</span>
            @endif
        </td>
        <td>{{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
        <td>
            <a href="{{ route('asesor.ceklis-observasi.show', $item->id) }}" class="btn-review">
                <i class="bi bi-eye"></i> Review
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada ceklis observasi yang Anda isi.</p>
            </div>
        </td>
    </tr>
@endforelse
