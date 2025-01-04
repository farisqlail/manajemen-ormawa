<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_club',
        'name',
        'document_lpj',
        'budget',
        'target_event',
        'status',
    ];

    public function club()
    {
        return $this->belongsTo(Clubs::class, 'id_club');
    }
}
