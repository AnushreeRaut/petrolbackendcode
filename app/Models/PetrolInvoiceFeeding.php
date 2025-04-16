<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetrolInvoiceFeeding extends Model
{
    use HasFactory;

    protected $table = 'petrol_invoice_feeding';

    protected $fillable = [
        'tank_id',
        'invoice_no',
        'kl_qty',
        'rate',
        'value',
        'tax_amt',
        'prod_amt',
        'vat_lst_value',
        'vat_lst',
        'cess',
        'tcs',
        'total_amt',
        'tds_percent',
        'lfr_rate',
        'cgst',
        'sgst',
        'tds_lfr',
        'added_by',
        'updated_by',
        'date',
    ];
    /**
     * Get the tank associated with the invoice feeding.
     */
    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function decantinations()
    {
        return $this->hasMany(Decantination::class, 'petrol_invoice_feeding_id');
    }
}
