@forelse($skemas as $skema)
<tr>
    <td>
        <span class="code-badge">{{ $skema->nomor_skema }}</span>
    </td>
    <td>
        <div class="user-info">
            <div class="skema-icon {{ strtolower($skema->jenis_skema) }}">
                <i class="bi {{ $skema->jenis_skema === 'KKNI' ? 'bi-patch-check' : ($skema->jenis_skema === 'Okupasi' ? 'bi-briefcase' : 'bi-diagram-3') }}"></i>
            </div>
            <div class="user-details">
                <div class="user-name">{{ $skema->nama_skema }}</div>
                <div class="user-id">{{ $skema->nomor_skema }}</div>
            </div>
        </div>
    </td>
    <td>
        <span class="badge badge-{{ strtolower($skema->jenis_skema) }}">
            {{ $skema->jenis_skema }}
        </span>
    </td>
    <td>
        @if($skema->jurusan)
            <span class="text-xs font-medium">{{ strtoupper($skema->jurusan->kode_jurusan) }}</span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        <span class="date-text">{{ $skema->created_at ? $skema->created_at->format('d M Y') : 'N/A' }}</span>
    </td>
    <td>
        <div class="dropdown-action">
            <button class="btn-dropdown" onclick="toggleDropdown(event)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.skema.edit', $skema->id) }}" class="dropdown-item">
                    <i class="bi bi-pencil-square"></i> Ubah
                </a>
                <a href="{{ route('admin.skema.edit', $skema->id) }}" class="dropdown-item">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <form action="{{ route('admin.skema.destroy', $skema->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item danger" onclick="return confirm('Apakah Anda yakin ingin menghapus skema ini?')">
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
        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data skema sertifikasi ditemukan</h4>
        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
    </td>
</tr>
@endforelse
