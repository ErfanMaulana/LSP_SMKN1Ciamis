<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';

    protected $fillable = ['nama_kelompok', 'skema_id'];

    protected $appends = ['status', 'status_label', 'status_badge_class'];

    public const STATUS_BELUM_TERJADWAL = 'belum terjadwal';
    public const STATUS_TERJADWAL = 'terjadwal';
    public const STATUS_SEDANG_ASESMEN = 'sedang asesmen';
    public const STATUS_SELESAI = 'selesai';

    public static function jadwalStatusAliases(): array
    {
        return [
            'scheduled'    => ['dijadwalkan', 'terjadwal', 'scheduled', 'planned'],
            'ongoing'      => ['berlangsung', 'sedang asesmen', 'sedang', 'ongoing', 'in progress', 'in_progress'],
            'finished'     => ['selesai', 'done', 'completed', 'complete', 'closed'],
            'canceled'     => ['dibatalkan', 'cancelled', 'canceled'],
        ];
    }

    public static function normalizeJadwalStatus(?string $status): string
    {
        $status = strtolower(trim((string) $status));
        $status = str_replace(['_', '-'], ' ', $status);

        foreach (static::jadwalStatusAliases() as $normalized => $aliases) {
            if (in_array($status, $aliases, true)) {
                return $normalized;
            }
        }

        return $status;
    }

    public static function statusFilterValues(string $group): array
    {
        return array_map('strtolower', static::jadwalStatusAliases()[$group] ?? []);
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function asesors()
    {
        return $this->belongsToMany(Asesor::class, 'kelompok_asesor', 'kelompok_id', 'asesor_id');
    }

    public function asesis()
    {
        return $this->hasMany(Asesi::class, 'kelompok_id');
    }

    public function jadwals()
    {
        return $this->belongsToMany(
            JadwalUjikom::class,
            'jadwal_kelompok',
            'kelompok_id',
            'jadwal_id'
        );
    }

    public function getStatusAttribute()
    {
        $jadwals = $this->relationLoaded('jadwals') ? $this->jadwals : $this->jadwals()->get();

        if ($jadwals->isEmpty()) {
            return static::STATUS_BELUM_TERJADWAL;
        }

        $normalizedStatuses = $jadwals->map(fn ($jadwal) => static::normalizeJadwalStatus($jadwal->status ?? null));

        if ($normalizedStatuses->contains('ongoing')) {
            return static::STATUS_SEDANG_ASESMEN;
        }

        if ($normalizedStatuses->every(fn ($status) => $status === 'finished' || $status === 'canceled')) {
            return static::STATUS_SELESAI;
        }

        if ($normalizedStatuses->contains('scheduled')) {
            return static::STATUS_TERJADWAL;
        }

        // Fallback
        return static::STATUS_TERJADWAL;
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            static::STATUS_BELUM_TERJADWAL => 'Belum Terjadwal',
            static::STATUS_TERJADWAL       => 'Terjadwal',
            static::STATUS_SEDANG_ASESMEN  => 'Sedang Asesmen',
            static::STATUS_SELESAI         => 'Selesai',
            default           => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            static::STATUS_BELUM_TERJADWAL => 'badge-gray',
            static::STATUS_TERJADWAL       => 'badge-blue',
            static::STATUS_SEDANG_ASESMEN  => 'badge-amber',
            static::STATUS_SELESAI         => 'badge-green',
            default           => 'badge-gray',
        };
    }

    public function getStatusDetailAttribute(): string
    {
        $jadwals = $this->relationLoaded('jadwals') ? $this->jadwals : $this->jadwals()->get();

        if ($jadwals->isEmpty()) {
            return 'Belum ada jadwal terkait.';
        }

        $items = $jadwals->map(function ($jadwal) {
            $mulai = $jadwal->tanggal_mulai ? \Illuminate\Support\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') : '-';
            $selesai = $jadwal->tanggal_selesai ? \Illuminate\Support\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') : '-';
            $status = match (static::normalizeJadwalStatus($jadwal->status ?? null)) {
                'scheduled' => 'terjadwal',
                'ongoing' => 'berlangsung',
                'finished' => 'selesai',
                'canceled' => 'dibatalkan',
                default => $jadwal->status ?? '-',
            };

            return trim(($jadwal->judul_jadwal ?: 'Jadwal') . ' | ' . $mulai . ' - ' . $selesai . ' | ' . $status);
        });

        return $items->take(2)->join(' || ');
    }

    public function getStatusTooltipAttribute(): string
    {
        $detail = $this->status_detail;
        return $this->status_label . ' - ' . $detail;
    }

    public function scopeFilterStatus($query, ?string $status = null)
    {
        $status = strtolower(trim((string) $status));

        if ($status === '' || $status === 'all') {
            return $query;
        }

        return match ($status) {
            static::STATUS_BELUM_TERJADWAL => $query->whereDoesntHave('jadwals'),
            static::STATUS_SEDANG_ASESMEN => $query->whereHas('jadwals', function ($jadwalQuery) {
                $values = static::statusFilterValues('ongoing');
                $jadwalQuery->whereRaw('LOWER(status) IN (' . implode(',', array_fill(0, count($values), '?')) . ')', $values);
            }),
            static::STATUS_SELESAI => $query->whereHas('jadwals')->whereDoesntHave('jadwals', function ($jadwalQuery) {
                $values = array_merge(static::statusFilterValues('finished'), static::statusFilterValues('canceled'));
                $jadwalQuery->whereRaw('LOWER(status) NOT IN (' . implode(',', array_fill(0, count($values), '?')) . ')', $values);
            }),
            default => $query->whereHas('jadwals', function ($jadwalQuery) {
                $scheduled = static::statusFilterValues('scheduled');
                $ongoing = static::statusFilterValues('ongoing');
                $jadwalQuery->whereRaw('LOWER(status) IN (' . implode(',', array_fill(0, count($scheduled), '?')) . ')', $scheduled)
                    ->whereRaw('LOWER(status) NOT IN (' . implode(',', array_fill(0, count($ongoing), '?')) . ')', $ongoing);
            }),
        };
    }
}
