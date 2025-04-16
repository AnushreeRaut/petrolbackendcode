<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletPayment extends Model
{
    use HasFactory;

    protected $fillable = ['add_wallet_id', 'number_of_trans', 'amount', 'added_by', 'updated_by','date'];

    public function wallet()
    {
        return $this->belongsTo(AddWallet::class, 'add_wallet_id');
    }
}
