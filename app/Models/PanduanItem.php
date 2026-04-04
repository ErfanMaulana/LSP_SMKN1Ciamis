<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanduanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'description',
        'penjelasan',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
