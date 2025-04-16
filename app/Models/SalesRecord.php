<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'oil_product_id',
        'product_name',
        'volume',
        'rate',
        'morning_sale_1', 'morning_sale_2', 'morning_sale_3', 'morning_sale_4', 'morning_sale_5', 'morning_sale_6',
        'morning_sale_total',
        'evening_sale_1', 'evening_sale_2', 'evening_sale_3', 'evening_sale_4', 'evening_sale_5', 'evening_sale_6',
        'evening_sale_total',
        'total_1', 'total_2', 'total_3', 'total_4', 'total_5', 'total_6',
        'total_sum',
        'added_by',
        'updated_by',
    ];
}
