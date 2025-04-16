<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'stock_in_liters',
        'discount',
        'balance',
        'cgst',
        'sgst',
        'tcs',
        'total_amt',
        'total_pcs',
        'landing_price',
        'purchase_amt',
        'other_discount',
        'invoice_amt',
        'added_by',
        'updated_by'
    ];

    // Define relationship if needed
    // public function oilProduct()
    // {
    //     return $this->belongsTo(OilProduct::class);
    // }
}
