<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayEnd extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'a_ms_amt',
        'b_speed_amt',
        'b_hsd_amt',
        'total',
        'total_incoming',
        'total_outgoing',
        'total_balance_cash',
        'to_bank_on_date_cash',
        'add_bank_deposit_id',
        'short',
        't_short',
        'date',
    ];

    public function bankDeposit()
    {
        return $this->belongsTo(AddBankDeposit::class, 'add_bank_deposit_id');
    }
}
