<?php

namespace App\Models\Commission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commission extends Model
{
    use HasFactory;

    protected $fillable = [
            'id',
            'commission_id',
            'iuid',
            'reference_id',
            'financial_year_id',
            'attachments',
            'payment_id',
            'customer_id',
            'supplier_id',
            'bill_no',
            'bill_date',
            'deposite_bank',
            'cheque_date',
            'cheque_dd_no',
            'cheque_dd_bank',
            'bill_amount',
            'received_amount',
            'tds',
            'net_received_amount',
            'received_commission_amount',
            'commission_date',
            'commission_account',
            'remark',
            'required_followup',
            'commission_reciept_mode',
            'commission_payment_date',
            'commission_deposite_bank',
            'commission_cheque_date',
            'commission_cheque_dd_no',
            'commission_cheque_dd_bank',
            'commission_payment_amount',
            'done_outward',
            'service_tax_val',
            'normal_amt_flag',
            'is_invoice',
            'date_added',
            'is_deleted',
    ];
}
