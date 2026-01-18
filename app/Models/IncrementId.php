<?php

namespace App\Models;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncrementId extends Model
{
    use HasFactory, GeneratesAutoId;

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
