@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp
@forelse($items as $item)
    <tr>
        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
        <td>{{ $item->skema?->nama_skema }}<br><small style="color:#64748b;">{{ $item->skema?->nomor_skema }}</small></td>
        <td>
            @if(!empty($item->is_pending))
                <span style="color:#94a3b8;">-</span>
            @else
                @if($item->rekomendasi === 'kompeten')
                    <span class="badge badge-rekomendasi-kompeten">Kompeten</span>
                @else
                    <span class="badge badge-rekomendasi-belum">Belum Kompeten</span>
                @endif
            @endif
        </td>
        <td>
            @if(!empty($item->is_pending))
                <span class="badge" style="background:#fef3c7; color:#d97706; padding:4px 8px; border-radius:6px; font-weight:600; font-size:11px; display:inline-block;">
                    <i class="bi bi-hourglass-split"></i> Menunggu Pengisian
                </span>
            @elseif(empty($item->ttd_asesi_file))
                <span class="badge" style="background:#e0f2fe; color:#0073bd; padding:4px 8px; border-radius:6px; font-weight:600; font-size:11px; display:inline-block;">
                    <i class="bi bi-clock-history"></i> Menunggu TTD Asesi
                </span>
            @else
                <span class="badge" style="background:#d1fae5; color:#059669; padding:4px 8px; border-radius:6px; font-weight:600; font-size:11px; display:inline-block;">
                    <i class="bi bi-check-circle-fill"></i> Selesai
                </span>
            @endif
        </td>
        <td>{{ $item->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
        <td>
            @if(!empty($item->is_pending))
                <a href="{{ route('asesor.ceklis-observasi.create', ['asesi_nik' => $item->asesi_nik, 'skema_id' => $item->skema_id]) }}" class="btn-review" style="background:#0073bd; color:white; border-color:#0073bd; display:inline-flex; align-items:center; gap:4px;">
                    <i class="bi bi-pencil-square"></i> Isi Ceklis
                </a>
            @else
                <a href="{{ route('asesor.ceklis-observasi.show', $item->id) }}" class="btn-review" style="display:inline-flex; align-items:center; gap:4px;">
                    <i class="bi bi-eye"></i> Detail
                </a>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada ceklis observasi yang cocok.</p>
            </div>
        </td>
    </tr>
@endforelse
