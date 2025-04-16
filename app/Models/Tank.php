<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    use HasFactory;

    protected $fillable = [
        'tank_number',
        'product',
        'no_of_nozzles',
        'capacity',
        'opening_reading',
        'added_by',
        'updated_by',
        'status'
    ];
  /**
     * Get the machines connected to this tank.
     */
    // public function machines()
    // {
    //     return $this->hasMany(Machine::class, 'tank_id');
    // }


    // Define the relationship with CreditClient (one-to-many)
    public function creditClients()
    {
        return $this->hasMany(CreditClient::class, 'tank_id');
    }
    public function machines()
    {
        return $this->belongsToMany(Machine::class, 'machine_tank');
    }
       public function nozzles()
    {
        return $this->hasMany(Nozzle::class);
    }
    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
     // Relationship with invoices
     public function invoices()
     {
         return $this->hasMany(AddPetrolInvoice::class); // One tank can have many invoices
     }
}
