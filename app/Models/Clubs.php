<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clubs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'logo', 'photo_structure'];
    protected $dates = ['deleted_at'];

    public function divisions()
    {
        return $this->hasMany(Division::class, 'id_clubs');
    }

    public function prokers()
    {
        return $this->hasMany(Proker::class, 'id_club'); 
    }
}
