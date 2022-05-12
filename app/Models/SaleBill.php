<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_bill_id',
        'iuid',
        'sale_bill_via',
        'attachment',
        'financial_year_id',
        'general_ref_id',
        'new_or_old_reference',
        'sale_bill_for',
        'product_default_category_id',
        'product_category_id',
        'inward_id',
        'company_id',
        'address',
        'supplier_id',
        'agent_id',
        'supplier_invoice_no',
        'select_date',
        'change_in_amount',
        'sign_change',
        'total',
        'total_peices',
        'total_meters',
        'remark',
        // 'required_followup',
        'sale_bill_flag',
        'done_outward',
        'is_copied',
        'is_moved',
        'inward_main_or_sub_id',
        'inward_action_id',
        'payment_status',
        'received_payment',
        'pending_payment',
        'is_deleted',
    ];
}