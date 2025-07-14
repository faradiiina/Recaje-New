<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CafeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cafe_id',
        'image_path'
    ];

    public function cafe()
    {
        return $this->belongsTo(Cafe::class);
    }
} 