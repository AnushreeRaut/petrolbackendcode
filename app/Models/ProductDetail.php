<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';

    protected $fillable = [
        'invoice_feeding_id',
        'invoice_date',
        'products',
        'added_by',
        'updated_by',
    ];

    /**
     * Cast products field to array automatically.
     */
    protected $casts = [
        'products' => 'array',
    ];

    /**
     * Get the associated invoice feeding.
     */
    public function invoiceFeeding()
    {
        return $this->belongsTo(InvoiceFeeding::class);
    }
}
