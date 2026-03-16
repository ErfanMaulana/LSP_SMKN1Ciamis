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
        <span class="scheme-text">{{ $item->jurusan->nama_jurusan ?? 'Belum Ditentukan' }}</span>
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
                <form action="{{ route('admin.asesi.destroy', $item->NIK) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus asesi {{ addslashes($item->nama) }}?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">Tidak ada data asesi</td>
</tr>
@endforelse
