<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['id_clubs', 'name'];

    public function club()
    {
        return $this->belongsTo(Clubs::class, 'id_clubs');
    }
}
