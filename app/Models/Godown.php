<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_invoices_id',
        'oil_product_id',
        'opening_stk', // Remove extra space
        't_opening_stk',
        'outward_retail',
        'bal_stk',
        'date',
    ];


    // Relationship with OilInvoice
    public function oilInvoice()
    {
        return $this->belongsTo(OilInvoice::class, 'oil_invoices_id');
    }

    // Relationship with OilProduct
    public function oilProduct()
    {
        return $this->belongsTo(OilProduct::class, 'oil_product_id');
    }
}
