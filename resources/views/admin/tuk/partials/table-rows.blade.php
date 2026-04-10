@forelse($tuks as $i => $tuk)
<tr>
    <td style="color:#94a3b8;font-weight:600;">{{ ($tuks->currentPage() - 1) * $tuks->perPage() + $i + 1 }}</td>
    <td>
        <div style="font-weight:600;color:#0F172A;">{{ $tuk->nama_tuk }}</div>                   
    </td>
    <td>
        @php
            $tipeMap = ['sewaktu'=>'TUK Sewaktu','tempat_kerja'=>'Tempat Kerja','mandiri'=>'TUK Mandiri'];
        @endphp
        <span class="badge {{ $tuk->tipe_tuk }}">{{ $tipeMap[$tuk->tipe_tuk] ?? $tuk->tipe_tuk }}</span>
    </td>
    <td>{{ $tuk->kota ?? '-' }}</td>
    <td>
        <div style="display:flex;align-items:center;gap:6px;">
            <span>{{ number_format($tuk->kapasitas) }} peserta</span>
        </div>
    </td>
    <td>
        <span style="font-weight:600;color:#0061a5;">{{ $tuk->jadwal_ujikom_count }}</span>
    </td>
    <td>
        <span class="badge {{ $tuk->status }}">
            <i class="bi bi-{{ $tuk->status === 'aktif' ? 'check-circle' : 'x-circle' }}"></i>
            {{ $tuk->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
        </span>
    </td>
    <td>
        <div class="action-menu">
            <button type="button" class="action-btn" onclick="toggleMenu(event, this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.tuk.edit', $tuk->id) }}">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
                <form action="{{ route('admin.tuk.toggle', $tuk->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">
                        <i class="bi bi-{{ $tuk->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                        {{ $tuk->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                <form action="{{ route('admin.tuk.destroy', $tuk->id) }}" method="POST" style="margin: 0;" onsubmit="return openTukDeleteModal(event, this, @js('Hapus TUK "' . $tuk->nama_tuk . '" ini?'))">
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
    <td colspan="8" style="text-align: center; padding: 60px 20px;">
        <i class="bi bi-inbox" style="font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
        <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data TUK ditemukan</h4>
        <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
        @if(empty($search) && (($status ?? 'all') === 'all'))
            <a href="{{ route('admin.tuk.create') }}" class="btn-add" style="display:inline-flex;margin-top:12px;">
                <i class="bi bi-plus-lg"></i> Tambah TUK Sekarang
            </a>
        @endif
    </td>
</tr>
@endforelse
