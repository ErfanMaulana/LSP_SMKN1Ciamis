<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function logUser(?string $id, ?string $name, string $activity, ?string $description = null, ?Request $request = null, array $meta = []): void
    {
        self::write('user', $id, $name, $activity, $description, $request, $meta);
    }

    public static function logAdmin(?string $id, ?string $name, string $activity, ?string $description = null, ?Request $request = null, array $meta = []): void
    {
        self::write('admin', $id, $name, $activity, $description, $request, $meta);
    }

    private static function write(string $actorType, ?string $actorId, ?string $actorName, string $activity, ?string $description, ?Request $request, array $meta): void
    {
        ActivityLog::create([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'actor_name' => $actorName,
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'meta' => empty($meta) ? null : $meta,
        ]);
    }
}
