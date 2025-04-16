<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_holder_name',
        'loan_name',
        'loan_no',
        'loan_start_date',
        'loan_end_date',
        'mode',
        'date',
        'loan_amt',
        'agent_name',
        'contact_number',
        'payment_model',
        'payment_date',
        'payment_amt',
        'updated_by',
    ];
}
