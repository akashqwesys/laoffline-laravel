<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyContactDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'contact_person_name',
        'contact_person_designation',
        'contact_person_profile_pic',
        'contact_person_mobile',
        'contact_person_email',
    ];
}
