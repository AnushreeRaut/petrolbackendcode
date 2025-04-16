<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_record_id',
        'sale_record_id',
        'bal_id',
        'total_sale',
        'rate',
        'total_amount',
        'open_stk',
        'stk_recd',
        'total_stk',
        'bal_stk',
        'added_by',
        'updated_by',
    ];
}
