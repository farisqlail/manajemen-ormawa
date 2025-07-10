<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_club',
        'name',
        'description',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    protected $dates = ['deleted_at'];
}
