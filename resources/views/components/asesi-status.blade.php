@php
    $object = $row ?? $pivot ?? null;
    $statusLabel = '—';
    $statusClass = 'badge-belum';

    if ($object) {
        if (!($object->has_asesmen_mandiri ?? false)) {
            $statusLabel = 'Menunggu Asesmen Mandiri';
            $statusClass = 'badge-belum';
        } elseif (empty($object->rekomendasi)) {
            $statusLabel = 'Menunggu Persetujuan Asesmen';
            $statusClass = 'badge-sedang';
        } elseif (($object->rekomendasi ?? '') === 'tidak_lanjut') {
            $statusLabel = 'Persetujuan: Tidak Lanjut';
            $statusClass = 'badge-belum';
        } else {
            if (!($object->has_rekaman ?? false)) {
                $statusLabel = 'Menunggu Rekaman Asesmen';
                $statusClass = 'badge-sedang';
            } elseif (!($object->has_ceklis_observasi ?? false)) {
                $statusLabel = 'Menunggu Ceklis Observasi';
                $statusClass = 'badge-sedang';
            } elseif (!($object->has_penilaian ?? false)) {
                $statusLabel = 'Menunggu Entry Penilaian';
                $statusClass = 'badge-sedang';
            } else {
                $statusLabel = 'Selesai';
                $statusClass = 'badge-selesai';
            }
        }
    }
@endphp

<span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
