<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $table = 'commissions';

    protected $fillable = [
        'invoice_feeding_id',
        'total_amount',
        'kl_liters',
        'purchase_per_liter',
        'selling_price',
        'diff_comm',
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
