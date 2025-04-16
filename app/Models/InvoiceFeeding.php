<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceFeeding extends Model
{
    use HasFactory;

    protected $table = 'invoice_feedings';

    protected $fillable = [
        'invoice_no',
        'tank_id',
        'kl_qty',
        'rate_per_unit',
        'value',
        'taxable_amount',
        'product_amount',
        'vat_percent',
        'vat_lst',
        'cess',
        'tcs',
        't_amount',
        't_invoice_amount',
        'added_by',
        'updated_by',
    ];

    /**
     * Get the tank associated with the invoice feeding.
     */
    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}
