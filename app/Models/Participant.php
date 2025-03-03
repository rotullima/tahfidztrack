<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'program_id',
        'joined_at',
        'email_notifications'
    ];
    
    protected $casts = [
        'joined_at' => 'datetime',
    ];
    
    // Relasi ke program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    // Relasi ke progress items
    public function progressItems()
    {
        return $this->hasMany(ProgressItem::class);
    }
    
    // Izin peer yang diberikan oleh participant ini
    public function grantedPermissions()
    {
        return $this->hasMany(PeerPermission::class, 'grantor_id');
    }
    
    // Izin peer yang diterima oleh participant ini
    public function receivedPermissions()
    {
        return $this->hasMany(PeerPermission::class, 'grantee_id');
    }
}