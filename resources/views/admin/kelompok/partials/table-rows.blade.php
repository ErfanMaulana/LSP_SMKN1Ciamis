@forelse($kelompoks as $index => $kelompok)
    <tr>
        <td>{{ $kelompoks->firstItem() + $index }}</td>
        <td>
            <div style="font-weight: 600; color: #1e293b;">{{ $kelompok->nama_kelompok }}</div>
        </td>
        <td>
            @if($kelompok->skema)
                <span class="badge badge-blue">{{ $kelompok->skema->nama_skema }}</span>
            @else
                <span class="badge badge-gray">Tidak ada skema</span>
            @endif
        </td>
        <td>
            @forelse($kelompok->asesors as $asesor)
                <span class="badge badge-green" style="margin-bottom: 2px;">{{ $asesor->nama }}</span>
            @empty
                <span class="badge badge-gray">Belum ada asesor</span>
            @endforelse
        </td>
        <td>
            @php $jumlahAsesi = $kelompok->asesis->count(); @endphp
            <div style="display: flex; align-items: center; gap: 10px; min-width: 120px;">
                <span style="font-weight: 700; color: #0073bd; font-size: 16px;">{{ $jumlahAsesi }}</span>
                
            </div>
        </td>
        <td>
            <div class="action-menu">
                <button class="action-btn" onclick="toggleMenu(event, this)">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="action-dropdown">
                    <a href="{{ route('admin.kelompok.show', $kelompok->id) }}">
                        <i class="bi bi-people-fill"></i> Kelola Asesi
                    </a>
                    <a href="{{ route('admin.kelompok.edit', $kelompok->id) }}">
                        <i class="bi bi-pencil"></i> Ubah
                    </a>
                    <form action="{{ route('admin.kelompok.destroy', $kelompok->id) }}" method="POST" style="margin: 0;" onsubmit="return openKelompokDeleteModal(event, this, @js('Apakah Anda yakin menghapus "' . $kelompok->nama_kelompok . '" ini?'))">
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
        <td colspan="6" class="text-center">
            <div style="padding: 40px 20px;">
                <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
                <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data kelompok ditemukan</h4>
                <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
            </div>
        </td>
    </tr>
@endforelse
