@forelse($asesor as $item)
<tr>
    <td>
        <div class="user-info">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=3b82f6&color=fff" alt="{{ $item->nama }}">
            </div>
            <div class="user-details">
                <div class="user-name">{{ $item->nama }}</div>
                <div class="user-id">ID: {{ $item->ID_asesor }}</div>
            </div>
        </div>
    </td>
    <td>
        @if($item->skemas->count())
            @foreach($item->skemas as $skema)
                <span class="expertise-text">{{ $skema->nama_skema }}</span>@if(!$loop->last), @endif
            @endforeach
        @else
            <span class="expertise-text" style="color:#94a3b8;">Belum Ditentukan</span>
        @endif
    </td>
    <td>
        <span class="badge badge-active">AKTIF</span>
    </td>
    <td>
        <div class="dropdown-action">
            <button class="btn-dropdown" onclick="toggleDropdown(this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.asesor.edit', $item->ID_asesor) }}" class="dropdown-item">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
                <a href="{{ route('admin.asesor.show', $item->ID_asesor) }}" class="dropdown-item">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <form action="{{ route('admin.asesor.destroy', $item->ID_asesor) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item danger" onclick="return confirm('Apakah Anda yakin?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">
        <div style="padding: 40px 20px;">
            <i class="bi bi-search" style="font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 12px;"></i>
            <p style="color: #64748b; margin: 0;">Tidak ada data asesor ditemukan</p>
        </div>
    </td>
</tr>
@endforelse
