<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncrementId extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'financial_year_id',
        'iuid',
        'ouid',
        'reference_id',
        'sale_bill_id',
        'payment_id',
        'commission_id',
        'goods_return_id',
    ];
}
