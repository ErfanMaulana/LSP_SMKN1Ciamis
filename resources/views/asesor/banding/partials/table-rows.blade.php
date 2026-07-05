@forelse($rows as $row)
    @php
        $statusBanding = $row->banding_status ?: 'draft';
        $statusLabel = [
            'draft' => 'Belum Diajukan Asesi',
            'diajukan' => 'Diajukan',
            'ditinjau' => 'Ditinjau',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'asesmen_ulang' => 'Perlu Asesmen Ulang',
            'tidak_banding' => 'Tidak Banding',
        ][$statusBanding] ?? ucfirst($statusBanding);
    @endphp
    <tr>
        <td>
            <div style="font-weight:700;color:#0f172a;">{{ $row->asesi_nama }}</div>
            <div style="font-size:12px;color:#64748b;">NIK: {{ $row->asesi_nik }}</div>
        </td>
        <td>
            <div style="font-weight:600;">{{ $row->nama_skema }}</div>
            <div style="font-size:12px;color:#64748b;">{{ $row->nomor_skema }}</div>
        </td>
        <td>
            @if($row->rekomendasi === 'lanjut')
                <span class="badge badge-keputusan-lanjut">Asesmen Dapat Dilanjutkan</span>
            @else
                <span class="badge badge-keputusan-tidak">Asesmen Tidak Dapat Dilanjutkan</span>
            @endif
        </td>
        <td><span class="badge badge-status-{{ $statusBanding }}">{{ $statusLabel }}</span></td>
        <td>{{ $row->tanggal_pengajuan ? \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d-m-Y') : '-' }}</td>
        <td>
            @if($row->banding_id)
                <a href="{{ route('asesor.banding.form', [$row->asesi_nik, $row->skema_id]) }}" class="btn-review">
                    <i class="bi bi-eye"></i> Detail
                </a>
            @else
                <span class="btn-review disabled">
                    <i class="bi bi-hourglass-split"></i> Menunggu Asesi
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="empty-state">
                <i class="bi bi-clipboard-x"></i>
                <p>Belum ada data banding asesmen yang bisa ditampilkan.</p>
            </div>
        </td>
    </tr>
@endforelse
