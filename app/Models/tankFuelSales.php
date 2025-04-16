<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankFuelSales extends Model
{
    use HasFactory;

    protected $table = 'tank_fule_sales'; // Ensure the correct table name

    protected $fillable = [
        'tank_id',
        'rate',
        'total_sales',
        'total_a_sales',
        'total_testing',
        'total_amount',
    ];

    /**
     * Relationship with the Tank model.
     */
    public function tank()
    {
        return $this->belongsTo(Tank::class, 'tank_id');
    }
}
