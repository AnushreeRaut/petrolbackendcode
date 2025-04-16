<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankFuleSale extends Model
{
    use HasFactory;

    protected $table = 'tank_fule_sales';

    protected $fillable = ['tank_id', 'rate', 'total_sales', 'total_testing', 'total_amount','total_a_sales','date'];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }
}
