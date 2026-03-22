@forelse($asesor as $item)
<tr>
    <td>
        <div class="user-info">
            <div class="user-avatar">
                @if($item->foto_profil)
                    <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="{{ $item->nama }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=3b82f6&color=fff" alt="{{ $item->nama }}">
                @endif
            </div>
            <div class="user-details">
                <div class="user-name">{{ $item->nama }}</div>
            </div>
        </div>
    </td>
    <td>
        @if($item->skemas->count() > 0)
            @foreach($item->skemas as $skema)
                <span class="expertise-text">{{ $skema->nama_skema }}</span>@if(!$loop->last), @endif
            @endforeach
        @else
            <span style="color:#94a3b8;font-size:13px;">Belum Ditentukan</span>
        @endif
    </td>
    <td>
        <div style="font-size:13px;font-weight:600;color:#1e293b;">{{ $item->no_met ?? '—' }}</div>
    </td>
    <td>
        <span class="badge badge-active">AKTIF</span>
    </td>
    <td>
        <div class="action-menu">
            <button class="action-btn" onclick="toggleMenu(event, this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.asesor.show', $item->ID_asesor) }}">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.asesor.edit', $item->ID_asesor) }}">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.asesor.destroy', $item->ID_asesor) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus asesor {{ addslashes($item->nama) }}?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" style="text-align: center; padding: 60px 20px;">
        <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data asesor ditemukan</h4>
        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
    </td>
</tr>
@endforelse
