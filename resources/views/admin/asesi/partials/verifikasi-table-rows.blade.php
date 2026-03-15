@forelse($asesi as $item)
<tr>
    <td>
        <div class="user-info">
            @if($item->pas_foto)
                <img src="{{ asset('storage/' . $item->pas_foto) }}" alt="Foto" class="user-avatar-img">
            @else
                <div class="user-avatar-initials">
                    {{ strtoupper(substr($item->nama, 0, 2)) }}
                </div>
            @endif
            <div class="user-details">
                <div class="user-name">{{ $item->nama }}</div>
                <div class="user-id">{{ $item->email ?? '-' }}</div>
            </div>
        </div>
    </td>
    <td>
        <span class="nik-text">{{ $item->NIK }}</span>
    </td>
    <td>
        <span class="scheme-text">{{ $item->jurusan->nama_jurusan ?? '-' }}</span>
    </td>
    <td>
        <span class="date-text">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
    </td>
    <td>
        @if($item->status === 'pending')
            <span class="badge badge-pending">Menunggu</span>
        @elseif($item->status === 'approved')
            <span class="badge badge-approved">Disetujui</span>
        @else
            <span class="badge badge-rejected">Ditolak</span>
        @endif
    </td>
    <td style="text-align:center;">
        <div class="dropdown-action">
            <button class="btn-dropdown" onclick="toggleDropdown(event)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}" class="dropdown-item">
                    <i class="bi bi-eye"></i> Review
                </a>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="empty-state-row">
        <div class="empty-state-content">
            <i class="bi bi-search"></i>
            <p>Tidak ada data asesi ditemukan</p>
        </div>
    </td>
</tr>
@endforelse
