<?php

namespace App\Models;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentDetail extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'payment_details_id',
        'payment_id',
        'p_increment_id',
        'financial_year_id',
        // 'payment_followup_id',
        'sr_no',
        'supplier_invoice_no',
        'amount',
        'adjust_amount',
        'status',
        'discount',
        'discount_amount',
        'vatav',
        'agent_commission',
        'bank_commission',
        'claim',
        'goods_return',
        'short',
        'interest',
        'rate_difference',
        'remark',
        'flag_sale_bill_sr_no',
        'is_deleted'
    ];
}
