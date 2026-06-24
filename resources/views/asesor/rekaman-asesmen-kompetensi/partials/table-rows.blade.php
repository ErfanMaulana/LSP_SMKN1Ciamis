@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp
@forelse($items as $item)
    <tr>
        <td>{{ $isPaginator ? $items->firstItem() + $loop->index : $loop->iteration }}</td>
        <td>
            {{ $item->skema?->nama_skema ?? '-' }}
            <br>
            <small style="color:#64748b;">{{ $item->skema?->nomor_skema ?? '-' }}</small>
        </td>
        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
        <td>
            @if($item->rekomendasi === 'kompeten')
                <span class="badge badge-rekomendasi-kompeten">Kompeten</span>
            @else
                <span class="badge badge-rekomendasi-belum">Belum Kompeten</span>
            @endif
        </td>
        <td>
            {{ $item->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
            <span style="color:#94a3b8;"> - </span>
            {{ $item->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}
        </td>
        <td>
            <div class="action-menu-wrapper">
                <button class="btn-kebab" type="button" title="Pilihan aksi">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('asesor.rekaman-asesmen-kompetensi.show', $item->id) }}" title="Lihat detail rekaman">
                        <i class="bi bi-eye"></i>
                        <span class="menu-entry-label">Lihat Detail</span>
                    </a>
                    <a href="{{ route('asesor.rekaman-asesmen-kompetensi.edit', $item->id) }}" title="Edit rekaman">
                        <i class="bi bi-pencil"></i>
                        <span class="menu-entry-label">Edit</span>
                    </a>
                    <!-- <a href="{{ route('asesor.rekaman-asesmen-kompetensi.export', $item->id) }}" title="Ekspor ke Word">
                        <i class="bi bi-file-earmark-word"></i>
                        <span class="menu-entry-label">Ekspor Word</span>
                    </a> -->
                    <form method="POST" action="{{ route('asesor.rekaman-asesmen-kompetensi.destroy', $item->id) }}" onsubmit="return confirm('Hapus rekaman ini?');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="menu-danger" title="Hapus rekaman">
                            <i class="bi bi-trash"></i>
                            <span class="menu-entry-label">Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada rekaman asesmen kompetensi.</p>
            </div>
        </td>
    </tr>
@endforelse
