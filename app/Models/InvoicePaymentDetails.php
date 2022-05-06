<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_invoice_id',
        'financial_year_id',
        'payment_id',
        'payment_date',
        'company_id',
        'received_amount',
        'total_amount',
        'flag',
    ];
}
