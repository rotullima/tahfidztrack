<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'surah',
        'is_completed',
        'notes',
        'completed_at'
    ];
    
    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];
    
    // Relasi ke participant
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}