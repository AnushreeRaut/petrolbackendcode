<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $table = 'advances';

    protected $fillable = [
        'add_advance_client_id',
        'voucher_type',
        'amount',
        'narration',
        'added_by',
        'updated_by',
        'date',
    ];

    public function client()
    {
        return $this->belongsTo(AddAdvanceClient::class, 'add_advance_client_id');
    }

}
