<?php

namespace App\Models\Company;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddressOwner extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'company_address_id',
        'name',
        'designation',
        'profile_pic',
        'mobile',
        'email',
    ];
}
