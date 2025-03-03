<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'token',
        'is_active'
    ];
    
    // Relasi ke program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    // Generate invite token
    public static function generateToken()
    {
        return Str::random(32);
    }
}