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
        <div style="font-size:13px;"><i class="bi bi-clock" style="color:#64748b;"></i> {{ substr($jadwal->waktu_mulai,0,5) }} – {{ substr($jadwal->waktu_selesai,0,5) }}</div>
    </td>
    <td>
        @php $pct = $jadwal->kuota > 0 ? min(100, round($jadwal->peserta_terdaftar / $jadwal->kuota * 100)) : 0; @endphp
        <div style="font-size:13px;font-weight:600;">{{ $jadwal->peserta_terdaftar }} / {{ $jadwal->kuota }}</div>
        <div class="kuota-bar">
            <div class="kuota-fill" style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#0061a5') }};"></div>
        </div>
        <div style="font-size:10px;color:#94a3b8;margin-top:2px;">Sisa: {{ $jadwal->sisa_kuota }}</div>
    </td>
    <td>
        <span class="badge {{ $jadwal->status }}">
            {{ $jadwal->status_label }}
        </span>
    </td>
    <td>
        <div class="dropdown-action">
            <button class="btn-dropdown" onclick="toggleDropdown(this, event)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.jadwal-ujikom.edit', $jadwal->id) }}" class="dropdown-item">
                    <i class="bi bi-pencil"></i> Ubah
                </a>
                <form action="{{ route('admin.jadwal-ujikom.destroy', $jadwal->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8">
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <p>Belum ada jadwal ujikom yang ditemukan.</p>
        </div>
    </td>
</tr>
@endforelse
