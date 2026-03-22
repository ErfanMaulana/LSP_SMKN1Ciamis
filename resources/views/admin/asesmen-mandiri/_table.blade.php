<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Asesi</th>
            <th>Skema</th>
            <th>Status</th>
            <th>Rekomendasi</th>
            <th>Terakhir Update</th>
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
                    @if($item->status !== 'belum_mulai')
                        <a href="{{ route('admin.asesmen-mandiri.show', [$item->NIK, $item->skema_id]) }}" class="btn-detail">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    @else
                        <span style="color:#cbd5e1;font-size:12px">-</span>
                    @endif
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
