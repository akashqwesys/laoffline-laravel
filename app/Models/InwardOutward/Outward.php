<?php

namespace App\Models\inwardOutward;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outward extends Model
{
    use HasFactory;
    protected $fillable =[
            'outward_id',
            'ouid',
            'outward_ref_via',
            'general_output_ref_id',
            'new_or_old_outward',
            'connected_outward',
            'outward_date',
            'subject',
            'employe_id',
            'type_of_outward',
            'from_number',
            'company_id',
            'supplier_id',
            'courier_name',
            'weight_of_parcel',
            'courier_receipt_no',
            'courier_received_time',
            'no_of_parcel',
            'from_name',
            'attachments',
            'remarks',
            'latter_by_id',
            'delivery_by',
            'receiver_email_id',
            'from_email_id',
            'product_main_id',
            'product_image_id',
            'outward_link_with_id',
            'enquiry_complain_for',
            'client_remark',
            'notify_client',
            'notify_md',
            'required_followup',
            'courier_agent',
            'mark_as_draft',
            'outward_courier_flag',
            'outward_employe_id',
            'is_deleted',
    ];
}
