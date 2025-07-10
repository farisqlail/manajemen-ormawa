<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id_clubs', 'name'];

    protected $dates = ['deleted_at'];

    public function club()
    {
        return $this->belongsTo(Clubs::class, 'id_clubs');
    }
}
