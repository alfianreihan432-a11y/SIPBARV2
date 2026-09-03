<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'kode',
        'description',
        'pembina',
        'pembina_phone',
        'jadwal',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
