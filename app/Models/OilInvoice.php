<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'invoice_amt',
        'oil_product_id',
        'purchase_t_cases',
        'total_cases',
        'total_liters',
        'per_unit_price',
        'taxable_value',
        'discount',
        'bal_amt',
        'cgst',
        'sgst',
        'tcs',
        'total_amt',
        'total_pcs',
        'landing_prices',
        'purchase_amount',
        'other_discounts',
        'invoice_amount',
        'diff_per_pc',
        't_stk_amt ',
        'cgst_rate',
        'sgst_rate',
        'date',
    ];

    // Relationship with OilProduct
    public function oilProduct()
    {
        return $this->belongsTo(OilProduct::class, 'oil_product_id');
    }
  // Relationship with Godown
  public function godowns()
  {
      return $this->hasMany(Godown::class, 'oil_invoices_id');
  }

}
