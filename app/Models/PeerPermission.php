<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeerPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'grantor_id',
        'grantee_id',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Relasi ke pemberi izin
    public function grantor()
    {
        return $this->belongsTo(Participant::class, 'grantor_id');
    }
    
    // Relasi ke penerima izin
    public function grantee()
    {
        return $this->belongsTo(Participant::class, 'grantee_id');
    }
}