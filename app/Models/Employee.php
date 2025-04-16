<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'fname',
        'lname',
        'joining_date',
        'relieving_date',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'relieving_date' => 'date',
        'is_active' => 'boolean',
    ];
}
