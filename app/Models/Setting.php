<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['key', 'value', 'label'];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function get(string $key, $default = null)
    {
        $row = static::find($key);
        if ($row === null) return $default;
        return $row->value ?? $default;
    }

    /**
     * Set (upsert) a setting value.
     */
    public static function set(string $key, $value, ?string $label = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            array_filter(['value' => $value, 'label' => $label], fn($v) => $v !== null)
        );
    }
}
