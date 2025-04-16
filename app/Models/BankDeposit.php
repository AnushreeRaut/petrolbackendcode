<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'bank_deposits';

    // Fillable properties for mass assignment
    protected $fillable = [
        'add_bank_deposit_id',
        'account_number',
        'amount_words',
        'amount',
        'added_by',
        'updated_by',
        'date',
    ];

    // Define the relationship with the 'add_bank_deposits' table
    public function addBankDeposit()
    {
        return $this->belongsTo(AddBankDeposit::class, 'add_bank_deposit_id');
    }


}
