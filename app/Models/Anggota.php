<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_club',
        'id_division',
        'name',
    ];

    public function club()
    {
        return $this->belongsTo(Clubs::class, 'id_club');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division');
    }
}
