<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decantination extends Model
{
    use HasFactory;

    protected $table = 'decantinations';

    protected $fillable = [
        'invoice_number',
        'tank_1_ms',
        'tank_2_speed',
        'tank_3_hsd',
        'total_kl',
        'added_by',
        'updated_by',
    ];

    public function petrolInvoiceFeeding()
    {
        return $this->belongsTo(PetrolInvoiceFeeding::class, 'petrol_invoice_feeding_id');
    }
}
