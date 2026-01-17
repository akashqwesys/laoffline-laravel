<?php

namespace App\Models\Company;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmails extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'company_id',
        'email_id',
    ];
}
