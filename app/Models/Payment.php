<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_holder_name',
        'payment_name',
        'payment_no',
        'payment_start_date',
        'payment_end_date',
        'mode',
        'date',
        'payment_account',
        'agent_name',
        'contact_no',
        'payment_model',
        'payment_date',
        'payment_amt',
        'updated_by',
    ];
}
