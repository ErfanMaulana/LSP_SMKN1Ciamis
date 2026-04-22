<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banding extends Model
{
    use HasFactory;

    protected $table = 'bandings';

    protected $fillable = [
        'asesi_nik',
        'skema_id',
        'asesor_id',
        'status',
        'alasan_banding',
        'total_elemen',
        'total_k_sebelum',
        'total_bk_sebelum',
        'catatan_asesor',
        'total_k_sesudah',
        'total_bk_sesudah',
        'diajukan_at',
        'direview_at',
        'direview_oleh',
    ];

    protected $casts = [
        'diajukan_at' => 'datetime',
        'direview_at' => 'datetime',
    ];

    // Relationships
    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesor_id', 'ID_asesor');
    }

    public function asesorReviewer()
    {
        return $this->belongsTo(Asesor::class, 'direview_oleh', 'no_reg');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeRevised($query)
    {
        return $query->where('status', 'revised');
    }

    // Accessors
    public function getHasBeenReviewedAttribute()
    {
        return in_array($this->status, ['approved', 'rejected', 'revised']);
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved' || $this->status === 'revised';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Nilai Direvisi',
            default => 'Unknown',
        };
    }
}
