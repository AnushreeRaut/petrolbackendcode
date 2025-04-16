<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_holder_name',
        'policy_name',
        'policy_no',
        'policy_start_date',
        'policy_end_date',
        'paying_term',
        'date',
        'policy_amt',
        'agent_name',
        'contact_number',
        'payment_mode',
        'payment_date',
        'payment_amt',
        'updated_by',
    ];
}
