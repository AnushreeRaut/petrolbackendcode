<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients'; // Table name

    protected $fillable = [
        'client_name',
        'added_by',
        'updated_by',
    ];
}
