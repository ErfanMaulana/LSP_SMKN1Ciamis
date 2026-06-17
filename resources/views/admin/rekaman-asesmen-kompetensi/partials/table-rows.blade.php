@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp

@forelse($items as $item)
    <tr>
        <td>{{ $isPaginator ? $items->firstItem() + $loop->index : $loop->iteration }}</td>
        <td>
            <div>{{ $item->skema?->nama_skema ?? '-' }}</div>
            <small style="color:#64748b;">{{ $item->skema?->nomor_skema ?? '-' }}</small>
        </td>
        <td>{{ $item->asesi?->nama ?? $item->asesi_nik }}</td>
        <td>{{ $item->asesor?->nama ?? '-' }}</td>
        <td>
            @if($item->rekomendasi === 'kompeten')
                <span class="badge success">Kompeten</span>
            @else
                <span class="badge warning">Belum Kompeten</span>
            @endif
        </td>
        <td>{{ $item->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</td>
        <td style="text-align:center;">
            <div class="action-wrap">
                <div class="action-menu">
                    <button type="button" class="action-btn" onclick="toggleMenu(this)">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="action-dropdown">
                        <a href="{{ route('admin.rekaman-asesmen-kompetensi.show', $item->id) }}" class="dropdown-item">
                            <i class="bi bi-eye"></i> Lihat
                        </a>

                        <!-- @if(Auth::guard('admin')->user()->hasPermission('rekaman-asesmen-kompetensi.edit'))
                            <a href="{{ route('admin.rekaman-asesmen-kompetensi.edit', $item->id) }}" class="dropdown-item">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif -->

                        @if(Auth::guard('admin')->user()->hasPermission('rekaman-asesmen-kompetensi.delete'))
                            <form method="POST" action="{{ route('admin.rekaman-asesmen-kompetensi.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">
            <div class="empty">
                <i class="bi bi-inboxes" style="font-size: 28px;"></i>
                <div>Belum ada data rekaman asesmen kompetensi.</div>
            </div>
        </td>
    </tr>
@endforelse
