<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'slug',
        'surah',
        'deadline',
        'deadline_date'
    ];

    protected $casts = [
        'surah' => 'array',
    ];
    
    // Relasi ke invitation
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
    
    // Relasi ke participants
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}