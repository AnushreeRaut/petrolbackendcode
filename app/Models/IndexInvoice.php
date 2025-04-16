<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndexInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_feeding_id',
        'product_name',
        'rate_per_unit',
        'taxable_amount',
        'vat_lst',
        'cess',
        'tcs',
        'tds',
        'cgst',
        'sgst',
        'lfr',
        'added_by',
        'updated_by'
    ];

    // Define relationship if needed
    public function invoiceFeeding()
    {
        return $this->belongsTo(InvoiceFeeding::class);
    }
}
