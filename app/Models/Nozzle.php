<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nozzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id', 'tank_id', 'nozzle_number', 'opening_reading','side1', 'side2','nozzle_stamping_date','nozzle_next_due_date'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}
