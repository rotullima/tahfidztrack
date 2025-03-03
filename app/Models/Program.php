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

    // Cast surah to array when retrieving from database
    protected $casts = [
        'surah' => 'array',
    ];

    
}