@forelse($asesi as $item)
<tr>
    <td>
        <div class="user-info">
            <div class="user-avatar-initials">
                {{ strtoupper(substr($item->nama, 0, 2)) }}
            </div>
            <div class="user-details">
                <div class="user-name">{{ $item->nama }}</div>
                <div class="user-id">{{ $item->NIK }}</div>
            </div>
        </div>
    </td>
    <td>
        @if($item->skemas->isNotEmpty())
            <span class="scheme-text">{{ $item->skemas->pluck('nama_skema')->implode(', ') }}</span>
        @else
            <span class="scheme-text">Belum Ditentukan</span>
        @endif
    </td>
    <td>
        @if($item->account)
            <div style="font-size:12px;font-weight:600;color:#1e293b;font-family:monospace;">{{ $item->NIK }}</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Password awal: NIK</div>
        @else
            <span style="font-size:11px;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;">Belum ada akun</span>
        @endif
    </td>
    <td>
        @php
            $badgeClass = match($item->status ?? '') {
                'approved' => 'badge-success',
                'pending'  => 'badge-warning',
                'rejected' => 'badge-danger',
                default    => 'badge-info',
            };
            $statusLabel = match($item->status ?? '') {
                'approved' => 'Disetujui',
                'pending'  => 'Menunggu',
                'rejected' => 'Ditolak',
                default    => 'Dalam Proses',
            };
        @endphp
        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
    </td>
    <td>
        <span class="date-text">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') : 'N/A' }}</span>
    </td>
    <td>
        <div class="action-menu">
            <button class="action-btn" onclick="toggleMenu(event, this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.asesi.edit', $item->NIK) }}">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.asesi.destroy', $item->NIK) }}" method="POST" style="margin:0;" onsubmit="return openSingleDeleteModal(event, this, @js('Hapus asesi ' . $item->nama . '?'))">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align: center; padding: 60px 20px;">
        <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data asesi ditemukan</h4>
        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
    </td>
</tr>
@endforelse
