<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'mobile_number',
        'address',
        'firm_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'account_type',
        'added_by',
        'updated_by',
        'has_vehicle',
        'status',
    ];


    // Define the inverse relationship (one-to-many with CreditClient)
    public function creditClients()
    {
        return $this->hasMany(CreditClient::class, 'add_client_credit_id');
    }


    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'add_client_credit_id');
    }
    // In ClientCredit model
    public function bills()
    {
        return $this->hasMany(Bill::class, 'client_credits_id');
    }
}
