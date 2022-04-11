<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleBillTransport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_bill_id',
        'financial_year_id',
        'transport_id',
        'station',
        'lr_mr_no',
        'date',
        'cases',
        'weight',
        'freight',
        'is_deleted',
    ];
}
