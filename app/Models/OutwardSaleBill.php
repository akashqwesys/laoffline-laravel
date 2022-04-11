<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardSaleBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'outward_id',
        'sale_bill_id',
        'payment_id',
        'commission_id',
        'commission_invoice_id',
        'is_deleted',
    ];
}
