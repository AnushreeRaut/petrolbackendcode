<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'credit_client_id',
        'bill_no',
        'date',
        'billing_date',
    ];

    public function clientCredit()
    {
        return $this->belongsTo(ClientCredit::class, 'credit_client_id'); // Incorrect column name
    }

    public function creditClient()
    {
        return $this->belongsTo(CreditClient::class, 'credit_client_id');
    }
}
