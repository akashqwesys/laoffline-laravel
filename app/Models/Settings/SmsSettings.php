<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'enquiry_general',
        'enquiry_supplier',
        'enquiry_footer_message',
        'enquiry_followup_general',
        'enquiry_followup_supplier',
        'order_general',
        'order_supplier',
        'order_footer_message',
        'order_followup_general',
        'order_followup_supplier',
        'complain_general',
        'complain_supplier',
        'complain_footer_message',
        'complain_followup_general',
        'complain_followup_supplier',
        'general_general',
        'general_supplier',
        'general_footer_message',
        'general_followup_general',
        'general_followup_supplier',
        'salebill_inward_general',
        'salebill_inward_supplier',
        'salebill_inward_footer_message',
        'salebill_outward_followup_general',
        'salebill_outward_followup_supplier',
        'salebill_outward_followup_footer_message',
        'salebill_followup_general',
        'salebill_followup_supplier',
        'payment_general',
        'payment_supplier',
        'payment_footer_message',
        'payment_outward_followup_general',
        'payment_outward_followup_supplier',
        'payment_outward_footer_message',
        'payment_followup_general',
        'payment_followup_supplier',
        'commission_general',
        'commission_supplier',
        'commission_footer_message',
        'commission_followup_general',
        'commission_followup_supplier',
        'automated_payment_general',
        'automated_payment_supplier',
        'automated_payment_footer_message',
        'automated_commission_followup_general',
        'automated_commission_followup_supplier',
        'automated_commission_followup_footer_message',
    ];
}
