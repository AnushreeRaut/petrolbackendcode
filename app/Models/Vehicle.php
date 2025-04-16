<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_no',
        'company_name',
        'description',
        'add_client_credit_id'
    ];

    public function clientCredit()
    {
        return $this->belongsTo(ClientCredit::class, 'add_client_credit_id');
    }
}
