<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddAdvanceClient extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'add_advance_client';

    // Specify which columns can be mass-assigned
    protected $fillable = [
        'client_name',
    ];

}
