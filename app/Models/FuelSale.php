<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelSale extends Model
{
    use HasFactory;

    protected $table = 'fuel_sales';

    protected $fillable = [
        'fuel_sales_details_id',
        'product_name',
        'open_stk',
        'purchase',
        'total_stk',
        'a_sale',
        'bal_stk',
        'actual_bal_stk',
        'variation',
        't_variation',
        'added_by',
        'updated_by',
    ];

    /**
     * Get the associated fuel sales details.
     */
    public function fuelSalesDetails()
    {
        return $this->belongsTo(FuelSalesDetail::class);
    }
}
