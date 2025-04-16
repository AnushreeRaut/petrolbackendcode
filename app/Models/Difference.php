<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difference extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'volume_per_pcs',
        'vol_type',
        'mrp_price',
        'landing_price',
        'difference_per_pc',
        'added_by',
        'updated_by'
    ];

    // Define relationship if needed
    // public function oilProduct()
    // {
    //     return $this->belongsTo(OilProduct::class);
    // }
}
