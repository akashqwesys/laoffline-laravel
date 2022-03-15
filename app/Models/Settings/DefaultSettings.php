<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'cgst',
        'sgst',
        'igst',
        'tds',
        'service_tax_limit',
    ];
}
