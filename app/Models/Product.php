<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'min_stock_alert',
        'unit_type_small',
        'unit_type_large',
        'conversion_rate',
        'selling_price_pcs',
        'selling_price_box',
    ];

    public function productBatches()
    {
        return $this->hasMany(ProductBatch::class);
    }
}
