<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetrolCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'amount',
        'current_batch_no',
        'last_batch_no',
        'added_by',
        'updated_by',
        'date',
    ];

// PetrolCard.php
// Define the relationship
public function addCard() {
    return $this->belongsTo(AddCard::class, 'card_id'); // Adjust if necessary
}

}
