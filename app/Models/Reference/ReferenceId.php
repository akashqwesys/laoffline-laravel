<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceId extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'employee_id',
        'financial_year_id',
        'inward_or_outward',
        'type_of_inward',
        'company_id',
        'selection_date',
        'from_name',
        'from_number',
        'receiver_number',
        'from_email_id',
        'receiver_email_id',
        'latter_by_id',
        'courier_name',
        'weight_of_parcel',
        'courier_receipt_no',
        'courier_received_time',
        'delivery_by',
        'mark_as_sample',
        'gmail_mail_id',
        'gmail_folder_name',
        'is_deleted',
        'date_added',
        'created_at',
        'updated_at'
    ];
}
