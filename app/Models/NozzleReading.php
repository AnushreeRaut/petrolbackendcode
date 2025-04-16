<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NozzleReading extends Model
{
    use HasFactory;

    protected $table = 'nozzle_readings';

    protected $fillable = [
        'invoice_feeding_id',
        'nozzle_name',
        'opening',
        'closing',
        'sale',
        'testing',
        'a_sale',
        'total_amt',
        'rate',
        'amount',
        'added_by',
        'updated_by',
    ];

    /**
     * Get the associated invoice feeding.
     */
    public function invoiceFeeding()
    {
        return $this->belongsTo(InvoiceFeeding::class);
    }
}
