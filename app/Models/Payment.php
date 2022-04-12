<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id', 
        'iuid', 
        'reference_id', 
        'attachments', 
        'letter_attachment', 
        'financial_year_id', 
        'reciept_mode', 
        'slip_no', 
        'date', 
        'deposite_bank', 
        'cheque_date', 
        'cheque_dd_no', 
        'cheque_dd_bank', 
        'receipt_from', 
        'trns', 
        'supplier_id', 
        'customer_id', 
        'receipt_amount', 
        'total_amount', 
        'tot_adjust_amount', 
        'tot_discount', 
        'tot_vatav', 
        'tot_agent_commission', 
        'tot_bank_cpmmission', 
        'tot_claim', 
        'tot_good_returns', 
        'tot_short', 
        'tot_interest', 
        'tot_rate_difference', 
        'payment_ok_or_not', 
        'old_commission_status', 
        'customer_commission_status', 
        'right_of_amount', 
        'right_of_remark', 
        'is_deleted', 
        'done_outward'
    ];
    
}
