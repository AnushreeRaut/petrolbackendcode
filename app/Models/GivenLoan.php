<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GivenLoan extends Model
{
    use HasFactory;

    protected $table = 'given_loan'; // Table name

    protected $fillable = [
        'client_id',
        'voucher_type',
        'amount',
        'narration',
        'amount_in_words',
        'added_by',
        'updated_by',
        'date',
    ];

    /**
     * Get the client that owns the hand loan.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
