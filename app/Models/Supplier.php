<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'address', 'email', 'phone'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
