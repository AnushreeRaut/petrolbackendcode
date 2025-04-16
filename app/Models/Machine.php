<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispensing_unit_no', 'make', 'serial_no', 'connected_tanks', 'number_of_nozzles',
        'opening_reading', 'added_by', 'updated_by','stamping_date','next_due_date','is_active','sr_no',
    ];

    public function tanks()
    {
        return $this->belongsToMany(Tank::class, 'machine_tank');
    }

    /**
     * Define the relationship with the Nozzle model
     */
    public function nozzles()
    {
        return $this->hasMany(Nozzle::class);
    }
}
