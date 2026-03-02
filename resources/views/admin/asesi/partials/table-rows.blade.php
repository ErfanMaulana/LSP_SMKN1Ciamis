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
        @php
            // Map status ke badge class
            $statusMap = [
                'pending' => ['label' => 'Menunggu', 'class' => 'badge-warning'],
                'approved' => ['label' => 'Dalam Proses', 'class' => 'badge-info'],
                'rejected' => ['label' => 'Ditolak', 'class' => 'badge-danger'],
                'completed' => ['label' => 'Selesai', 'class' => 'badge-success'],
            ];
            $currentStatus = $statusMap[$item->status] ?? ['label' => 'Menunggu', 'class' => 'badge-warning'];
        @endphp
        <span class="badge {{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
    </td>
    <td>
        <span class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}</span>
    </td>
    <td>
        <div class="action-menu">
            <button class="action-btn" onclick="toggleMenu(this)">
                <i class="bi bi-three-dots"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.asesi.edit', $item->NIK) }}">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
                <a href="#">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <form action="{{ route('admin.asesi.destroy', $item->NIK) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">
        <div style="padding: 40px 20px;">
            <i class="bi bi-search" style="font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 12px;"></i>
            <p style="color: #64748b; margin: 0;">Tidak ada data asesi ditemukan</p>
        </div>
    </td>
</tr>
@endforelse
