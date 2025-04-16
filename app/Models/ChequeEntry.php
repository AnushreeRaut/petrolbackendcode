<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChequeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_client_credit_id',
        'bill_no',
        'bill_date',
        'bill_amt',
        'cheque_no',
        'amount',
        'cheque_date',
        'bank_name',
        'status',
        'added_by',
        'updated_by',
    ];

    public function clientCredit()
    {
        return $this->belongsTo(ClientCredit::class, 'add_client_credit_id');
    }
}
