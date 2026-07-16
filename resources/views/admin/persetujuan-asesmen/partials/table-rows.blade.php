@php
    $isPaginator = is_object($items) && method_exists($items, 'firstItem');
@endphp

@forelse($items as $item)
    <tr>
        <td>{{ $item->nama_asesi }}</td>
        <td>{{ $item->nama_asesor }}</td>
        <td>{{ $item->judul_skema }}</td>
        <td>{{ $item->nomor_skema }}</td>
        <td>{{ $item->created_at?->locale('id')->translatedFormat('d M Y H:i') }}</td>
        <td class="action-cell">
            <div class="action-wrap">
                <div class="action-menu">
                    <button type="button" class="action-btn" onclick="toggleMenu(this)" aria-label="Aksi data">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="action-dropdown">
                        <a href="{{ route('admin.persetujuan-asesmen.show', $item->id) }}">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.persetujuan-asesmen.export', $item->id) }}">
                            <i class="bi bi-download"></i> Export FR.AK.01 (.doc)
                        </a>

                        <!-- @if(Auth::guard('admin')->user()->hasPermission('persetujuan-asesmen.edit'))
                            <a href="{{ route('admin.persetujuan-asesmen.edit', $item->id) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif -->

                        @if(Auth::guard('admin')->user()->hasPermission('persetujuan-asesmen.delete'))
                            <form method="POST" action="{{ route('admin.persetujuan-asesmen.destroy', $item->id) }}"
                                  onsubmit="return openPersetujuanDeleteModal(event, this, @js('Apakah Anda yakin menghapus data persetujuan asesmen asesi \'' . $item->nama_asesi . '\' ini?'))" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger">
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
        <td colspan="6">
            <div class="empty">
                <i class="bi bi-inboxes" style="font-size: 28px;"></i>
                <div>Belum ada data persetujuan asesmen.</div>
            </div>
        </td>
    </tr>
@endforelse
