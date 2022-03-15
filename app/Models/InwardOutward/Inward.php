<?php

namespace App\Models\inwardOutward;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inward extends Model
{
    use HasFactory;
    protected $fillable = [
        'inward_id',
            'iuid',
            'call_by',
            'inward_ref_via',
            'general_input_ref_id',
            'new_or_old_inward',
            'financial_year_id',
            'connected_inward',
            'inward_date',
            'subject',
            'employe_id',
            'type_of_inward',
            'receiver_number',
            'company_id',
            'supplier_id',
            'courier_name',
            'weight_of_parcel',
            'courier_receipt_no',
            'courier_received_time',
            'from_name',
            'attachments',
            'remarks',
            'latter_by_id',
            'delivery_by',
            'receiver_email_id',
            'from_email_id',
            'product_main_id',
            'product_image_id',
            'inward_link_with_id',
            'enquiry_complain_for',
            'client_remark',
            'notify_client',
            'notify_md',
            'required_followup',
            'delivery_period',
            'to_name',
            'mark_as_draft',
            'sample_via',
            'sample_for',
            'sample_prod_or_fabric',
            'product_qty',
            'fabric_meters',
            'is_deleted',
    ];
}
