<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clubs extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'logo', 'photo_structure',];

    public function divisions()
    {
        return $this->hasMany(Division::class, 'id_clubs');
    }
}
