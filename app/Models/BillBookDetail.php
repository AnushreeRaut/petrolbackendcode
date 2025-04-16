<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillBookDetail extends Model
{
    use HasFactory;

    protected $table = 'bill_book_details';

    protected $fillable = [
        'credit_client_id',
        'date',
        'mode',
        'account',
        'amount',
        'remark'
    ];

    public function creditClient()
    {
        return $this->belongsTo(CreditClient::class);
    }
}
