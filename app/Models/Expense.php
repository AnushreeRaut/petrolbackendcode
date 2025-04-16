<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expenses_id',
        'amount',
        'narration',
        'added_by',
        'updated_by',
    ];
    public function topic()
    {
        return $this->belongsTo(ExpensesTopic::class, 'expenses_id');
    }
}
