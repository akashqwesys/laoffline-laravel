<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesBillTransport extends Model
{
    use HasFactory;

    protected $fillable = [
            'sale_bill_transport_id',
            'sale_bill_id',
            'financial_year_id',
            'transport_id',
            'station',
            'lr_mr_no',
            'date',
            'cases',
            'weight',
            'freight',
            'dele_thr',
            'is_deleted',
    ];
}
