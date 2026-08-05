<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowingDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'borrowing_id',
        'item_id',
        'quantity',
        'status',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
