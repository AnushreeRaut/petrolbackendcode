<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_sales_details_id',
        'tank_id',
        'open_stk',
        'purchase',
        'total_stk',
        'a_sale',
        'bal_stk',
        'actual_bal_stk',
        'variation',
        't_variation',
        'added_by',
        'updated_by'
    ];

    public function fuelSalesDetail()
    {
        return $this->belongsTo(FuelSalesDetail::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function petrolInvoiceFeeding()
    {
        return $this->belongsTo(PetrolInvoiceFeeding::class);
    }
}
