<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankDecantination extends Model
{
    use HasFactory;

    protected $table = 'tank_decantations';

    protected $fillable = [
        'product_detail_id',
        'tank_1_ms',
        'tank_2_speed',
        'tank_3_hsd',
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
