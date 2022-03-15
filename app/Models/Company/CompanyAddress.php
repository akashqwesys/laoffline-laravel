<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',        
        'address_type',
        'address',
        'country_code',
        'mobile',
    ];
}
