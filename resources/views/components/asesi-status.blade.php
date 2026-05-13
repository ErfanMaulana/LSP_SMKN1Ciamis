@php
    $object = $row ?? $pivot ?? null;
    $statusLabel = '—';
    $statusClass = 'badge-belum';

    if ($object) {
        // Prefer detailed flag/rekomendasi-based status when available
        $hasFlags = isset($object->has_asesmen_mandiri) || isset($object->rekomendasi)
            || isset($object->has_rekaman) || isset($object->has_ceklis_observasi) || isset($object->has_penilaian);

        if ($hasFlags) {
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
        } elseif (isset($object->status) && is_string($object->status)) {
            // Fallback to simple status string mapping
            $map = [
                'belum_mulai' => ['Belum Mulai', 'badge-belum'],
                'sedang_mengerjakan' => ['Sedang Dikerjakan', 'badge-sedang'],
                'selesai' => ['Selesai', 'badge-selesai'],
            ];
            if (isset($map[$object->status])) {
                [$statusLabel, $statusClass] = $map[$object->status];
            } else {
                $statusLabel = $object->status;
                $statusClass = 'badge-belum';
            }
        }
    }
@endphp

<span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
