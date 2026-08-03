<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'inventory_number',
        'name',
        'description',
        'category_id',
        'location_id',
        'supplier_id',
        'brand',
        'type',
        'purchase_year',
        'price',
        'condition',
        'status',
        'stock',
        'photo_path',
        'qr_code',
        'barcode',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_year' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
