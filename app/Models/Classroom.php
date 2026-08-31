<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'kode',
        'status',
        'is_pkl',
    ];

    protected $casts = [
        'status' => 'integer',
        'is_pkl' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getColor(): string
    {
        $name = strtoupper($this->name ?? '');

        return match(true) {
            str_contains($name, 'PPLG') => 'orange',
            str_contains($name, 'AKL') => 'yellow',
            str_contains($name, 'PM') => 'purple',
            str_contains($name, 'MPLB') => 'blue',
            str_contains($name, 'TO') => 'red',
            default => 'blue',
        };
    }

    public function getBackgroundColor(): string
    {
        return match($this->getColor()) {
            'orange' => 'rgba(249,115,22,.12)',
            'yellow' => 'rgba(234,179,8,.12)',
            'purple' => 'rgba(168,85,247,.12)',
            'blue' => 'rgba(59,130,246,.12)',
            'red' => 'rgba(239,68,68,.12)',
            default => 'rgba(59,130,246,.12)',
        };
    }

    public function getBorderColor(): string
    {
        return match($this->getColor()) {
            'orange' => 'rgba(249,115,22,.2)',
            'yellow' => 'rgba(234,179,8,.2)',
            'purple' => 'rgba(168,85,247,.2)',
            'blue' => 'rgba(59,130,246,.2)',
            'red' => 'rgba(239,68,68,.2)',
            default => 'rgba(59,130,246,.2)',
        };
    }

    public function getTextColor(): string
    {
        return match($this->getColor()) {
            'orange' => '#f97316',
            'yellow' => '#eab308',
            'purple' => '#a855f7',
            'blue' => '#3b82f6',
            'red' => '#ef4444',
            default => '#3b82f6',
        };
    }
}
