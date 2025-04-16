<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankInvoice extends Model
{
    use HasFactory;

    protected $table = 'tank_invoice';

    protected $fillable = [
        'tank_id',
        'add_petrol_invoice_id',
    ];


}
