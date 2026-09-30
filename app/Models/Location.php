<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = ['building', 'floor', 'room'];

    public function getNameAttribute(): string
    {
        $result = $this->building;
        if ($this->floor) {
            $result .= ' Lt.' . $this->floor;
        }
        if ($this->room) {
            $result .= ' - ' . $this->room;
        }
        return $result;
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
