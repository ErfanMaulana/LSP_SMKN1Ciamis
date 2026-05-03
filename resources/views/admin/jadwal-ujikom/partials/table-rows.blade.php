@forelse($jadwals as $i => $jadwal)
<tr>
    <td style="color:#94a3b8;font-weight:600;">{{ ($jadwals->currentPage() - 1) * $jadwals->perPage() + $i + 1 }}</td>
    <td>
        <div style="font-weight:600;color:#0F172A;">{{ $jadwal->judul_jadwal }}</div>
        <div style="font-size:12px;color:#0061a5;margin-top:2px;font-weight:600;">
            <i class="bi bi-calendar3"></i>
            @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
                @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                    {{ $jadwal->tanggal_mulai->translatedFormat('d F Y') }}
                @else
                    {{ $jadwal->tanggal_mulai->translatedFormat('d M') }} - {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}
                @endif
            @else
                -
            @endif
        </div>
    </td>
    <td>
        @if($jadwal->skema)
        <div style="font-size:13px;font-weight:500;">{{ Str::limit($jadwal->skema->nama_skema, 40) }}</div>
        <div style="font-size:11px;color:#94a3b8;font-family:monospace;">{{ $jadwal->skema->nomor_skema }}</div>
        @else
        <span style="color:#94a3b8;">—</span>
        @endif
    </td>
    <td>
        @if($jadwal->tuk)
        <div style="font-size:13px;font-weight:500;">{{ $jadwal->tuk->nama_tuk }}</div>
        <div style="font-size:11px;color:#64748b;">{{ $jadwal->tuk->kota ?? '' }}</div>
        @else
        <span style="color:#94a3b8;">—</span>
        @endif
    </td>
    <td>
        @php($kelompokCount = $jadwal->kelompoks_count ?? $jadwal->kelompoks->count())
        <span class="badge kelompok-count" title="{{ $jadwal->kelompoks->pluck('nama_kelompok')->filter()->join(', ') ?: 'Tidak ada kelompok' }}">{{ $kelompokCount }} Kelompok</span>
    </td>
    <td>
        @if($jadwal->tanggal_mulai && $jadwal->tanggal_selesai)
            @if($jadwal->tanggal_mulai->eq($jadwal->tanggal_selesai))
                <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
            @else
                <div style="font-size:13px;font-weight:500;"><i class="bi bi-calendar3" style="color:#0061a5;"></i> {{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }}</div>
                <div style="font-size:12px;color:#64748b;">s/d {{ $jadwal->tanggal_selesai->translatedFormat('d M Y') }}</div>
            @endif
        @else
            <span style="color:#94a3b8;">—</span>
        @endif
    </td>
    <td>
        <span class="badge {{ $jadwal->status }}">
            {{ $jadwal->status_label }}
        </span>
    </td>
    <td>
        <div class="action-menu">
            <button class="action-btn" onclick="toggleMenu(this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="action-dropdown">
                <a href="{{ route('admin.jadwal-ujikom.edit', $jadwal->id) }}">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.jadwal-ujikom.destroy', $jadwal->id) }}" method="POST" style="margin: 0;" data-confirm-message="Hapus jadwal &quot;{{ $jadwal->judul_jadwal }}&quot; ini?" onsubmit="return openDeleteJadwalModal(event, this)">
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
                        <a href="{{ route('admin.jadwal-ujikom.show', $jadwal->id) }}">
                            <i class="bi bi-eye"></i> Detail
                        </a>
    <td colspan="9">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4 style="font-size: 15px; color: #6b7280; font-weight: 500; margin: 0 0 6px;">Tidak ada data jadwal ujikom ditemukan</h4>
            <p style="font-size: 13px; color: #9ca3af; margin: 0;">Coba kata kunci lain.</p>
        </div>
    </td>
</tr>
@endforelse
