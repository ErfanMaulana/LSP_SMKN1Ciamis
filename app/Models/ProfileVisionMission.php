<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileVisionMission extends Model
{
    use HasFactory;

    protected $table = 'profile_visions_missions';

    protected $fillable = [
        'type',
        'content',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk konten aktif, diurutkan berdasarkan order
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope untuk tipe tertentu
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
