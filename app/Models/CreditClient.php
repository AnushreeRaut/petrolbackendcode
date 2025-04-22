<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditClient extends Model
{

    use HasFactory;

    protected $fillable = [
        'add_client_credit_id',
        'vehicle_no_id',
        'tank_id',
        'bill_no',
        'amount',
        'rate',
        'quantity_in_liter',
        'amt_wrds',
        'vehicle_no',
        'added_by',
        'updated_by',
        'date',
    ];

     // Define the relationship with ClientCredit (belongsTo)
     public function clientCredit()
     {
         return $this->belongsTo(ClientCredit::class, foreignKey: 'add_client_credit_id');  // Correcting the foreign key usage
     }

     // Define the relationship with Tank (belongsTo)
     public function tank()
     {
         return $this->belongsTo(Tank::class, 'tank_id');  // Correct foreign key usage
     }

     public function vehicle()
     {
         return $this->belongsTo(Vehicle::class, 'vehicle_no_id');
     }


}
