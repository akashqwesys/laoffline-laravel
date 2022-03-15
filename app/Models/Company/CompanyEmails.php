<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmails extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'email_id',
    ];
}
