<?php

namespace App\Models\Company;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyReferences extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'company_id',
        'ref_person_name',
        'ref_person_mobile',
        'ref_person_company',
        'ref_person_address',
    ];
}
