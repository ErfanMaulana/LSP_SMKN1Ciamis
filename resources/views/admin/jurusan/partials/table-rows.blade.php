@forelse($jurusan as $i => $item)
<tr>
    <td>{{ $jurusan->firstItem() + $i }}</td>
    <td class="jurusan-name">{{ $item->nama_jurusan }}</td>
    <td><span class="jurusan-code">{{ $item->kode_jurusan ?? '-' }}</span></td>
    <td><span class="visi-text" title="{{ $item->visi }}">{{ $item->visi ? Str::limit($item->visi, 60) : '-' }}</span></td>
    <td>
        <span class="asesi-badge {{ $item->asesi_count == 0 ? 'empty' : '' }}">
            <i class="bi bi-people"></i> {{ $item->asesi_count }}
        </span>
    </td>
    <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
    <td>
        <div class="dropdown-action">
            <button type="button" class="btn-dropdown" onclick="toggleDropdown(event)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.jurusan.edit', $item->ID_jurusan) }}" class="dropdown-item">
                    <i class="bi bi-pencil-square"></i> Ubah
                </a>
                <a href="{{ route('admin.jurusan.edit', $item->ID_jurusan) }}" class="dropdown-item">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <button type="button" class="dropdown-item danger"
                    onclick="confirmDelete({{ $item->ID_jurusan }}, '{{ addslashes($item->nama_jurusan) }}', {{ $item->asesi_count }})">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="empty-state" style="padding: 60px 20px;">
        <i class="bi bi-mortarboard" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data jurusan ditemukan</h4>
        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
    </td>
</tr>
@endforelse
