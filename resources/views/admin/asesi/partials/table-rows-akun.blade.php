@forelse($akunTanpaAsesi as $akun)
<tr>
    <td>
        <div class="user-info">
            <div class="user-avatar-initials" style="background:#fce7f3;color:#9d174d;">
                {{ strtoupper(substr($akun->nama ?? '?', 0, 2)) }}
            </div>
            <div class="user-details">
                <div class="user-name">{{ $akun->nama }}</div>
                <div class="user-id">ID: {{ $akun->id }}</div>
            </div>
        </div>
    </td>
    <td>
        <div style="font-size:13px;font-weight:600;color:#1e293b;font-family:monospace;">{{ $akun->NIK ?? $akun->id }}</div>
        <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Password awal: NIK</div>
    </td>
    <td>
        <span class="date-text">{{ $akun->created_at ? \Carbon\Carbon::parse($akun->created_at)->locale('id')->translatedFormat('d M Y') : 'N/A' }}</span>
    </td>
    <td>
        <div class="action-menu">
            <button type="button" class="action-btn" onclick="toggleMenu(event, this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.asesi.create') }}?nik={{ urlencode($akun->NIK ?? $akun->id) }}&nama={{ urlencode($akun->nama) }}">
                    <i class="bi bi-person-plus"></i> Buat Data Asesi
                </a>
                <form action="{{ route('admin.akun-asesi.destroy', $akun->id) }}" method="POST" style="margin:0;" onsubmit="return openSingleDeleteModal(event, this, @js('Hapus akun ' . $akun->nama . '?'))">
                    @csrf
                    @method('DELETE')
                    <button type="button" type="submit">
                        <i class="bi bi-trash"></i> Hapus Akun
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center" style="padding: 40px 20px;">
        <i class="bi bi-inbox" style="font-size: 36px; color: #d1d5db; display: block; margin-bottom: 12px;"></i>
        <h4 style="font-size: 14px; color: #6b7280; font-weight: 500; margin: 0 0 4px;">Tidak ada data ditemukan</h4>
    </td>
</tr>
@endforelse
