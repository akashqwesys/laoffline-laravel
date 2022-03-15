<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportMultipleAddressDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'transport_details',
        'contact_person_name',
        'contact_person_address',
        'contact_person_office_no',
        'contact_person_email',
    ];
}
