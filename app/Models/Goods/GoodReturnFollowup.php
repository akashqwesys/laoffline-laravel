<?php

namespace App\Models\Goods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class goodReturnFollowup extends Model
{
    use HasFactory;

    protected $fillable =  [
            'goods_return_followup_id',
            'payment_id',
            'reference_id',
            'reference_via',
            'new_or_old_reference',
            'company_id',
            'supplier_id',
            'inward_or_outward',
            'inward_via',
            'attachment',
            'followup_by',
            'inform_md',
            'followup_via',
            'next_followup_date',
            'followup_time',
            'is_completed',
            'remark',
            'assign_to',
            'inward_via_name',
            'courier_name',
            'l_r_no',
            'l_r_date',
            'courier_no',
            'courier_date',
            'parcel_amount',
            'parcel_weight',
            'freight_charge',
            'no_of_parcel',
            'received_date',
            'to_pay_or_paid',
            'receiver_name',
            'date_added',
    ];
}
