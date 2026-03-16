<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $table = 'kontak';
    
    protected $fillable = [
        'alamat',
        'telepon',
        'telepon_whatsapp',
        'email_1',
        'email_2',
        'jam_pelayanan',
    ];
    
    protected $casts = [
        'jam_pelayanan' => 'array',
    ];
    
    /**
     * Get the first kontak data (since there's only one)
     */
    public static function getKontak()
    {
        return self::first() ?? new self();
    }
}
