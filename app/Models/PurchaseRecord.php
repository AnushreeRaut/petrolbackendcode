<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRecord extends Model
{
    use HasFactory;

    protected $table = 'purchase_records';

    protected $fillable = [
        'product_detail_id',
        'value_1_ms',
        'value_2_speed',
        'value_3_hsd',
        'total_kl',
        'added_by',
        'updated_by',
    ];

    /**
     * Get the associated product detail.
     */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }
}
