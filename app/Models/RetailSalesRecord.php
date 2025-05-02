<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailSalesRecord extends Model
{
    use HasFactory;

    protected $table = 'retail_sales_records';

    protected $fillable = [
        'oil_product_id',
        'godowns_id',
        'opening_stk_pcs',
        'inward_to_retail',
        'total_op_stk',
        'quality_Sale',
        'bal_stk',
        'sale_amt',
        'discount_amt',
        'act_sale_amt',
        'date',
    ];

    /**
     * Get the oil product associated with the retail sales record.
     */
    public function oilProduct()
    {
        return $this->belongsTo(OilProduct::class, 'oil_product_id');
    }

    public function oilInvoices()
    {
        return $this->hasMany(OilInvoice::class, 'oil_product_id');
    }

    /**
     * Get the godown associated with the retail sales record.
     */
    public function godown()
    {
        return $this->belongsTo(Godown::class, 'godowns_id');
    }
}
