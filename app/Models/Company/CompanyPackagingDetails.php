<?php

namespace App\Models\Company;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPackagingDetails extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'company_id',
        'gst_no',
        'cst_no',
        'tin_no',
        'vat_no',
    ];
}
