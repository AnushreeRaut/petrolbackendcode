<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeIncentive extends Model
{
    use HasFactory;

    protected $table = 'employee_incentives';

    protected $fillable = [
        'employee_id',
        'incentive_month',
        'month_days',
        'holidays',
        'work_days',
        't_sale',
        'avg_sale',
        'amt',
        'incentive',
        'payment',
        'bank_cheque',
    ];
// Define the relationship with the Employee model
public function employee()
{
    return $this->belongsTo(Employee::class);
}
}
