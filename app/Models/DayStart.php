<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayStart extends Model
{
    use HasFactory;

    protected $table = 'day_start'; // Table name

    protected $fillable = [
        'ms_rate_day',
        'speed_rate_day',
        'hsd_rate_day',
        'ms_last_day',
        'speed_last_day',
        'hsd_last_day',
        'ms_diff',
        'speed_diff',
        'hsd_diff',
        'added_by',
        'updated_by',
        'date',
    ];
}
