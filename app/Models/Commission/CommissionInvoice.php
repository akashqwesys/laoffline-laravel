<?php

namespace App\Models\Commission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commissionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
            'commission_invoice_id',
            'reference_id',
            'customer_id',
            'supplier_id',
            'financial_year_id',
            'generated_by',
            'bill_no',
           'bill_period_to',
           'bill_period_from',
           'bill_date',
            'commission_amount',
            'service_tax_amount',
            'service_tax',
            'other_amount',
            'rounded_off',
            'tds_amount',
            'final_amount',
            'service_tax_flag',
            'tds_flag',
            'tax_class',
            'with_without_gst',
            'cgst',
            'cgst_amount',
            'sgst',
            'sgst_amount',
            'igst',
            'igst_amount',
            'commission_percent',
            'agent_id',
            'done_outward',
            'commission_status',
            'right_of_amount',
            'right_of_remark',
            'is_deleted',
            'date_added',
    ];
}
