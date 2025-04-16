<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddBankDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'bank_branch',
        'account_type',
        'added_by',
        'updated_by',
        'status',
        'show',
    ];

    public function bankDeposits()
    {
        return $this->hasMany(BankDeposit::class, 'add_bank_deposit_id');
    }
}
