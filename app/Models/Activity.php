<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_club',
        'name',
        'description',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array', 
    ];
}
