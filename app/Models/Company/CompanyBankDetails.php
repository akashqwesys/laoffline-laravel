<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBankDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'bank_name',
        'account_holder_name',
        'account_no',
        'branch_name',
        'ifsc_code',
    ];
}
