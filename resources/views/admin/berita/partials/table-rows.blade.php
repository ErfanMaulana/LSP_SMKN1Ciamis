@forelse($berita as $item)
<tr>
    <td>
        @if($item->gambar)
            <img src="{{ asset('storage/' . $item->gambar) }}" 
                 alt="{{ $item->judul }}" 
                 class="berita-image"
                 onerror="this.onerror=null; this.src='{{ asset('storage/berita/default.png') }}';">
        @else
            <div style="width: 60px; height: 60px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-image" style="color: #9ca3af;"></i>
            </div>
        @endif
    </td>
    <td>
        <div class="berita-title">{{ $item->judul }}</div>
    </td>
    <td>{{ $item->penulis }}</td>
    <td>{{ \Carbon\Carbon::parse($item->tanggal_publikasi)->locale('id')->translatedFormat('d M Y') }}</td>
    <td>
        <span class="status-badge {{ $item->status }}">
            {{ $item->status == 'published' ? 'Published' : 'Draft' }}
        </span>
    </td>
    <td style="text-align: center;">
        <div class="dropdown-action">
            <button class="btn-dropdown" onclick="toggleDropdown(this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.berita.edit', $item->id) }}" class="dropdown-item">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
                <a href="{{ route('admin.berita.show', $item->id) }}" class="dropdown-item">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item danger">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6">
        <div class="empty-state">
            <i class="bi bi-newspaper"></i>
            <h4>Belum ada berita</h4>
            <p>Mulai tambahkan berita baru</p>
        </div>
    </td>
</tr>
@endforelse
