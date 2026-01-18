<?php

namespace App\Models;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iuid extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'iuid',
        'financial_year_id',
        'name',
        'inward_type',
        'inward_medium',
        'inward_details',
        'company_id',
        'company_type',
        'company_person',
        'generated_by',
        'assigned_to',
    ];
}
