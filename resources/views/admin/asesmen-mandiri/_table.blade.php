<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Asesi</th>
            <th>Skema</th>
            <th>Status</th>
            <th>Rekomendasi</th>
            <th>Terakhir Diperbarui</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $item)
            <tr>
                <td>{{ $data->firstItem() + $i }}</td>
                <td>
                    <div style="font-weight:600;color:#0F172A">{{ $item->asesi_nama }}</div>
                    <div style="font-size:11px;color:#94a3b8">{{ $item->NIK }} &middot; {{ $item->kelas ?? '-' }}</div>
                </td>
                <td>
                    <div style="font-weight:600">{{ $item->nama_skema }}</div>
                    <div style="font-size:11px;color:#94a3b8">{{ $item->kode_skema ?? '' }}</div>
                </td>
                <td>
                    @php
                        $statusLabels = [
                            'belum_mulai' => 'Belum Mulai',
                            'sedang_mengerjakan' => 'Sedang Mengerjakan',
                            'selesai' => 'Selesai',
                        ];
                    @endphp
                    <span class="badge {{ $item->status }}">{{ $statusLabels[$item->status] ?? $item->status }}</span>
                </td>
                <td>
                    @if($item->status === 'selesai' && $item->rekomendasi)
                        <span class="badge-rekom {{ $item->rekomendasi }}">
                            {{ $item->rekomendasi === 'lanjut' ? 'Lanjut' : 'Tidak Lanjut' }}
                        </span>
                    @elseif($item->status === 'selesai')
                        <span class="badge-rekom draft">Belum Direview</span>
                    @elseif($item->status === 'sedang_mengerjakan')
                        <span class="badge-rekom draft">Draft</span>
                    @else
                        <span style="color:#cbd5e1">-</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#64748b">
                    {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d M Y H:i') : '-' }}
                </td>
                <td>
                    <div class="action-dropdown" style="position:relative;display:inline-block;">
                        <button class="action-toggle" type="button" aria-label="Aksi" onclick="toggleActionMenu(this); return false;" style="border:0;background:transparent;padding:0;cursor:pointer;color:#0f172a;line-height:1;box-shadow:none;outline:none;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="action-menu" style="display:none;position:absolute;right:0;top:28px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 12px rgba(2,6,23,0.08);z-index:50;min-width:160px;overflow:hidden;">
                            <a href="{{ route('admin.asesmen-mandiri.show', [$item->NIK, $item->skema_id]) }}" class="action-item" style="display:block;padding:10px 12px;color:#0f172a;text-decoration:none;">Lihat Detail</a>
                            <form method="POST" action="{{ route('admin.asesmen-mandiri.reset', [$item->NIK, $item->skema_id]) }}"
                                  onsubmit="return openMandiriDeleteModal(event, this, @js('Apakah Anda yakin menghapus / reset asesmen mandiri asesi \'' . $item->asesi_nama . '\'? Semua jawaban akan dihapus.'))" style="margin:0;">
                                @csrf
                                <button type="submit" class="action-item" style="display:block;padding:10px 12px;width:100%;border:none;background:transparent;text-align:left;color:#dc2626;">Hapus / Reset</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data asesmen mandiri ditemukan</h4>
                        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($data->hasPages())
    <div class="pagination-wrapper">
        {{ $data->links() }}
    </div>
@endif

<script>
    function toggleActionMenu(button) {
        var menu = button.nextElementSibling;
        if (!menu) return;

        var isOpen = menu.style.display === 'block';
        document.querySelectorAll('.action-menu').forEach(function (item) {
            item.style.display = 'none';
        });

        menu.style.display = isOpen ? 'none' : 'block';
    }

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.action-menu').forEach(function (menu) {
            if (menu.style.display !== 'block') return;
            if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>
