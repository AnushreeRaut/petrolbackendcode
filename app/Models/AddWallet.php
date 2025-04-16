<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddWallet extends Model
{
    use HasFactory;

    protected $table = 'add_wallet';

    protected $fillable = [
        'bank_name',
        'status',
        'added_by',
        'updated_by',
    ];
}
