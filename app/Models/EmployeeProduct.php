<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProduct extends Model
{
    use HasFactory;

    protected $table = 'employee_products';

    protected $fillable = [
        'employee_id',
        'product_id',
        'month',
        'commission',
        'sales_done',
        'total_amount',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
