@forelse($items as $item)
    <tr>
        <td>{{ $item['asesi_nama'] }}</td>
        <td>{{ $item['skema_nama'] }}</td>
        <td>{{ $item['skema_nomor'] }}</td>
        <td>
            @php
                $status = $item['status'] ?? '-';
                $statusClass = 'status-badge--warning';
                if ($status === 'Belum Ditandatangani Asesi') {
                    $statusClass = 'status-badge--info';
                } elseif ($status === 'Sudah Ditandatangani') {
                    $statusClass = 'status-badge--success';
                }
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
        </td>
        <td>
            @if(!empty($item['asesi_nik']) && !empty($item['skema_id']))
                <a href="{{ route('asesor.persetujuan.front.asesor.show', [$item['asesi_nik'], $item['skema_id']]) }}" class="btn-review">
                    <i class="bi bi-eye"></i> Detail
                </a>
            @else
                <span class="btn-review disabled">
                    <i class="bi bi-eye"></i> Detail
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5">
            <div class="empty">
                <i class="bi bi-inboxes" style="font-size: 28px;"></i>
                <div>Belum ada asesi/skema yang terhubung ke akun asesor ini.</div>
            </div>
        </td>
    </tr>
@endforelse
