<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesBill extends Model
{
    use HasFactory;

    protected $fillable = [
            'id',
            'sale_bill_id',
            'iuid',
            'sale_bill_via',
            'attachments',
            'financial_year_id',
            'general_ref_id',
            'new_or_old_reference',
            'sale_bill_for',
            'product_main_category_id',
            'product_sub_category_id',
            'inward_id',
            'company_id',
            'address',
            'supplier_id',
            'agent_id',
            'supplier_invoice_no',
            'select_date',
            'date_added',
            'date_updated',
            'change_in_amount',
            'sign_change',
            'total',
            'total_peices',
            'total_meters',
            'remark',
            'required_followup',
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
