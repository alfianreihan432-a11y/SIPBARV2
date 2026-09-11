<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kepalaJurusan()
    {
        return $this->hasOne(User::class)->whereHas('roles', function($query) {
            $query->where('name', 'kepala_jurusan');
        });
    }

    public function laporanJurusans()
    {
        return $this->hasMany(LaporanJurusan::class);
    }
}