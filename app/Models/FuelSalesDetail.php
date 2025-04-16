<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelSalesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tank_id',
        'nozzle_name',
        'opening',
        'closing',
        'sale',
        'testing',
        'a_sale',
        'added_by',
        'updated_by',
        'date',
        'unique_id',
        'machine_id',
    ];

     /**
     * Get the tank associated with the invoice feeding.
     */
    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function tankFuelSale()
{
    return $this->hasOne(TankFuleSale::class, 'tank_id', 'tank_id');
}
public function machine()
{
    return $this->belongsTo(Machine::class);
}

}
