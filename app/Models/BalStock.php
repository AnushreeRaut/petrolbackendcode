<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'openstk_1', 'openstk_2', 'openstk_3', 'openstk_4', 'openstk_5', 'openstk_6', 'openstk_total',
        'stk_received_1', 'stk_received_2', 'stk_received_3', 'stk_received_4', 'stk_received_5', 'stk_received_6',
        'stk_received_total',
        'total_1', 'total_2', 'total_3', 'total_4', 'total_5', 'total_6', 'total_sum',
        'added_by',
        'updated_by',
    ];
}
