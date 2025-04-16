<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInLiter extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'vol_per_pcs',
        'vol_type',
        'total_liters',
        'perunit_price',
        'taxable_value',
        'added_by',
        'updated_by'
    ];

    // Define relationship if needed
    // public function oilProduct()
    // {
    //     return $this->belongsTo(OilProduct::class);
    // }
}
