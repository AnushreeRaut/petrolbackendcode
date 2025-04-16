<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDepositAddCard extends Model
{
    use HasFactory;

    protected $table = 'bank_deposit_add_card';

    protected $fillable = [
        'add_bank_deposit_id',
        'add_card_id',
        'tid_no',
        'narration',
    ];

     /**
     * Get the bank deposit associated with this pivot record.
     */
    public function bankDeposit()
    {
        return $this->belongsTo(AddBankDeposit::class, 'add_bank_deposit_id');
    }

    /**
     * Get the add card associated with this pivot record.
     */
    public function addCard()
    {
        return $this->belongsTo(AddCard::class, 'add_card_id');
    }
}
