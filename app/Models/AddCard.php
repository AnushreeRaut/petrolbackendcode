<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'petrol_card_machine_no',
        'petrol_card_no',
        'card_used_status',
        'batch_no',
        'open_closed',
        'added_by',
        'updated_by',
    ];

    // In Card.php (Card Model)   // Corrected relationship method
    public function petrolCard()
    {
        return $this->hasOne(PetrolCard::class, 'card_id'); // Assuming `card_id` is the foreign key in PetrolCard
    }

    public function bankDeposits()
    {
        return $this->hasMany(BankDepositAddCard::class, 'add_card_id');
    }

}
