<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilInvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'oil_invoices_details';

    protected $fillable = [
        'invoice_no',
        'invoice_amt',
        'purchase_amount',
        'other_discounts',
        'invoice_amount',
    ];


}
