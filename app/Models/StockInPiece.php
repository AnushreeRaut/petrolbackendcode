<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInPiece extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'grade',
        'color',
        'mrp',
        'volume_per_pcs',
        'vol_type',
        'pieces_purchase',
        'per_tcases',
        'total_pcs',
        'added_by',
        'updated_by'
    ];

    // Define relationship if needed
  // Define relationship with OilProduct
  public function oilProduct()
  {
      return $this->belongsTo(OilProduct::class, 'oil_product_id');
  }
}
