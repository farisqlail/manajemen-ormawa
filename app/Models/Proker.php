<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_club',
        'name',
        'proposal',
        'laporan',
        'budget',
        'target_event',
        'status',
        'status_laporan',
        'reason',
        'pdf_file'
    ];

    protected $dates = ['deleted_at'];

    public function club()
    {
        return $this->belongsTo(Clubs::class, 'id_club');
    }
}
