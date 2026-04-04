@forelse($asesi as $item)
<tr>
    <td class="bulk-col" style="display:none;text-align:center;">
        <input type="checkbox" class="bulk-checkbox" value="{{ $item->NIK }}"
            style="width:16px;height:16px;cursor:pointer;accent-color:#0073bd;">
    </td>
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
            </div>
        </div>
    </td>
    <td>
        <span class="nik-text">{{ $item->NIK }}</span>
    </td>
    <td>
        <span class="date-text">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
    </td>
    <td>
        @if($item->status === 'pending')
            <span class="badge badge-pending">Menunggu</span>
        @elseif($item->status === 'approved')
            <span class="badge badge-approved">Disetujui</span>
        @elseif($item->status === 'banned')
            <span class="badge badge-rejected">Ditolak Permanen</span>
        @else
            <span class="badge badge-rejected">Ditolak Sementara</span>
        @endif
    </td>
    <td style="text-align:center;">
        <div class="action-menu">
            <button class="action-btn" onclick="toggleMenu(this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.asesi.verifikasi.show', $item->NIK) }}" title="Review Detail">
                    <i class="bi bi-eye" style="font-size: 16px;"></i> Lihat Detail
                </a>
                @if($item->status === 'pending')
                <form action="{{ route('admin.asesi.approve', $item->NIK) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" title="Setujui" onclick="return confirm('Setujui pendaftaran {{ addslashes($item->nama) }}?')">
                        <i class="bi bi-check-lg" style="font-size: 16px;"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.asesi.reject', $item->NIK) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" title="Tolak" onclick="return confirm('Tolak pendaftaran {{ addslashes($item->nama) }}?')">
                        <i class="bi bi-x-lg" style="font-size: 16px;"></i> Tolak
                    </button>
                </form>
                @endif
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
