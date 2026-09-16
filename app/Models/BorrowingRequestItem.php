<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingRequestItem extends Model
{
    protected $fillable = [
        'borrowing_request_id',
        'item_id',
        'quantity',
        'kondisi_saat_pinjam',
        'status_pengembalian',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function borrowingRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowingRequest::class, 'borrowing_request_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function itemWithTrashed(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }
}
