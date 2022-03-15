<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class linkCompanies extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'link_companies_id',
    ];
}
