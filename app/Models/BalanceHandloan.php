<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHandloan extends Model
{
    use HasFactory;

    protected $table = 'balance_handloans';

    protected $fillable = [
        'hand_loans_id',
        'voucher_type',
        'cr_amount',
        'dr_amount',
        'bal_amount',
        'narration',
    ];

    public function handLoan()
    {
        return $this->belongsTo(HandLoan::class, 'hand_loans_id');
    }

}
