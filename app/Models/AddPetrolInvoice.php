<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddPetrolInvoice extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'add_petrol_invoice';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'tank_id',
        'rate_per_unit',
        'tax_amt_per_amt',
        'vat_lst',
        'cess_per_unit',
        'tcs_per_unit',
        '194Q_tds',
        'LFR_prt_kl',
        'Cgst',
        'SGST',
        '194I_tds_lfr',
        'is_active'
    ];
        // Define the relationship to Tank (inverse of the 'hasMany' relationship)
        public function tank()
        {
            return $this->belongsTo(Tank::class, 'tank_id');
        }

}
