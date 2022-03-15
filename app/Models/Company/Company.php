<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_type',
        'company_country',
        'company_state',
        'company_city',
        'company_website',
        'company_landline',
        'company_mobile',
        'company_watchout',
        'company_remark_watchout',
        'company_about',
        'company_category',
        'product_category',
        'product_sub_category',
        'company_transport',
        'company_discount',
        'company_payment_terms_in_days',
        'company_opening_balance',
        'favorite_flag',
        'is_verified',
        'verified_by',
        'generated_by',
        'updated_by',
        'is_linked',
        'is_active',
        'verified_date',
    ];
}
