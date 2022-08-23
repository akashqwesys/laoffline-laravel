<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_id',
        'supplier_id',
        'commission_percentage',
        'flag',
    ];
}
