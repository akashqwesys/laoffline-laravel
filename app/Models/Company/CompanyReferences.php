<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReferences extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'ref_person_name',
        'ref_person_mobile',
        'ref_person_company',
        'ref_person_address',
    ];
}
