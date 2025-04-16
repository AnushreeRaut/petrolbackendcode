<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineWiseGrouping extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'nozzle_number',
        'tank_id',
        'added_by',
        'updated_by',
    ];

    // Relationships
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}
