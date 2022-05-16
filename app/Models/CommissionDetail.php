<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_increment_id',
        'commission_id',
        'commission_invoice_id',
        'financial_year_id',
        'payment_id',
        'bill_date',
        'deposite_bank',
        'cheque_date',
        'cheque_dd_no',
        'cheque_dd_bank',
        'percentage',
        'bill_amount',
        'received_amount',
        'service_tax',
        'tds',
        'net_received_amount',
        'received_commission_amount',
        'commission_date',
        'commission_account',
        'status',
        'remark',
        'is_deleted'
    ];
}
