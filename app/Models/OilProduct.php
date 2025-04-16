<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_stock',
        'product_name',
        'grade',
        'color',
        'mrp',
        'volume',
        'price_per_piece',
        'pieces_per_case',
        'type',
        'status',
        'cgst',
        'sgst',
        'added_by',
        'updated_by'
    ];


    public function oilinvoice()
    {
        return $this->hasMany(OilInvoice::class, 'oil_product_id');
    }

    // Relationship with OilInvoice
    public function oilInvoices()
    {
        return $this->hasMany(OilInvoice::class, 'oil_product_id');
    }

    public function godownStock()
    {
        return $this->hasMany(Godown::class, 'oil_product_id'); // Adjust foreign key if necessary
    }
}
